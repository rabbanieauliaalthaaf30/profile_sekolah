<?php
$page_title = 'Prestasi';
$current_page = 'prestasi';
include 'includes/header.php';

$prestasi = fetchAll("SELECT * FROM prestasi ORDER BY tahun DESC, tingkat DESC, nama_prestasi ASC");

// Group by year
$by_year = [];
foreach ($prestasi as $p) {
    $by_year[$p['tahun']][] = $p;
}
krsort($by_year);

$tingkat_label = [
    'internasional' => ['label' => 'Internasional', 'color' => 'danger', 'icon' => 'fas fa-globe'],
    'nasional'      => ['label' => 'Nasional',      'color' => 'warning text-dark', 'icon' => 'fas fa-flag'],
    'provinsi'      => ['label' => 'Provinsi',      'color' => 'info',    'icon' => 'fas fa-map'],
    'kota'          => ['label' => 'Kota/Kab.',     'color' => 'success', 'icon' => 'fas fa-city'],
    'sekolah'       => ['label' => 'Sekolah',       'color' => 'secondary', 'icon' => 'fas fa-school'],
];
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Prestasi</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Prestasi</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Stats Prestasi -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <?php
            $total = count($prestasi);
            $akademik = count(array_filter($prestasi, fn($p) => $p['kategori'] == 'akademik'));
            $non_akademik = count(array_filter($prestasi, fn($p) => $p['kategori'] == 'non-akademik'));
            $nasional_up = count(array_filter($prestasi, fn($p) => in_array($p['tingkat'], ['nasional', 'internasional'])));
            ?>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                    <div class="stat-number" data-target="<?php echo $total; ?>"><?php echo $total; ?></div>
                    <div class="stat-label">Total Prestasi</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-book"></i></div>
                    <div class="stat-number" data-target="<?php echo $akademik; ?>"><?php echo $akademik; ?></div>
                    <div class="stat-label">Prestasi Akademik</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-medal"></i></div>
                    <div class="stat-number" data-target="<?php echo $non_akademik; ?>"><?php echo $non_akademik; ?></div>
                    <div class="stat-label">Non-Akademik</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-flag"></i></div>
                    <div class="stat-number" data-target="<?php echo $nasional_up; ?>"><?php echo $nasional_up; ?></div>
                    <div class="stat-label">Nasional & Internasional</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Daftar Prestasi -->
<section class="section">
    <div class="container">
        <!-- Filter -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" data-aos="fade-up">
            <button class="btn filter-btn active" onclick="filterPrestasi('all', this)">Semua</button>
            <button class="btn filter-btn" onclick="filterPrestasi('akademik', this)">
                <i class="fas fa-book me-1"></i>Akademik
            </button>
            <button class="btn filter-btn" onclick="filterPrestasi('non-akademik', this)">
                <i class="fas fa-medal me-1"></i>Non-Akademik
            </button>
        </div>

        <?php if (empty($prestasi)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada data prestasi.</p>
            </div>
        <?php else: ?>
            <?php foreach ($by_year as $year => $items): ?>
            <div class="year-group mb-5" data-aos="fade-up">
                <div class="year-header mb-4">
                    <h3 class="d-inline-block bg-primary text-white px-4 py-2 rounded-pill mb-0">
                        <i class="fas fa-calendar me-2"></i><?php echo $year; ?>
                    </h3>
                </div>

                <div class="row g-4">
                    <?php foreach ($items as $index => $item): ?>
                    <div class="col-lg-4 col-md-6 prestasi-item" data-kategori="<?php echo $item['kategori']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                        <div class="prestasi-card card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="prestasi-icon">
                                        <?php if ($item['tingkat'] == 'nasional' || $item['tingkat'] == 'internasional'): ?>
                                            <i class="fas fa-trophy text-warning" style="font-size: 40px;"></i>
                                        <?php elseif ($item['tingkat'] == 'provinsi'): ?>
                                            <i class="fas fa-medal text-secondary" style="font-size: 40px;"></i>
                                        <?php else: ?>
                                            <i class="fas fa-award text-primary" style="font-size: 40px;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?php echo $tingkat_label[$item['tingkat']]['color']; ?> mb-1 d-block">
                                            <i class="<?php echo $tingkat_label[$item['tingkat']]['icon']; ?> me-1"></i>
                                            <?php echo $tingkat_label[$item['tingkat']]['label']; ?>
                                        </span>
                                        <span class="badge bg-<?php echo $item['kategori'] == 'akademik' ? 'info' : 'success'; ?>">
                                            <?php echo ucfirst($item['kategori']); ?>
                                        </span>
                                    </div>
                                </div>

                                <h5 class="prestasi-name mb-2"><?php echo clean($item['nama_prestasi']); ?></h5>

                                <?php if ($item['deskripsi']): ?>
                                <p class="text-muted small mb-0"><?php echo clean($item['deskripsi']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.filter-btn { padding: 8px 20px; border-radius: 25px; border: 2px solid var(--primary-color); color: var(--primary-color); font-weight: 500; transition: all 0.3s ease; }
.filter-btn:hover, .filter-btn.active { background: var(--primary-color); color: white; }
.prestasi-item.hidden { display: none; }
.prestasi-card { transition: all 0.3s ease; }
.prestasi-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important; }
.prestasi-name { font-size: 16px; line-height: 1.4; }
.year-header { border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
</style>

<script>
function filterPrestasi(kategori, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.prestasi-item').forEach(item => {
        if (kategori === 'all' || item.dataset.kategori === kategori) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
