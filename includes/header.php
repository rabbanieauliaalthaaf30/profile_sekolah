<?php
require_once __DIR__ . '/../config/config.php';

// Get profil sekolah
$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo clean($profil['nama_sekolah'] ?? 'SMA Negeri 1 Harapan Bangsa'); ?> - Website Resmi">
    <meta name="keywords" content="sekolah, pendidikan, SMA, <?php echo clean($profil['nama_sekolah'] ?? ''); ?>">
    <meta name="author" content="<?php echo clean($profil['nama_sekolah'] ?? ''); ?>">
    <title><?php echo isset($page_title) ? clean($page_title) . ' - ' : ''; ?><?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/uploads/logo/<?php echo $profil['logo'] ?? 'logo-sekolah.png'; ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="contact-info">
                        <i class="fas fa-phone-alt me-2"></i>
                        <span><?php echo clean($profil['telepon'] ?? '(021) 1234-5678'); ?></span>
                        <i class="fas fa-envelope ms-3 me-2"></i>
                        <span><?php echo clean($profil['email'] ?? 'info@sekolah.sch.id'); ?></span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="social-links">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo SITE_URL; ?>">
                <img src="<?php echo SITE_URL; ?>/uploads/logo/<?php echo $profil['logo'] ?? 'logo-default.png'; ?>" alt="Logo" class="logo-img me-3">
                <div class="brand-text">
                    <div class="brand-name"><?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?></div>
                    <div class="brand-tagline">Mewujudkan Generasi Unggul</div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') == 'home' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo ($current_page ?? '') == 'profil' ? 'active' : ''; ?>" href="#" id="profilDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-building me-1"></i> Profil
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profil.php">Tentang Sekolah</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profil.php#visi-misi">Visi & Misi</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profil.php#fasilitas">Fasilitas</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') == 'guru' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/guru.php">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Guru & Staff
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') == 'berita' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/berita.php">
                            <i class="fas fa-newspaper me-1"></i> Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') == 'galeri' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/galeri.php">
                            <i class="fas fa-images me-1"></i> Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') == 'prestasi' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/prestasi.php">
                            <i class="fas fa-trophy me-1"></i> Prestasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') == 'kontak' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/kontak.php">
                            <i class="fas fa-envelope me-1"></i> Kontak
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
