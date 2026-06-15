<?php
$page_title  = 'Data Fasilitas';
$active_menu = 'fasilitas';
include __DIR__ . '/../includes/header.php';
requireAdmin();

$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search) $where .= " AND nama_fasilitas LIKE '%$search%'";

$total       = fetch("SELECT COUNT(*) as t FROM fasilitas $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$fasilitas = fetchAll("SELECT * FROM fasilitas $where ORDER BY urutan ASC, nama_fasilitas ASC LIMIT $limit OFFSET $offset");
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-building"></i> Data Fasilitas</div>
        <a href="<?php echo SITE_URL; ?>/admin/fasilitas/tambah.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Fasilitas
        </a>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="search-input" placeholder="Cari fasilitas..." value="<?php echo clean($search); ?>">
            <button type="submit" class="btn-add"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
            <a href="<?php echo SITE_URL; ?>/admin/fasilitas/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th width="70">Foto</th>
                    <th>Nama Fasilitas</th>
                    <th>Deskripsi</th>
                    <th width="80">Urutan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($fasilitas)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <h5>Belum ada data fasilitas</h5>
                            <p>Tambahkan fasilitas yang tersedia di sekolah</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($fasilitas as $i => $f): ?>
                <tr>
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <?php if ($f['foto']): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/fasilitas/<?php echo clean($f['foto']); ?>"
                             alt="<?php echo clean($f['nama_fasilitas']); ?>"
                             style="width:60px; height:45px; object-fit:cover; border-radius:6px;">
                        <?php else: ?>
                        <div style="width:60px; height:45px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-building text-muted"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-semibold"><?php echo clean($f['nama_fasilitas']); ?></td>
                    <td style="font-size:13px; color:#64748b;">
                        <?php echo clean(limitText($f['deskripsi'] ?? '', 80)); ?>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border"><?php echo (int)($f['urutan'] ?? 0); ?></span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/admin/fasilitas/edit.php?id=<?php echo $f['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/fasilitas/hapus.php?id=<?php echo $f['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="<?php echo clean($f['nama_fasilitas']); ?>" title="Hapus"><i class="fas fa-trash"></i></a>
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
