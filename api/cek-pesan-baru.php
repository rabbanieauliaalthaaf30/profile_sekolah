<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';

// Hanya bisa diakses kalau sudah login
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Ambil timestamp terakhir yang dikirim client
$since = trim($_GET['since'] ?? '');

$where = "WHERE 1=1";
if ($since) {
    $since_esc = escape($since);
    $where .= " AND tanggal_kirim > '$since_esc'";
}

// Total pesan belum dibaca
$total_belum = fetch("SELECT COUNT(*) as t FROM kontak_masuk WHERE status = 'belum_dibaca'")['t'] ?? 0;

// Pesan baru sejak timestamp terakhir
$pesan_baru = fetchAll("SELECT id, nama_pengirim, email_pengirim, subjek, tanggal_kirim
                         FROM kontak_masuk
                         $where
                         ORDER BY tanggal_kirim DESC
                         LIMIT 10");

// Timestamp server sekarang (untuk dikirim ke client sebagai referensi berikutnya)
$server_time = date('Y-m-d H:i:s');

echo json_encode([
    'success'      => true,
    'total_belum'  => (int)$total_belum,
    'pesan_baru'   => $pesan_baru,
    'jumlah_baru'  => count($pesan_baru),
    'server_time'  => $server_time,
]);
