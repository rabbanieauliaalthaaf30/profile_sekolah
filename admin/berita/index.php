<?php
$page_title  = 'Kelola Berita';
$active_menu = 'berita';
include __DIR__ . '/../includes/header.php';

$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? escape($_GET['status']) : '';
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search) $where .= " AND (b.judul LIKE '%$search%')";
if ($status_filter) $where .= " AND b.status = '$status_filter'";

$total = fetch("SELECT COUNT(*) as t FROM berita b $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$berita = fetchAll("SELECT b.*, k.nama_kategori, u.nama_lengkap as penulis
                    FROM berita b
                    LEFT JOIN kategori_berita k ON b.kategori_id = k.id
                    LEFT JOIN users u ON b.penulis_id = u.id
                    $where
                    ORDER BY b.created_at DESC
                    LIMIT $limit OFFSET $offset");
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-newspaper"></i> Daftar Berita</div>
        <a href="<?php echo SITE_URL; ?>/admin/berita/tambah.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Berita
        </a>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <form method="GET" class="d-flex gap-2 flex-wrap" style="flex: 1;">
            <input type="text" name="search" class="search-input" placeholder="Cari berita..." value="<?php echo clean($search); ?>">
            <select name="status" class="search-input" style="min-width: 130px;">
                <option value="">Semua Status</option>
                <option value="published" <?php echo $status_filter == 'published' ? 'selected' : ''; ?>>Published</option>
                <option value="draft" <?php echo $status_filter == 'draft' ? 'selected' : ''; ?>>Draft</option>
            </select>
            <button type="submit" class="btn-add"><i class="fas fa-search"></i></button>
            <?php if ($search || $status_filter): ?>
            <a href="<?php echo SITE_URL; ?>/admin/berita/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Tanggal</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($berita)): ?>
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-newspaper"></i><h5>Belum ada berita</h5><p>Mulai tambahkan berita baru</p></div></td></tr>
                <?php else: ?>
                <?php foreach ($berita as $i => $b): ?>
                <tr>
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <div style="max-width: 260px;">
                            <div class="fw-semibold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo clean($b['judul']); ?></div>
                            <div class="text-muted" style="font-size: 12px;"><?php echo clean(limitText($b['ringkasan'] ?? '', 60)); ?></div>
                        </div>
                    </td>
                    <td><span class="badge bg-primary"><?php echo clean($b['nama_kategori'] ?? '-'); ?></span></td>
                    <td style="white-space:nowrap; font-size:13px;"><?php echo clean($b['penulis']); ?></td>
                    <td>
                        <span class="badge-status status-<?php echo $b['status']; ?>">
                            <?php echo $b['status'] == 'published' ? '✓ Publik' : '✎ Draft'; ?>
                        </span>
                    </td>
                    <td><span class="text-muted small"><i class="far fa-eye me-1"></i><?php echo $b['views']; ?></span></td>
                    <td style="font-size:12px; white-space:nowrap; color:#64748b;"><?php echo formatTanggal($b['tanggal_publish'] ?? $b['created_at']); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/berita-detail.php?slug=<?php echo $b['slug']; ?>" target="_blank" class="btn-action btn-view" title="Lihat"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/berita/edit.php?id=<?php echo $b['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/berita/hapus.php?id=<?php echo $b['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="berita ini" title="Hapus"><i class="fas fa-trash"></i></a>
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
        <div class="text-muted small">Menampilkan <?php echo $offset+1; ?>-<?php echo min($offset+$limit, $total); ?> dari <?php echo $total; ?> data</div>
        <nav><ul class="pagination mb-0">
            <?php for ($p = max(1, $page-2); $p <= min($total_pages, $page+2); $p++): ?>
            <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $p; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>"><?php echo $p; ?></a>
            </li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
