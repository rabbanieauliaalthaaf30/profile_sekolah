<?php
$page_title  = 'Audit Log';
$active_menu = 'audit-log';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$filter_user = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$filter_aksi = isset($_GET['aksi']) ? escape($_GET['aksi']) : '';
$page        = max(1, (int)($_GET['page'] ?? 1));
$limit        = 20;
$offset       = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($filter_user) $where .= " AND al.user_id = $filter_user";
if ($filter_aksi) $where .= " AND al.aksi = '$filter_aksi'";

$total       = fetch("SELECT COUNT(*) as t FROM audit_log al $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$logs = fetchAll("SELECT al.*, u.nama_lengkap, u.username
                  FROM audit_log al
                  LEFT JOIN users u ON al.user_id = u.id
                  $where
                  ORDER BY al.created_at DESC
                  LIMIT $limit OFFSET $offset");

$users_list = fetchAll("SELECT id, nama_lengkap, username FROM users ORDER BY nama_lengkap ASC");

$aksi_list   = ['TAMBAH', 'EDIT', 'HAPUS', 'BACA', 'LOGIN', 'LOGOUT'];
$aksi_badge  = [
    'TAMBAH' => 'bg-success',
    'EDIT'   => 'bg-primary',
    'HAPUS'  => 'bg-danger',
    'BACA'   => 'bg-info text-dark',
    'LOGIN'  => 'bg-secondary',
    'LOGOUT' => 'bg-secondary',
];
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-history"></i> Audit Log Aktivitas</div>
        <span class="badge bg-secondary"><?php echo number_format($total); ?> entri</span>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <select name="user_id" class="search-input" style="min-width:180px;">
                <option value="">Semua User</option>
                <?php foreach ($users_list as $u): ?>
                <option value="<?php echo $u['id']; ?>" <?php echo $filter_user == $u['id'] ? 'selected' : ''; ?>>
                    <?php echo clean($u['nama_lengkap']); ?> (@<?php echo clean($u['username']); ?>)
                </option>
                <?php endforeach; ?>
            </select>
            <select name="aksi" class="search-input" style="min-width:130px;">
                <option value="">Semua Aksi</option>
                <?php foreach ($aksi_list as $a): ?>
                <option value="<?php echo $a; ?>" <?php echo $filter_aksi == $a ? 'selected' : ''; ?>><?php echo $a; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-add"><i class="fas fa-filter me-1"></i> Filter</button>
            <?php if ($filter_user || $filter_aksi): ?>
            <a href="<?php echo SITE_URL; ?>/admin/audit-log/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times me-1"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>User</th>
                    <th width="90">Aksi</th>
                    <th>Tabel</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h5>Tidak ada log aktivitas</h5>
                            <p>Log akan muncul setelah ada aktivitas pada sistem</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $i => $log): ?>
                <tr>
                    <td class="text-muted" style="font-size:12px;"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <div>
                            <div class="fw-semibold" style="font-size:13px;"><?php echo clean($log['nama_lengkap'] ?? 'User dihapus'); ?></div>
                            <div class="text-muted" style="font-size:11px;">@<?php echo clean($log['username'] ?? '-'); ?></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?php echo $aksi_badge[$log['aksi']] ?? 'bg-secondary'; ?>">
                            <?php echo clean($log['aksi']); ?>
                        </span>
                    </td>
                    <td style="font-size:12px; color:#64748b; font-family:monospace;"><?php echo clean($log['tabel']); ?></td>
                    <td style="font-size:13px; max-width:300px;">
                        <span title="<?php echo clean($log['deskripsi']); ?>">
                            <?php echo clean(limitText($log['deskripsi'], 60)); ?>
                        </span>
                    </td>
                    <td style="font-size:12px; font-family:monospace; color:#64748b;"><?php echo clean($log['ip_address'] ?? '-'); ?></td>
                    <td style="font-size:11px; white-space:nowrap; color:#64748b;"><?php echo formatTanggal($log['created_at']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div class="text-muted small">Menampilkan <?php echo $offset + 1; ?>–<?php echo min($offset + $limit, $total); ?> dari <?php echo number_format($total); ?> entri</div>
        <nav><ul class="pagination mb-0">
            <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&user_id=<?php echo $filter_user; ?>&aksi=<?php echo urlencode($filter_aksi); ?>"><i class="fas fa-chevron-left"></i></a></li>
            <?php endif; ?>
            <?php for ($p = max(1, $page - 2); $p <= min($total_pages, $page + 2); $p++): ?>
            <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $p; ?>&user_id=<?php echo $filter_user; ?>&aksi=<?php echo urlencode($filter_aksi); ?>"><?php echo $p; ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&user_id=<?php echo $filter_user; ?>&aksi=<?php echo urlencode($filter_aksi); ?>"><i class="fas fa-chevron-right"></i></a></li>
            <?php endif; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<!-- Info -->
<div class="alert alert-light border mt-3">
    <i class="fas fa-info-circle text-muted me-2"></i>
    <span class="text-muted small">Audit log adalah catatan read-only. Data tidak dapat dihapus melalui antarmuka ini untuk menjaga integritas sistem.</span>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
