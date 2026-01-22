<?php
/**
 * Security Helper Functions
 * Provides XSS protection, CSRF protection, and other security utilities
 */

/**
 * Escape output to prevent XSS attacks
 * @param string $string The string to escape
 * @return string The escaped string
 */
function e($string) {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 * @return string The CSRF token
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token The token to verify
 * @return bool True if valid, false otherwise
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate CSRF token input field for forms
 * @return string HTML input field
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Sanitize filename for uploads
 * @param string $filename Original filename
 * @return string Sanitized filename
 */
function sanitize_filename($filename) {
    // Remove any path info
    $filename = basename($filename);
    // Remove special characters
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    return $filename;
}

/**
 * Generate random filename for uploads
 * @param string $extension File extension
 * @return string Random filename
 */
function generate_random_filename($extension) {
    return bin2hex(random_bytes(16)) . '.' . $extension;
}

/**
 * Validate image file
 * @param array $file $_FILES array element
 * @return array ['success' => bool, 'message' => string, 'mime' => string]
 */
function validate_image_file($file) {
    $allowed_mimes = [
        'image/jpeg',
        'image/jpg', 
        'image/png',
        'image/gif',
        'image/webp'
    ];
    
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    // Check file size (5MB max)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File too large. Maximum 5MB'];
    }
    
    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed_mimes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only images allowed'];
    }
    
    // Validate extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Invalid file extension'];
    }
    
    return ['success' => true, 'message' => 'Valid image file', 'mime' => $mime, 'extension' => $extension];
}

/**
 * Redirect helper
 * @param string $location Location to redirect to
 */
function redirect($location) {
    header('Location: ' . BASE_URL . '/' . $location);
    exit;
}

/**
 * Check if user is logged in
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 * @return bool
 */
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Require authentication
 * Redirects to login if not authenticated
 */
function require_auth() {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('auth/login');
    }
}

/**
 * Require admin role
 * Redirects to home if not admin
 */
function require_admin() {
    require_auth();
    if (!is_admin()) {
        redirect('home');
    }
}
