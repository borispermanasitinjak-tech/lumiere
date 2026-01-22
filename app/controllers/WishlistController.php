<?php
class WishlistController extends Controller {
    private $wishlistModel;
    private $productModel;

    public function __construct() {
        // Require authentication
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }

        $this->wishlistModel = $this->model('Wishlist');
        $this->productModel = $this->model('Product');
    }

    /**
     * Display wishlist
     */
    public function index() {
        $wishlist = $this->wishlistModel->getUserWishlist($_SESSION['user_id']);

        $data = [
            'wishlist' => $wishlist
        ];

        $this->view('wishlist/index', $data);
    }

    /**
     * Add product to wishlist (AJAX compatible)
     */
    public function add($product_id) {
        if ($this->wishlistModel->addToWishlist($_SESSION['user_id'], $product_id)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                // AJAX request
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Added to wishlist']);
                exit;
            } else {
                $_SESSION['flash_success'] = 'Product added to wishlist';
            }
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Already in wishlist']);
                exit;
            } else {
                $_SESSION['flash_error'] = 'Product already in wishlist';
            }
        }

        // Redirect back or to wishlist
        $this->redirect('wishlist');
    }

    /**
     * Remove product from wishlist
     */
    public function remove($product_id) {
        if ($this->wishlistModel->removeFromWishlist($_SESSION['user_id'], $product_id)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Removed from wishlist']);
                exit;
            } else {
                $_SESSION['flash_success'] = 'Product removed from wishlist';
            }
        }

        $this->redirect('wishlist');
    }

    /**
     * Toggle wishlist (add if not in, remove if in) - for heart icon
     */
    public function toggle($product_id) {
        if ($this->wishlistModel->isInWishlist($_SESSION['user_id'], $product_id)) {
            $this->wishlistModel->removeFromWishlist($_SESSION['user_id'], $product_id);
            $action = 'removed';
        } else {
            $this->wishlistModel->addToWishlist($_SESSION['user_id'], $product_id);
            $action = 'added';
        }

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'action' => $action]);
            exit;
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? 'wishlist');
    }
}
