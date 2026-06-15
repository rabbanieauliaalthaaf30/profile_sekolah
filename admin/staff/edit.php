<?php
$page_title  = 'Edit Staff';
$active_menu = 'staff';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$id    = (int)($_GET['id'] ?? 0);
$staff = fetch("SELECT * FROM staff WHERE id = $id");
if (!$staff) {
    setFlash('danger', 'Data staff tidak ditemukan.');
    redirect(SITE_URL . '/admin/staff/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = escape($_POST['nama_lengkap'] ?? '');
    $jabatan      = escape($_POST['jabatan'] ?? '');
    $email        = escape($_POST['email'] ?? '');
    $telepon      = escape($_POST['telepon'] ?? '');
    $status       = escape($_POST['status'] ?? 'aktif');

    if (!$nama_lengkap) $errors[] = 'Nama lengkap wajib diisi.';
    if ($email && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    $foto = $staff['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'staff/');
        if ($upload['success']) {
            if ($foto) deleteFile(UPLOAD_PATH . 'staff/' . $foto);
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        query("UPDATE staff SET nama_lengkap='$nama_lengkap', jabatan='$jabatan', email='$email',
               telepon='$telepon', foto='$foto', status='$status'
               WHERE id=$id");

        logActivity($_SESSION['user_id'], 'EDIT', 'staff', $id, "Mengedit data staff: $nama_lengkap");
        setFlash('success', 'Data staff berhasil diperbarui!');
        redirect(SITE_URL . '/admin/staff/index.php');
    }
    $staff = array_merge($staff, $_POST);
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/staff/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Data Staff</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-id-card"></i> Informasi Staff</div></div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?php echo clean($staff['nama_lengkap']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="<?php echo clean($staff['jabatan'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo clean($staff['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?php echo clean($staff['telepon'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif"    <?php echo $staff['status'] == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                                <option value="nonaktif" <?php echo $staff['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-camera"></i> Foto Staff</div></div>
                <div class="admin-card-body text-center">
                    <div style="margin-bottom:12px;">
                        <?php if ($staff['foto']): ?>
                        <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/staff/<?php echo clean($staff['foto']); ?>"
                             class="rounded-circle" style="width:120px; height:120px; object-fit:cover; border:3px solid #e5e7eb;">
                        <?php else: ?>
                        <img id="fotoPreview" src="" alt="" class="rounded-circle" style="display:none; width:120px; height:120px; object-fit:cover; border:3px solid #e5e7eb;">
                        <div id="fotoPlaceholder" style="width:120px; height:120px; background:#dcfce7; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#16a34a;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah</small>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        <a href="<?php echo SITE_URL; ?>/admin/staff/index.php" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('fotoPreview');
            preview.src = e.target.result;
            preview.style.display = 'inline-block';
            const placeholder = document.getElementById('fotoPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
