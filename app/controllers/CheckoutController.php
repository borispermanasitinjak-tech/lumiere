<?php
class CheckoutController extends Controller {
    private $productModel;
    private $orderModel;
    private $addressModel;
    private $paymentModel;
    private $promoModel;

    public function __construct() {
        // Require authentication for checkout
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Please login to checkout';
            $this->redirect('auth/login');
        }

        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->addressModel = $this->model('ShippingAddress');
        $this->paymentModel = $this->model('PaymentMethod');
        $this->promoModel = $this->model('PromoCode');
    }

    /**
     * Display checkout page
     */
    public function index() {
        // Get cart items
        $products = [];
        $subtotal = 0;

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['flash_message'] = 'Your cart is empty';
            $this->redirect('cart');
            return;
        }

        foreach ($_SESSION['cart'] as $id => $qty) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                // Check stock availability
                if ($product->stock < $qty) {
                    $_SESSION['flash_error'] = "Insufficient stock for {$product->name}. Only {$product->stock} available.";
                    $this->redirect('cart');
                    return;
                }

                $product->qty = $qty;
                $product->subtotal = $product->price * $qty;
                $products[] = $product;
                $subtotal += $product->subtotal;
            }
        }

        // Get addresses
        $addresses = $this->addressModel->getUserAddresses($_SESSION['user_id']);
        
        // CHECK: If no address, redirect to add address page
        if (empty($addresses)) {
            $_SESSION['flash_error'] = 'Please add a shipping address before checkout';
            $this->redirect('user/add_address');
            return;
        }
        
        $default_address = $this->addressModel->getDefaultAddress($_SESSION['user_id']);

        // Get payment methods
        $payment_methods = $this->paymentModel->getActiveMethods();

        // Calculate discount if promo code in session
        $discount = 0;
        $promo = null;
        if (isset($_SESSION['promo_code']) && !empty($_SESSION['promo_code'])) {
            $promo = $this->promoModel->getCodeByString($_SESSION['promo_code']);
            if ($promo) {
                $discount = $this->promoModel->calculateDiscount($promo, $subtotal);
            }
        }

        $total = $subtotal - $discount;

        $data = [
            'products' => $products,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'addresses' => $addresses,
            'default_address' => $default_address,
            'payment_methods' => $payment_methods,
            'promo' => $promo
        ];

        $this->view('checkout/index', $data);
    }

    /**
     * Apply promo code (AJAX)
     */
    public function apply_promo() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $code = trim($_POST['code'] ?? '');
        $total = floatval($_POST['total'] ?? 0);

        $validation = $this->promoModel->validateCode($code, $total);

        if ($validation['valid']) {
            $promo = $validation['promo'];
            $discount = $this->promoModel->calculateDiscount($promo, $total);
            $_SESSION['promo_code'] = $code;

            echo json_encode([
                'success' => true,
                'discount' => $discount,
                'discount_formatted' => format_rupiah($discount),
                'new_total' => $total - $discount,
                'new_total_formatted' => format_rupiah($total - $discount)
            ]);
        } else {
            unset($_SESSION['promo_code']);
            echo json_encode([
                'success' => false,
                'message' => $validation['message']
            ]);
        }
        exit;
    }

    /**
     * Remove promo code
     */
    public function remove_promo() {
        unset($_SESSION['promo_code']);
        $this->redirect('checkout');
    }

    /**
     * Process checkout and create order
     */
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('checkout');
            return;
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = 'Invalid request. Please try again.';
            $this->redirect('checkout');
            return;
        }

        // Validate cart
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['flash_error'] = 'Your cart is empty';
            $this->redirect('cart');
            return;
        }

        // Get address and payment method
        $address_id = $_POST['shipping_address_id'] ?? null;
        $payment_method_id = $_POST['payment_method_id'] ?? null;

        if (!$address_id) {
            $_SESSION['flash_error'] = 'Please select a shipping address';
            $this->redirect('checkout');
            return;
        }

        if (!$payment_method_id) {
            $_SESSION['flash_error'] = 'Please select a payment method';
            $this->redirect('checkout');
            return;
        }

        // Prepare order data
        $order_items = [];
        $subtotal = 0;

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product = $this->productModel->getProductById($product_id);
            
            if (!$product) {
                $_SESSION['flash_error'] = 'Some products are no longer available';
                $this->redirect('cart');
                return;
            }

            // Check stock
            if ($product->stock < $quantity) {
                $_SESSION['flash_error'] = "Insufficient stock for {$product->name}";
                $this->redirect('cart');
                return;
            }

            $order_items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $product->price
            ];

            $subtotal += $product->price * $quantity;
        }

        // Apply promo code
        $discount = 0;
        $promo_code_id = null;
        if (isset($_SESSION['promo_code']) && !empty($_SESSION['promo_code'])) {
            $promo = $this->promoModel->getCodeByString($_SESSION['promo_code']);
            if ($promo) {
                $validation = $this->promoModel->validateCode($_SESSION['promo_code'], $subtotal);
                if ($validation['valid']) {
                    $discount = $this->promoModel->calculateDiscount($promo, $subtotal);
                    $promo_code_id = $promo->id;
                }
            }
        }

        $total_amount = $subtotal - $discount;

        // Create order
        $order_data = [
            'user_id' => $_SESSION['user_id'],
            'payment_method_id' => $payment_method_id,
            'shipping_address_id' => $address_id,
            'promo_code_id' => $promo_code_id,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total_amount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'items' => $order_items
        ];

        $order_id = $this->orderModel->createOrder($order_data);

        if ($order_id) {
            // Update product stock
            foreach ($order_items as $item) {
                $product = $this->productModel->getProductById($item['product_id']);
                $new_stock = $product->stock - $item['quantity'];
                
                $this->productModel->updateProduct([
                    'id' => $item['product_id'],
                    'name' => $product->name,
                    'category_id' => $product->category_id,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $new_stock,
                    'image' => $product->image
                ]);
            }

            // Increment promo code usage
            if ($promo_code_id) {
                $this->promoModel->incrementUsage($promo_code_id);
            }

            // Clear cart and promo
            unset($_SESSION['cart']);
            unset($_SESSION['promo_code']);

            // Set success message
            $_SESSION['flash_success'] = 'Order placed successfully! Order #' . $order_id;
            
            // Redirect to order confirmation
            $this->redirect('checkout/confirmation/' . $order_id);
        } else {
            $_SESSION['flash_error'] = 'Failed to create order. Please try again.';
            $this->redirect('checkout');
        }
    }

    /**
     * Order confirmation page
     */
    public function confirmation($order_id) {
        $order = $this->orderModel->getOrderById($order_id);

        // Verify order belongs to current user
        if (!$order || $order->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Order not found';
            $this->redirect('home');
            return;
        }

        // Get payment method info
        $payment_method = $this->paymentModel->getMethodById($order->payment_method_id);

        $data = [
            'order' => $order,
            'payment_method' => $payment_method
        ];

        $this->view('checkout/confirmation', $data);
    }
}
