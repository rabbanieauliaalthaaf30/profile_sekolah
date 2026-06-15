<?php
require_once __DIR__ . '/config/config.php';

// Update logo di database ke file yang sudah ada
query("UPDATE profil_sekolah SET logo = 'logo-sekolah.png' WHERE id = 1");

$affected = mysqli_affected_rows($conn);

$profil = fetch("SELECT logo FROM profil_sekolah LIMIT 1");

echo "<h3>Update Logo</h3>";
echo "Affected rows: $affected<br>";
echo "Logo sekarang: <b>" . clean($profil['logo']) . "</b><br>";
echo "<br>";

// Cek file ada
$path = __DIR__ . '/uploads/logo/logo-sekolah.png';
echo "File ada: " . (file_exists($path) ? "✅ Ya" : "❌ Tidak - upload dulu file logo-sekolah.png ke folder uploads/logo/") . "<br>";

echo "<br><a href='" . SITE_URL . "' style='background:#2563eb;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;'>→ Lihat Website</a>";
echo "&nbsp;&nbsp;<a href='" . SITE_URL . "/admin/' style='background:#059669;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;'>→ Dashboard Admin</a>";
?>
