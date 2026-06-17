<?php
$page_title  = 'Edit Fasilitas';
$active_menu = 'fasilitas';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$id        = (int)($_GET['id'] ?? 0);
$fasilitas = fetch("SELECT * FROM fasilitas WHERE id = $id");
if (!$fasilitas) {
    setFlash('danger', 'Data fasilitas tidak ditemukan.');
    redirect(SITE_URL . '/admin/fasilitas/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_fasilitas = escape($_POST['nama_fasilitas'] ?? '');
    $deskripsi      = escape($_POST['deskripsi'] ?? '');
    $urutan         = (int)($_POST['urutan'] ?? 0);

    if (!$nama_fasilitas) $errors[] = 'Nama fasilitas wajib diisi.';

    $foto = $fasilitas['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'fasilitas/');
        if ($upload['success']) {
            if ($foto) deleteFile(UPLOAD_PATH . 'fasilitas/' . $foto);
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        query("UPDATE fasilitas SET nama_fasilitas='$nama_fasilitas', deskripsi='$deskripsi',
               foto='$foto', urutan=$urutan
               WHERE id=$id");

        setFlash('success', 'Fasilitas berhasil diperbarui!');
        redirect(SITE_URL . '/admin/fasilitas/index.php');
    }
    $fasilitas = array_merge($fasilitas, $_POST);
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/fasilitas/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Fasilitas</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-building"></i> Detail Fasilitas</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_fasilitas" class="form-control" value="<?php echo clean($fasilitas['nama_fasilitas']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="5"><?php echo clean($fasilitas['deskripsi'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control" min="0" value="<?php echo (int)($fasilitas['urutan'] ?? 0); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-image"></i> Foto Fasilitas</div></div>
                <div class="admin-card-body">
                    <?php if ($fasilitas['foto']): ?>
                    <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/fasilitas/<?php echo clean($fasilitas['foto']); ?>"
                         class="img-fluid rounded mb-2 w-100" style="height:180px; object-fit:cover;">
                    <?php else: ?>
                    <img id="fotoPreview" src="" alt="" class="img-fluid rounded mb-2" style="display:none; width:100%; height:180px; object-fit:cover;">
                    <div id="fotoPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:150px; border:2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-building fa-2x mb-2"></i><div class="small">Belum ada foto</div></div>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        <a href="<?php echo SITE_URL; ?>/admin/fasilitas/index.php" class="btn btn-light">Batal</a>
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
            preview.style.display = 'block';
            const placeholder = document.getElementById('fotoPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
