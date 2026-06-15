<?php
$page_title  = 'Kelola Prestasi';
$active_menu = 'prestasi';
include __DIR__ . '/../includes/header.php';

$search          = isset($_GET['search']) ? escape($_GET['search']) : '';
$filter_kategori = isset($_GET['kategori']) ? escape($_GET['kategori']) : '';
$filter_tingkat  = isset($_GET['tingkat']) ? escape($_GET['tingkat']) : '';
$page            = max(1, (int)($_GET['page'] ?? 1));
$limit           = ITEMS_PER_PAGE;
$offset          = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search)          $where .= " AND nama_prestasi LIKE '%$search%'";
if ($filter_kategori) $where .= " AND kategori = '$filter_kategori'";
if ($filter_tingkat)  $where .= " AND tingkat = '$filter_tingkat'";

$total       = fetch("SELECT COUNT(*) as t FROM prestasi $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$prestasi = fetchAll("SELECT * FROM prestasi $where ORDER BY tahun DESC, nama_prestasi ASC LIMIT $limit OFFSET $offset");

$badge_tingkat = [
    'sekolah'       => 'bg-secondary',
    'kota'          => 'bg-info text-dark',
    'provinsi'      => 'bg-primary',
    'nasional'      => 'bg-warning text-dark',
    'internasional' => 'bg-success',
];
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-trophy"></i> Daftar Prestasi</div>
        <a href="<?php echo SITE_URL; ?>/admin/prestasi/tambah.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Prestasi
        </a>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="search-input" placeholder="Cari prestasi..." value="<?php echo clean($search); ?>">
            <select name="kategori" class="search-input" style="min-width:150px;">
                <option value="">Semua Kategori</option>
                <option value="akademik"     <?php echo $filter_kategori == 'akademik'     ? 'selected' : ''; ?>>Akademik</option>
                <option value="non-akademik" <?php echo $filter_kategori == 'non-akademik' ? 'selected' : ''; ?>>Non-Akademik</option>
            </select>
            <select name="tingkat" class="search-input" style="min-width:150px;">
                <option value="">Semua Tingkat</option>
                <option value="sekolah"       <?php echo $filter_tingkat == 'sekolah'       ? 'selected' : ''; ?>>Sekolah</option>
                <option value="kota"          <?php echo $filter_tingkat == 'kota'          ? 'selected' : ''; ?>>Kota</option>
                <option value="provinsi"      <?php echo $filter_tingkat == 'provinsi'      ? 'selected' : ''; ?>>Provinsi</option>
                <option value="nasional"      <?php echo $filter_tingkat == 'nasional'      ? 'selected' : ''; ?>>Nasional</option>
                <option value="internasional" <?php echo $filter_tingkat == 'internasional' ? 'selected' : ''; ?>>Internasional</option>
            </select>
            <button type="submit" class="btn-add"><i class="fas fa-search"></i></button>
            <?php if ($search || $filter_kategori || $filter_tingkat): ?>
            <a href="<?php echo SITE_URL; ?>/admin/prestasi/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Nama Prestasi</th>
                    <th>Kategori</th>
                    <th>Tingkat</th>
                    <th>Tahun</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prestasi)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-trophy"></i>
                            <h5>Belum ada data prestasi</h5>
                            <p>Tambahkan prestasi yang telah diraih</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($prestasi as $i => $p): ?>
                <tr>
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <div style="max-width: 280px;">
                            <div class="fw-semibold"><?php echo clean($p['nama_prestasi']); ?></div>
                            <?php if ($p['deskripsi']): ?>
                            <div class="text-muted" style="font-size:12px;"><?php echo clean(limitText($p['deskripsi'], 60)); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?php echo $p['kategori'] == 'akademik' ? 'bg-primary' : 'bg-success'; ?>">
                            <?php echo ucfirst(clean($p['kategori'])); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?php echo $badge_tingkat[$p['tingkat']] ?? 'bg-secondary'; ?>">
                            <?php echo ucfirst(clean($p['tingkat'])); ?>
                        </span>
                    </td>
                    <td style="font-size:13px;"><?php echo clean($p['tahun']); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/admin/prestasi/edit.php?id=<?php echo $p['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/prestasi/hapus.php?id=<?php echo $p['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="<?php echo clean($p['nama_prestasi']); ?>" title="Hapus"><i class="fas fa-trash"></i></a>
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
                <a class="page-link" href="?page=<?php echo $p; ?>&search=<?php echo urlencode($search); ?>&kategori=<?php echo urlencode($filter_kategori); ?>&tingkat=<?php echo urlencode($filter_tingkat); ?>"><?php echo $p; ?></a>
            </li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
