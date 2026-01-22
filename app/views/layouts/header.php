<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>"><?php echo APP_NAME; ?></a>
                </div>
                <ul class="nav-links">
                    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/home/catalog">Shop</a></li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['user_role'] == 'admin'): ?>
                            <li><a href="<?php echo BASE_URL; ?>/admin/orders">Orders</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/admin/payment_methods">Payment</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/admin/promo_codes">Promo</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/admin/dashboard">Admin Panel</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo BASE_URL; ?>/user/dashboard">My Orders</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/wishlist"><i class="fas fa-heart"></i> Wishlist</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/user/profile">Profile</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo BASE_URL; ?>/auth/logout">Logout</a></li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/cart" class="btn btn-outline">
                                <i class="fas fa-shopping-cart"></i> 
                                (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)
                            </a>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/auth/login">Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-primary">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    $flash_types = ['success', 'error', 'message'];
    foreach ($flash_types as $type) {
        if (isset($_SESSION['flash_' . $type])) {
            $class = $type == 'error' ? 'danger' : ($type == 'message' ? 'info' : 'success');
            echo '<div class="container" style="margin-top: 20px;"><div class="alert alert-' . $class . '">' . $_SESSION['flash_' . $type] . '</div></div>';
            unset($_SESSION['flash_' . $type]);
        }
    }
    ?>
