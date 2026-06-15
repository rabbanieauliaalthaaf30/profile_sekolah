<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_sekolah');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF8
mysqli_set_charset($conn, "utf8mb4");

// Function to prevent SQL injection
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($string));
}

// Function to execute query
function query($query) {
    global $conn;
    return mysqli_query($conn, $query);
}

// Function to fetch single row
function fetch($query) {
    $result = query($query);
    return mysqli_fetch_assoc($result);
}

// Function to fetch all rows
function fetchAll($query) {
    $result = query($query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Function to get last insert ID
function lastInsertId() {
    global $conn;
    return mysqli_insert_id($conn);
}

// Function to create slug from text
function createSlug($text) {
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);
    
    return empty($text) ? 'n-a' : $text;
}

// Function to format date
function formatTanggal($date, $format = 'd F Y') {
    if (!$date) return '-';
    
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $bulan[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    $time = date('H:i', $timestamp);
    
    if ($format == 'd F Y') {
        return $day . ' ' . $month . ' ' . $year;
    } elseif ($format == 'd F Y H:i') {
        return $day . ' ' . $month . ' ' . $year . ' ' . $time;
    } else {
        return date($format, $timestamp);
    }
}

// Function to limit text
function limitText($text, $limit = 100) {
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . '...';
    }
    return $text;
}

// Function to sanitize output
function clean($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
