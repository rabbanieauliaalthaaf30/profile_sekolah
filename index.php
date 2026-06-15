<?php
$page_title = 'Beranda';
$current_page = 'home';
include 'includes/header.php';

// Get latest news
$latest_news = fetchAll("SELECT b.*, k.nama_kategori, u.nama_lengkap as penulis
                         FROM berita b
                         LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                         LEFT JOIN users u ON b.penulis_id = u.id
                         WHERE b.status = 'published'
                         ORDER BY b.tanggal_publish DESC
                         LIMIT 6");

// Get profil sekolah
$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");

// Get statistics
$total_siswa = 720; // Static or from database
$total_guru = fetch("SELECT COUNT(*) as total FROM guru WHERE status = 'aktif'")['total'] ?? 0;
$total_staff = fetch("SELECT COUNT(*) as total FROM staff WHERE status = 'aktif'")['total'] ?? 0;
$total_prestasi = fetch("SELECT COUNT(*) as total FROM prestasi WHERE tahun >= YEAR(CURDATE()) - 2")['total'] ?? 0;
?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?php
            $slide1 = file_exists(__DIR__ . '/uploads/slider/slide1.jpg') ? SITE_URL . '/uploads/slider/slide1.jpg' : '';
            $slide2 = file_exists(__DIR__ . '/uploads/slider/slide2.jpg') ? SITE_URL . '/uploads/slider/slide2.jpg' : '';
            $slide3 = file_exists(__DIR__ . '/uploads/slider/slide3.jpg') ? SITE_URL . '/uploads/slider/slide3.jpg' : '';
            ?>
            <?php if ($slide1): ?>
                <img src="<?php echo $slide1; ?>" class="d-block w-100" alt="Slide 1" style="height:600px; object-fit:cover;">
            <?php else: ?>
                <div style="height:600px; background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #1e40af 100%);"></div>
            <?php endif; ?>
            <div class="carousel-caption">
                <h2 data-aos="fade-up">Selamat Datang di <?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?></h2>
                <p data-aos="fade-up" data-aos-delay="100">Mewujudkan generasi unggul, berakhlak mulia, dan berwawasan global</p>
                <a href="<?php echo SITE_URL; ?>/profil.php" class="btn btn-hero-primary mt-3" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-info-circle me-2"></i>Tentang Kami
                </a>
            </div>
        </div>
        
        <div class="carousel-item">
            <?php if ($slide2): ?>
                <img src="<?php echo $slide2; ?>" class="d-block w-100" alt="Slide 2" style="height:600px; object-fit:cover;">
            <?php else: ?>
                <div style="height:600px; background: linear-gradient(135deg, #1e40af 0%, #7c3aed 60%, #4f46e5 100%);"></div>
            <?php endif; ?>
            <div class="carousel-caption">
                <h2 data-aos="fade-up">Fasilitas Modern & Lengkap</h2>
                <p data-aos="fade-up" data-aos-delay="100">Laboratorium, perpustakaan, dan ruang multimedia untuk mendukung pembelajaran</p>
                <a href="<?php echo SITE_URL; ?>/profil.php#fasilitas" class="btn btn-hero-primary mt-3" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-building me-2"></i>Lihat Fasilitas
                </a>
            </div>
        </div>
        
        <div class="carousel-item">
            <?php if ($slide3): ?>
                <img src="<?php echo $slide3; ?>" class="d-block w-100" alt="Slide 3" style="height:600px; object-fit:cover;">
            <?php else: ?>
                <div style="height:600px; background: linear-gradient(135deg, #065f46 0%, #059669 60%, #10b981 100%);"></div>
            <?php endif; ?>
            <div class="carousel-caption">
                <h2 data-aos="fade-up">Prestasi Membanggakan</h2>
                <p data-aos="fade-up" data-aos-delay="100">Raih prestasi di berbagai kompetisi akademik dan non-akademik</p>
                <a href="<?php echo SITE_URL; ?>/prestasi.php" class="btn btn-hero-primary mt-3" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-trophy me-2"></i>Lihat Prestasi
                </a>
            </div>
        </div>
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" data-target="<?php echo $total_siswa; ?>"><?php echo $total_siswa; ?></div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number" data-target="<?php echo $total_guru; ?>"><?php echo $total_guru; ?></div>
                    <div class="stat-label">Guru Profesional</div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-number" data-target="<?php echo $total_staff; ?>"><?php echo $total_staff; ?></div>
                    <div class="stat-label">Staff Administrasi</div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-number" data-target="<?php echo $total_prestasi; ?>"><?php echo $total_prestasi; ?></div>
                    <div class="stat-label">Prestasi Diraih</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sambutan Kepala Sekolah -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                <?php
                $foto_kepsek = !empty($profil['kepala_sekolah_foto'])
                    ? SITE_URL . '/uploads/profil/' . clean($profil['kepala_sekolah_foto'])
                    : 'https://ui-avatars.com/api/?name=' . urlencode($profil['kepala_sekolah_nama'] ?? 'Kepala Sekolah') . '&size=600&background=2563eb&color=fff&bold=true&length=2';
                ?>
                <img src="<?php echo $foto_kepsek; ?>" alt="Kepala Sekolah" class="img-fluid rounded shadow">
            </div>
            
            <div class="col-lg-7" data-aos="fade-left">
                <div class="ps-lg-4">
                    <span class="badge bg-primary mb-3">Sambutan Kepala Sekolah</span>
                    <h2 class="section-title mb-4"><?php echo clean($profil['kepala_sekolah_nama'] ?? 'Dr. Budi Santoso, M.Pd.'); ?></h2>
                    
                    <p class="text-muted mb-3">
                        Assalamu'alaikum Warahmatullahi Wabarakatuh,
                    </p>
                    
                    <p class="text-muted mb-3">
                        Puji syukur kehadirat Allah SWT yang telah memberikan rahmat dan karunia-Nya, sehingga kita dapat melaksanakan tugas kita sebagai pendidik dengan baik.
                    </p>
                    
                    <p class="text-muted mb-3">
                        <?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?> berkomitmen untuk memberikan pendidikan berkualitas yang tidak hanya fokus pada akademik, tetapi juga pengembangan karakter dan keterampilan siswa. Kami berupaya menciptakan lingkungan belajar yang kondusif, inovatif, dan menyenangkan.
                    </p>
                    
                    <p class="text-muted mb-4">
                        Mari bersama-sama kita wujudkan generasi yang cerdas, berakhlak mulia, dan siap menghadapi tantangan di era global.
                    </p>
                    
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0"><?php echo clean($profil['kepala_sekolah_nama'] ?? 'Dr. Budi Santoso, M.Pd.'); ?></h5>
                            <p class="text-muted mb-0"><small>Kepala Sekolah</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Berita Terbaru -->
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Berita Terbaru</h2>
            <p class="section-subtitle">Informasi dan kegiatan terkini dari sekolah</p>
        </div>
        
        <div class="row g-4">
            <?php if (empty($latest_news)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada berita tersedia.</p>
                </div>
            <?php else: ?>
                <?php foreach ($latest_news as $index => $news): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="card news-card h-100">
                        <div class="card-img-wrapper">
                            <?php $img_news = !empty($news['foto_utama']) ? SITE_URL . '/uploads/berita/' . clean($news['foto_utama']) : ''; ?>
                            <?php if ($img_news): ?>
                            <img src="<?php echo $img_news; ?>" class="card-img-top" alt="<?php echo clean($news['judul']); ?>">
                            <?php else: ?>
                            <div class="card-img-top d-flex align-items-center justify-content-center" style="height:200px; background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#2563eb;">
                                <i class="fas fa-newspaper fa-3x opacity-50"></i>
                            </div>
                            <?php endif; ?>
                            <span class="card-badge"><?php echo clean($news['nama_kategori'] ?? 'Umum'); ?></span>
                        </div>
                        <div class="card-body">
                            <div class="news-meta">
                                <span><i class="far fa-calendar"></i> <?php echo formatTanggal($news['tanggal_publish']); ?></span>
                                <span><i class="far fa-eye"></i> <?php echo $news['views']; ?></span>
                            </div>
                            <h5 class="news-title">
                                <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $news['slug']; ?>">
                                    <?php echo clean($news['judul']); ?>
                                </a>
                            </h5>
                            <p class="news-excerpt">
                                <?php echo clean(limitText($news['ringkasan'] ?? strip_tags($news['konten']), 120)); ?>
                            </p>
                            <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $news['slug']; ?>" class="btn-read-more">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($latest_news)): ?>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?php echo SITE_URL; ?>/berita.php" class="btn btn-primary btn-lg">
                <i class="fas fa-newspaper me-2"></i>Lihat Semua Berita
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Visi Misi -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="stat-icon me-3" style="background: linear-gradient(135deg, #2563eb, #1e40af);">
                                <i class="fas fa-eye text-white"></i>
                            </div>
                            <h3 class="mb-0">Visi</h3>
                        </div>
                        <p class="text-muted mb-0">
                            <?php echo nl2br(clean($profil['visi'] ?? 'Mewujudkan peserta didik yang beriman, bertaqwa, berakhlak mulia, berprestasi, berbudaya lingkungan, dan berwawasan global.')); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4" data-aos="fade-left">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="stat-icon me-3" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-bullseye text-white"></i>
                            </div>
                            <h3 class="mb-0">Misi</h3>
                        </div>
                        <div class="text-muted">
                            <?php 
                            $misi = $profil['misi'] ?? "1. Menyelenggarakan pembelajaran yang berkualitas\n2. Menumbuhkan semangat keunggulan\n3. Mengembangkan potensi siswa";
                            $misi_array = explode("\n", $misi);
                            foreach ($misi_array as $item):
                                if (trim($item)):
                            ?>
                            <p class="mb-2"><i class="fas fa-check-circle text-success me-2"></i><?php echo clean(trim($item)); ?></p>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
