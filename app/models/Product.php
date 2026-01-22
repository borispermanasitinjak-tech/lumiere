<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProducts() {
        $this->db->query('SELECT * FROM products ORDER BY created_at DESC');

        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query('SELECT * FROM products WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }
    
    // Admin functions
    public function addProduct($data) {
        $this->db->query('INSERT INTO products (name, category_id, description, price, stock, image) VALUES(:name, :category_id, :description, :price, :stock, :image)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateProduct($data) {
        $this->db->query('UPDATE products SET name = :name, category_id = :category_id, description = :description, price = :price, stock = :stock, image = :image WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id) {
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Search products by name or description
     */
    public function searchProducts($keyword, $filters = []) {
        $sql = 'SELECT * FROM products WHERE (name LIKE :keyword OR description LIKE :keyword)';
        
        // Add category filter
        if (isset($filters['category_id']) && $filters['category_id']) {
            $sql .= ' AND category_id = :category_id';
        }
        
        // Add price range filter
        if (isset($filters['min_price']) && $filters['min_price']) {
            $sql .= ' AND price >= :min_price';
        }
        if (isset($filters['max_price']) && $filters['max_price']) {
            $sql .= ' AND price <= :max_price';
        }
        
        // Add rating filter
        if (isset($filters['min_rating']) && $filters['min_rating']) {
            $sql .= ' AND average_rating >= :min_rating';
        }
        
        $sql .= ' ORDER BY created_at DESC';
        
        $this->db->query($sql);
        $this->db->bind(':keyword', '%' . $keyword . '%');
        
        if (isset($filters['category_id']) && $filters['category_id']) {
            $this->db->bind(':category_id', $filters['category_id']);
        }
        if (isset($filters['min_price']) && $filters['min_price']) {
            $this->db->bind(':min_price', $filters['min_price']);
        }
        if (isset($filters['max_price']) && $filters['max_price']) {
            $this->db->bind(':max_price', $filters['max_price']);
        }
        if (isset($filters['min_rating']) && $filters['min_rating']) {
            $this->db->bind(':min_rating', $filters['min_rating']);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory($category_id) {
        $this->db->query('SELECT * FROM products WHERE category_id = :category_id ORDER BY created_at DESC');
        $this->db->bind(':category_id', $category_id);
        return $this->db->resultSet();
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts($threshold = 10) {
        $this->db->query('SELECT * FROM products WHERE stock <= :threshold ORDER BY stock ASC');
        $this->db->bind(':threshold', $threshold);
        return $this->db->resultSet();
    }

    /**
     * Get product count
     */
    public function getProductCount() {
        $this->db->query('SELECT COUNT(*) as count FROM products');
        $result = $this->db->single();
        return $result->count ?? 0;
    }
}
