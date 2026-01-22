<?php
class ShippingAddress {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all addresses for a user
     */
    public function getUserAddresses($user_id) {
        $this->db->query('SELECT * FROM shipping_addresses WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    /**
     * Get address by ID
     */
    public function getAddressById($id) {
        $this->db->query('SELECT * FROM shipping_addresses WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Get default address for user
     */
    public function getDefaultAddress($user_id) {
        $this->db->query('SELECT * FROM shipping_addresses WHERE user_id = :user_id AND is_default = 1');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    /**
     * Add new address
     */
    public function addAddress($data) {
        // If this is set as default, unset other defaults
        if (isset($data['is_default']) && $data['is_default']) {
            $this->db->query('UPDATE shipping_addresses SET is_default = 0 WHERE user_id = :user_id');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();
        }

        $this->db->query('INSERT INTO shipping_addresses (user_id, recipient_name, phone, address_line1, address_line2, city, province, postal_code, is_default) 
                         VALUES(:user_id, :recipient_name, :phone, :address_line1, :address_line2, :city, :province, :postal_code, :is_default)');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':recipient_name', $data['recipient_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address_line1', $data['address_line1']);
        $this->db->bind(':address_line2', $data['address_line2'] ?? '');
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':is_default', $data['is_default'] ?? 0);

        return $this->db->execute();
    }

    /**
     * Update address
     */
    public function updateAddress($data) {
        // If this is set as default, unset other defaults
        if (isset($data['is_default']) && $data['is_default']) {
            $this->db->query('UPDATE shipping_addresses SET is_default = 0 WHERE user_id = :user_id AND id != :id');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();
        }

        $this->db->query('UPDATE shipping_addresses SET 
                         recipient_name = :recipient_name,
                         phone = :phone,
                         address_line1 = :address_line1,
                         address_line2 = :address_line2,
                         city = :city,
                         province = :province,
                         postal_code = :postal_code,
                         is_default = :is_default
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':recipient_name', $data['recipient_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address_line1', $data['address_line1']);
        $this->db->bind(':address_line2', $data['address_line2'] ?? '');
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':is_default', $data['is_default'] ?? 0);

        return $this->db->execute();
    }

    /**
     * Delete address
     */
    public function deleteAddress($id) {
        $this->db->query('DELETE FROM shipping_addresses WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Set address as default
     */
    public function setDefault($id, $user_id) {
        // Unset all defaults for user
        $this->db->query('UPDATE shipping_addresses SET is_default = 0 WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        // Set this one as default
        $this->db->query('UPDATE shipping_addresses SET is_default = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
