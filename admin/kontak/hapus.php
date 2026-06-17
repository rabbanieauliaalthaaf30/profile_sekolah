<?php
require_once __DIR__ . '/../../config/config.php';
requireLogin();

$id     = (int)($_GET['id'] ?? 0);
$kontak = fetch("SELECT * FROM kontak_masuk WHERE id = $id");

if (!$kontak) {
    setFlash('danger', 'Pesan tidak ditemukan.');
} else {
    query("DELETE FROM kontak_masuk WHERE id = $id");
    setFlash('success', 'Pesan berhasil dihapus.');
}

redirect(SITE_URL . '/admin/kontak/index.php');
