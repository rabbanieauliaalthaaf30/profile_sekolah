<?php
require_once __DIR__ . '/../../config/config.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$berita = fetch("SELECT * FROM berita WHERE id = $id");

if (!$berita) {
    setFlash('danger', 'Berita tidak ditemukan.');
} else {
    // Delete foto
    if ($berita['foto_utama']) deleteFile(UPLOAD_PATH . 'berita/' . $berita['foto_utama']);

    query("DELETE FROM berita WHERE id = $id");
    logActivity($_SESSION['user_id'], 'HAPUS', 'berita', $id, "Menghapus berita: " . $berita['judul']);
    setFlash('success', 'Berita berhasil dihapus.');
}

redirect(SITE_URL . '/admin/berita/index.php');
