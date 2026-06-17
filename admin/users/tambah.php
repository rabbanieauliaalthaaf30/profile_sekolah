<?php
$page_title  = 'Tambah User';
$active_menu = 'users';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username     = escape(trim($_POST['username'] ?? ''));
    $nama_lengkap = escape(trim($_POST['nama_lengkap'] ?? ''));
    $email        = escape(trim($_POST['email'] ?? ''));
    $password     = $_POST['password'] ?? '';
    $role         = escape($_POST['role'] ?? 'staff');
    $status       = escape($_POST['status'] ?? 'aktif');

    if (!$username)     $errors[] = 'Username wajib diisi.';
    if (!$nama_lengkap) $errors[] = 'Nama lengkap wajib diisi.';
    if (!$password)     $errors[] = 'Password wajib diisi.';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
    if ($email && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (!in_array($role, ['admin', 'staff'])) $errors[] = 'Role tidak valid.';

    // Check duplicate username
    if ($username && fetch("SELECT id FROM users WHERE username = '$username'")) {
        $errors[] = "Username '$username' sudah digunakan.";
    }
    // Check duplicate email
    if ($email && fetch("SELECT id FROM users WHERE email = '$email'")) {
        $errors[] = "Email '$email' sudah digunakan.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $escaped_hash    = escape($hashed_password);

        query("INSERT INTO users (username, nama_lengkap, email, password, role, status)
               VALUES ('$username', '$nama_lengkap', '$email', '$escaped_hash', '$role', '$status')");

        $new_id = lastInsertId();
        setFlash('success', "User <strong>$username</strong> berhasil ditambahkan!");
        redirect(SITE_URL . '/admin/users/index.php');
    }
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Tambah User Baru</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-user-plus"></i> Informasi Akun</div></div>
            <div class="admin-card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap pengguna" value="<?php echo isset($_POST['nama_lengkap']) ? clean($_POST['nama_lengkap']) : ''; ?>" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="username" value="<?php echo isset($_POST['username']) ? clean($_POST['username']) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="user@email.com" value="<?php echo isset($_POST['email']) ? clean($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Minimal 6 karakter" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()"><i class="fas fa-eye" id="eyeIcon"></i></button>
                            </div>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select">
                                <option value="staff" <?php echo (isset($_POST['role']) ? $_POST['role'] : 'staff') == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                <option value="admin" <?php echo (isset($_POST['role']) ? $_POST['role'] : '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif"    <?php echo (isset($_POST['status']) ? $_POST['status'] : 'aktif') == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                            <option value="nonaktif" <?php echo (isset($_POST['status']) ? $_POST['status'] : '') == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                        </select>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan User</button>
                        <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
