<?php
$page_title = 'Galeri';
$current_page = 'galeri';
include 'includes/header.php';

$kategori_filter = isset($_GET['kategori']) ? escape($_GET['kategori']) : '';
$where = $kategori_filter ? "WHERE kategori = '$kategori_filter'" : '';

$galeri = fetchAll("SELECT * FROM galeri $where ORDER BY tanggal_kegiatan DESC, created_at DESC");
$kategori_list = fetchAll("SELECT DISTINCT kategori FROM galeri WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori ASC");

// Placeholder jika foto tidak tersedia
$placeholder_img = 'https://via.placeholder.com/600x400?text=Tidak+Ada+Foto';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Galeri Kegiatan</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Galeri</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- Filter Kategori -->
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="galeri-filter">
                <button class="btn filter-btn <?php echo !$kategori_filter ? 'active' : ''; ?>" onclick="filterGaleri('all', this)">
                    Semua
                </button>
                <?php foreach ($kategori_list as $kat): ?>
                <button class="btn filter-btn <?php echo $kategori_filter == $kat['kategori'] ? 'active' : ''; ?>" onclick="filterGaleri('<?php echo clean($kat['kategori']); ?>', this)">
                    <?php echo clean($kat['kategori']); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row g-4" id="galeri-grid">
            <?php if (empty($galeri)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada foto galeri tersedia.</p>
                </div>
            <?php else: ?>
                <?php foreach ($galeri as $index => $item): ?>
                <?php
                    // Gunakan foto dari database, fallback ke placeholder
                    $img_src = !empty($item['foto'])
                        ? SITE_URL . '/uploads/galeri/' . clean($item['foto'])
                        : $placeholder_img;
                ?>
                <div class="col-lg-4 col-md-6 galeri-item" data-kategori="<?php echo clean($item['kategori']); ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                    <div class="gallery-item">
                        <img src="<?php echo $img_src; ?>" alt="<?php echo clean($item['judul']); ?>" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-info">
                                <h5 class="gallery-title"><?php echo clean($item['judul']); ?></h5>
                                <p class="gallery-date">
                                    <i class="far fa-calendar me-1"></i>
                                    <?php echo $item['tanggal_kegiatan'] ? formatTanggal($item['tanggal_kegiatan']) : '-'; ?>
                                </p>
                                <?php if ($item['deskripsi']): ?>
                                <p class="small mt-2"><?php echo clean(limitText($item['deskripsi'], 80)); ?></p>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-light mt-2" onclick="openLightbox('<?php echo $img_src; ?>', '<?php echo clean($item['judul']); ?>')">
                                    <i class="fas fa-expand me-1"></i>Perbesar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <span class="badge bg-primary"><?php echo clean($item['kategori']); ?></span>
                        <small class="text-muted ms-2"><?php echo $item['tanggal_kegiatan'] ? formatTanggal($item['tanggal_kegiatan']) : ''; ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="lightboxTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" id="lightboxImg" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<style>
.galeri-filter { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-bottom: 20px; }
.filter-btn { padding: 8px 20px; border-radius: 25px; border: 2px solid var(--primary-color); color: var(--primary-color); font-weight: 500; transition: all 0.3s ease; }
.filter-btn:hover, .filter-btn.active { background: var(--primary-color); color: white; }
.galeri-item.hidden { display: none; }
</style>

<script>
function filterGaleri(kategori, btn) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Filter items
    document.querySelectorAll('.galeri-item').forEach(item => {
        if (kategori === 'all' || item.dataset.kategori === kategori) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}

function openLightbox(src, title) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
</script>

<?php include 'includes/footer.php'; ?>
