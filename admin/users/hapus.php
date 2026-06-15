<?php
require_once __DIR__ . '/../../config/config.php';
requireAdmin();

$id   = (int)($_GET['id'] ?? 0);

// Tidak bisa hapus diri sendiri
if ($id == (int)$_SESSION['user_id']) {
    setFlash('danger', 'Anda tidak dapat menghapus akun Anda sendiri.');
    redirect(SITE_URL . '/admin/users/index.php');
}

$user = fetch("SELECT * FROM users WHERE id = $id");

if (!$user) {
    setFlash('danger', 'User tidak ditemukan.');
} else {
    query("DELETE FROM users WHERE id = $id");
    logActivity($_SESSION['user_id'], 'HAPUS', 'users', $id, "Menghapus user: " . $user['username']);
    setFlash('success', "User <strong>" . clean($user['username']) . "</strong> berhasil dihapus.");
}

redirect(SITE_URL . '/admin/users/index.php');
