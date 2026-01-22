<?php
class AdminController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            $this->redirect('auth/login');
        }
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->categoryModel = $this->model('Category');
    }

    public function reports() {
        $dailySales = $this->orderModel->getDailySales();
        
        // Prepare data for Chart.js
        $dates = [];
        $revenues = [];
        $orders = [];
        
        foreach($dailySales as $day) {
            $dates[] = $day->date;
            $revenues[] = $day->revenue;
            $orders[] = $day->orders;
        }

        $data = [
            'dates' => json_encode($dates),
            'revenues' => json_encode($revenues),
            'orders' => json_encode($orders)
        ];

        $this->view('admin/reports', $data);
    }

    public function export_csv() {
        $dailySales = $this->orderModel->getDailySales();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sales_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Revenue', 'Orders']);
        
        foreach($dailySales as $row) {
            fputcsv($output, [$row->date, $row->revenue, $row->orders]);
        }
        
        fclose($output);
        exit;
    }

    public function dashboard() {
        $products = $this->productModel->getProducts();

        $data = [
            'products' => $products
        ];

        $this->view('admin/dashboard', $data);
    }

    public function add_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize via trim, avoid filter_input_array which can be buggy/deprecated
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'stock' => trim($_POST['stock'] ?? ''),
                'image' => '', // Will be filled
                'name_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_err' => ''
            ];

            // Validate
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
        if (empty($data['category_id'])) {
                $data['category_err'] = 'Please select a category';
            }
            
            // Image Upload
            if(!empty($_FILES['image']['name'])){
                $uploadResult = $this->uploadImage($_FILES['image']);
                if($uploadResult['status']){
                    $data['image'] = $uploadResult['path'];
                } else {
                    $data['image_err'] = $uploadResult['msg'];
                }
            } else {
                // Default image or error? Let's use a placeholder if empty
                $data['image'] = 'https://via.placeholder.com/300'; 
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['image_err']) && empty($data['category_err'])) {
                if ($this->productModel->addProduct($data)) {
                    $this->redirect('admin/dashboard');
                } else {
                    $_SESSION['flash_error'] = 'Something went wrong adding the product';
                    $this->view('admin/add_product', $data);
                }
            } else {
                 // Load categories if validation fails
                 $categories = $this->categoryModel->getCategories();
                 $data['categories'] = $categories;
                 $this->view('admin/add_product', $data);
            }
        } else {
            $categories = $this->categoryModel->getCategories();
            $data = [
                'name' => '',
                'category_id' => '',
                'description' => '',
                'price' => '',
                'stock' => '',
                'image' => '',
                'name_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_err' => '',
                'categories' => $categories
            ];
            $this->view('admin/add_product', $data);
        }
    }

    public function edit_product($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize
            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'stock' => trim($_POST['stock'] ?? ''),
                'image' => trim($_POST['existing_image'] ?? ''),
                'name_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['category_id'])) {
                $data['category_err'] = 'Please select a category';
            }
            
            // Check for new image
            if(!empty($_FILES['image']['name'])){
                $uploadResult = $this->uploadImage($_FILES['image']);
                if($uploadResult['status']){
                    $data['image'] = $uploadResult['path'];
                } else {
                    $data['image_err'] = $uploadResult['msg'];
                }
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['image_err']) && empty($data['category_err'])) {
                if ($this->productModel->updateProduct($data)) {
                    $this->redirect('admin/dashboard');
                } else {
                    $_SESSION['flash_error'] = 'Something went wrong updating the product';
                    $this->view('admin/edit_product', $data);
                }
            } else {
                // Load categories if validation fails
                $categories = $this->categoryModel->getCategories();
                $data['categories'] = $categories;
                $this->view('admin/edit_product', $data);
            }
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getCategories();

            $data = [
                'id' => $id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'image' => $product->image,
                'name_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_err' => '',
                'categories' => $categories
            ];
            $this->view('admin/edit_product', $data);
        }
    }

    public function uploadImage($file){
        $target_dir = APPROOT . '/../public/uploads/';
        // Ensure dir exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $fileName = basename($file["name"]);
        // Rename file to unique to avoid collisions
        $imageFileType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $newFileName;
        
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
             return ['status' => false, 'msg' => 'File is not an image.'];
        }
        
        // Check file size (limit 5MB)
        if ($file["size"] > 5000000) {
            return ['status' => false, 'msg' => 'Sorry, your file is too large.'];
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "webp" ) {
            return ['status' => false, 'msg' => 'Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed.'];
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Return the URL to save in DB
            return ['status' => true, 'path' => BASE_URL . '/public/uploads/' . $newFileName];
        } else {
            return ['status' => false, 'msg' => 'Sorry, there was an error uploading your file.'];
        }
    }

    public function delete_product($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->deleteProduct($id)) {
                $this->redirect('admin/dashboard');
            } else {
                $_SESSION['flash_error'] = 'Something went wrong deleting the product';
                $this->redirect('admin/dashboard');
            }
        } else {
            $this->redirect('admin/dashboard');
        }
    }

    public function add_category() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter category name';
            }

            if (empty($data['name_err'])) {
                if ($this->categoryModel->addCategory($data)) {
                    // Redirect to add_product so they can use it, or dashboard
                    $this->redirect('admin/dashboard'); 
                } else {
                     $_SESSION['flash_error'] = 'Something went wrong adding the category';
                     $this->view('admin/add_category', $data);
                }
            } else {
                $this->view('admin/add_category', $data);
            }
        } else {
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => ''
            ];
            $this->view('admin/add_category', $data);
        }
    }

    public function categories() {
        $categories = $this->categoryModel->getCategories();
        $this->view('admin/categories', ['categories' => $categories]);
    }

    public function edit_category($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter category name';
            }

            if (empty($data['name_err'])) {
                if ($this->categoryModel->updateCategory($data)) {
                    $this->redirect('admin/categories'); 
                } else {
                    $_SESSION['flash_error'] = 'Something went wrong updating the category';
                    $this->view('admin/edit_category', $data);
                }
            } else {
                $this->view('admin/edit_category', $data);
            }
        } else {
            $category = $this->categoryModel->getCategoryById($id);
            $data = [
                'id' => $id,
                'name' => $category->name,
                'description' => $category->description,
                'name_err' => ''
            ];
            $this->view('admin/edit_category', $data);
        }
    }

    public function delete_category($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->categoryModel->deleteCategory($id)) {
                $this->redirect('admin/categories');
            } else {
                $_SESSION['flash_error'] = 'Something went wrong deleting the category';
                $this->redirect('admin/categories');
            }
        } else {
            $this->redirect('admin/categories');
        }
    }

    /**
     * View all orders
     */
    public function orders() {
        $orders = $this->orderModel->getAllOrders();
        $stats = $this->orderModel->getOrderStats();

        $data = [
            'orders' => $orders,
            'stats' => $stats
        ];

        $this->view('admin/orders', $data);
    }

    /**
     * View order detail
     */
    public function order_detail($id) {
        $order = $this->orderModel->getOrderById($id);

        if (!$order) {
            $this->redirect('admin/orders');
            return;
        }

        $data = [
            'order' => $order
        ];

        $this->view('admin/order_detail', $data);
    }

    /**
     * Update order status
     */
    public function update_order_status($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'] ?? '';
            
            if (in_array($status, ['pending', 'completed', 'cancelled'])) {
                if ($this->orderModel->updateStatus($id, $status)) {
                    $_SESSION['flash_success'] = 'Order status updated successfully';
                } else {
                    $_SESSION['flash_error'] = 'Failed to update order status';
                }
            }
            
            $this->redirect('admin/order_detail/' . $id);
        } else {
            $this->redirect('admin/orders');
        }
    }

    // ========== PAYMENT METHODS ==========
    public function payment_methods() {
        $this->paymentModel = $this->model('PaymentMethod');
        $methods = $this->paymentModel->getAllMethods();
        $this->view('admin/payment_methods', ['methods' => $methods]);
    }

    public function add_payment_method() {
        $this->paymentModel = $this->model('PaymentMethod');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'type' => $_POST['type'] ?? '',
                'account_name' => trim($_POST['account_name'] ?? ''),
                'account_number' => trim($_POST['account_number'] ?? ''),
                'instructions' => trim($_POST['instructions'] ?? ''),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($this->paymentModel->addMethod($data)) {
                $_SESSION['flash_success'] = 'Payment method added successfully';
                $this->redirect('admin/payment_methods');
            } else {
                $_SESSION['flash_error'] = 'Failed to add payment method';
                $this->view('admin/add_payment_method', $data);
            }
        } else {
            $this->view('admin/add_payment_method', []);
        }
    }

    public function edit_payment_method($id) {
        $this->paymentModel = $this->model('PaymentMethod');
        $method = $this->paymentModel->getMethodById($id);

        if (!$method) {
            $this->redirect('admin/payment_methods');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'type' => $_POST['type'] ?? '',
                'account_name' => trim($_POST['account_name'] ?? ''),
                'account_number' => trim($_POST['account_number'] ?? ''),
                'instructions' => trim($_POST['instructions'] ?? ''),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($this->paymentModel->updateMethod($data)) {
                $_SESSION['flash_success'] = 'Payment method updated successfully';
                $this->redirect('admin/payment_methods');
            }
        } else {
            $this->view('admin/edit_payment_method', ['method' => $method]);
        }
    }

    public function delete_payment_method($id) {
        $this->paymentModel = $this->model('PaymentMethod');
        if ($this->paymentModel->deleteMethod($id)) {
            $_SESSION['flash_success'] = 'Payment method deleted';
        }
        $this->redirect('admin/payment_methods');
    }

    // ========== PROMO CODES ==========
    public function promo_codes() {
        $this->promoModel = $this->model('PromoCode');
        $codes = $this->promoModel->getAllCodes();
        $this->view('admin/promo_codes', ['codes' => $codes]);
    }

    public function add_promo() {
        $this->promoModel = $this->model('PromoCode');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => trim($_POST['code'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => floatval($_POST['discount_value'] ?? 0),
                'min_purchase' => floatval($_POST['min_purchase'] ?? 0),
                'max_discount' => !empty($_POST['max_discount']) ? floatval($_POST['max_discount']) : null,
                'usage_limit' => !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null,
                'valid_from' => $_POST['valid_from'] ?? date('Y-m-d H:i:s'),
                'valid_until' => $_POST['valid_until'] ?? date('Y-m-d H:i:s', strtotime('+30 days')),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($this->promoModel->addCode($data)) {
                $_SESSION['flash_success'] = 'Promo code added successfully';
                $this->redirect('admin/promo_codes');
            } else {
                $_SESSION['flash_error'] = 'Failed to add promo code';
                $this->view('admin/add_promo', $data);
            }
        } else {
            $this->view('admin/add_promo', []);
        }
    }

    public function edit_promo($id) {
        $this->promoModel = $this->model('PromoCode');
        $promo = $this->promoModel->getCodeById($id);

        if (!$promo) {
            $this->redirect('admin/promo_codes');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'code' => trim($_POST['code'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => floatval($_POST['discount_value'] ?? 0),
                'min_purchase' => floatval($_POST['min_purchase'] ?? 0),
                'max_discount' => !empty($_POST['max_discount']) ? floatval($_POST['max_discount']) : null,
                'usage_limit' => !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null,
                'valid_from' => $_POST['valid_from'] ?? $promo->valid_from,
                'valid_until' => $_POST['valid_until'] ?? $promo->valid_until,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($this->promoModel->updateCode($data)) {
                $_SESSION['flash_success'] = 'Promo code updated successfully';
                $this->redirect('admin/promo_codes');
            }
        } else {
            $this->view('admin/edit_promo', ['promo' => $promo]);
        }
    }

    public function delete_promo($id) {
        $this->promoModel = $this->model('PromoCode');
        if ($this->promoModel->deleteCode($id)) {
            $_SESSION['flash_success'] = 'Promo code deleted';
        }
        $this->redirect('admin/promo_codes');
    }

    // ========== LOW STOCK ALERT ==========
    public function low_stock() {
        $products = $this->productModel->getLowStockProducts(10);
        $this->view('admin/low_stock', ['products' => $products]);
    }
}
