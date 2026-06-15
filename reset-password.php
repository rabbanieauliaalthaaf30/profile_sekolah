<?php
// ⚠️ FILE INI HANYA UNTUK SETUP AWAL - HAPUS SETELAH DIGUNAKAN!
require_once __DIR__ . '/config/config.php';

$done = [];

// Reset password admin => admin123
$hash_admin = password_hash('admin123', PASSWORD_BCRYPT);
query("UPDATE users SET password = '" . mysqli_real_escape_string($conn, $hash_admin) . "' WHERE username = 'admin'");
$done[] = "✅ Password admin direset → <strong>admin123</strong>";

// Reset password staff1 => staff123
$hash_staff = password_hash('staff123', PASSWORD_BCRYPT);
query("UPDATE users SET password = '" . mysqli_real_escape_string($conn, $hash_staff) . "' WHERE username = 'staff1'");
$done[] = "✅ Password staff1 direset → <strong>staff123</strong>";

// Update logo sekolah
query("UPDATE profil_sekolah SET logo = 'logo-sekolah.png' WHERE id = 1");
$done[] = "✅ Logo sekolah diperbarui → <strong>logo-sekolah.png</strong>";

// Verifikasi
$admin = fetch("SELECT username, password FROM users WHERE username = 'admin'");
$staff = fetch("SELECT username, password FROM users WHERE username = 'staff1'");

$verify_admin = password_verify('admin123', $admin['password']) ? '✅ Berhasil' : '❌ Gagal';
$verify_staff = password_verify('staff123', $staff['password']) ? '✅ Berhasil' : '❌ Gagal';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; padding: 20px; background: #f9fafb; }
        .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        h2 { color: #1e40af; }
        .item { padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; }
        .verify { background: #f0fdf4; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .warning { background: #fef2f2; border: 1px solid #fca5a5; padding: 15px; border-radius: 8px; color: #991b1b; margin-top: 20px; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🔧 Setup Password & Logo</h2>
        <p style="color:#6b7280;">Proses reset berhasil:</p>

        <?php foreach ($done as $d): ?>
        <div class="item"><?php echo $d; ?></div>
        <?php endforeach; ?>

        <div class="verify">
            <strong>Verifikasi Login:</strong><br>
            admin / admin123 → <?php echo $verify_admin; ?><br>
            staff1 / staff123 → <?php echo $verify_staff; ?>
        </div>

        <a href="<?php echo SITE_URL; ?>/admin/login.php" class="btn">→ Pergi ke Halaman Login</a>

        <div class="warning">
            ⚠️ <strong>PENTING:</strong> Segera hapus file <code>reset-password.php</code> setelah login berhasil!
        </div>
    </div>
</body>
</html>
