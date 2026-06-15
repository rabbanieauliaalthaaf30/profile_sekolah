<?php
require_once __DIR__ . '/../../config/config.php';
requireLogin();

$id     = (int)($_GET['id'] ?? 0);
$galeri = fetch("SELECT * FROM galeri WHERE id = $id");

if (!$galeri) {
    setFlash('danger', 'Galeri tidak ditemukan.');
} else {
    if ($galeri['foto']) deleteFile(UPLOAD_PATH . 'galeri/' . $galeri['foto']);
    query("DELETE FROM galeri WHERE id = $id");
    logActivity($_SESSION['user_id'], 'HAPUS', 'galeri', $id, "Menghapus galeri: " . $galeri['judul']);
    setFlash('success', 'Galeri berhasil dihapus.');
}

redirect(SITE_URL . '/admin/galeri/index.php');
