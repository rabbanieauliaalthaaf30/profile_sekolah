<?php
$page_title  = 'Kelola Slider';
$active_menu = 'slider';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$slides = [
    1 => ['label' => 'Slide 1', 'judul' => 'Selamat Datang',    'warna' => '#2563eb'],
    2 => ['label' => 'Slide 2', 'judul' => 'Fasilitas Sekolah', 'warna' => '#7c3aed'],
    3 => ['label' => 'Slide 3', 'judul' => 'Prestasi Sekolah',  'warna' => '#059669'],
];

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slide_no = (int)($_POST['slide_no'] ?? 0);

    if ($slide_no >= 1 && $slide_no <= 3 && !empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'slider/');
        if ($upload['success']) {
            // Rename ke slide1.jpg / slide2.jpg / slide3.jpg
            $ext      = pathinfo($upload['filename'], PATHINFO_EXTENSION);
            $old_path = UPLOAD_PATH . 'slider/' . $upload['filename'];
            $new_name = 'slide' . $slide_no . '.jpg';
            $new_path = UPLOAD_PATH . 'slider/' . $new_name;

            // Hapus file lama jika ada
            if (file_exists($new_path)) unlink($new_path);

            // Convert/rename ke jpg
            rename($old_path, $new_path);

            setFlash('success', "Foto Slide $slide_no berhasil diupload!");
            redirect(SITE_URL . '/admin/slider/index.php');
        } else {
            setFlash('danger', 'Upload gagal: ' . $upload['message']);
            redirect(SITE_URL . '/admin/slider/index.php');
        }
    }
}

// Handle hapus
if (isset($_GET['hapus'])) {
    $no = (int)$_GET['hapus'];
    if ($no >= 1 && $no <= 3) {
        $path = UPLOAD_PATH . 'slider/slide' . $no . '.jpg';
        if (file_exists($path)) {
            unlink($path);
            setFlash('success', "Foto Slide $no berhasil dihapus.");
        }
    }
    redirect(SITE_URL . '/admin/slider/index.php');
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1">Kelola Foto Slider</h5>
        <p class="text-muted small mb-0">Upload foto untuk ditampilkan di slider homepage. Ukuran ideal: <strong>1920 × 600 px</strong></p>
    </div>
    <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn-add" style="background:#059669;">
        <i class="fas fa-eye me-1"></i> Preview Website
    </a>
</div>

<div class="row g-4">
    <?php foreach ($slides as $no => $slide): ?>
    <?php
        $file_path = UPLOAD_PATH . 'slider/slide' . $no . '.jpg';
        $has_foto  = file_exists($file_path);
        $img_url   = SITE_URL . '/uploads/slider/slide' . $no . '.jpg';
    ?>
    <div class="col-lg-4">
        <div class="admin-card">
            <!-- Preview Foto -->
            <div style="position:relative; height:200px; overflow:hidden; background:<?php echo $slide['warna']; ?>20;">
                <?php if ($has_foto): ?>
                    <img src="<?php echo $img_url; ?>?t=<?php echo filemtime($file_path); ?>"
                         alt="<?php echo $slide['label']; ?>"
                         style="width:100%; height:200px; object-fit:cover; display:block;">
                    <!-- Tombol hapus -->
                    <a href="?hapus=<?php echo $no; ?>"
                       class="btn-confirm-delete"
                       data-name="foto Slide <?php echo $no; ?>"
                       style="position:absolute; top:10px; right:10px; background:rgba(239,68,68,0.9); color:white; width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; text-decoration:none;"
                       title="Hapus Foto">
                        <i class="fas fa-trash" style="font-size:13px;"></i>
                    </a>
                    <!-- Badge ada foto -->
                    <span style="position:absolute; bottom:10px; left:10px; background:rgba(16,185,129,0.9); color:white; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px;">
                        <i class="fas fa-check me-1"></i>Foto terpasang
                    </span>
                <?php else: ?>
                    <!-- Placeholder gradient -->
                    <div style="width:100%; height:200px; background:linear-gradient(135deg, <?php echo $slide['warna']; ?>, <?php echo $slide['warna']; ?>88); display:flex; align-items:center; justify-content:center; flex-direction:column; color:white;">
                        <i class="fas fa-image" style="font-size:40px; opacity:0.5; margin-bottom:8px;"></i>
                        <span style="font-size:13px; opacity:0.7;">Belum ada foto</span>
                    </div>
                    <!-- Badge belum -->
                    <span style="position:absolute; bottom:10px; left:10px; background:rgba(107,114,128,0.9); color:white; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px;">
                        <i class="fas fa-image me-1"></i>Gunakan warna default
                    </span>
                <?php endif; ?>
            </div>

            <!-- Info & Form Upload -->
            <div class="admin-card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:10px; height:10px; border-radius:50%; background:<?php echo $slide['warna']; ?>;"></div>
                    <h6 class="fw-bold mb-0"><?php echo $slide['label']; ?></h6>
                    <span class="text-muted small">— <?php echo $slide['judul']; ?></span>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="slide_no" value="<?php echo $no; ?>">
                    <div class="mb-2">
                        <label class="form-label" style="font-size:13px;">
                            <?php echo $has_foto ? 'Ganti Foto' : 'Upload Foto'; ?>
                        </label>
                        <input type="file" name="foto" class="form-control form-control-sm"
                               accept="image/jpeg,image/png,image/webp"
                               onchange="previewSlide(this, 'prev<?php echo $no; ?>')"
                               required>
                        <div class="text-muted mt-1" style="font-size:11px;">
                            JPG/PNG/WEBP · Max 5MB · Rekomendasi 1920×600px
                        </div>
                    </div>
                    <!-- Preview sebelum upload -->
                    <img id="prev<?php echo $no; ?>" src="" alt="" class="img-fluid rounded mb-2"
                         style="display:none; width:100%; height:100px; object-fit:cover;">

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
<div class="admin-card mt-4">
    <div class="admin-card-body">
        <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Tips Foto Slider</h6>
        <div class="row g-3 text-muted small">
            <div class="col-md-3">
                <i class="fas fa-expand-arrows-alt text-primary me-1"></i>
                Ukuran ideal: <strong>1920 × 600 px</strong>
            </div>
            <div class="col-md-3">
                <i class="fas fa-file-image text-primary me-1"></i>
                Format: JPG, PNG, atau WEBP
            </div>
            <div class="col-md-3">
                <i class="fas fa-weight text-primary me-1"></i>
                Maksimal ukuran file: <strong>5 MB</strong>
            </div>
            <div class="col-md-3">
                <i class="fas fa-moon text-primary me-1"></i>
                Gunakan foto gelap agar teks mudah dibaca
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
