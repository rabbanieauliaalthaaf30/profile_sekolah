<?php
$page_title  = 'Ganti Password';
$active_menu = '';
include __DIR__ . '/includes/header.php';

$id     = (int)$_SESSION['user_id'];
$user   = fetch("SELECT * FROM users WHERE id = $id");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama    = $_POST['password_lama'] ?? '';
    $password_baru    = $_POST['password_baru'] ?? '';
    $konfirmasi       = $_POST['konfirmasi_password'] ?? '';

    if (!$password_lama)  $errors[] = 'Password lama wajib diisi.';
    if (!$password_baru)  $errors[] = 'Password baru wajib diisi.';
    if (strlen($password_baru) < 6) $errors[] = 'Password baru minimal 6 karakter.';
    if ($password_baru !== $konfirmasi) $errors[] = 'Konfirmasi password tidak cocok.';

    // Verifikasi password lama
    if (empty($errors) && !password_verify($password_lama, $user['password'])) {
        $errors[] = 'Password lama yang Anda masukkan salah.';
    }

    if (empty($errors)) {
        $hashed_new = password_hash($password_baru, PASSWORD_BCRYPT);
        $escaped    = escape($hashed_new);
        query("UPDATE users SET password='$escaped' WHERE id=$id");

        setFlash('success', 'Password berhasil diubah!');
        redirect(SITE_URL . '/admin/ganti-password.php');
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-key"></i> Ganti Password</div>
            </div>
            <div class="admin-card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
                <?php endif; ?>

                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background:#f8fafc; border:1px solid #e5e7eb;">
                    <div style="width:48px; height:48px; background:#e0e7ff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:#4f46e5; font-size:18px; flex-shrink:0;">
                        <?php echo strtoupper(substr($user['nama_lengkap'], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="fw-semibold"><?php echo clean($user['nama_lengkap']); ?></div>
                        <div class="text-muted small">@<?php echo clean($user['username']); ?></div>
                    </div>
                </div>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="passLama" class="form-control" placeholder="Masukkan password saat ini" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passLama','eyeLama')"><i class="fas fa-eye" id="eyeLama"></i></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="passBaru" class="form-control" placeholder="Minimal 6 karakter" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passBaru','eyeBaru')"><i class="fas fa-eye" id="eyeBaru"></i></button>
                        </div>
                        <div id="strengthBar" class="mt-2" style="display:none;">
                            <div style="height:4px; border-radius:2px; background:#e5e7eb; overflow:hidden;">
                                <div id="strengthFill" style="height:100%; width:0; transition:width 0.3s, background 0.3s;"></div>
                            </div>
                            <small id="strengthText" class="text-muted"></small>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="konfirmasi_password" id="passKonfirm" class="form-control" placeholder="Ulangi password baru" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passKonfirm','eyeKonfirm')"><i class="fas fa-eye" id="eyeKonfirm"></i></button>
                        </div>
                        <div id="matchMsg" class="small mt-1"></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Ubah Password
                        </button>
                        <a href="<?php echo SITE_URL; ?>/admin/profil-saya.php" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Profil
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips -->
        <div class="admin-card mt-4">
            <div class="admin-card-body">
                <h6 class="fw-bold mb-2"><i class="fas fa-shield-alt text-primary me-2"></i>Tips Password Aman</h6>
                <ul class="small text-muted mb-0 ps-3">
                    <li>Gunakan minimal 8 karakter</li>
                    <li>Kombinasikan huruf besar, huruf kecil, angka, dan simbol</li>
                    <li>Jangan gunakan informasi pribadi (nama, tanggal lahir)</li>
                    <li>Gunakan password yang berbeda untuk setiap akun</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Password strength meter
document.getElementById('passBaru').addEventListener('input', function () {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');

    if (!val) { bar.style.display = 'none'; return; }
    bar.style.display = 'block';

    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '20%',  bg: '#ef4444', label: 'Sangat lemah' },
        { w: '40%',  bg: '#f97316', label: 'Lemah' },
        { w: '60%',  bg: '#eab308', label: 'Cukup' },
        { w: '80%',  bg: '#22c55e', label: 'Kuat' },
        { w: '100%', bg: '#16a34a', label: 'Sangat kuat' },
    ];
    const lvl = levels[Math.max(0, score - 1)];
    fill.style.width = lvl.w;
    fill.style.background = lvl.bg;
    text.textContent = lvl.label;
    text.style.color = lvl.bg;
});

// Confirm password match
document.getElementById('passKonfirm').addEventListener('input', function () {
    const baru     = document.getElementById('passBaru').value;
    const konfirm  = this.value;
    const msg      = document.getElementById('matchMsg');
    if (!konfirm) { msg.textContent = ''; return; }
    if (baru === konfirm) {
        msg.innerHTML = '<span style="color:#16a34a;"><i class="fas fa-check-circle me-1"></i>Password cocok</span>';
    } else {
        msg.innerHTML = '<span style="color:#ef4444;"><i class="fas fa-times-circle me-1"></i>Password tidak cocok</span>';
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
