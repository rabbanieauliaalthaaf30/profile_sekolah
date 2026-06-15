<?php
$page_title = 'Kontak';
$current_page = 'kontak';
include 'includes/header.php';

$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Hubungi Kami</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Kontak</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5" data-aos="fade-right">
                <h2 class="section-title mb-5">Informasi Kontak</h2>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h6 class="mb-1">Alamat Sekolah</h6>
                        <p class="text-muted mb-0"><?php echo clean($profil['alamat_lengkap'] ?? 'Jl. Pendidikan No. 123, Kota Harapan, Provinsi Contoh, 12345'); ?></p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h6 class="mb-1">Telepon</h6>
                        <p class="text-muted mb-0">
                            <a href="tel:<?php echo clean($profil['telepon'] ?? ''); ?>" class="text-muted">
                                <?php echo clean($profil['telepon'] ?? '(021) 1234-5678'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h6 class="mb-1">Email</h6>
                        <p class="text-muted mb-0">
                            <a href="mailto:<?php echo clean($profil['email'] ?? ''); ?>" class="text-muted">
                                <?php echo clean($profil['email'] ?? 'info@sekolah.sch.id'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <h6 class="mb-1">Jam Operasional</h6>
                        <p class="text-muted mb-0">Senin - Jumat: 07.00 - 16.00 WIB</p>
                        <p class="text-muted mb-0">Sabtu: 07.00 - 13.00 WIB</p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Website</h6>
                        <p class="text-muted mb-0"><?php echo clean($profil['website'] ?? 'www.sekolah.sch.id'); ?></p>
                    </div>
                </div>

                <h6 class="mb-3 mt-4">Media Sosial</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="social-btn-lg"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn-lg"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn-lg"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-btn-lg"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="contact-form">
                    <h3 class="mb-4">Kirim Pesan</h3>

                    <!-- Alert area -->
                    <div id="formAlert" style="display:none;" class="alert alert-dismissible fade show mb-4" role="alert">
                        <span id="formAlertMsg"></span>
                        <button type="button" class="btn-close" onclick="tutupAlert()"></button>
                    </div>

                    <form id="kontakForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="inputNama" class="form-control" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subjek</label>
                                <input type="text" name="subjek" id="inputSubjek" class="form-control" placeholder="Subjek pesan">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                                <textarea name="pesan" id="inputPesan" class="form-control" rows="6" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="btnKirim" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="mt-5" data-aos="fade-up">
            <h4 class="mb-4"><i class="fas fa-map-marked-alt me-2 text-primary"></i>Lokasi Sekolah</h4>
            <div class="rounded shadow overflow-hidden" style="height: 400px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322287!2d106.8195613507864!3d-6.194741395474983!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917d759%3A0x6b45e67356080477!2sMuseum%20Nasional!5e0!3m2!1sid!2sid!4v1623825272927!5m2!1sid!2sid"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>

<style>
.contact-icon { width: 50px; height: 50px; min-width: 50px; background: linear-gradient(135deg, #2563eb, #1e40af); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.social-btn-lg { width: 45px; height: 45px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.3s ease; }
.social-btn-lg:hover { background: var(--secondary-color); color: white; transform: translateY(-3px); }
</style>

<script>
const API_KIRIM = '<?php echo SITE_URL; ?>/api/kirim-pesan.php';

// Submit form via AJAX
document.getElementById('kontakForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn  = document.getElementById('btnKirim');
    const nama  = document.getElementById('inputNama').value.trim();
    const email = document.getElementById('inputEmail').value.trim();
    const pesan = document.getElementById('inputPesan').value.trim();

    // Validasi sisi klien
    if (!nama || !email || !pesan) {
        tampilAlert('danger', '<i class="fas fa-exclamation-circle me-2"></i>Harap isi semua kolom yang wajib diisi.');
        return;
    }

    // Loading state
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

    const formData = new FormData(this);

    fetch(API_KIRIM, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                tampilAlert('success', '<i class="fas fa-check-circle me-2"></i>' + data.message, true);
                document.getElementById('kontakForm').reset();
            } else {
                tampilAlert('danger', '<i class="fas fa-exclamation-circle me-2"></i>' + data.message);
            }
        })
        .catch(() => {
            tampilAlert('danger', '<i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan. Coba lagi.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Pesan';
        });
});

function tampilAlert(type, msg, autoHide = false) {
    const box = document.getElementById('formAlert');
    const txt = document.getElementById('formAlertMsg');
    box.className = 'alert alert-' + type + ' alert-dismissible fade show mb-4';
    txt.innerHTML = msg;
    box.style.display = 'block';

    // Scroll ke alert
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    // Auto-hide sukses setelah 3 detik
    if (autoHide) {
        setTimeout(() => {
            box.classList.remove('show');
            setTimeout(() => { box.style.display = 'none'; }, 300);
        }, 3000);
    }
}

function tutupAlert() {
    const box = document.getElementById('formAlert');
    box.classList.remove('show');
    setTimeout(() => { box.style.display = 'none'; }, 300);
}
</script>

<?php include 'includes/footer.php'; ?>
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Hubungi Kami</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Kontak</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5" data-aos="fade-right">
                <h2 class="section-title mb-5">Informasi Kontak</h2>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Alamat Sekolah</h6>
                        <p class="text-muted mb-0"><?php echo clean($profil['alamat_lengkap'] ?? 'Jl. Pendidikan No. 123, Kota Harapan, Provinsi Contoh, 12345'); ?></p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Telepon</h6>
                        <p class="text-muted mb-0">
                            <a href="tel:<?php echo clean($profil['telepon'] ?? ''); ?>" class="text-muted">
                                <?php echo clean($profil['telepon'] ?? '(021) 1234-5678'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Email</h6>
                        <p class="text-muted mb-0">
                            <a href="mailto:<?php echo clean($profil['email'] ?? ''); ?>" class="text-muted">
                                <?php echo clean($profil['email'] ?? 'info@sekolah.sch.id'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Jam Operasional</h6>
                        <p class="text-muted mb-0">Senin - Jumat: 07.00 - 16.00 WIB</p>
                        <p class="text-muted mb-0">Sabtu: 07.00 - 13.00 WIB</p>
                    </div>
                </div>

                <div class="contact-info-box mb-4 d-flex gap-4">
                    <div class="contact-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Website</h6>
                        <p class="text-muted mb-0"><?php echo clean($profil['website'] ?? 'www.sekolah.sch.id'); ?></p>
                    </div>
                </div>

                <!-- Social Media -->
                <h6 class="mb-3 mt-4">Media Sosial</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="social-btn-lg"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn-lg"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn-lg"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-btn-lg"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="contact-form">
                    <h3 class="mb-4">Kirim Pesan</h3>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" value="<?php echo isset($_POST['nama']) ? clean($_POST['nama']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" value="<?php echo isset($_POST['email']) ? clean($_POST['email']) : ''; ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subjek</label>
                                <input type="text" name="subjek" class="form-control" placeholder="Subjek pesan" value="<?php echo isset($_POST['subjek']) ? clean($_POST['subjek']) : ''; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                                <textarea name="pesan" class="form-control" rows="6" placeholder="Tuliskan pesan Anda di sini..." required><?php echo isset($_POST['pesan']) ? clean($_POST['pesan']) : ''; ?></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="mt-5" data-aos="fade-up">
            <h4 class="mb-4"><i class="fas fa-map-marked-alt me-2 text-primary"></i>Lokasi Sekolah</h4>
            <div class="rounded shadow overflow-hidden" style="height: 400px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322287!2d106.8195613507864!3d-6.194741395474983!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917d759%3A0x6b45e67356080477!2sMuseum%20Nasional!5e0!3m2!1sid!2sid!4v1623825272927!5m2!1sid!2sid"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>

<style>
.contact-icon { width: 50px; height: 50px; min-width: 50px; background: linear-gradient(135deg, #2563eb, #1e40af); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.social-btn-lg { width: 45px; height: 45px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.3s ease; }
.social-btn-lg:hover { background: var(--secondary-color); color: white; transform: translateY(-3px); }
</style>

<?php include 'includes/footer.php'; ?>
