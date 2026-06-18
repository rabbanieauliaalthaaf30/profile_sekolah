<?php
$page_title  = 'Kelola Galeri';
$active_menu = 'galeri';
include __DIR__ . '/../includes/header.php';

$search   = isset($_GET['search']) ? escape($_GET['search']) : '';
$page     = max(1, (int)($_GET['page'] ?? 1));
$limit    = ITEMS_PER_PAGE;
$offset   = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search) $where .= " AND judul LIKE '%$search%'";

$total       = fetch("SELECT COUNT(*) as t FROM galeri $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$galeri = fetchAll("SELECT g.*,
                  ub.nama_lengkap as nama_pembuat,
                  ue.nama_lengkap as nama_pengedit
                  FROM galeri g
                  LEFT JOIN users ub ON g.dibuat_oleh = ub.id
                  LEFT JOIN users ue ON g.diedit_oleh = ue.id
                  $where ORDER BY g.tanggal_kegiatan DESC LIMIT $limit OFFSET $offset");
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-images"></i> Daftar Galeri</div>
        <a href="<?php echo SITE_URL; ?>/admin/galeri/tambah.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Galeri
        </a>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <form method="GET" class="d-flex gap-2 flex-wrap" style="flex: 1;">
            <input type="text" name="search" class="search-input" placeholder="Cari galeri..." value="<?php echo clean($search); ?>">
            <button type="submit" class="btn-add"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
            <a href="<?php echo SITE_URL; ?>/admin/galeri/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Foto</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Dibuat/Diedit Oleh</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($galeri)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-images"></i>
                            <h5>Belum ada galeri</h5>
                            <p>Mulai tambahkan foto kegiatan sekolah</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($galeri as $i => $g): ?>
                <tr>
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <?php if ($g['foto']): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/galeri/<?php echo clean($g['foto']); ?>"
                             alt="<?php echo clean($g['judul']); ?>"
                             style="width:60px; height:45px; object-fit:cover; border-radius:6px;">
                        <?php else: ?>
                        <div style="width:60px; height:45px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="max-width: 240px;">
                            <div class="fw-semibold" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo clean($g['judul']); ?></div>
                            <div class="text-muted" style="font-size:12px;"><?php echo clean(limitText($g['deskripsi'] ?? '', 50)); ?></div>
                        </div>
                    </td>
                    <td><span class="badge bg-info text-dark"><?php echo clean($g['kategori'] ?? '-'); ?></span></td>
                    <td style="font-size:12px; white-space:nowrap; color:#64748b;"><?php echo formatTanggal($g['tanggal_kegiatan']); ?></td>
                    <td style="font-size:12px;">
                        <?php if ($g['dibuat_oleh']): ?>
                        <div><i class="fas fa-plus-circle text-success me-1"></i><?php echo clean($g['nama_pembuat'] ?? '-'); ?></div>
                        <?php endif; ?>
                        <?php if ($g['diedit_oleh']): ?>
                        <div class="text-muted"><i class="fas fa-edit text-warning me-1"></i><?php echo clean($g['nama_pengedit'] ?? '-'); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/admin/galeri/edit.php?id=<?php echo $g['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/galeri/hapus.php?id=<?php echo $g['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="<?php echo clean($g['judul']); ?>" title="Hapus"><i class="fas fa-trash"></i></a>
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
