<?php
$page_title  = 'Edit Berita';
$active_menu = 'berita';
include __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$berita = fetch("SELECT * FROM berita WHERE id = $id");
if (!$berita) { setFlash('danger', 'Berita tidak ditemukan.'); redirect(SITE_URL . '/admin/berita/index.php'); }

$categories = fetchAll("SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul       = escape($_POST['judul'] ?? '');
    $ringkasan   = escape($_POST['ringkasan'] ?? '');
    $konten      = escape($_POST['konten'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $status      = escape($_POST['status'] ?? 'draft');
    $tanggal     = escape($_POST['tanggal_publish'] ?? date('Y-m-d H:i:s'));

    if (!$judul) $errors[] = 'Judul berita wajib diisi.';
    if (!$konten) $errors[] = 'Konten berita wajib diisi.';

    if (empty($errors)) {
        $foto = $berita['foto_utama'];
        if (!empty($_FILES['foto_utama']['name'])) {
            $upload = uploadFile($_FILES['foto_utama'], UPLOAD_PATH . 'berita/');
            if ($upload['success']) {
                // Delete old file
                if ($foto) deleteFile(UPLOAD_PATH . 'berita/' . $foto);
                $foto = $upload['filename'];
            } else {
                $errors[] = $upload['message'];
            }
        }

        if (empty($errors)) {
            $tanggal_publish = $status == 'published' ? "'$tanggal'" : 'NULL';
            query("UPDATE berita SET judul='$judul', ringkasan='$ringkasan', konten='$konten',
                   kategori_id=" . ($kategori_id ?: 'NULL') . ", foto_utama='$foto', status='$status',
                   tanggal_publish=$tanggal_publish, updated_at=NOW()
                   WHERE id=$id");

            logActivity($_SESSION['user_id'], 'EDIT', 'berita', $id, "Mengedit berita: $judul");
            setFlash('success', 'Berita berhasil diperbarui!');
            redirect(SITE_URL . '/admin/berita/index.php');
        }
    }
    // Repopulate from POST on error
    $berita = array_merge($berita, $_POST);
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="btn-action btn-view"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Berita</h5>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-edit"></i> Konten Berita</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="<?php echo clean($berita['judul']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan</label>
                        <textarea name="ringkasan" class="form-control" rows="3"><?php echo clean($berita['ringkasan'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten Berita <span class="text-danger">*</span></label>
                        <textarea name="konten" class="form-control" rows="14"><?php echo clean($berita['konten']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-paper-plane"></i> Publikasi</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="statusSelect">
                            <option value="draft" <?php echo $berita['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $berita['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tanggalBox" style="<?php echo $berita['status'] == 'draft' ? 'display:none' : ''; ?>">
                        <label class="form-label">Tanggal Publish</label>
                        <input type="datetime-local" name="tanggal_publish" class="form-control" value="<?php echo $berita['tanggal_publish'] ? date('Y-m-d\TH:i', strtotime($berita['tanggal_publish'])) : date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $berita['kategori_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo clean($cat['nama_kategori']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-image"></i> Foto Utama</div></div>
                <div class="admin-card-body">
                    <?php if ($berita['foto_utama']): ?>
                    <img id="fotoPreview" src="<?php echo SITE_URL; ?>/uploads/berita/<?php echo $berita['foto_utama']; ?>" class="img-fluid rounded mb-2 w-100" style="height:180px; object-fit:cover;">
                    <?php else: ?>
                    <img id="fotoPreview" src="" alt="" class="img-fluid rounded mb-2" style="display:none; width:100%; height:180px; object-fit:cover;">
                    <div id="fotoPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:150px; border: 2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-image fa-2x mb-2"></i><div class="small">Belum ada foto</div></div>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto_utama" class="form-control" accept="image/*" data-preview="fotoPreview"
                           onchange="document.getElementById('fotoPlaceholder') && (document.getElementById('fotoPlaceholder').style.display='none'); document.getElementById('fotoPreview').style.display='block';">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('statusSelect').addEventListener('change', function() {
    document.getElementById('tanggalBox').style.display = this.value == 'published' ? 'block' : 'none';
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
