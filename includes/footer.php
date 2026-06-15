    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="row g-4">
                    <!-- About Section -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <h4 class="footer-title">Tentang Kami</h4>
                            <div class="footer-logo mb-3">
                                <img src="<?php echo SITE_URL; ?>/uploads/logo/<?php echo $profil['logo'] ?? 'logo-default.png'; ?>" alt="Logo" style="height: 60px;">
                            </div>
                            <p class="footer-text">
                                <?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?> adalah lembaga pendidikan yang berkomitmen mencetak generasi unggul, berakhlak mulia, dan berwawasan global.
                            </p>
                            <div class="footer-social mt-3">
                                <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget">
                            <h4 class="footer-title">Link Cepat</h4>
                            <ul class="footer-links">
                                <li><a href="<?php echo SITE_URL; ?>"><i class="fas fa-chevron-right me-2"></i>Beranda</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/profil.php"><i class="fas fa-chevron-right me-2"></i>Profil</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/guru.php"><i class="fas fa-chevron-right me-2"></i>Guru & Staff</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/berita.php"><i class="fas fa-chevron-right me-2"></i>Berita</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/galeri.php"><i class="fas fa-chevron-right me-2"></i>Galeri</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Information -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h4 class="footer-title">Informasi</h4>
                            <ul class="footer-links">
                                <li><a href="<?php echo SITE_URL; ?>/prestasi.php"><i class="fas fa-chevron-right me-2"></i>Prestasi</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/kontak.php"><i class="fas fa-chevron-right me-2"></i>Kontak</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/profil.php#fasilitas"><i class="fas fa-chevron-right me-2"></i>Fasilitas</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/admin/login.php"><i class="fas fa-chevron-right me-2"></i>Login Admin</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h4 class="footer-title">Hubungi Kami</h4>
                            <ul class="footer-contact">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo clean($profil['alamat_lengkap'] ?? 'Jl. Pendidikan No. 123'); ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo clean($profil['telepon'] ?? '(021) 1234-5678'); ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo clean($profil['email'] ?? 'info@sekolah.sch.id'); ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-globe"></i>
                                    <span><?php echo clean($profil['website'] ?? 'www.sekolah.sch.id'); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">
                            &copy; <?php echo date('Y'); ?> <strong><?php echo clean($profil['nama_sekolah'] ?? SITE_NAME); ?></strong>. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">
                            Developed with <i class="fas fa-heart text-danger"></i> by <strong>School Team</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
