<?php
class ReviewController extends Controller {
    private $reviewModel;
    private $productModel;

    public function __construct() {
        // Require authentication
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }

        $this->reviewModel = $this->model('Review');
        $this->productModel = $this->model('Product');
    }

    /**
     * Add review for a product
     */
    public function add($product_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = 'Invalid request';
                $this->redirect('home/product/' . $product_id);
                return;
            }

            // Check if user can review
            if (!$this->reviewModel->canUserReview($_SESSION['user_id'], $product_id)) {
                $_SESSION['flash_error'] = 'You can only review products you have purchased';
                $this->redirect('home/product/' . $product_id);
                return;
            }

            $data = [
                'product_id' => $product_id,
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'] ?? 5,
                'review_text' => trim($_POST['review_text'] ?? ''),
                'is_approved' => 1 // Auto-approve for now
            ];

            // Validate
            if ($data['rating'] < 1 || $data['rating'] > 5) {
                $_SESSION['flash_error'] = 'Rating must be between 1 and 5';
                $this->redirect('home/product/' . $product_id);
                return;
            }

            if ($this->reviewModel->addReview($data)) {
                $_SESSION['flash_success'] = 'Review submitted successfully';
            } else {
                $_SESSION['flash_error'] = 'Failed to submit review';
            }
        }

        $this->redirect('home/product/' . $product_id);
    }

    /**
     * Edit review
     */
    public function edit($id) {
        $review = $this->reviewModel->getReviewById($id);

        // Verify ownership
        if (!$review || $review->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Review not found';
            $this->redirect('user/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'rating' => $_POST['rating'] ?? 5,
                'review_text' => trim($_POST['review_text'] ?? '')
            ];

            if ($this->reviewModel->updateReview($data)) {
                $_SESSION['flash_success'] = 'Review updated successfully';
            } else {
                $_SESSION['flash_error'] = 'Failed to update review';
            }

            $this->redirect('home/product/' . $review->product_id);
        }
    }

    /**
     * Delete review
     */
    public function delete($id) {
        $review = $this->reviewModel->getReviewById($id);

        // Verify ownership
        if (!$review || $review->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Review not found';
            $this->redirect('user/dashboard');
            return;
        }

        if ($this->reviewModel->deleteReview($id)) {
            $_SESSION['flash_success'] = 'Review deleted successfully';
        } else {
            $_SESSION['flash_error'] = 'Failed to delete review';
        }

        $this->redirect('home/product/' . $review->product_id);
    }
}
