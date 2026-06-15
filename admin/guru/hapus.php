<?php
require_once __DIR__ . '/../../config/config.php';
requireAdmin();

$id   = (int)($_GET['id'] ?? 0);
$guru = fetch("SELECT * FROM guru WHERE id = $id");

if (!$guru) {
    setFlash('danger', 'Data guru tidak ditemukan.');
} else {
    if ($guru['foto']) deleteFile(UPLOAD_PATH . 'guru/' . $guru['foto']);
    query("DELETE FROM guru WHERE id = $id");
    logActivity($_SESSION['user_id'], 'HAPUS', 'guru', $id, "Menghapus data guru: " . $guru['nama_lengkap']);
    setFlash('success', 'Data guru berhasil dihapus.');
}

redirect(SITE_URL . '/admin/guru/index.php');
