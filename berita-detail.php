<?php
$current_page = 'berita';
include 'includes/header.php';

$slug = isset($_GET['slug']) ? escape($_GET['slug']) : '';

if (!$slug) {
    header('Location: ' . SITE_URL . '/berita.php');
    exit;
}

// Get berita
$berita = fetch("SELECT b.*, k.nama_kategori, k.slug as kategori_slug, u.nama_lengkap as penulis
                 FROM berita b
                 LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                 LEFT JOIN users u ON b.penulis_id = u.id
                 WHERE b.slug = '$slug' AND b.status = 'published'");

if (!$berita) {
    header('Location: ' . SITE_URL . '/berita.php');
    exit;
}

$page_title = $berita['judul'];

// Increment views
query("UPDATE berita SET views = views + 1 WHERE id = " . $berita['id']);

// Get related news
$related = [];
if (!empty($berita['kategori_id'])) {
    $related = fetchAll("SELECT b.*, k.nama_kategori
                         FROM berita b
                         LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                         WHERE b.status = 'published' AND b.id != " . $berita['id'] . "
                         AND b.kategori_id = " . (int)$berita['kategori_id'] . "
                         ORDER BY b.tanggal_publish DESC
                         LIMIT 3");
}
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up" style="font-size: 32px;"><?php echo clean($berita['judul']); ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/berita.php">Berita</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8 mb-4" data-aos="fade-right">
                <article class="berita-detail-card">
                    <!-- Featured Image -->
                    <?php if (!empty($berita['foto_utama'])): ?>
                    <div class="berita-detail-img mb-4">
                        <img src="<?php echo SITE_URL . '/uploads/berita/' . clean($berita['foto_utama']); ?>" alt="<?php echo clean($berita['judul']); ?>" class="img-fluid rounded shadow w-100" style="height: 400px; object-fit: cover;">
                    </div>
                    <?php endif; ?>

                    <!-- Meta Info -->
                    <div class="berita-detail-meta mb-4">
                        <span class="badge bg-primary me-2"><?php echo clean($berita['nama_kategori'] ?? 'Umum'); ?></span>
                        <span class="text-muted me-3"><i class="far fa-calendar me-1"></i><?php echo formatTanggal($berita['tanggal_publish'], 'd F Y H:i'); ?></span>
                        <span class="text-muted me-3"><i class="far fa-user me-1"></i><?php echo clean($berita['penulis']); ?></span>
                        <span class="text-muted"><i class="far fa-eye me-1"></i><?php echo $berita['views']; ?> kali dibaca</span>
                    </div>

                    <!-- Title -->
                    <h1 class="berita-detail-title mb-4"><?php echo clean($berita['judul']); ?></h1>

                    <!-- Content -->
                    <div class="berita-detail-content">
                        <?php echo nl2br(clean($berita['konten'])); ?>
                    </div>

                    <!-- Share -->
                    <div class="berita-share mt-5 pt-4 border-top">
                        <h6 class="mb-3"><i class="fas fa-share-alt me-2"></i>Bagikan Berita:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/berita-detail.php?slug=' . $berita['slug']); ?>" target="_blank" class="btn btn-sm" style="background:#1877f2; color:white;">
                                <i class="fab fa-facebook-f me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($berita['judul']); ?>&url=<?php echo urlencode(SITE_URL . '/berita-detail.php?slug=' . $berita['slug']); ?>" target="_blank" class="btn btn-sm" style="background:#1da1f2; color:white;">
                                <i class="fab fa-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($berita['judul'] . ' - ' . SITE_URL . '/berita-detail.php?slug=' . $berita['slug']); ?>" target="_blank" class="btn btn-sm" style="background:#25d366; color:white;">
                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Related News -->
                <?php if (!empty($related)): ?>
                <div class="related-news mt-5">
                    <h4 class="mb-4"><i class="fas fa-newspaper me-2 text-primary"></i>Berita Terkait</h4>
                    <div class="row g-3">
                        <?php foreach ($related as $rel): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php $img_rel = !empty($rel['foto_utama']) ? SITE_URL . '/uploads/berita/' . clean($rel['foto_utama']) : ''; ?>
                                <?php if ($img_rel): ?>
                                <img src="<?php echo $img_rel; ?>" class="card-img-top" alt="<?php echo clean($rel['judul']); ?>" style="height: 150px; object-fit: cover;">
                                <?php else: ?>
                                <div style="height:150px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;"><i class="fas fa-image fa-2x"></i></div>
                                <?php endif; ?>
                                <div class="card-body p-3">
                                    <small class="text-muted"><?php echo formatTanggal($rel['tanggal_publish']); ?></small>
                                    <h6 class="mt-1 mb-2">
                                        <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $rel['slug']; ?>" class="text-dark">
                                            <?php echo clean(limitText($rel['judul'], 60)); ?>
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4" data-aos="fade-left">
                <!-- Latest News -->
                <?php
                $latest = fetchAll("SELECT b.*, k.nama_kategori FROM berita b
                                    LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                                    WHERE b.status = 'published' AND b.id != " . $berita['id'] . "
                                    ORDER BY b.tanggal_publish DESC LIMIT 5");
                ?>
                <?php if (!empty($latest)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4 pb-2 border-bottom">
                            <i class="fas fa-clock me-2 text-primary"></i>Berita Terbaru
                        </h5>
                        <?php foreach ($latest as $l): ?>
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <?php $img_latest = !empty($l['foto_utama']) ? SITE_URL . '/uploads/berita/' . clean($l['foto_utama']) : ''; ?>
                            <?php if ($img_latest): ?>
                            <img src="<?php echo $img_latest; ?>" alt="" class="rounded" style="width: 70px; height: 70px; object-fit: cover; flex-shrink: 0;">
                            <?php else: ?>
                            <div class="rounded" style="width:70px; height:70px; background:#f1f5f9; flex-shrink:0; display:flex; align-items:center; justify-content:center; color:#94a3b8;"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                            <div>
                                <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $l['slug']; ?>" class="text-dark small fw-semibold">
                                    <?php echo clean(limitText($l['judul'], 65)); ?>
                                </a>
                                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">
                                    <i class="far fa-calendar me-1"></i><?php echo formatTanggal($l['tanggal_publish']); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Categories -->
                <?php $cats = fetchAll("SELECT k.*, COUNT(b.id) as jumlah FROM kategori_berita k LEFT JOIN berita b ON k.id = b.kategori_id AND b.status = 'published' GROUP BY k.id ORDER BY k.nama_kategori"); ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4 pb-2 border-bottom">
                            <i class="fas fa-folder me-2 text-primary"></i>Kategori
                        </h5>
                        <?php foreach ($cats as $cat): ?>
                        <a href="<?php echo SITE_URL; ?>/berita.php?kategori=<?php echo $cat['id']; ?>" class="d-flex justify-content-between align-items-center mb-2 text-decoration-none text-dark p-2 rounded" style="transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''">
                            <span><i class="fas fa-chevron-right text-primary me-2 small"></i><?php echo clean($cat['nama_kategori']); ?></span>
                            <span class="badge bg-primary rounded-pill"><?php echo $cat['jumlah']; ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.berita-detail-title { font-size: 28px; line-height: 1.4; }
.berita-detail-content { font-size: 16px; line-height: 1.9; color: #374151; }
.berita-detail-content p { margin-bottom: 1.5rem; }
</style>

<?php include 'includes/footer.php'; ?>
