<?php
$page_title  = 'Kelola User';
$active_menu = 'users';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search) $where .= " AND (nama_lengkap LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%')";

$total       = fetch("SELECT COUNT(*) as t FROM users $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$users = fetchAll("SELECT * FROM users $where ORDER BY role ASC, nama_lengkap ASC LIMIT $limit OFFSET $offset");
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-users-cog"></i> Kelola User</div>
        <a href="<?php echo SITE_URL; ?>/admin/users/tambah.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="search-input" placeholder="Cari nama, username, email..." value="<?php echo clean($search); ?>">
            <button type="submit" class="btn-add"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
            <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h5>Belum ada user</h5>
                            <p>Tambahkan akun admin atau staff</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $i => $u): ?>
                <tr>
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px; height:36px; background:<?php echo $u['role'] == 'admin' ? '#fef3c7' : '#e0e7ff'; ?>; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:<?php echo $u['role'] == 'admin' ? '#d97706' : '#4f46e5'; ?>; flex-shrink:0;">
                                <?php echo strtoupper(substr($u['nama_lengkap'], 0, 1)); ?>
                            </div>
                            <span class="fw-semibold"><?php echo clean($u['nama_lengkap']); ?></span>
                            <?php if ($u['id'] == $_SESSION['user_id']): ?>
                            <span class="badge bg-success" style="font-size:10px;">Saya</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="font-size:13px;">@<?php echo clean($u['username']); ?></td>
                    <td style="font-size:13px;"><?php echo clean($u['email'] ?? '-'); ?></td>
                    <td>
                        <span class="badge <?php echo $u['role'] == 'admin' ? 'bg-warning text-dark' : 'bg-primary'; ?>">
                            <i class="fas fa-<?php echo $u['role'] == 'admin' ? 'shield-alt' : 'user'; ?> me-1"></i>
                            <?php echo ucfirst(clean($u['role'])); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge-status status-<?php echo ($u['status'] ?? 'aktif') == 'aktif' ? 'aktif' : 'nonaktif'; ?>">
                            <?php echo ($u['status'] ?? 'aktif') == 'aktif' ? '✓ Aktif' : '✗ Nonaktif'; ?>
                        </span>
                    </td>
                    <td style="font-size:12px; color:#64748b; white-space:nowrap;"><?php echo formatTanggal($u['created_at']); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/admin/users/edit.php?id=<?php echo $u['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <a href="<?php echo SITE_URL; ?>/admin/users/hapus.php?id=<?php echo $u['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="user <?php echo clean($u['username']); ?>" title="Hapus"><i class="fas fa-trash"></i></a>
                            <?php else: ?>
                            <button class="btn-action btn-delete" disabled title="Tidak bisa hapus akun sendiri" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-trash"></i></button>
                            <?php endif; ?>
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
                <a class="page-link" href="?page=<?php echo $p; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $p; ?></a>
            </li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
