<?php
require_once __DIR__ . '/../../config/config.php';
requireAdmin();

$id        = (int)($_GET['id'] ?? 0);
$fasilitas = fetch("SELECT * FROM fasilitas WHERE id = $id");

if (!$fasilitas) {
    setFlash('danger', 'Data fasilitas tidak ditemukan.');
} else {
    if ($fasilitas['foto']) deleteFile(UPLOAD_PATH . 'fasilitas/' . $fasilitas['foto']);
    query("DELETE FROM fasilitas WHERE id = $id");
    setFlash('success', 'Fasilitas berhasil dihapus.');
}

redirect(SITE_URL . '/admin/fasilitas/index.php');
