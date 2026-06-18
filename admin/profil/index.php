<?php
$page_title  = 'Profil Sekolah';
$active_menu = 'profil';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");
$errors = [];

// ── Slider config ──
$slides = [
    1 => ['label' => 'Slide 1', 'judul' => 'Selamat Datang',    'warna' => '#2563eb'],
    2 => ['label' => 'Slide 2', 'judul' => 'Fasilitas Sekolah', 'warna' => '#7c3aed'],
    3 => ['label' => 'Slide 3', 'judul' => 'Galeri Kegiatan',   'warna' => '#059669'],
];

// ── Handle slider upload ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slide_no'])) {
    $slide_no = (int)($_POST['slide_no'] ?? 0);
    if ($slide_no >= 1 && $slide_no <= 3 && !empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'slider/');
        if ($upload['success']) {
            $old_path = UPLOAD_PATH . 'slider/' . $upload['filename'];
            $new_name = 'slide' . $slide_no . '.jpg';
            $new_path = UPLOAD_PATH . 'slider/' . $new_name;
            if (file_exists($new_path)) unlink($new_path);
            rename($old_path, $new_path);
            setFlash('success', "Foto Slide $slide_no berhasil diupload!");
        } else {
            setFlash('danger', 'Upload gagal: ' . $upload['message']);
        }
    }
    redirect(SITE_URL . '/admin/profil/index.php#slider');
}

// ── Handle slider hapus ──
if (isset($_GET['hapus_slide'])) {
    $no = (int)$_GET['hapus_slide'];
    if ($no >= 1 && $no <= 3) {
        $path = UPLOAD_PATH . 'slider/slide' . $no . '.jpg';
        if (file_exists($path)) {
            unlink($path);
            setFlash('success', "Foto Slide $no berhasil dihapus.");
        }
    }
    redirect(SITE_URL . '/admin/profil/index.php#slider');
}

