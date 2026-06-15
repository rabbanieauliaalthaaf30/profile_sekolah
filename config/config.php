<?php
ob_start();
// General Configuration
date_default_timezone_set('Asia/Jakarta');

// Site Configuration
define('SITE_NAME', 'SMA Negeri 1 Harapan Bangsa');
define('SITE_URL', 'http://localhost/profile_sekolah');
define('BASE_PATH', __DIR__ . '/../');

// Upload Configuration
define('UPLOAD_PATH', BASE_PATH . 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes

// Allowed file extensions
define('ALLOWED_IMAGE_EXT', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCUMENT_EXT', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Pagination
define('ITEMS_PER_PAGE', 10);
define('BERITA_PER_PAGE', 6);
define('GALERI_PER_PAGE', 12);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database
require_once __DIR__ . '/database.php';

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Function to check if user is staff
function isStaff() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'staff';
}

// Function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

// Function to require admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/admin/index.php');
        exit;
    }
}

// Function to redirect
function redirect($url) {
    session_write_close();
    header('Location: ' . $url);
    exit;
}

// Function to set flash message
function setFlash($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

// Function to get and clear flash message
function getFlash() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'];
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return ['type' => $type, 'message' => $message];
    }
    return null;
}

// Function to log activity (Audit Log)
function logActivity($user_id, $aksi, $tabel, $id_data = null, $deskripsi = '') {
    global $conn;

    $user_id    = (int)$user_id;
    $aksi       = mysqli_real_escape_string($conn, $aksi);
    $tabel      = mysqli_real_escape_string($conn, $tabel);
    $id_data    = $id_data !== null ? (int)$id_data : 'NULL';
    $deskripsi  = mysqli_real_escape_string($conn, $deskripsi);
    $ip_address = mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR'] ?? '');
    $user_agent = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT'] ?? '');

    $id_val = $id_data === 'NULL' ? 'NULL' : $id_data;

    mysqli_query($conn, "INSERT INTO audit_log (user_id, aksi, tabel, id_data, deskripsi, ip_address, user_agent)
                         VALUES ($user_id, '$aksi', '$tabel', $id_val, '$deskripsi', '$ip_address', '$user_agent')");
}

// Function to upload file
function uploadFile($file, $targetDir, $allowedExt = null) {
    if (!isset($file) || $file['error'] != 0) {
        return ['success' => false, 'message' => 'File upload error'];
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File too large (max 5MB)'];
    }
    
    // Get file extension
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Set allowed extensions
    if ($allowedExt === null) {
        $allowedExt = ALLOWED_IMAGE_EXT;
    }
    
    // Check file extension
    if (!in_array($fileExt, $allowedExt)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    // Generate unique filename
    $newFilename = uniqid() . '_' . time() . '.' . $fileExt;
    $targetPath = $targetDir . $newFilename;
    
    // Create directory if not exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $newFilename];
    }
    
    return ['success' => false, 'message' => 'Failed to move uploaded file'];
}

// Function to delete file
function deleteFile($filepath) {
    if (file_exists($filepath) && is_file($filepath)) {
        return unlink($filepath);
    }
    return false;
}

// Function to generate random password
function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

// Function to get client IP
function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
