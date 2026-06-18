<?php
$page_title  = 'Tambah Guru';
$active_menu = 'guru';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip                = escape($_POST['nip'] ?? '');
    $nama_lengkap       = escape($_POST['nama_lengkap'] ?? '');
    $mata_pelajaran     = escape($_POST['mata_pelajaran'] ?? '');
    $pendidikan_terakhir = escape($_POST['pendidikan_terakhir'] ?? '');
    $email              = escape($_POST['email'] ?? '');
    $status             = escape($_POST['status'] ?? 'aktif');
    $urutan             = (int)($_POST['urutan'] ?? 0);

    if (!$nama_lengkap)  $errors[] = 'Nama lengkap wajib diisi.';
    if ($email && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    $foto = '';
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'guru/');
        if ($upload['success']) {
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        $dibuat_oleh = (int)$_SESSION['user_id'];
        query("INSERT INTO guru (nip, nama_lengkap, mata_pelajaran, pendidikan_terakhir, email, foto, status, urutan, dibuat_oleh)
               VALUES ('$nip', '$nama_lengkap', '$mata_pelajaran', '$pendidikan_terakhir', '$email', '$foto', '$status', $urutan, $dibuat_oleh)");

        setFlash('success', 'Data guru berhasil ditambahkan!');
        redirect(SITE_URL . '/admin/guru/index.php');
    }
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/guru/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Tambah Data Guru</h5>
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
                            <input type="text" name="nip" class="form-control" placeholder="Nomor Induk Pegawai" value="<?php echo isset($_POST['nip']) ? clean($_POST['nip']) : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap beserta gelar" value="<?php echo isset($_POST['nama_lengkap']) ? clean($_POST['nama_lengkap']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika" value="<?php echo isset($_POST['mata_pelajaran']) ? clean($_POST['mata_pelajaran']) : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="Contoh: S1 Pendidikan Matematika" value="<?php echo isset($_POST['pendidikan_terakhir']) ? clean($_POST['pendidikan_terakhir']) : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@sekolah.sch.id" value="<?php echo isset($_POST['email']) ? clean($_POST['email']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif"    <?php echo (isset($_POST['status']) ? $_POST['status'] : 'aktif') == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                                <option value="nonaktif" <?php echo (isset($_POST['status']) ? $_POST['status'] : '')       == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control" min="0" placeholder="0" value="<?php echo isset($_POST['urutan']) ? (int)$_POST['urutan'] : 0; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-camera"></i> Foto Guru</div></div>
                <div class="admin-card-body text-center">
                    <div id="fotoContainer" style="margin-bottom:12px;">
                        <img id="fotoPreview" src="" alt="" class="rounded-circle" style="display:none; width:120px; height:120px; object-fit:cover; border:3px solid #e5e7eb;">
                        <div id="fotoPlaceholder" style="width:120px; height:120px; background:#e0e7ff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#4f46e5;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    </div>
                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted d-block mt-1">Format: JPG, PNG. Max 5MB</small>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Data Guru</button>
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
            document.getElementById('fotoPreview').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'inline-block';
            document.getElementById('fotoPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
