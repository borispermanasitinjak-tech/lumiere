<?php
class Review {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get reviews for a product
     */
    public function getProductReviews($product_id) {
        $this->db->query('SELECT r.*, u.username 
                         FROM product_reviews r
                         INNER JOIN users u ON r.user_id = u.id
                         WHERE r.product_id = :product_id AND r.is_approved = 1
                         ORDER BY r.created_at DESC');
        $this->db->bind(':product_id', $product_id);
        return $this->db->resultSet();
    }

    /**
     * Get user's reviews
     */
    public function getUserReviews($user_id) {
        $this->db->query('SELECT r.*, p.name as product_name, p.image as product_image
                         FROM product_reviews r
                         INNER JOIN products p ON r.product_id = p.id
                         WHERE r.user_id = :user_id
                         ORDER BY r.created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    /**
     * Add review
     */
    public function addReview($data) {
        $this->db->query('INSERT INTO product_reviews (product_id, user_id, order_id, rating, review_text, is_approved) 
                         VALUES(:product_id, :user_id, :order_id, :rating, :review_text, :is_approved)');
        
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':order_id', $data['order_id'] ?? null);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review_text', $data['review_text'] ?? '');
        $this->db->bind(':is_approved', $data['is_approved'] ?? 1);

        if ($this->db->execute()) {
            // Update product rating
            $this->updateProductRating($data['product_id']);
            return true;
        }
        return false;
    }

    /**
     * Update review
     */
    public function updateReview($data) {
        $this->db->query('UPDATE product_reviews SET 
                         rating = :rating,
                         review_text = :review_text
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review_text', $data['review_text'] ?? '');

        if ($this->db->execute()) {
            // Get product_id from review
            $this->db->query('SELECT product_id FROM product_reviews WHERE id = :id');
            $this->db->bind(':id', $data['id']);
            $review = $this->db->single();
            
            if ($review) {
                $this->updateProductRating($review->product_id);
            }
            return true;
        }
        return false;
    }

    /**
     * Delete review
     */
    public function deleteReview($id) {
        // Get product_id first
        $this->db->query('SELECT product_id FROM product_reviews WHERE id = :id');
        $this->db->bind(':id', $id);
        $review = $this->db->single();

        $this->db->query('DELETE FROM product_reviews WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute() && $review) {
            $this->updateProductRating($review->product_id);
            return true;
        }
        return false;
    }

    /**
     * Update product's average rating
     */
    public function updateProductRating($product_id) {
        $this->db->query('SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
                         FROM product_reviews 
                         WHERE product_id = :product_id AND is_approved = 1');
        $this->db->bind(':product_id', $product_id);
        $stats = $this->db->single();

        $avg_rating = $stats->avg_rating ?? 0;
        $review_count = $stats->review_count ?? 0;

        $this->db->query('UPDATE products SET average_rating = :avg_rating, review_count = :review_count WHERE id = :id');
        $this->db->bind(':avg_rating', round($avg_rating, 1));
        $this->db->bind(':review_count', $review_count);
        $this->db->bind(':id', $product_id);
        return $this->db->execute();
    }

    /**
     * Check if user can review product (must have purchased)
     */
    public function canUserReview($user_id, $product_id) {
        // Check if user has ordered this product
        $this->db->query('SELECT COUNT(*) as count 
                         FROM order_items oi
                         INNER JOIN orders o ON oi.order_id = o.id
                         WHERE o.user_id = :user_id AND oi.product_id = :product_id AND o.status = "completed"');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        $result = $this->db->single();

        if ($result->count == 0) {
            return false;
        }

        // Check if user already reviewed
        $this->db->query('SELECT COUNT(*) as count FROM product_reviews WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        $existing = $this->db->single();

        return $existing->count == 0;
    }

    /**
     * Get review by ID
     */
    public function getReviewById($id) {
        $this->db->query('SELECT * FROM product_reviews WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
