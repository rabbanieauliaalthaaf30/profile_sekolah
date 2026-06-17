<?php
$page_title  = 'Profil Saya';
$active_menu = '';
include __DIR__ . '/includes/header.php';

$id      = (int)$_SESSION['user_id'];
$user    = fetch("SELECT * FROM users WHERE id = $id");
$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = escape(trim($_POST['nama_lengkap'] ?? ''));
    $email        = escape(trim($_POST['email'] ?? ''));

    if (!$nama_lengkap) $errors[] = 'Nama lengkap wajib diisi.';
    if ($email && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    // Check duplicate email (exclude current user)
    if ($email && fetch("SELECT id FROM users WHERE email = '$email' AND id != $id")) {
        $errors[] = "Email '$email' sudah digunakan oleh pengguna lain.";
    }

    // Upload foto profil
    $foto_profil = $user['foto_profil'] ?? '';
    if (!empty($_FILES['foto_profil']['name'])) {
        $upload = uploadFile($_FILES['foto_profil'], UPLOAD_PATH . 'users/');
        if ($upload['success']) {
            if ($foto_profil) deleteFile(UPLOAD_PATH . 'users/' . $foto_profil);
            $foto_profil = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        query("UPDATE users SET nama_lengkap='$nama_lengkap', email='$email', foto_profil='$foto_profil' WHERE id=$id");

        // Update session
        $_SESSION['user_nama'] = $nama_lengkap;
        $_SESSION['user_email'] = $_POST['email'];

        setFlash('success', 'Profil berhasil diperbarui!');
        redirect(SITE_URL . '/admin/profil-saya.php');
    }
    // Re-populate from POST
    $user = array_merge($user, $_POST);
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header Profile Card -->
        <div class="admin-card mb-4">
            <div class="admin-card-body" style="padding: 32px;">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div style="position:relative;">
                        <?php if (!empty($user['foto_profil'])): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/users/<?php echo clean($user['foto_profil']); ?>"
                             class="rounded-circle" style="width:90px; height:90px; object-fit:cover; border:4px solid #e5e7eb;">
                        <?php else: ?>
                        <div style="width:90px; height:90px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:36px; font-weight:700; color:#fff; border:4px solid #e5e7eb;">
                            <?php echo strtoupper(substr($user['nama_lengkap'], 0, 1)); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold"><?php echo clean($user['nama_lengkap']); ?></h4>
                        <div class="text-muted">@<?php echo clean($user['username']); ?></div>
                        <span class="badge <?php echo $user['role'] == 'admin' ? 'bg-warning text-dark' : 'bg-primary'; ?> mt-1">
                            <i class="fas fa-<?php echo $user['role'] == 'admin' ? 'shield-alt' : 'user'; ?> me-1"></i>
                            <?php echo ucfirst(clean($user['role'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="admin-card">
            <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-user-edit"></i> Edit Profil</div></div>
            <div class="admin-card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?php echo clean($user['nama_lengkap']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo clean($user['email'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo clean($user['username']); ?>" disabled>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Foto Profil</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <?php if (!empty($user['foto_profil'])): ?>
                                <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/users/<?php echo clean($user['foto_profil']); ?>"
                                     class="rounded-circle" style="width:60px; height:60px; object-fit:cover; border:2px solid #e5e7eb;">
                                <?php else: ?>
                                <img id="fotoPreview" src="" alt="" class="rounded-circle" style="display:none; width:60px; height:60px; object-fit:cover; border:2px solid #e5e7eb;">
                                <div id="fotoPlaceholder" style="width:60px; height:60px; background:#e0e7ff; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#4f46e5; font-weight:700; font-size:22px;">
                                    <?php echo strtoupper(substr($user['nama_lengkap'], 0, 1)); ?>
                                </div>
                                <?php endif; ?>
                                <input type="file" name="foto_profil" class="form-control" accept="image/*" onchange="previewFoto(this)">
                            </div>
                            <small class="text-muted">Format: JPG, PNG, WEBP. Max 5MB. Kosongkan jika tidak ingin mengubah.</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        <a href="<?php echo SITE_URL; ?>/admin/ganti-password.php" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-2"></i>Ganti Password
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('fotoPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            const placeholder = document.getElementById('fotoPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
