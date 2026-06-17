<?php
$page_title  = 'Dashboard';
$active_menu = 'dashboard';
include __DIR__ . '/includes/header.php';

// Statistics
$total_guru     = fetch("SELECT COUNT(*) as t FROM guru WHERE status='aktif'")['t'] ?? 0;
$total_galeri   = fetch("SELECT COUNT(*) as t FROM galeri")['t'] ?? 0;
$total_fasilitas= fetch("SELECT COUNT(*) as t FROM fasilitas")['t'] ?? 0;
$unread         = fetch("SELECT COUNT(*) as t FROM kontak_masuk WHERE status='belum_dibaca'")['t'] ?? 0;

// Recent galeri
$latest_galeri = fetchAll("SELECT * FROM galeri ORDER BY created_at DESC LIMIT 5");
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/guru/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#d1fae5;">
                <i class="fas fa-chalkboard-teacher" style="color:#059669;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $total_guru; ?></div>
                <div class="stat-card-label">Guru Aktif</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-6 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/galeri/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#ede9fe;">
                <i class="fas fa-images" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $total_galeri; ?></div>
                <div class="stat-card-label">Galeri</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-6 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/fasilitas/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#fef3c7;">
                <i class="fas fa-building" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $total_fasilitas; ?></div>
                <div class="stat-card-label">Fasilitas</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-6 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/kontak/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#fee2e2;">
                <i class="fas fa-envelope" style="color:#ef4444;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $unread; ?></div>
                <div class="stat-card-label">Pesan Belum Dibaca</div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Latest Galeri -->
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="fas fa-images"></i> Galeri Terbaru
                </div>
                <a href="<?php echo SITE_URL; ?>/admin/galeri/tambah.php" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($latest_galeri)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada galeri</td></tr>
                        <?php else: ?>
                        <?php foreach ($latest_galeri as $g): ?>
                        <tr>
                            <td>
                                <?php if ($g['foto']): ?>
                                <img src="<?php echo SITE_URL; ?>/uploads/galeri/<?php echo clean($g['foto']); ?>"
                                     style="width:50px; height:38px; object-fit:cover; border-radius:6px;">
                                <?php else: ?>
                                <div style="width:50px; height:38px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="max-width:200px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    <?php echo clean($g['judul']); ?>
                                </div>
                            </td>
                            <td><span class="badge bg-info text-dark"><?php echo clean($g['kategori'] ?? '-'); ?></span></td>
                            <td style="white-space:nowrap; font-size:12px;"><?php echo formatTanggal($g['tanggal_kegiatan']); ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo SITE_URL; ?>/admin/galeri/edit.php?id=<?php echo $g['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="padding: 12px 24px; border-top: 1px solid #f1f5f9;">
                <a href="<?php echo SITE_URL; ?>/admin/galeri/index.php" class="text-primary small fw-semibold">
                    Lihat Semua Galeri <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.activity-list { max-height: 460px; overflow-y: auto; }
.activity-item { display: flex; gap: 12px; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: #fafbfc; }
.activity-avatar { width: 36px; height: 36px; min-width: 36px; background: linear-gradient(135deg, #2563eb, #1e40af); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
.activity-info { flex: 1; min-width: 0; }
.activity-text { font-size: 13px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.activity-desc { font-size: 12px; color: #64748b; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.activity-time { font-size: 11px; color: #94a3b8; margin-top: 3px; }
.badge-aksi { font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 5px; text-transform: uppercase; }
.aksi-login   { background: #d1fae5; color: #065f46; }
.aksi-logout  { background: #f3f4f6; color: #374151; }
.aksi-tambah, .aksi-insert  { background: #dbeafe; color: #1e40af; }
.aksi-edit, .aksi-update    { background: #fef3c7; color: #92400e; }
.aksi-hapus, .aksi-delete   { background: #fee2e2; color: #991b1b; }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
