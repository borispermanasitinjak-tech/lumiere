<?php
class HomeController extends Controller {
    public function __construct() {
        $this->productModel = $this->model('Product');
    }

    public function index() {
        $keyword = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        
        if (!empty($keyword)) {
            $filters = [];
            if ($category) $filters['category_id'] = $category;
            $products = $this->productModel->searchProducts($keyword, $filters);
        } elseif (!empty($category)) {
            $products = $this->productModel->getProductsByCategory($category);
        } else {
            $products = $this->productModel->getProducts();
        }

        // Get categories for filter
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getCategories();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'search' => $keyword,
            'selected_category' => $category
        ];

        $this->view('home/index', $data);
    }
    
    public function catalog() {
        // Alias for index with filters
        $this->index();
    }

    public function product($id) {
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            $this->redirect('home');
            return;
        }

        // Get reviews
        $reviewModel = $this->model('Review');
        $reviews = $reviewModel->getProductReviews($id);
        
        // Check if user can review
        $canReview = false;
        if (isset($_SESSION['user_id'])) {
            $canReview = $reviewModel->canUserReview($_SESSION['user_id'], $id);
        }
        
        // Check if in wishlist
        $inWishlist = false;
        if (isset($_SESSION['user_id'])) {
            $wishlistModel = $this->model('Wishlist');
            $inWishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $id);
        }

        $data = [
            'product' => $product,
            'reviews' => $reviews,
            'canReview' => $canReview,
            'inWishlist' => $inWishlist
        ];

        $this->view('home/product', $data);
    }
}
