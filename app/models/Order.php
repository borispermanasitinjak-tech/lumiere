<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Create new order with items
     * @param array $data Order data with items
     * @return int|false Order ID or false
     */
    public function createOrder($data) {
        try {
            // Insert order
            $this->db->query('INSERT INTO orders (user_id, total_amount, status) VALUES(:user_id, :total_amount, :status)');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':status', $data['status'] ?? 'pending');
            
            if ($this->db->execute()) {
                $order_id = $this->db->lastInsertId();
                
                // Insert order items
                foreach ($data['items'] as $item) {
                    $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES(:order_id, :product_id, :quantity, :price)');
                    $this->db->bind(':order_id', $order_id);
                    $this->db->bind(':product_id', $item['product_id']);
                    $this->db->bind(':quantity', $item['quantity']);
                    $this->db->bind(':price', $item['price']);
                    $this->db->execute();
                }
                
                return $order_id;
            }
            
            return false;
        } catch (Exception $e) {
            error_log('Order creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order by ID with items
     * @param int $id Order ID
     * @return object|false Order object or false
     */
    public function getOrderById($id) {
        $this->db->query('SELECT * FROM orders WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $order = $this->db->single();
        
        if ($order) {
            $order->items = $this->getOrderItems($id);
        }
        
        return $order;
    }

    /**
     * Get order items for an order
     * @param int $order_id Order ID
     * @return array Order items
     */
    public function getOrderItems($order_id) {
        $this->db->query('SELECT oi.*, p.name as product_name, p.image as product_image 
                         FROM order_items oi
                         INNER JOIN products p ON oi.product_id = p.id
                         WHERE oi.order_id = :order_id');
        $this->db->bind(':order_id', $order_id);
        
        return $this->db->resultSet();
    }

    /**
     * Get all orders for a user
     * @param int $user_id User ID
     * @return array Orders
     */
    public function getUserOrders($user_id) {
        $this->db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->resultSet();
    }

    /**
     * Get all orders (for admin)
     * @return array All orders with user info
     */
    public function getAllOrders() {
        $this->db->query('SELECT o.*, u.username, u.email 
                         FROM orders o
                         INNER JOIN users u ON o.user_id = u.id
                         ORDER BY o.created_at DESC');
        
        return $this->db->resultSet();
    }

    /**
     * Update order status
     * @param int $id Order ID
     * @param string $status New status
     * @return bool Success
     */
    public function updateStatus($id, $status) {
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }

    /**
     * Get order statistics (for admin dashboard)
     * @return object Statistics
     */
    public function getOrderStats() {
        $this->db->query('SELECT 
                         COUNT(*) as total_orders,
                         SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders,
                         SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_orders,
                         SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders,
                         SUM(total_amount) as total_revenue,
                         SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as completed_revenue
                         FROM orders');
        
        return $this->db->single();
    }

    /**
     * Get recent orders (for admin dashboard)
     * @param int $limit Number of orders
     * @return array Recent orders
     */
    public function getRecentOrders($limit = 10) {
        $this->db->query('SELECT o.*, u.username 
                         FROM orders o
                         INNER JOIN users u ON o.user_id = u.id
                         ORDER BY o.created_at DESC
                         LIMIT :limit');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    /**
     * Get daily sales data for reports
     * @param int $days Number of days to fetch
     * @return array Daily sales
     */
    public function getDailySales($days = 30) {
        $this->db->query('SELECT 
                         DATE(created_at) as date,
                         COUNT(*) as orders,
                         SUM(total_amount) as revenue
                         FROM orders
                         WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                         GROUP BY DATE(created_at)
                         ORDER BY date ASC');
        $this->db->bind(':days', $days, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
}
