<?php
$page_title  = 'Dashboard';
$active_menu = 'dashboard';
include __DIR__ . '/includes/header.php';

// Statistics
$total_berita   = fetch("SELECT COUNT(*) as t FROM berita WHERE status='published'")['t'] ?? 0;
$total_guru     = fetch("SELECT COUNT(*) as t FROM guru WHERE status='aktif'")['t'] ?? 0;
$total_staff    = fetch("SELECT COUNT(*) as t FROM staff WHERE status='aktif'")['t'] ?? 0;
$total_prestasi = fetch("SELECT COUNT(*) as t FROM prestasi")['t'] ?? 0;
$total_galeri   = fetch("SELECT COUNT(*) as t FROM galeri")['t'] ?? 0;
$unread         = fetch("SELECT COUNT(*) as t FROM kontak_masuk WHERE status='belum_dibaca'")['t'] ?? 0;

// Latest berita
$latest_berita = fetchAll("SELECT b.*, u.nama_lengkap as penulis, k.nama_kategori
                            FROM berita b
                            LEFT JOIN users u ON b.penulis_id = u.id
                            LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                            ORDER BY b.created_at DESC LIMIT 5");

// Recent activity (Audit Log)
$recent_logs = fetchAll("SELECT a.*, u.nama_lengkap, u.role
                          FROM audit_log a
                          LEFT JOIN users u ON a.user_id = u.id
                          ORDER BY a.created_at DESC LIMIT 8");
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#dbeafe;">
                <i class="fas fa-newspaper" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $total_berita; ?></div>
                <div class="stat-card-label">Berita Publik</div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
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
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/staff/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#fef3c7;">
                <i class="fas fa-user-tie" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $total_staff; ?></div>
                <div class="stat-card-label">Staff Aktif</div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <a href="<?php echo SITE_URL; ?>/admin/prestasi/index.php" class="stat-card">
            <div class="stat-card-icon" style="background:#fce7f3;">
                <i class="fas fa-trophy" style="color:#db2777;"></i>
            </div>
            <div>
                <div class="stat-card-number"><?php echo $total_prestasi; ?></div>
                <div class="stat-card-label">Prestasi</div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
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
    <div class="col-xl-2 col-lg-4 col-sm-6">
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
    <!-- Latest Berita -->
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="fas fa-newspaper"></i> Berita Terbaru
                </div>
                <a href="<?php echo SITE_URL; ?>/admin/berita/tambah.php" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($latest_berita)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada berita</td></tr>
                        <?php else: ?>
                        <?php foreach ($latest_berita as $b): ?>
                        <tr>
                            <td>
                                <div style="max-width: 200px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo clean($b['judul']); ?>
                                </div>
                            </td>
                            <td><span class="badge bg-primary"><?php echo clean($b['nama_kategori'] ?? '-'); ?></span></td>
                            <td style="white-space:nowrap;"><?php echo clean($b['penulis']); ?></td>
                            <td>
                                <span class="badge-status status-<?php echo $b['status']; ?>">
                                    <?php echo $b['status'] == 'published' ? 'Publik' : 'Draft'; ?>
                                </span>
                            </td>
                            <td style="white-space:nowrap; font-size: 12px;"><?php echo formatTanggal($b['tanggal_publish'] ?? $b['created_at']); ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $b['slug']; ?>" target="_blank" class="btn-action btn-view" title="Lihat"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo SITE_URL; ?>/admin/berita/edit.php?id=<?php echo $b['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="padding: 12px 24px; border-top: 1px solid #f1f5f9;">
                <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="text-primary small fw-semibold">
                    Lihat Semua Berita <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Audit Log -->
    <div class="col-lg-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="fas fa-history"></i> Aktivitas Terbaru
                </div>
                <?php if (isAdmin()): ?>
                <a href="<?php echo SITE_URL; ?>/admin/audit-log/index.php" class="text-muted small">Lihat Semua</a>
                <?php endif; ?>
            </div>
            <div class="admin-card-body" style="padding: 0;">
                <?php if (empty($recent_logs)): ?>
                <div class="empty-state py-4"><p class="text-muted mb-0">Belum ada aktivitas</p></div>
                <?php else: ?>
                <div class="activity-list">
                    <?php foreach ($recent_logs as $log): ?>
                    <div class="activity-item">
                        <div class="activity-avatar">
                            <?php echo strtoupper(substr($log['nama_lengkap'] ?? 'U', 0, 1)); ?>
                        </div>
                        <div class="activity-info">
                            <div class="activity-text">
                                <strong><?php echo clean($log['nama_lengkap'] ?? 'Unknown'); ?></strong>
                                <span class="badge-aksi aksi-<?php echo strtolower($log['aksi']); ?>">
                                    <?php echo clean($log['aksi']); ?>
                                </span>
                                <span class="text-muted small"><?php echo clean($log['tabel']); ?></span>
                            </div>
                            <div class="activity-desc"><?php echo clean(limitText($log['deskripsi'] ?? '', 60)); ?></div>
                            <div class="activity-time">
                                <i class="far fa-clock me-1"></i>
                                <?php echo formatTanggal($log['created_at'], 'd F Y H:i'); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
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
