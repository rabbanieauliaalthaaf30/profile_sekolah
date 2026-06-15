<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$nama   = trim($_POST['nama'] ?? '');
$email  = trim($_POST['email'] ?? '');
$subjek = trim($_POST['subjek'] ?? '');
$pesan  = trim($_POST['pesan'] ?? '');

// Validasi
if (!$nama || !$email || !$pesan) {
    echo json_encode(['success' => false, 'message' => 'Harap isi semua kolom yang wajib diisi.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
    exit;
}

// Sanitize
$nama   = escape($nama);
$email  = escape($email);
$subjek = escape($subjek);
$pesan  = escape($pesan);

// Simpan ke database
$result = query("INSERT INTO kontak_masuk (nama_pengirim, email_pengirim, subjek, pesan) VALUES ('$nama', '$email', '$subjek', '$pesan')");

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pesan. Coba lagi.']);
}
