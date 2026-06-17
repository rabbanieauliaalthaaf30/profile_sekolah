<?php
$page_title   = 'Guru';
$current_page = 'guru';
include 'includes/header.php';

$guru = fetchAll("SELECT * FROM guru WHERE status = 'aktif' ORDER BY urutan ASC, nama_lengkap ASC");

/**
 * Ambil URL foto:
 * - Jika ada foto di database & file-nya ada  → pakai foto upload
 * - Jika tidak ada → tampilkan avatar inisial via UI Avatars
 */
function getFotoGuru($foto, $nama, $folder = 'guru') {
    if (!empty($foto)) {
        $path = __DIR__ . '/uploads/' . $folder . '/' . $foto;
        if (file_exists($path)) {
            return SITE_URL . '/uploads/' . $folder . '/' . $foto;
        }
    }
    // Fallback: avatar warna dengan inisial nama
    $initials = urlencode(mb_strtoupper(mb_substr($nama, 0, 2, 'UTF-8'), 'UTF-8'));
    return 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&size=300&background=2563eb&color=fff&bold=true&length=2';
}
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Guru</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Guru & Staff</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Daftar Guru -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Tenaga Pendidik</h2>
            <p class="section-subtitle">Guru profesional dan berdedikasi untuk pendidikan terbaik</p>
        </div>

        <div class="row g-4">
            <?php if (empty($guru)): ?>
                <div class="col-12 text-center"><p class="text-muted">Belum ada data guru.</p></div>
            <?php else: ?>
                <?php foreach ($guru as $index => $g): ?>
                <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 100; ?>">
                    <div class="teacher-card">
                        <img src="<?php echo getFotoGuru($g['foto'], $g['nama_lengkap'], 'guru'); ?>"
                             alt="<?php echo clean($g['nama_lengkap']); ?>"
                             class="teacher-img"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($g['nama_lengkap']); ?>&size=300&background=2563eb&color=fff&bold=true&length=2'">
                        <div class="teacher-name"><?php echo clean($g['nama_lengkap']); ?></div>
                        <div class="teacher-subject"><?php echo clean($g['mata_pelajaran']); ?></div>
                        <div class="teacher-education">
                            <i class="fas fa-graduation-cap me-1 text-muted"></i>
                            <?php echo clean($g['pendidikan_terakhir'] ?? '-'); ?>
                        </div>
                        <div class="mt-auto pt-3 w-100">
                        <?php if ($g['email']): ?>
                            <a href="mailto:<?php echo clean($g['email']); ?>" class="text-primary small d-block">
                                <i class="fas fa-envelope me-1"></i><?php echo clean($g['email']); ?>
                            </a>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
