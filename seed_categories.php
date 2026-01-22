<?php
// Load the necessary files to connect to the DB
require_once 'app/config/config.php';
require_once 'app/libraries/Database.php';

$db = new Database;

$categories = [
    ['name' => 'Lips', 'description' => 'Lipsticks, glosses, and balms'],
    ['name' => 'Eyes', 'description' => 'Mascara, eyeshadows, and liners'],
    ['name' => 'Face', 'description' => 'Foundation, concealer, and powder']
];

echo "Checking categories...<br>";

$db->query("SELECT count(*) as count FROM categories");
$row = $db->single();

if ($row->count > 0) {
    echo "Categories already exist! count: " . $row->count;
} else {
    echo "Seeding categories...<br>";
    foreach ($categories as $cat) {
        $db->query("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $db->bind(':name', $cat['name']);
        $db->bind(':description', $cat['description']);
        
        if($db->execute()){
             echo "Inserted: " . $cat['name'] . "<br>";
        } else {
             echo "Failed to insert: " . $cat['name'] . "<br>";
        }
    }
    echo "Done! <a href='" . BASE_URL . "/admin/add_product'>Go back to Add Product</a>";
}
