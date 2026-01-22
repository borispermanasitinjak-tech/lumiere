<?php
class PromoCode {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all promo codes (admin)
     */
    public function getAllCodes() {
        $this->db->query('SELECT * FROM promo_codes ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    /**
     * Get active promo codes
     */
    public function getActiveCodes() {
        $this->db->query('SELECT * FROM promo_codes 
                         WHERE is_active = 1 
                         AND valid_from <= NOW() 
                         AND valid_until >= NOW()
                         ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    /**
     * Get promo code by code string
     */
    public function getCodeByString($code) {
        $this->db->query('SELECT * FROM promo_codes WHERE code = :code');
        $this->db->bind(':code', strtoupper($code));
        return $this->db->single();
    }

    /**
     * Get promo code by ID
     */
    public function getCodeById($id) {
        $this->db->query('SELECT * FROM promo_codes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Add promo code
     */
    public function addCode($data) {
        $this->db->query('INSERT INTO promo_codes (code, description, discount_type, discount_value, min_purchase, max_discount, usage_limit, valid_from, valid_until, is_active) 
                         VALUES(:code, :description, :discount_type, :discount_value, :min_purchase, :max_discount, :usage_limit, :valid_from, :valid_until, :is_active)');
        
        $this->db->bind(':code', strtoupper($data['code']));
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':discount_type', $data['discount_type']);
        $this->db->bind(':discount_value', $data['discount_value']);
        $this->db->bind(':min_purchase', $data['min_purchase'] ?? 0);
        $this->db->bind(':max_discount', $data['max_discount'] ?? null);
        $this->db->bind(':usage_limit', $data['usage_limit'] ?? null);
        $this->db->bind(':valid_from', $data['valid_from']);
        $this->db->bind(':valid_until', $data['valid_until']);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        return $this->db->execute();
    }

    /**
     * Update promo code
     */
    public function updateCode($data) {
        $this->db->query('UPDATE promo_codes SET 
                         code = :code,
                         description = :description,
                         discount_type = :discount_type,
                         discount_value = :discount_value,
                         min_purchase = :min_purchase,
                         max_discount = :max_discount,
                         usage_limit = :usage_limit,
                         valid_from = :valid_from,
                         valid_until = :valid_until,
                         is_active = :is_active
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':code', strtoupper($data['code']));
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':discount_type', $data['discount_type']);
        $this->db->bind(':discount_value', $data['discount_value']);
        $this->db->bind(':min_purchase', $data['min_purchase'] ?? 0);
        $this->db->bind(':max_discount', $data['max_discount'] ?? null);
        $this->db->bind(':usage_limit', $data['usage_limit'] ?? null);
        $this->db->bind(':valid_from', $data['valid_from']);
        $this->db->bind(':valid_until', $data['valid_until']);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        return $this->db->execute();
    }

    /**
     * Delete promo code
     */
    public function deleteCode($id) {
        $this->db->query('DELETE FROM promo_codes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Increment usage count
     */
    public function incrementUsage($id) {
        $this->db->query('UPDATE promo_codes SET used_count = used_count + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Validate promo code
     */
    public function validateCode($code, $total) {
        $promo = $this->getCodeByString($code);

        if (!$promo) {
            return ['valid' => false, 'message' => 'Invalid promo code'];
        }

        // Check if active
        if (!$promo->is_active) {
            return ['valid' => false, 'message' => 'Promo code is not active'];
        }

        // Check dates
        $now = date('Y-m-d H:i:s');
        if ($now < $promo->valid_from) {
            return ['valid' => false, 'message' => 'Promo code not yet valid'];
        }
        if ($now > $promo->valid_until) {
            return ['valid' => false, 'message' => 'Promo code has expired'];
        }

        // Check usage limit
        if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) {
            return ['valid' => false, 'message' => 'Promo code usage limit reached'];
        }

        // Check minimum purchase
        if ($promo->min_purchase > $total) {
            return ['valid' => false, 'message' => 'Minimum purchase of ' . format_rupiah($promo->min_purchase) . ' required'];
        }

        return ['valid' => true, 'promo' => $promo];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($promo, $total) {
        if ($promo->discount_type == 'percentage') {
            $discount = ($promo->discount_value / 100) * $total;
            
            // Apply max discount if set
            if ($promo->max_discount && $discount > $promo->max_discount) {
                $discount = $promo->max_discount;
            }
        } else {
            // Fixed discount
            $discount = $promo->discount_value;
        }

        // Discount can't be more than total
        if ($discount > $total) {
            $discount = $total;
        }

        return $discount;
    }
}
