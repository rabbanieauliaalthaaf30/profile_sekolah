<?php
$page_title  = 'Edit Galeri';
$active_menu = 'galeri';
include __DIR__ . '/../includes/header.php';

$kategori_list = ['Akademik', 'Ekstrakurikuler', 'Upacara', 'Olahraga', 'Kompetisi'];

$id     = (int)($_GET['id'] ?? 0);
$galeri = fetch("SELECT * FROM galeri WHERE id = $id");
if (!$galeri) {
    setFlash('danger', 'Galeri tidak ditemukan.');
    redirect(SITE_URL . '/admin/galeri/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul            = escape($_POST['judul'] ?? '');
    $deskripsi        = escape($_POST['deskripsi'] ?? '');
    $kategori         = escape($_POST['kategori'] ?? '');
    $tanggal_kegiatan = escape($_POST['tanggal_kegiatan'] ?? date('Y-m-d'));

    if (!$judul) $errors[] = 'Judul galeri wajib diisi.';
    if (!$tanggal_kegiatan) $errors[] = 'Tanggal kegiatan wajib diisi.';
    if (!in_array($kategori, $kategori_list)) $errors[] = 'Kategori tidak valid.';

    $foto = $galeri['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'galeri/');
        if ($upload['success']) {
            if ($foto) deleteFile(UPLOAD_PATH . 'galeri/' . $foto);
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        $diedit_oleh = (int)$_SESSION['user_id'];
        query("UPDATE galeri SET judul='$judul', deskripsi='$deskripsi', foto='$foto',
               kategori='$kategori', tanggal_kegiatan='$tanggal_kegiatan', diedit_oleh=$diedit_oleh
               WHERE id=$id");

        setFlash('success', 'Galeri berhasil diperbarui!');
        redirect(SITE_URL . '/admin/galeri/index.php');
    }
    $galeri = array_merge($galeri, $_POST);
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/galeri/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Galeri</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-edit"></i> Informasi Galeri</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Galeri <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="<?php echo clean($galeri['judul']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?php echo clean($galeri['deskripsi'] ?? ''); ?></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori_list as $kat): ?>
                                <option value="<?php echo $kat; ?>" <?php echo ($galeri['kategori'] == $kat) ? 'selected' : ''; ?>>
                                    <?php echo $kat; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kegiatan" class="form-control" value="<?php echo clean($galeri['tanggal_kegiatan']); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-image"></i> Foto Galeri</div></div>
                <div class="admin-card-body">
                    <?php if ($galeri['foto']): ?>
                    <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/galeri/<?php echo clean($galeri['foto']); ?>"
                         class="img-fluid rounded mb-2 w-100" style="height:200px; object-fit:cover;">
                    <?php else: ?>
                    <img id="fotoPreview" src="" alt="" class="img-fluid rounded mb-2" style="display:none; width:100%; height:200px; object-fit:cover;">
                    <div id="fotoPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:150px; border:2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-image fa-2x mb-2"></i><div class="small">Belum ada foto</div></div>
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
                        <a href="<?php echo SITE_URL; ?>/admin/galeri/index.php" class="btn btn-light">Batal</a>
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
