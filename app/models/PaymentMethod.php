<?php
class PaymentMethod {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all payment methods (admin)
     */
    public function getAllMethods() {
        $this->db->query('SELECT * FROM payment_methods ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    /**
     * Get active payment methods (for users)
     */
    public function getActiveMethods() {
        $this->db->query('SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY id ASC');
        return $this->db->resultSet();
    }

    /**
     * Get payment method by ID
     */
    public function getMethodById($id) {
        $this->db->query('SELECT * FROM payment_methods WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Add payment method
     */
    public function addMethod($data) {
        $this->db->query('INSERT INTO payment_methods (name, type, account_name, account_number, instructions, is_active) 
                         VALUES(:name, :type, :account_name, :account_number, :instructions, :is_active)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':account_name', $data['account_name'] ?? null);
        $this->db->bind(':account_number', $data['account_number'] ?? null);
        $this->db->bind(':instructions', $data['instructions'] ?? null);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        return $this->db->execute();
    }

    /**
     * Update payment method
     */
    public function updateMethod($data) {
        $this->db->query('UPDATE payment_methods SET 
                         name = :name,
                         type = :type,
                         account_name = :account_name,
                         account_number = :account_number,
                         instructions = :instructions,
                         is_active = :is_active
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':account_name', $data['account_name'] ?? null);
        $this->db->bind(':account_number', $data['account_number'] ?? null);
        $this->db->bind(':instructions', $data['instructions'] ?? null);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        return $this->db->execute();
    }

    /**
     * Delete payment method
     */
    public function deleteMethod($id) {
        $this->db->query('DELETE FROM payment_methods WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id) {
        $this->db->query('UPDATE payment_methods SET is_active = NOT is_active WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
