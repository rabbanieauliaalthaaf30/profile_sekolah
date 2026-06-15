<?php
$page_title  = 'Tambah Berita';
$active_menu = 'berita';
include __DIR__ . '/../includes/header.php';

$categories = fetchAll("SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = escape($_POST['judul'] ?? '');
    $ringkasan = escape($_POST['ringkasan'] ?? '');
    $konten    = escape($_POST['konten'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $status    = escape($_POST['status'] ?? 'draft');
    $tanggal   = escape($_POST['tanggal_publish'] ?? date('Y-m-d H:i:s'));

    if (!$judul) $errors[] = 'Judul berita wajib diisi.';
    if (!$konten) $errors[] = 'Konten berita wajib diisi.';

    if (empty($errors)) {
        $slug = createSlug($judul);
        // Ensure unique slug
        $slug_check = fetch("SELECT id FROM berita WHERE slug = '$slug'");
        if ($slug_check) $slug .= '-' . time();

        $foto = '';
        if (!empty($_FILES['foto_utama']['name'])) {
            $upload = uploadFile($_FILES['foto_utama'], UPLOAD_PATH . 'berita/');
            if ($upload['success']) {
                $foto = $upload['filename'];
            } else {
                $errors[] = $upload['message'];
            }
        }

        if (empty($errors)) {
            $penulis_id = (int)$_SESSION['user_id'];
            $tanggal_publish = $status == 'published' ? "'$tanggal'" : 'NULL';

            query("INSERT INTO berita (judul, slug, ringkasan, konten, kategori_id, penulis_id, foto_utama, status, tanggal_publish)
                   VALUES ('$judul', '$slug', '$ringkasan', '$konten', " . ($kategori_id ?: 'NULL') . ", $penulis_id, '$foto', '$status', $tanggal_publish)");

            $new_id = lastInsertId();
            logActivity($_SESSION['user_id'], 'TAMBAH', 'berita', $new_id, "Menambah berita: $judul");
            setFlash('success', 'Berita berhasil ditambahkan!');
            redirect(SITE_URL . '/admin/berita/index.php');
        }
    }
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Tambah Berita Baru</h5>
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
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita" value="<?php echo isset($_POST['judul']) ? clean($_POST['judul']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan</label>
                        <textarea name="ringkasan" class="form-control" rows="3" placeholder="Ringkasan singkat berita (tampil di list berita)..."><?php echo isset($_POST['ringkasan']) ? clean($_POST['ringkasan']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten Berita <span class="text-danger">*</span></label>
                        <textarea name="konten" id="konten" class="form-control" rows="14" placeholder="Tulis isi berita di sini..."><?php echo isset($_POST['konten']) ? clean($_POST['konten']) : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Publish Box -->
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-paper-plane"></i> Publikasi</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="statusSelect">
                            <option value="draft" <?php echo (isset($_POST['status']) && $_POST['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo (isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tanggalBox">
                        <label class="form-label">Tanggal Publish</label>
                        <input type="datetime-local" name="tanggal_publish" class="form-control" value="<?php echo isset($_POST['tanggal_publish']) ? $_POST['tanggal_publish'] : date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo clean($cat['nama_kategori']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Berita
                        </button>
                        <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </div>

            <!-- Foto Utama -->
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-image"></i> Foto Utama</div></div>
                <div class="admin-card-body">
                    <div class="mb-2">
                        <img id="fotoPreview" src="" alt="Preview" class="img-fluid rounded mb-2" style="display:none; width:100%; height:180px; object-fit:cover;">
                        <div id="fotoPlaceholder" class="rounded border-2 border-dashed d-flex align-items-center justify-content-center" style="height: 150px; border: 2px dashed #e5e7eb; background: #f9fafb; color: #9ca3af;">
                            <div class="text-center"><i class="fas fa-image fa-2x mb-2"></i><div class="small">Pilih foto berita</div></div>
                        </div>
                    </div>
                    <input type="file" name="foto_utama" class="form-control" accept="image/*" data-preview="fotoPreview"
                           onchange="document.getElementById('fotoPlaceholder').style.display='none'">
                    <small class="text-muted">Format: JPG, PNG, WEBP. Max 5MB</small>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Show/hide tanggal based on status
document.getElementById('statusSelect').addEventListener('change', function() {
    document.getElementById('tanggalBox').style.display = this.value == 'published' ? 'block' : 'none';
});
// Initial check
if (document.getElementById('statusSelect').value == 'draft') {
    document.getElementById('tanggalBox').style.display = 'none';
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
