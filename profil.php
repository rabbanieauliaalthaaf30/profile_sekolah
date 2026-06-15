<?php
$page_title = 'Profil Sekolah';
$current_page = 'profil';
include 'includes/header.php';

// Get profil sekolah
$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");

// Get fasilitas
$fasilitas = fetchAll("SELECT * FROM fasilitas ORDER BY urutan ASC");
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Profil Sekolah</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Tentang Sekolah -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <?php
                $foto_gedung_url = !empty($profil['foto_gedung'])
                    ? SITE_URL . '/uploads/profil/' . clean($profil['foto_gedung'])
                    : null;
                ?>
                <?php if ($foto_gedung_url): ?>
                <img src="<?php echo $foto_gedung_url; ?>" alt="Gedung Sekolah" class="img-fluid rounded shadow">
                <?php else: ?>
                <div class="rounded shadow d-flex align-items-center justify-content-center" style="height:400px; background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#2563eb;">
                    <div class="text-center"><i class="fas fa-school fa-5x opacity-50 mb-3"></i><p class="fw-semibold">Foto Gedung Sekolah</p></div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="ps-lg-4">
                    <h2 class="section-title mb-4">Tentang <?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?></h2>
                    
                    <div class="mb-4">
                        <div class="d-flex mb-3">
                            <i class="fas fa-school text-primary me-3 mt-1"></i>
                            <div>
                                <strong>NPSN:</strong> <?php echo clean($profil['npsn'] ?? '20123456'); ?>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <i class="fas fa-calendar-alt text-primary me-3 mt-1"></i>
                            <div>
                                <strong>Tahun Berdiri:</strong> <?php echo clean($profil['tahun_berdiri'] ?? '1985'); ?>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <i class="fas fa-certificate text-primary me-3 mt-1"></i>
                            <div>
                                <strong>Akreditasi:</strong> <span class="badge bg-success"><?php echo clean($profil['akreditasi'] ?? 'A'); ?></span>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <i class="fas fa-user-tie text-primary me-3 mt-1"></i>
                            <div>
                                <strong>Kepala Sekolah:</strong> <?php echo clean($profil['kepala_sekolah_nama'] ?? 'Dr. Budi Santoso, M.Pd.'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-muted">
                        <?php echo nl2br(clean($profil['sejarah'] ?? 'SMA Negeri 1 Harapan Bangsa adalah sekolah yang berdedikasi untuk memberikan pendidikan berkualitas.')); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi -->
<section class="section bg-light" id="visi-misi">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Visi & Misi</h2>
            <p class="section-subtitle">Tujuan dan arah pendidikan kami</p>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="stat-icon mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #2563eb, #1e40af);">
                                <i class="fas fa-eye text-white" style="font-size: 35px;"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-center mb-4">Visi</h3>
                        
                        <p class="text-center text-muted mb-0" style="font-size: 16px; line-height: 1.8;">
                            <?php echo nl2br(clean($profil['visi'] ?? 'Mewujudkan peserta didik yang beriman, bertaqwa, berakhlak mulia, berprestasi, berbudaya lingkungan, dan berwawasan global.')); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4" data-aos="fade-left">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="stat-icon mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-bullseye text-white" style="font-size: 35px;"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-center mb-4">Misi</h3>
                        
                        <div>
                            <?php 
                            $misi = $profil['misi'] ?? "1. Menyelenggarakan pembelajaran yang aktif dan inovatif\n2. Menumbuhkan semangat keunggulan\n3. Mendorong siswa mengenali potensi diri\n4. Menumbuhkan penghayatan terhadap ajaran agama\n5. Menerapkan manajemen partisipatif";
                            $misi_array = explode("\n", $misi);
                            foreach ($misi_array as $item):
                                if (trim($item)):
                            ?>
                            <p class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="text-muted"><?php echo clean(trim($item)); ?></span>
                            </p>
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

<!-- Fasilitas -->
<section class="section" id="fasilitas">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Fasilitas Sekolah</h2>
            <p class="section-subtitle">Fasilitas modern untuk mendukung pembelajaran</p>
        </div>
        
        <div class="row g-4">
            <?php if (empty($fasilitas)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada data fasilitas tersedia.</p>
                </div>
            <?php else: ?>
                <?php foreach ($fasilitas as $index => $item): ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #2563eb, #1e40af);">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <h5 class="card-title mb-3"><?php echo clean($item['nama_fasilitas']); ?></h5>
                            <p class="card-text text-muted small">
                                <?php echo clean($item['deskripsi']); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background: linear-gradient(135deg, #2563eb, #1e40af); padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="text-white mb-3">Ingin Tahu Lebih Lanjut?</h2>
                <p class="text-white opacity-75 mb-0">Hubungi kami untuk informasi lebih detail tentang sekolah dan program pendidikan</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end" data-aos="fade-left">
                <a href="<?php echo SITE_URL; ?>/kontak.php" class="btn btn-hero-primary btn-lg">
                    <i class="fas fa-envelope me-2"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
