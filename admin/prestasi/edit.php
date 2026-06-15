<?php
$page_title  = 'Edit Prestasi';
$active_menu = 'prestasi';
include __DIR__ . '/../includes/header.php';

$id       = (int)($_GET['id'] ?? 0);
$prestasi = fetch("SELECT * FROM prestasi WHERE id = $id");
if (!$prestasi) {
    setFlash('danger', 'Data prestasi tidak ditemukan.');
    redirect(SITE_URL . '/admin/prestasi/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_prestasi = escape($_POST['nama_prestasi'] ?? '');
    $kategori      = escape($_POST['kategori'] ?? '');
    $tingkat       = escape($_POST['tingkat'] ?? '');
    $tahun         = (int)($_POST['tahun'] ?? date('Y'));
    $deskripsi     = escape($_POST['deskripsi'] ?? '');

    $valid_kategori = ['akademik', 'non-akademik'];
    $valid_tingkat  = ['sekolah', 'kota', 'provinsi', 'nasional', 'internasional'];

    if (!$nama_prestasi)                          $errors[] = 'Nama prestasi wajib diisi.';
    if (!in_array($kategori, $valid_kategori))    $errors[] = 'Kategori tidak valid.';
    if (!in_array($tingkat, $valid_tingkat))      $errors[] = 'Tingkat tidak valid.';
    if (!$tahun || $tahun < 1900 || $tahun > (int)date('Y') + 1) $errors[] = 'Tahun tidak valid.';

    $foto_dokumentasi = $prestasi['foto_dokumentasi'];
    if (!empty($_FILES['foto_dokumentasi']['name'])) {
        $upload = uploadFile($_FILES['foto_dokumentasi'], UPLOAD_PATH . 'prestasi/');
        if ($upload['success']) {
            if ($foto_dokumentasi) deleteFile(UPLOAD_PATH . 'prestasi/' . $foto_dokumentasi);
            $foto_dokumentasi = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        query("UPDATE prestasi SET nama_prestasi='$nama_prestasi', kategori='$kategori', tingkat='$tingkat',
               tahun=$tahun, deskripsi='$deskripsi', foto_dokumentasi='$foto_dokumentasi'
               WHERE id=$id");

        logActivity($_SESSION['user_id'], 'EDIT', 'prestasi', $id, "Mengedit prestasi: $nama_prestasi");
        setFlash('success', 'Prestasi berhasil diperbarui!');
        redirect(SITE_URL . '/admin/prestasi/index.php');
    }
    $prestasi = array_merge($prestasi, $_POST);
}

$tahun_sekarang = (int)date('Y');
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/prestasi/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Prestasi</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-trophy"></i> Detail Prestasi</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_prestasi" class="form-control" value="<?php echo clean($prestasi['nama_prestasi']); ?>" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="akademik"     <?php echo $prestasi['kategori'] == 'akademik'     ? 'selected' : ''; ?>>Akademik</option>
                                <option value="non-akademik" <?php echo $prestasi['kategori'] == 'non-akademik' ? 'selected' : ''; ?>>Non-Akademik</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="sekolah"       <?php echo $prestasi['tingkat'] == 'sekolah'       ? 'selected' : ''; ?>>Sekolah</option>
                                <option value="kota"          <?php echo $prestasi['tingkat'] == 'kota'          ? 'selected' : ''; ?>>Kota/Kabupaten</option>
                                <option value="provinsi"      <?php echo $prestasi['tingkat'] == 'provinsi'      ? 'selected' : ''; ?>>Provinsi</option>
                                <option value="nasional"      <?php echo $prestasi['tingkat'] == 'nasional'      ? 'selected' : ''; ?>>Nasional</option>
                                <option value="internasional" <?php echo $prestasi['tingkat'] == 'internasional' ? 'selected' : ''; ?>>Internasional</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control" min="1990" max="<?php echo $tahun_sekarang + 1; ?>" value="<?php echo (int)$prestasi['tahun']; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?php echo clean($prestasi['deskripsi'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-image"></i> Foto Dokumentasi</div></div>
                <div class="admin-card-body">
                    <?php if ($prestasi['foto_dokumentasi']): ?>
                    <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/prestasi/<?php echo clean($prestasi['foto_dokumentasi']); ?>"
                         class="img-fluid rounded mb-2 w-100" style="height:180px; object-fit:cover;">
                    <?php else: ?>
                    <img id="fotoPreview" src="" alt="" class="img-fluid rounded mb-2" style="display:none; width:100%; height:180px; object-fit:cover;">
                    <div id="fotoPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:150px; border:2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-image fa-2x mb-2"></i><div class="small">Belum ada foto</div></div>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto_dokumentasi" class="form-control" accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        <a href="<?php echo SITE_URL; ?>/admin/prestasi/index.php" class="btn btn-light">Batal</a>
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
