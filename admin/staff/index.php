<?php
$page_title  = 'Data Staff';
$active_menu = 'staff';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$search        = isset($_GET['search']) ? escape($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? escape($_GET['status']) : '';
$page          = max(1, (int)($_GET['page'] ?? 1));
$limit         = ITEMS_PER_PAGE;
$offset        = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search)        $where .= " AND (nama_lengkap LIKE '%$search%' OR jabatan LIKE '%$search%')";
if ($filter_status) $where .= " AND status = '$filter_status'";

$total       = fetch("SELECT COUNT(*) as t FROM staff $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$staff = fetchAll("SELECT * FROM staff $where ORDER BY nama_lengkap ASC LIMIT $limit OFFSET $offset");
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-user-tie"></i> Data Staff</div>
        <a href="<?php echo SITE_URL; ?>/admin/staff/tambah.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Staff
        </a>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="search-input" placeholder="Cari nama atau jabatan..." value="<?php echo clean($search); ?>">
            <select name="status" class="search-input" style="min-width:140px;">
                <option value="">Semua Status</option>
                <option value="aktif"    <?php echo $filter_status == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                <option value="nonaktif" <?php echo $filter_status == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
            </select>
            <button type="submit" class="btn-add"><i class="fas fa-search"></i></button>
            <?php if ($search || $filter_status): ?>
            <a href="<?php echo SITE_URL; ?>/admin/staff/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th width="60">Foto</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($staff)): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-user-tie"></i>
                            <h5>Belum ada data staff</h5>
                            <p>Tambahkan data tenaga kependidikan atau staff sekolah</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($staff as $i => $s): ?>
                <tr>
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <?php if ($s['foto']): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/staff/<?php echo clean($s['foto']); ?>"
                             alt="<?php echo clean($s['nama_lengkap']); ?>"
                             style="width:44px; height:44px; object-fit:cover; border-radius:50%; border:2px solid #e5e7eb;">
                        <?php else: ?>
                        <div style="width:44px; height:44px; background:#dcfce7; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:600; color:#16a34a;">
                            <?php echo strtoupper(substr($s['nama_lengkap'], 0, 1)); ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-semibold"><?php echo clean($s['nama_lengkap']); ?></td>
                    <td style="font-size:13px;"><?php echo clean($s['jabatan'] ?? '-'); ?></td>
                    <td style="font-size:13px;"><?php echo clean($s['telepon'] ?? '-'); ?></td>
                    <td style="font-size:13px;"><?php echo clean($s['email'] ?? '-'); ?></td>
                    <td>
                        <span class="badge-status status-<?php echo $s['status']; ?>">
                            <?php echo $s['status'] == 'aktif' ? '✓ Aktif' : '✗ Nonaktif'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/admin/staff/edit.php?id=<?php echo $s['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/staff/hapus.php?id=<?php echo $s['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="<?php echo clean($s['nama_lengkap']); ?>" title="Hapus"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div class="text-muted small">Menampilkan <?php echo $offset + 1; ?>–<?php echo min($offset + $limit, $total); ?> dari <?php echo $total; ?> data</div>
        <nav><ul class="pagination mb-0">
            <?php for ($p = max(1, $page - 2); $p <= min($total_pages, $page + 2); $p++): ?>
            <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $p; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($filter_status); ?>"><?php echo $p; ?></a>
            </li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
