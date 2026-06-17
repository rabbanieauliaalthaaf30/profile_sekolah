<?php
$page_title  = 'Profil Sekolah';
$active_menu = 'profil';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sekolah        = escape($_POST['nama_sekolah'] ?? '');
    $npsn                = escape($_POST['npsn'] ?? '');
    $sejarah             = escape($_POST['sejarah'] ?? '');
    $visi                = escape($_POST['visi'] ?? '');
    $misi                = escape($_POST['misi'] ?? '');
    $alamat_lengkap      = escape($_POST['alamat_lengkap'] ?? '');
    $telepon             = escape($_POST['telepon'] ?? '');
    $email               = escape($_POST['email'] ?? '');
    $website             = escape($_POST['website'] ?? '');
    $kepala_sekolah_nama = escape($_POST['kepala_sekolah_nama'] ?? '');
    $tahun_berdiri       = (int)($_POST['tahun_berdiri'] ?? 0);
    $akreditasi          = escape($_POST['akreditasi'] ?? '');

    if (!$nama_sekolah) $errors[] = 'Nama sekolah wajib diisi.';

    // Upload Logo
    $logo = $profil['logo'] ?? '';
    if (!empty($_FILES['logo']['name'])) {
        $upload = uploadFile($_FILES['logo'], UPLOAD_PATH . 'logo/');
        if ($upload['success']) {
            if ($logo) deleteFile(UPLOAD_PATH . 'logo/' . $logo);
            $logo = $upload['filename'];
        } else {
            $errors[] = 'Logo: ' . $upload['message'];
        }
    }

    // Upload Foto Gedung
    $foto_gedung = $profil['foto_gedung'] ?? '';
    if (!empty($_FILES['foto_gedung']['name'])) {
        $upload2 = uploadFile($_FILES['foto_gedung'], UPLOAD_PATH . 'profil/');
        if ($upload2['success']) {
            if ($foto_gedung) deleteFile(UPLOAD_PATH . 'profil/' . $foto_gedung);
            $foto_gedung = $upload2['filename'];
        } else {
            $errors[] = 'Foto gedung: ' . $upload2['message'];
        }
    }

    // Upload Foto Kepala Sekolah
    $kepala_sekolah_foto = $profil['kepala_sekolah_foto'] ?? '';
    if (!empty($_FILES['kepala_sekolah_foto']['name'])) {
        $upload3 = uploadFile($_FILES['kepala_sekolah_foto'], UPLOAD_PATH . 'profil/');
        if ($upload3['success']) {
            if ($kepala_sekolah_foto) deleteFile(UPLOAD_PATH . 'profil/' . $kepala_sekolah_foto);
            $kepala_sekolah_foto = $upload3['filename'];
        } else {
            $errors[] = 'Foto kepala sekolah: ' . $upload3['message'];
        }
    }

    if (empty($errors)) {
        if ($profil) {
            // Update existing
            query("UPDATE profil_sekolah SET
                   nama_sekolah='$nama_sekolah', npsn='$npsn', sejarah='$sejarah', visi='$visi', misi='$misi',
                   alamat_lengkap='$alamat_lengkap', telepon='$telepon', email='$email', website='$website',
                   kepala_sekolah_nama='$kepala_sekolah_nama', tahun_berdiri=" . ($tahun_berdiri ?: 'NULL') . ",
                   akreditasi='$akreditasi', logo='$logo', foto_gedung='$foto_gedung',
                   kepala_sekolah_foto='$kepala_sekolah_foto'
                   WHERE id={$profil['id']}");
        } else {
            // Insert new
            query("INSERT INTO profil_sekolah (nama_sekolah, npsn, sejarah, visi, misi, alamat_lengkap, telepon, email, website,
                   kepala_sekolah_nama, tahun_berdiri, akreditasi, logo, foto_gedung, kepala_sekolah_foto)
                   VALUES ('$nama_sekolah', '$npsn', '$sejarah', '$visi', '$misi', '$alamat_lengkap', '$telepon', '$email', '$website',
                   '$kepala_sekolah_nama', " . ($tahun_berdiri ?: 'NULL') . ", '$akreditasi', '$logo', '$foto_gedung', '$kepala_sekolah_foto')");
        }

        setFlash('success', 'Profil sekolah berhasil disimpan!');
        redirect(SITE_URL . '/admin/profil/index.php');
    }
    // Re-populate from POST on error
    $profil = array_merge($profil ?? [], $_POST);
}
?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">

            <!-- Identitas Sekolah -->
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-school"></i> Identitas Sekolah</div></div>
                <div class="admin-card-body">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger mb-3"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul></div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sekolah" class="form-control" value="<?php echo clean($profil['nama_sekolah'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NPSN</label>
                            <input type="text" name="npsn" class="form-control" placeholder="Nomor Pokok Sekolah Nasional" value="<?php echo clean($profil['npsn'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" class="form-control" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo (int)($profil['tahun_berdiri'] ?? 0) ?: ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Akreditasi</label>
                            <select name="akreditasi" class="form-select">
                                <option value="">-- Pilih Akreditasi --</option>
                                <?php foreach (['A', 'B', 'C', 'Belum Terakreditasi'] as $akr): ?>
                                <option value="<?php echo $akr; ?>" <?php echo ($profil['akreditasi'] ?? '') == $akr ? 'selected' : ''; ?>><?php echo $akr; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Kepala Sekolah</label>
                            <input type="text" name="kepala_sekolah_nama" class="form-control" placeholder="Nama lengkap beserta gelar" value="<?php echo clean($profil['kepala_sekolah_nama'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-address-card"></i> Kontak & Lokasi</div></div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Jl. ... No. ... Kecamatan ... Kabupaten/Kota ..."><?php echo clean($profil['alamat_lengkap'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" placeholder="(0xxx) xxxxxxxx" value="<?php echo clean($profil['telepon'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="info@sekolah.sch.id" value="<?php echo clean($profil['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" placeholder="https://sekolah.sch.id" value="<?php echo clean($profil['website'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sejarah & Visi Misi -->
            <div class="admin-card">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-book-open"></i> Sejarah & Visi Misi</div></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label">Sejarah Sekolah</label>
                        <textarea name="sejarah" class="form-control" rows="6" placeholder="Ceritakan sejarah berdirinya sekolah..."><?php echo clean($profil['sejarah'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visi</label>
                        <textarea name="visi" class="form-control" rows="3" placeholder="Visi sekolah..."><?php echo clean($profil['visi'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Misi</label>
                        <textarea name="misi" class="form-control" rows="5" placeholder="Misi sekolah (tulis per poin jika perlu)..."><?php echo clean($profil['misi'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <!-- Logo -->
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-image"></i> Logo Sekolah</div></div>
                <div class="admin-card-body text-center">
                    <?php if (!empty($profil['logo'])): ?>
                    <img id="logoPreview" src="<?php echo SITE_URL; ?>/uploads/logo/<?php echo clean($profil['logo']); ?>"
                         class="img-fluid mb-2" style="max-height:120px; object-fit:contain;">
                    <?php else: ?>
                    <img id="logoPreview" src="" alt="" class="img-fluid mb-2" style="display:none; max-height:120px;">
                    <div id="logoPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:100px; border:2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-school fa-2x mb-1"></i><div class="small">Upload logo</div></div>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImg(this,'logoPreview','logoPlaceholder')">
                    <small class="text-muted">Format: PNG/SVG. Max 5MB</small>
                </div>
            </div>

            <!-- Foto Gedung -->
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-building"></i> Foto Gedung Sekolah</div></div>
                <div class="admin-card-body">
                    <?php if (!empty($profil['foto_gedung'])): ?>
                    <img id="gedungPreview" src="<?php echo SITE_URL; ?>/uploads/profil/<?php echo clean($profil['foto_gedung']); ?>"
                         class="img-fluid rounded mb-2 w-100" style="height:150px; object-fit:cover;">
                    <?php else: ?>
                    <img id="gedungPreview" src="" alt="" class="img-fluid rounded mb-2" style="display:none; width:100%; height:150px; object-fit:cover;">
                    <div id="gedungPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:120px; border:2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-image fa-2x mb-1"></i><div class="small">Upload foto gedung</div></div>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto_gedung" class="form-control" accept="image/*" onchange="previewImg(this,'gedungPreview','gedungPlaceholder')">
                    <small class="text-muted">Format: JPG, PNG. Max 5MB</small>
                </div>
            </div>

            <!-- Foto Kepala Sekolah -->
            <div class="admin-card mb-4">
                <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-user-tie"></i> Foto Kepala Sekolah</div></div>
                <div class="admin-card-body">
                    <?php if (!empty($profil['kepala_sekolah_foto'])): ?>
                    <img id="kepsekPreview" src="<?php echo SITE_URL; ?>/uploads/profil/<?php echo clean($profil['kepala_sekolah_foto']); ?>"
                         class="img-fluid rounded mb-2 w-100" style="height:150px; object-fit:cover; object-position:top;">
                    <?php else: ?>
                    <img id="kepsekPreview" src="" alt="" class="img-fluid rounded mb-2" style="display:none; width:100%; height:150px; object-fit:cover;">
                    <div id="kepsekPlaceholder" class="rounded d-flex align-items-center justify-content-center mb-2" style="height:120px; border:2px dashed #e5e7eb; background:#f9fafb; color:#9ca3af;">
                        <div class="text-center"><i class="fas fa-user-tie fa-2x mb-1"></i><div class="small">Upload foto kepala sekolah</div></div>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="kepala_sekolah_foto" class="form-control" accept="image/*" onchange="previewImg(this,'kepsekPreview','kepsekPlaceholder')">
                    <small class="text-muted">Foto ditampilkan di halaman Beranda. Format: JPG, PNG. Max 5MB</small>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Profil Sekolah
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function previewImg(input, previewId, placeholderId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.style.display = 'block';
            const placeholder = document.getElementById(placeholderId);
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
