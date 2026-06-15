<?php
require_once __DIR__ . '/../../config/config.php';
requireAdmin();

$id    = (int)($_GET['id'] ?? 0);
$staff = fetch("SELECT * FROM staff WHERE id = $id");

if (!$staff) {
    setFlash('danger', 'Data staff tidak ditemukan.');
} else {
    if ($staff['foto']) deleteFile(UPLOAD_PATH . 'staff/' . $staff['foto']);
    query("DELETE FROM staff WHERE id = $id");
    logActivity($_SESSION['user_id'], 'HAPUS', 'staff', $id, "Menghapus data staff: " . $staff['nama_lengkap']);
    setFlash('success', 'Data staff berhasil dihapus.');
}

redirect(SITE_URL . '/admin/staff/index.php');
