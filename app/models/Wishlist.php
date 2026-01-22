<?php
class Wishlist {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get user's wishlist with product details
     */
    public function getUserWishlist($user_id) {
        $this->db->query('SELECT w.*, p.name, p.price, p.image, p.stock, p.average_rating, p.review_count
                         FROM wishlist w
                         INNER JOIN products p ON w.product_id = p.id
                         WHERE w.user_id = :user_id
                         ORDER BY w.created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    /**
     * Add product to wishlist
     */
    public function addToWishlist($user_id, $product_id) {
        try {
            $this->db->query('INSERT INTO wishlist (user_id, product_id) VALUES(:user_id, :product_id)');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':product_id', $product_id);
            return $this->db->execute();
        } catch (Exception $e) {
            // Duplicate entry (already in wishlist)
            return false;
        }
    }

    /**
     * Remove product from wishlist
     */
    public function removeFromWishlist($user_id, $product_id) {
        $this->db->query('DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }

    /**
     * Check if product is in user's wishlist
     */
    public function isInWishlist($user_id, $product_id) {
        $this->db->query('SELECT COUNT(*) as count FROM wishlist WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        $result = $this->db->single();
        return $result->count > 0;
    }

    /**
     * Get wishlist count for user
     */
    public function getWishlistCount($user_id) {
        $this->db->query('SELECT COUNT(*) as count FROM wishlist WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        return $result->count ?? 0;
    }
}
