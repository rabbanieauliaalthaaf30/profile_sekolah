<?php
$page_title = 'Beranda';
$current_page = 'home';
include 'includes/header.php';

// Get profil sekolah
$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");

// Get statistics
$total_siswa  = 720;
$total_guru   = fetch("SELECT COUNT(*) as total FROM guru WHERE status = 'aktif'")['total'] ?? 0;
$total_galeri = fetch("SELECT COUNT(*) as total FROM galeri")['total'] ?? 0;
$total_fasilitas = fetch("SELECT COUNT(*) as total FROM fasilitas")['total'] ?? 0;
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
                <h2 data-aos="fade-up">Galeri Kegiatan</h2>
                <p data-aos="fade-up" data-aos-delay="100">Momen-momen berkesan dari berbagai kegiatan sekolah</p>
                <a href="<?php echo SITE_URL; ?>/galeri.php" class="btn btn-hero-primary mt-3" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-images me-2"></i>Lihat Galeri
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
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number" data-target="<?php echo $total_siswa; ?>"><?php echo $total_siswa; ?></div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-number" data-target="<?php echo $total_guru; ?>"><?php echo $total_guru; ?></div>
                    <div class="stat-label">Guru Profesional</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-images"></i></div>
                    <div class="stat-number" data-target="<?php echo $total_galeri; ?>"><?php echo $total_galeri; ?></div>
                    <div class="stat-label">Foto Galeri</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-building"></i></div>
                    <div class="stat-number" data-target="<?php echo $total_fasilitas; ?>"><?php echo $total_fasilitas; ?></div>
                    <div class="stat-label">Fasilitas Sekolah</div>
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

<!-- Galeri Singkat -->
<?php $galeri_preview = fetchAll("SELECT * FROM galeri ORDER BY tanggal_kegiatan DESC LIMIT 6"); ?>
<?php if (!empty($galeri_preview)): ?>
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Galeri Kegiatan</h2>
            <p class="section-subtitle">Momen berkesan dari berbagai kegiatan sekolah</p>
        </div>
        <div class="row g-3">
            <?php foreach ($galeri_preview as $i => $g): ?>
            <?php $img = !empty($g['foto']) ? SITE_URL . '/uploads/galeri/' . clean($g['foto']) : ''; ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 100; ?>">
                <div class="gallery-item">
                    <?php if ($img): ?>
                    <img src="<?php echo $img; ?>" alt="<?php echo clean($g['judul']); ?>" class="gallery-img">
                    <?php else: ?>
                    <div class="gallery-img d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#2563eb; height:250px;">
                        <i class="fas fa-image fa-3x opacity-50"></i>
                    </div>
                    <?php endif; ?>
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h5 class="gallery-title"><?php echo clean($g['judul']); ?></h5>
                            <p class="gallery-date"><i class="far fa-calendar me-1"></i><?php echo formatTanggal($g['tanggal_kegiatan']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?php echo SITE_URL; ?>/galeri.php" class="btn btn-primary btn-lg">
                <i class="fas fa-images me-2"></i>Lihat Semua Galeri
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

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
