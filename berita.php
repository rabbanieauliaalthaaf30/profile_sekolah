<?php
$page_title = 'Berita';
$current_page = 'berita';
include 'includes/header.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = BERITA_PER_PAGE;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$kategori_filter = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

// Build query
$where = "WHERE b.status = 'published'";
if ($search) {
    $where .= " AND (b.judul LIKE '%$search%' OR b.konten LIKE '%$search%')";
}
if ($kategori_filter > 0) {
    $where .= " AND b.kategori_id = $kategori_filter";
}

// Get total
$total = fetch("SELECT COUNT(*) as total FROM berita b $where")['total'];
$total_pages = ceil($total / $limit);

// Get berita
$berita = fetchAll("SELECT b.*, k.nama_kategori, u.nama_lengkap as penulis
                    FROM berita b
                    LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                    LEFT JOIN users u ON b.penulis_id = u.id
                    $where
                    ORDER BY b.tanggal_publish DESC
                    LIMIT $limit OFFSET $offset");

// Get categories
$categories = fetchAll("SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Berita & Pengumuman</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Berita</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4 mb-4" data-aos="fade-right">
                <!-- Search Box -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-search me-2 text-primary"></i>Pencarian
                        </h5>
                        <form action="" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Cari berita..." value="<?php echo clean($search); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Categories -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-folder me-2 text-primary"></i>Kategori
                        </h5>
                        <div class="list-group list-group-flush">
                            <a href="<?php echo SITE_URL; ?>/berita.php" class="list-group-item list-group-item-action <?php echo $kategori_filter == 0 ? 'active' : ''; ?>">
                                <i class="fas fa-chevron-right me-2"></i>Semua Berita
                            </a>
                            <?php foreach ($categories as $cat): ?>
                            <a href="<?php echo SITE_URL; ?>/berita.php?kategori=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action <?php echo $kategori_filter == $cat['id'] ? 'active' : ''; ?>">
                                <i class="fas fa-chevron-right me-2"></i><?php echo clean($cat['nama_kategori']); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-8">
                <?php if (empty($berita)): ?>
                    <div class="alert alert-info" data-aos="fade-up">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php if ($search): ?>
                            Tidak ada berita yang ditemukan dengan kata kunci "<strong><?php echo clean($search); ?></strong>".
                        <?php else: ?>
                            Belum ada berita tersedia.
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($berita as $index => $item): ?>
                        <div class="col-12" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                            <div class="card news-card border-0 shadow-sm">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <div class="card-img-wrapper">
                                            <?php $img_berita = !empty($item['foto_utama']) ? SITE_URL . '/uploads/berita/' . clean($item['foto_utama']) : ''; ?>
                                            <?php if ($img_berita): ?>
                                            <img src="<?php echo $img_berita; ?>" class="img-fluid w-100 h-100" alt="<?php echo clean($item['judul']); ?>" style="object-fit: cover;">
                                            <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background:#f1f5f9; min-height:160px; color:#94a3b8;">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                            <?php endif; ?>
                                            <span class="card-badge"><?php echo clean($item['nama_kategori'] ?? 'Umum'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="news-meta">
                                                <span><i class="far fa-calendar"></i> <?php echo formatTanggal($item['tanggal_publish']); ?></span>
                                                <span><i class="far fa-user"></i> <?php echo clean($item['penulis']); ?></span>
                                                <span><i class="far fa-eye"></i> <?php echo $item['views']; ?></span>
                                            </div>
                                            <h5 class="news-title">
                                                <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $item['slug']; ?>">
                                                    <?php echo clean($item['judul']); ?>
                                                </a>
                                            </h5>
                                            <p class="news-excerpt">
                                                <?php echo clean(limitText($item['ringkasan'] ?? strip_tags($item['konten']), 150)); ?>
                                            </p>
                                            <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $item['slug']; ?>" class="btn-read-more">
                                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav class="mt-5" data-aos="fade-up">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $kategori_filter ? '&kategori=' . $kategori_filter : ''; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page || $i == 1 || $i == $total_pages || ($i >= $page - 1 && $i <= $page + 1)): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $kategori_filter ? '&kategori=' . $kategori_filter : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php elseif ($i == $page - 2 || $i == $page + 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $kategori_filter ? '&kategori=' . $kategori_filter : ''; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
