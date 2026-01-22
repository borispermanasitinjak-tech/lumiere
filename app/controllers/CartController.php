<?php
class CartController extends Controller {
    public function __construct() {
        $this->productModel = $this->model('Product');
    }

    public function add($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        } else {
            $qty = 1;
        }

        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            die('Product not found');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $qty;
        } else {
            $_SESSION['cart'][$id] = $qty;
        }

        // Redirect back to where they came from if possible, or cart
        // For now, redirect to cart or stay on page with message
        // Let's redirect to cart to show it was added
        $this->redirect('cart');
    }

    public function index() {
        $products = [];
        $total = 0;

        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $qty) {
                $product = $this->productModel->getProductById($id);
                if ($product) {
                    $product->qty = $qty;
                    $products[] = $product;
                    $total += $product->price * $qty;
                }
            }
        }

        $data = [
            'products' => $products,
            'total' => $total
        ];

        $this->view('cart/index', $data);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST['qty'] as $id => $qty) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id] = (int)$qty;
                }
            }
        }
        $this->redirect('cart');
    }

    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        $this->redirect('cart');
    }
}
