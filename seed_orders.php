<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = new Database();

// Get a user ID (User role)
$db->query("SELECT id FROM users WHERE role = 'user' LIMIT 1");
$user = $db->single();
$userId = $user->id;

// Dummy data for the last 7 days
$orders = [
    ['date' => date('Y-m-d', strtotime('-6 days')), 'amount' => 150.00, 'count' => 3],
    ['date' => date('Y-m-d', strtotime('-5 days')), 'amount' => 220.50, 'count' => 5],
    ['date' => date('Y-m-d', strtotime('-4 days')), 'amount' => 85.00, 'count' => 2],
    ['date' => date('Y-m-d', strtotime('-3 days')), 'amount' => 310.00, 'count' => 6],
    ['date' => date('Y-m-d', strtotime('-2 days')), 'amount' => 190.00, 'count' => 4],
    ['date' => date('Y-m-d', strtotime('-1 days')), 'amount' => 450.00, 'count' => 8],
    ['date' => date('Y-m-d'), 'amount' => 120.00, 'count' => 2],
];

echo "Seeding Orders...<br>";

foreach ($orders as $day) {
    // Insert orders for this day
    for ($i = 0; $i < $day['count']; $i++) {
        // Split amount roughly
        $avgAmount = $day['amount'] / $day['count'];
        $amount = $avgAmount + rand(-10, 10);
        
        $sql = "INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (:user_id, :total_amount, 'completed', :created_at)";
        $db->query($sql);
        $db->bind(':user_id', $userId);
        $db->bind(':total_amount', $amount);
        $db->bind(':created_at', $day['date'] . ' ' . rand(10, 20) . ':00:00');
        $db->execute();
    }
    echo "Inserted " . $day['count'] . " orders for " . $day['date'] . "<br>";
}

echo "<br><b>Done!</b> <a href='" . BASE_URL . "/admin/reports'>View Reports</a>";
