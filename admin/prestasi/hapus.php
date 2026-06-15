<?php
require_once __DIR__ . '/../../config/config.php';
requireLogin();

$id       = (int)($_GET['id'] ?? 0);
$prestasi = fetch("SELECT * FROM prestasi WHERE id = $id");

if (!$prestasi) {
    setFlash('danger', 'Data prestasi tidak ditemukan.');
} else {
    if ($prestasi['foto_dokumentasi']) deleteFile(UPLOAD_PATH . 'prestasi/' . $prestasi['foto_dokumentasi']);
    query("DELETE FROM prestasi WHERE id = $id");
    logActivity($_SESSION['user_id'], 'HAPUS', 'prestasi', $id, "Menghapus prestasi: " . $prestasi['nama_prestasi']);
    setFlash('success', 'Prestasi berhasil dihapus.');
}

redirect(SITE_URL . '/admin/prestasi/index.php');
