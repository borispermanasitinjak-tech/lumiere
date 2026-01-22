<?php
// Quick test file to check checkout access
require_once './config/config.php';

echo "<h2>Checkout Debug Test</h2>";

// Test 1: Check if files exist
echo "<h3>1. File Checks:</h3>";
$checkoutController = APPROOT . '/app/controllers/CheckoutController.php';
echo "CheckoutController exists: " . (file_exists($checkoutController) ? "✅ YES" : "❌ NO") . "<br>";

$addressModel = APPROOT . '/app/models/ShippingAddress.php';
echo "ShippingAddress model exists: " . (file_exists($addressModel) ? "✅ YES" : "❌ NO") . "<br>";

$paymentModel = APPROOT . '/app/models/PaymentMethod.php';
echo "PaymentMethod model exists: " . (file_exists($paymentModel) ? "✅ YES" : "❌ NO") . "<br>";

// Test 2: Check session
session_start();
echo "<h3>2. Session Check:</h3>";
echo "User logged in: " . (isset($_SESSION['user_id']) ? "✅ YES (ID: {$_SESSION['user_id']})" : "❌ NO") . "<br>";
echo "User role: " . ($_SESSION['user_role'] ?? 'Not set') . "<br>";

// Test 3: Check cart
echo "<h3>3. Cart Check:</h3>";
echo "Cart exists: " . (isset($_SESSION['cart']) ? "✅ YES" : "❌ NO") . "<br>";
if (isset($_SESSION['cart'])) {
    echo "Cart items: " . count($_SESSION['cart']) . "<br>";
    echo "<pre>";
    print_r($_SESSION['cart']);
    echo "</pre>";
}

// Test 4: Check database connection
echo "<h3>4. Database Check:</h3>";
try {
    require_once APPROOT . '/core/Database.php';
    $db = new Database();
    echo "Database connection: ✅ SUCCESS<br>";
    
    // Check if shipping_addresses table exists
    $db->query("SHOW TABLES LIKE 'shipping_addresses'");
    $result = $db->single();
    echo "shipping_addresses table: " . ($result ? "✅ EXISTS" : "❌ NOT FOUND") . "<br>";
    
    // Check if payment_methods table exists
    $db->query("SHOW TABLES LIKE 'payment_methods'");
    $result = $db->single();
    echo "payment_methods table: " . ($result ? "✅ EXISTS" : "❌ NOT FOUND") . "<br>";
    
    // Check if user has addresses
    if (isset($_SESSION['user_id'])) {
        $db->query("SELECT COUNT(*) as count FROM shipping_addresses WHERE user_id = :user_id");
        $db->bind(':user_id', $_SESSION['user_id']);
        $result = $db->single();
        echo "User shipping addresses: " . ($result->count ?? 0) . "<br>";
    }
    
} catch (Exception $e) {
    echo "Database connection: ❌ FAILED - " . $e->getMessage() . "<br>";
}

// Test 5: Direct checkout URL
echo "<h3>5. Direct Test:</h3>";
echo '<a href="' . BASE_URL . '/checkout" style="padding: 10px 20px; background: #8B5A3C; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Click Here to Test Checkout</a><br><br>';

echo "<h3>6. Instructions:</h3>";
echo "<ul>";
echo "<li>If you're NOT logged in → Login first at <a href='" . BASE_URL . "/auth/login'>Login Page</a></li>";
echo "<li>If you DON'T have items in cart → Add products first</li>";
echo "<li>If you DON'T have shipping address → Will redirect to add address page</li>";
echo "</ul>";
?>
