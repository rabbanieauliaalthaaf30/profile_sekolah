<?php
$page_title  = 'Edit User';
$active_menu = 'users';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$id   = (int)($_GET['id'] ?? 0);
$user = fetch("SELECT * FROM users WHERE id = $id");
if (!$user) {
    setFlash('danger', 'User tidak ditemukan.');
    redirect(SITE_URL . '/admin/users/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username     = escape(trim($_POST['username'] ?? ''));
    $nama_lengkap = escape(trim($_POST['nama_lengkap'] ?? ''));
    $email        = escape(trim($_POST['email'] ?? ''));
    $role         = escape($_POST['role'] ?? 'staff');
    $status       = escape($_POST['status'] ?? 'aktif');

    if (!$username)     $errors[] = 'Username wajib diisi.';
    if (!$nama_lengkap) $errors[] = 'Nama lengkap wajib diisi.';
    if ($email && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (!in_array($role, ['admin', 'staff'])) $errors[] = 'Role tidak valid.';

    // Check duplicate username (exclude current user)
    if ($username && fetch("SELECT id FROM users WHERE username = '$username' AND id != $id")) {
        $errors[] = "Username '$username' sudah digunakan.";
    }
    // Check duplicate email (exclude current user)
    if ($email && fetch("SELECT id FROM users WHERE email = '$email' AND id != $id")) {
        $errors[] = "Email '$email' sudah digunakan.";
    }

    if (empty($errors)) {
        query("UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', email='$email',
               role='$role', status='$status'
               WHERE id=$id");

        logActivity($_SESSION['user_id'], 'EDIT', 'users', $id, "Mengedit user: $username");
        setFlash('success', 'Data user berhasil diperbarui!');
        redirect(SITE_URL . '/admin/users/index.php');
    }
    $user = array_merge($user, $_POST);
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit User</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-user-edit"></i> Edit Akun</div>
                <span class="badge bg-secondary">ID: <?php echo $id; ?></span>
            </div>
            <div class="admin-card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?php echo clean($user['nama_lengkap']); ?>" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                <input type="text" name="username" class="form-control" value="<?php echo clean($user['username']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo clean($user['email'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="staff" <?php echo $user['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif"    <?php echo ($user['status'] ?? 'aktif') == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                                <option value="nonaktif" <?php echo ($user['status'] ?? '') == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0 py-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Untuk mengubah password, gunakan menu <a href="<?php echo SITE_URL; ?>/admin/ganti-password.php" class="alert-link">Ganti Password</a>.
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
