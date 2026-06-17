<?php
$page_title  = 'Detail Pesan';
$active_menu = 'kontak';
include __DIR__ . '/../includes/header.php';

$id     = (int)($_GET['id'] ?? 0);
$kontak = fetch("SELECT * FROM kontak_masuk WHERE id = $id");

if (!$kontak) {
    setFlash('danger', 'Pesan tidak ditemukan.');
    redirect(SITE_URL . '/admin/kontak/index.php');
}

// Otomatis mark as read saat dibuka
if ($kontak['status'] === 'belum_dibaca') {
    query("UPDATE kontak_masuk SET status = 'sudah_dibaca' WHERE id = $id");
}
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/kontak/index.php" class="btn-action btn-view" title="Kembali"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Detail Pesan Masuk</h5>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-envelope-open-text"></i> Isi Pesan</div>
                <span class="badge-status status-aktif">Sudah Dibaca</span>
            </div>
            <div class="admin-card-body">
                <h5 class="fw-bold mb-3"><?php echo clean($kontak['subjek']); ?></h5>
                <div style="background:#f8fafc; border-radius:10px; padding:20px; line-height:1.8; color:#374151; white-space:pre-wrap; word-wrap:break-word;">
                    <?php echo nl2br(clean($kontak['pesan'])); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Info Pengirim -->
        <div class="admin-card mb-4">
            <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-user"></i> Info Pengirim</div></div>
            <div class="admin-card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted" style="width:100px;">Nama</td>
                        <td><strong><?php echo clean($kontak['nama_pengirim']); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>
                            <a href="mailto:<?php echo clean($kontak['email_pengirim']); ?>" class="text-primary">
                                <?php echo clean($kontak['email_pengirim']); ?>
                            </a>
                        </td>
                    </tr>
                    <?php if (!empty($kontak['telepon'])): ?>
                    <tr>
                        <td class="text-muted">Telepon</td>
                        <td><?php echo clean($kontak['telepon']); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td style="font-size:13px;"><?php echo formatTanggal($kontak['tanggal_kirim']); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Aksi -->
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:<?php echo clean($kontak['email_pengirim']); ?>?subject=Re: <?php echo urlencode($kontak['subjek']); ?>"
                       class="btn btn-primary">
                        <i class="fas fa-reply me-2"></i>Balas via Email
                    </a>
                    <a href="<?php echo SITE_URL; ?>/admin/kontak/hapus.php?id=<?php echo $kontak['id']; ?>"
                       class="btn btn-danger btn-confirm-delete" data-name="pesan ini">
                        <i class="fas fa-trash me-2"></i>Hapus Pesan
                    </a>
                    <a href="<?php echo SITE_URL; ?>/admin/kontak/index.php" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
