<?php
$page_title  = 'Edit Guru';
$active_menu = 'guru';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$id   = (int)($_GET['id'] ?? 0);
$guru = fetch("SELECT * FROM guru WHERE id = $id");
if (!$guru) {
    setFlash('danger', 'Data guru tidak ditemukan.');
    redirect(SITE_URL . '/admin/guru/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip                 = escape($_POST['nip'] ?? '');
    $nama_lengkap        = escape($_POST['nama_lengkap'] ?? '');
    $mata_pelajaran      = escape($_POST['mata_pelajaran'] ?? '');
    $pendidikan_terakhir = escape($_POST['pendidikan_terakhir'] ?? '');
    $email               = escape($_POST['email'] ?? '');
    $status              = escape($_POST['status'] ?? 'aktif');
    $urutan              = (int)($_POST['urutan'] ?? 0);

    if (!$nama_lengkap)  $errors[] = 'Nama lengkap wajib diisi.';
    if ($email && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    $foto = $guru['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'guru/');
        if ($upload['success']) {
            if ($foto) deleteFile(UPLOAD_PATH . 'guru/' . $foto);
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        query("UPDATE guru SET nip='$nip', nama_lengkap='$nama_lengkap', mata_pelajaran='$mata_pelajaran',
               pendidikan_terakhir='$pendidikan_terakhir', email='$email', foto='$foto',
               status='$status', urutan=$urutan
               WHERE id=$id");

        logActivity($_SESSION['user_id'], 'EDIT', 'guru', $id, "Mengedit data guru: $nama_lengkap");
        setFlash('success', 'Data guru berhasil diperbarui!');
        redirect(SITE_URL . '/admin/guru/index.php');
    }
    $guru = array_merge($guru, $_POST);
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/guru/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Data Guru</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-user-tie"></i> Informasi Guru</div></div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" value="<?php echo clean($guru['nip'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?php echo clean($guru['nama_lengkap']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" class="form-control" value="<?php echo clean($guru['mata_pelajaran'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" value="<?php echo clean($guru['pendidikan_terakhir'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo clean($guru['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif"    <?php echo $guru['status'] == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                                <option value="nonaktif" <?php echo $guru['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control" min="0" value="<?php echo (int)($guru['urutan'] ?? 0); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-camera"></i> Foto Guru</div></div>
                <div class="admin-card-body text-center">
                    <div style="margin-bottom:12px;">
                        <?php if ($guru['foto']): ?>
                        <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/guru/<?php echo clean($guru['foto']); ?>"
                             class="rounded-circle" style="width:120px; height:120px; object-fit:cover; border:3px solid #e5e7eb;">
                        <?php else: ?>
                        <img id="fotoPreview" src="" alt="" class="rounded-circle" style="display:none; width:120px; height:120px; object-fit:cover; border:3px solid #e5e7eb;">
                        <div id="fotoPlaceholder" style="width:120px; height:120px; background:#e0e7ff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#4f46e5;">
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
                        <a href="<?php echo SITE_URL; ?>/admin/guru/index.php" class="btn btn-light">Batal</a>
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
