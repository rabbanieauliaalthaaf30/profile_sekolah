<?php
require_once __DIR__ . '/../../config/config.php';
requireLogin();

$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");
$unread_kontak = fetch("SELECT COUNT(*) as total FROM kontak_masuk WHERE status = 'belum_dibaca'")['total'] ?? 0;

// Get current user
$current_user = fetch("SELECT * FROM users WHERE id = " . (int)$_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? clean($page_title) . ' - ' : ''; ?>Admin Panel | <?php echo clean($profil['nama_sekolah'] ?? 'Sekolah'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/admin/assets/admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-school me-2" style="flex-shrink:0;"></i>
            <span><?php echo clean($profil['nama_sekolah'] ?? 'Admin Panel'); ?></span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Utama</div>
            <a href="<?php echo SITE_URL; ?>/admin/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>

            <div class="nav-label">Konten</div>
            <a href="<?php echo SITE_URL; ?>/admin/galeri/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'galeri' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i><span>Galeri</span>
            </a>

            <div class="nav-label">Komunikasi</div>
            <a href="<?php echo SITE_URL; ?>/admin/kontak/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'kontak' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i><span>Pesan Masuk</span>
                <?php if ($unread_kontak > 0): ?>
                <span class="nav-badge"><?php echo $unread_kontak; ?></span>
                <?php endif; ?>
            </a>

            <?php if (isAdmin()): ?>
            <div class="nav-label">Manajemen</div>
            <a href="<?php echo SITE_URL; ?>/admin/profil/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'profil' ? 'active' : ''; ?>">
                <i class="fas fa-school"></i><span>Profil Sekolah</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/slider/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'slider' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i><span>Slider Homepage</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/guru/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'guru' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i><span>Data Guru</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/fasilitas/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'fasilitas' ? 'active' : ''; ?>">
                <i class="fas fa-building"></i><span>Fasilitas</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="nav-item <?php echo ($active_menu ?? '') == 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users-cog"></i><span>Kelola User</span>
            </a>
            <?php endif; ?>

            <div class="nav-divider"></div>
            <a href="<?php echo SITE_URL; ?>" target="_blank" class="nav-item">
                <i class="fas fa-external-link-alt"></i><span>Lihat Website</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="nav-item text-danger btn-logout-confirm">
                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb-nav">
                    <?php echo isset($page_title) ? clean($page_title) : 'Dashboard'; ?>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <a href="<?php echo SITE_URL; ?>/admin/kontak/index.php" class="top-nav-icon position-relative">
                    <i class="fas fa-bell"></i>
                    <?php if ($unread_kontak > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                        <?php echo $unread_kontak; ?>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($current_user['nama_lengkap'] ?? 'A', 0, 1)); ?>
                        </div>
                        <div class="user-info d-none d-md-block">
                            <div class="user-name"><?php echo clean($current_user['nama_lengkap'] ?? ''); ?></div>
                            <div class="user-role"><?php echo ucfirst($current_user['role'] ?? ''); ?></div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="dropdown-header">
                            <strong><?php echo clean($current_user['nama_lengkap'] ?? ''); ?></strong>
                            <div class="text-muted small"><?php echo clean($current_user['email'] ?? ''); ?></div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/profil-saya.php"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/ganti-password.php"><i class="fas fa-key me-2"></i>Ganti Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger btn-logout-confirm" href="<?php echo SITE_URL; ?>/admin/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Flash Message -->
        <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="fas fa-<?php echo $flash['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
            <?php echo $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <div class="page-content">
