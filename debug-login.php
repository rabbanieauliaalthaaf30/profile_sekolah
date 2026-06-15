<?php
require_once __DIR__ . '/config/config.php';

echo "<h2>Debug Login</h2>";

// 1. Cek koneksi database
echo "<h3>1. Koneksi Database</h3>";
if ($conn) {
    echo "✅ Koneksi OK - Database: <b>" . mysqli_get_host_info($conn) . "</b><br>";
    echo "DB Name: <b>db_sekolah</b><br>";
} else {
    echo "❌ Koneksi GAGAL<br>";
}

// 2. Cek tabel users ada
echo "<h3>2. Tabel Users</h3>";
$result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($result) > 0) {
    echo "✅ Tabel 'users' ditemukan<br>";
} else {
    echo "❌ Tabel 'users' TIDAK ADA - pastikan sudah import database.sql!<br>";
}

// 3. Cek data user
echo "<h3>3. Data User di Database</h3>";
$users = mysqli_query($conn, "SELECT id, username, nama_lengkap, role, status, LEFT(password,30) as pass_preview FROM users");
if ($users && mysqli_num_rows($users) > 0) {
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Nama</th><th>Role</th><th>Status</th><th>Password (30 char pertama)</th></tr>";
    while ($row = mysqli_fetch_assoc($users)) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>{$row['nama_lengkap']}</td>
            <td>{$row['role']}</td>
            <td>{$row['status']}</td>
            <td>{$row['pass_preview']}...</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "❌ Tidak ada data user! Tabel users kosong.<br>";
}

// 4. Test password_verify
echo "<h3>4. Test password_verify</h3>";
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username = 'admin'"));
if ($admin) {
    $test1 = password_verify('admin123', $admin['password']);
    echo "Test admin/admin123: " . ($test1 ? "✅ COCOK" : "❌ TIDAK COCOK") . "<br>";
    echo "Hash tersimpan: <code>" . htmlspecialchars($admin['password']) . "</code><br>";
    echo "Panjang hash: " . strlen($admin['password']) . " karakter (seharusnya 60)<br>";
} else {
    echo "❌ User 'admin' tidak ditemukan di database<br>";
}

// 5. Cek session
echo "<h3>5. Session Status</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Session save path: " . session_save_path() . "<br>";
$writable = is_writable(session_save_path() ?: sys_get_temp_dir());
echo "Session path writable: " . ($writable ? "✅ Ya" : "❌ Tidak") . "<br>";

// 6. Cek PHP version
echo "<h3>6. Info PHP</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "password_hash tersedia: " . (function_exists('password_hash') ? "✅ Ya" : "❌ Tidak") . "<br>";

// 7. Generate hash baru dan update langsung
echo "<h3>7. Reset Password (Otomatis)</h3>";
$new_hash_admin = password_hash('admin123', PASSWORD_BCRYPT);
$new_hash_staff = password_hash('staff123', PASSWORD_BCRYPT);

$esc_admin = mysqli_real_escape_string($conn, $new_hash_admin);
$esc_staff = mysqli_real_escape_string($conn, $new_hash_staff);

$r1 = mysqli_query($conn, "UPDATE users SET password = '$esc_admin' WHERE username = 'admin'");
$r2 = mysqli_query($conn, "UPDATE users SET password = '$esc_staff' WHERE username = 'staff1'");

echo "Update password admin: " . ($r1 ? "✅ Berhasil (affected: ".mysqli_affected_rows($conn).")" : "❌ Gagal - " . mysqli_error($conn)) . "<br>";
echo "Update password staff1: " . ($r2 ? "✅ Berhasil" : "❌ Gagal - " . mysqli_error($conn)) . "<br>";

// 8. Verifikasi ulang setelah update
echo "<h3>8. Verifikasi Setelah Reset</h3>";
$admin2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username = 'admin'"));
if ($admin2) {
    $ok = password_verify('admin123', $admin2['password']);
    echo "admin/admin123: " . ($ok ? "✅ BERHASIL - bisa login sekarang!" : "❌ Masih gagal") . "<br>";
    if (!$ok) {
        echo "Hash baru: <code>" . htmlspecialchars($admin2['password']) . "</code><br>";
    }
}

echo "<br><hr>";
echo "<a href='/profile_sekolah/admin/login.php' style='background:#2563eb;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;font-weight:bold;'>→ Coba Login Sekarang</a>";
echo "&nbsp;&nbsp;";
echo "<a href='/profile_sekolah/debug-login.php' style='background:#6b7280;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;font-weight:bold;'>🔄 Refresh Debug</a>";
?>