// ── Handle profil update ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_sekolah'])) {
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

    $logo = $profil['logo'] ?? '';
    if (!empty($_FILES['logo']['name'])) {
        $upload = uploadFile($_FILES['logo'], UPLOAD_PATH . 'logo/');
        if ($upload['success']) {
            if ($logo) deleteFile(UPLOAD_PATH . 'logo/' . $logo);
            $logo = $upload['filename'];
        } else { $errors[] = 'Logo: ' . $upload['message']; }
    }

    $foto_gedung = $profil['foto_gedung'] ?? '';
    if (!empty($_FILES['foto_gedung']['name'])) {
        $upload2 = uploadFile($_FILES['foto_gedung'], UPLOAD_PATH . 'profil/');
        if ($upload2['success']) {
            if ($foto_gedung) deleteFile(UPLOAD_PATH . 'profil/' . $foto_gedung);
            $foto_gedung = $upload2['filename'];
        } else { $errors[] = 'Foto gedung: ' . $upload2['message']; }
    }

    $kepala_sekolah_foto = $profil['kepala_sekolah_foto'] ?? '';
    if (!empty($_FILES['kepala_sekolah_foto']['name'])) {
        $upload3 = uploadFile($_FILES['kepala_sekolah_foto'], UPLOAD_PATH . 'profil/');
        if ($upload3['success']) {
            if ($kepala_sekolah_foto) deleteFile(UPLOAD_PATH . 'profil/' . $kepala_sekolah_foto);
            $kepala_sekolah_foto = $upload3['filename'];
        } else { $errors[] = 'Foto kepala sekolah: ' . $upload3['message']; }
    }

    if (empty($errors)) {
        $diedit_oleh = (int)$_SESSION['user_id'];
        if ($profil) {
            query("UPDATE profil_sekolah SET
                   nama_sekolah='$nama_sekolah', npsn='$npsn', sejarah='$sejarah', visi='$visi', misi='$misi',
                   alamat_lengkap='$alamat_lengkap', telepon='$telepon', email='$email', website='$website',
                   kepala_sekolah_nama='$kepala_sekolah_nama', tahun_berdiri=" . ($tahun_berdiri ?: 'NULL') . ",
                   akreditasi='$akreditasi', logo='$logo', foto_gedung='$foto_gedung',
                   kepala_sekolah_foto='$kepala_sekolah_foto', diedit_oleh=$diedit_oleh
                   WHERE id={$profil['id']}");
        } else {
            query("INSERT INTO profil_sekolah (nama_sekolah, npsn, sejarah, visi, misi, alamat_lengkap, telepon, email, website,
                   kepala_sekolah_nama, tahun_berdiri, akreditasi, logo, foto_gedung, kepala_sekolah_foto, diedit_oleh)
                   VALUES ('$nama_sekolah', '$npsn', '$sejarah', '$visi', '$misi', '$alamat_lengkap', '$telepon', '$email', '$website',
                   '$kepala_sekolah_nama', " . ($tahun_berdiri ?: 'NULL') . ", '$akreditasi', '$logo', '$foto_gedung', '$kepala_sekolah_foto', $diedit_oleh)");
        }
        setFlash('success', 'Profil sekolah berhasil disimpan!');
        redirect(SITE_URL . '/admin/profil/index.php');
    }
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
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Siswa Aktif</label>
                            <input type="number" id="inputTotalSiswa" class="form-control" min="0" placeholder="Contoh: 720"
                                   onchange="simpanSiswa(this.value)">
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

<!-- ══════════════════════════════════════
     SECTION: FOTO SLIDER HOMEPAGE
═══════════════════════════════════════ -->
<div class="admin-card mt-4" id="slider">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-images"></i> Foto Slider Homepage</div>
        <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn-add" style="background:#059669;">
            <i class="fas fa-eye me-1"></i> Preview Website
        </a>
    </div>
    <div class="admin-card-body">
        <p class="text-muted small mb-4">Upload foto untuk ditampilkan di slider homepage. Ukuran ideal: <strong>1920 × 600 px</strong></p>
        <div class="row g-4">
            <?php foreach ($slides as $no => $slide):
                $file_path = UPLOAD_PATH . 'slider/slide' . $no . '.jpg';
                $has_foto  = file_exists($file_path);
                $img_url   = SITE_URL . '/uploads/slider/slide' . $no . '.jpg';
            ?>
            <div class="col-lg-4">
                <div class="border rounded-3 overflow-hidden">
                    <!-- Preview -->
                    <div style="position:relative; height:180px; background:<?php echo $slide['warna']; ?>20;">
                        <?php if ($has_foto): ?>
                            <img src="<?php echo $img_url; ?>?t=<?php echo filemtime($file_path); ?>"
                                 alt="<?php echo $slide['label']; ?>"
                                 style="width:100%; height:180px; object-fit:cover; display:block;">
                            <a href="?hapus_slide=<?php echo $no; ?>"
                               class="btn-confirm-delete"
                               data-name="foto Slide <?php echo $no; ?>"
                               style="position:absolute; top:8px; right:8px; background:rgba(239,68,68,0.9); color:white; width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; text-decoration:none;"
                               title="Hapus Foto">
                                <i class="fas fa-trash" style="font-size:12px;"></i>
                            </a>
                            <span style="position:absolute; bottom:8px; left:8px; background:rgba(16,185,129,0.9); color:white; font-size:11px; font-weight:600; padding:2px 10px; border-radius:20px;">
                                <i class="fas fa-check me-1"></i>Terpasang
                            </span>
                        <?php else: ?>
                            <div style="width:100%; height:180px; background:linear-gradient(135deg,<?php echo $slide['warna']; ?>,<?php echo $slide['warna']; ?>88); display:flex; align-items:center; justify-content:center; flex-direction:column; color:white;">
                                <i class="fas fa-image" style="font-size:36px; opacity:0.5; margin-bottom:8px;"></i>
                                <span style="font-size:13px; opacity:0.7;">Belum ada foto</span>
                            </div>
                            <span style="position:absolute; bottom:8px; left:8px; background:rgba(107,114,128,0.9); color:white; font-size:11px; font-weight:600; padding:2px 10px; border-radius:20px;">
                                Warna default
                            </span>
                        <?php endif; ?>
                    </div>
                    <!-- Form upload -->
                    <div class="p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:10px; height:10px; border-radius:50%; background:<?php echo $slide['warna']; ?>;"></div>
                            <span class="fw-bold small"><?php echo $slide['label']; ?></span>
                            <span class="text-muted small">— <?php echo $slide['judul']; ?></span>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="slide_no" value="<?php echo $no; ?>">
                            <input type="file" name="foto" class="form-control form-control-sm mb-2"
                                   accept="image/jpeg,image/png,image/webp"
                                   onchange="previewSlide(this,'prev<?php echo $no; ?>')" required>
                            <img id="prev<?php echo $no; ?>" src="" alt="" class="img-fluid rounded mb-2"
                                 style="display:none; width:100%; height:80px; object-fit:cover;">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-upload me-1"></i>
                                <?php echo $has_foto ? 'Ganti Foto Slide ' . $no : 'Upload Foto Slide ' . $no; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tips -->
        <div class="mt-4 p-3 bg-light rounded-3">
            <div class="row g-2 text-muted small">
                <div class="col-md-3"><i class="fas fa-expand-arrows-alt text-primary me-1"></i>Ukuran ideal: <strong>1920×600px</strong></div>
                <div class="col-md-3"><i class="fas fa-file-image text-primary me-1"></i>Format: JPG, PNG, WEBP</div>
                <div class="col-md-3"><i class="fas fa-weight text-primary me-1"></i>Maks. ukuran: <strong>5 MB</strong></div>
                <div class="col-md-3"><i class="fas fa-moon text-primary me-1"></i>Gunakan foto gelap agar teks terbaca</div>
            </div>
        </div>
    </div>
</div>

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

function previewSlide(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Jumlah Siswa via localStorage ──
(function() {
    const input = document.getElementById('inputTotalSiswa');
    if (!input) return;
    const saved = localStorage.getItem('total_siswa');
    if (saved !== null && !isNaN(parseInt(saved))) {
        input.value = parseInt(saved);
    } else {
        input.value = 720; // default
    }
})();

function simpanSiswa(val) {
    const n = parseInt(val);
    if (!isNaN(n) && n >= 0) {
        localStorage.setItem('total_siswa', n);
        // Tampilkan toast konfirmasi
        showToastSiswa('Jumlah siswa berhasil disimpan: ' + n);
    }
}

function showToastSiswa(msg) {
    let toast = document.getElementById('toastSiswa');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toastSiswa';
        toast.style.cssText = 'position:fixed;bottom:30px;right:30px;z-index:9999;background:#10b981;color:white;padding:12px 20px;border-radius:12px;font-size:14px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.2);transition:opacity 0.4s;';
        document.body.appendChild(toast);
    }
    toast.textContent = '✓ ' + msg;
    toast.style.opacity = '1';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => { toast.style.opacity = '0'; }, 2500);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
