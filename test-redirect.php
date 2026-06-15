<?php
ob_start();
require_once __DIR__ . '/config/config.php';

// Simulasi login manual tanpa password_verify
$user = fetch("SELECT * FROM users WHERE username = 'admin' AND status = 'aktif'");

if ($user) {
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama']     = $user['nama_lengkap'];
    $_SESSION['role']     = $user['role'];
    $_SESSION['foto']     = $user['foto_profil'];

    echo "Session berhasil diset:<br>";
    echo "user_id: " . $_SESSION['user_id'] . "<br>";
    echo "role: " . $_SESSION['role'] . "<br>";
    echo "<br>";
    echo "isLoggedIn(): " . (isLoggedIn() ? 'TRUE' : 'FALSE') . "<br>";
    echo "<br>";
    echo '<a href="' . SITE_URL . '/admin/index.php" style="background:#2563eb;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">→ Buka Dashboard Admin</a>';
} else {
    echo "User admin tidak ditemukan!";
}
?>
