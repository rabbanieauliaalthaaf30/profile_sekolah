<?php
$page_title  = 'Pesan Masuk';
$active_menu = 'kontak';
include __DIR__ . '/../includes/header.php';

$filter_status = isset($_GET['status']) ? escape($_GET['status']) : '';
$page          = max(1, (int)($_GET['page'] ?? 1));
$limit         = ITEMS_PER_PAGE;
$offset        = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($filter_status) $where .= " AND status = '$filter_status'";

$total       = fetch("SELECT COUNT(*) as t FROM kontak_masuk $where")['t'] ?? 0;
$total_pages = ceil($total / $limit);

$kontak = fetchAll("SELECT * FROM kontak_masuk $where ORDER BY tanggal_kirim DESC LIMIT $limit OFFSET $offset");

$total_belum_dibaca = fetch("SELECT COUNT(*) as t FROM kontak_masuk WHERE status = 'belum_dibaca'")['t'] ?? 0;
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fas fa-envelope"></i> Pesan Masuk
            <?php if ($total_belum_dibaca > 0): ?>
            <span class="badge bg-danger ms-2"><?php echo $total_belum_dibaca; ?> baru</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter Bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <select name="status" class="search-input" style="min-width:170px;">
                <option value="">Semua Pesan</option>
                <option value="belum_dibaca" <?php echo $filter_status == 'belum_dibaca' ? 'selected' : ''; ?>>Belum Dibaca</option>
                <option value="sudah_dibaca" <?php echo $filter_status == 'sudah_dibaca' ? 'selected' : ''; ?>>Sudah Dibaca</option>
            </select>
            <button type="submit" class="btn-add"><i class="fas fa-filter me-1"></i> Filter</button>
            <?php if ($filter_status): ?>
            <a href="<?php echo SITE_URL; ?>/admin/kontak/index.php" class="btn-add" style="background:#6b7280;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Pengirim</th>
                    <th>Subjek</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kontak)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-envelope-open"></i>
                            <h5>Tidak ada pesan</h5>
                            <p>Belum ada pesan masuk dari pengunjung</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($kontak as $i => $k): ?>
                <tr style="<?php echo $k['status'] == 'belum_dibaca' ? 'background: #fefce8;' : ''; ?>">
                    <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                    <td>
                        <div>
                            <div class="fw-semibold <?php echo $k['status'] == 'belum_dibaca' ? 'fw-bold' : ''; ?>">
                                <?php echo clean($k['nama_pengirim']); ?>
                            </div>
                            <div class="text-muted" style="font-size:12px;"><?php echo clean($k['email_pengirim']); ?></div>
                        </div>
                    </td>
                    <td>
                        <div style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?php if ($k['status'] == 'belum_dibaca'): ?>
                            <i class="fas fa-circle text-warning me-1" style="font-size:8px;"></i>
                            <?php endif; ?>
                            <?php echo clean($k['subjek']); ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge-status <?php echo $k['status'] == 'belum_dibaca' ? 'status-nonaktif' : 'status-aktif'; ?>">
                            <?php echo $k['status'] == 'belum_dibaca' ? 'Belum Dibaca' : 'Sudah Dibaca'; ?>
                        </span>
                    </td>
                    <td style="font-size:12px; white-space:nowrap; color:#64748b;"><?php echo formatTanggal($k['tanggal_kirim']); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo SITE_URL; ?>/admin/kontak/detail.php?id=<?php echo $k['id']; ?>" class="btn-action btn-view" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/kontak/hapus.php?id=<?php echo $k['id']; ?>" class="btn-action btn-delete btn-confirm-delete" data-name="pesan dari <?php echo clean($k['nama_pengirim']); ?>" title="Hapus"><i class="fas fa-trash"></i></a>
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
                <a class="page-link" href="?page=<?php echo $p; ?><?php echo $filter_status ? '&status=' . urlencode($filter_status) : ''; ?>"><?php echo $p; ?></a>
            </li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
// ============================================================
// REALTIME POLLING - cek pesan baru setiap 3 detik
// ============================================================
(function() {
    const API_URL    = '<?php echo SITE_URL; ?>/api/cek-pesan-baru.php';
    const INTERVAL   = 3000; // 3 detik
    let   lastTime   = '<?php echo date('Y-m-d H:i:s'); ?>'; // waktu saat halaman pertama dibuka
    let   knownIds   = new Set([<?php
        // Kirim semua ID yang sudah tampil ke JS agar tidak ditampilkan sebagai notif
        $existing_ids = fetchAll("SELECT id FROM kontak_masuk ORDER BY id");
        echo implode(',', array_column($existing_ids, 'id'));
    ?>]);

    // Referensi elemen UI
    const badgeHeader   = document.querySelector('.admin-card-title .badge.bg-danger');
    const tabelBody     = document.querySelector('.admin-table tbody');

    function polling() {
        fetch(API_URL + '?since=' + encodeURIComponent(lastTime))
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                // Update badge jumlah belum dibaca di header card
                updateBadge(data.total_belum);

                // Jika ada pesan baru
                const baru = data.pesan_baru.filter(p => !knownIds.has(p.id));
                if (baru.length > 0) {
                    baru.forEach(p => {
                        knownIds.add(p.id);
                        tambahBarisBaru(p);
                    });
                    // Notifikasi browser (opsional)
                    tampilNotifBrowser(baru.length, baru[0].nama_pengirim);
                }

                lastTime = data.server_time;
            })
            .catch(() => {}); // silent fail kalau offline
    }

    function updateBadge(total) {
        // Update badge di header card
        let badge = document.getElementById('badgeBelumDibaca');
        if (!badge) {
            const title = document.querySelector('.admin-card-title');
            if (title) {
                badge = document.createElement('span');
                badge.id = 'badgeBelumDibaca';
                badge.className = 'badge bg-danger ms-2';
                title.appendChild(badge);
            }
        }
        if (badge) {
            badge.textContent = total > 0 ? total + ' baru' : '';
            badge.style.display = total > 0 ? 'inline' : 'none';
        }

        // Update badge di navbar bell icon
        const bell = document.querySelector('.top-nav-icon .badge');
        if (bell) {
            bell.textContent = total > 0 ? total : '';
            bell.style.display = total > 0 ? 'inline' : 'none';
        }

        // Update judul tab browser
        if (total > 0) {
            document.title = '(' + total + ') Pesan Masuk - Admin Panel';
        } else {
            document.title = 'Pesan Masuk - Admin Panel';
        }
    }

    function tambahBarisBaru(p) {
        // Hapus "empty state" kalau ada
        const emptyRow = tabelBody.querySelector('td[colspan]');
        if (emptyRow) emptyRow.closest('tr').remove();

        // Format tanggal
        const tgl = new Date(p.tanggal_kirim.replace(' ', 'T'));
        const tglStr = tgl.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

        const subjek = p.subjek || '(tanpa subjek)';
        const BASE   = '<?php echo SITE_URL; ?>';

        const tr = document.createElement('tr');
        tr.style.background = '#fefce8';
        tr.style.animation  = 'fadeInRow 0.5s ease';
        tr.innerHTML = `
            <td class="text-muted">—</td>
            <td>
                <div>
                    <div class="fw-bold">${escHtml(p.nama_pengirim)}</div>
                    <div class="text-muted" style="font-size:12px;">${escHtml(p.email_pengirim)}</div>
                </div>
            </td>
            <td>
                <div style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    <i class="fas fa-circle text-warning me-1" style="font-size:8px;"></i>
                    ${escHtml(subjek)}
                </div>
            </td>
            <td><span class="badge-status status-nonaktif">Belum Dibaca</span></td>
            <td style="font-size:12px; white-space:nowrap; color:#64748b;">${tglStr}</td>
            <td>
                <div class="d-flex gap-1">
                    <a href="${BASE}/admin/kontak/detail.php?id=${p.id}" class="btn-action btn-view" title="Lihat"><i class="fas fa-eye"></i></a>
                    <a href="${BASE}/admin/kontak/hapus.php?id=${p.id}" class="btn-action btn-delete btn-confirm-delete" data-name="pesan dari ${escHtml(p.nama_pengirim)}" title="Hapus"><i class="fas fa-trash"></i></a>
                </div>
            </td>`;

        // Sisipkan di atas (baris paling pertama)
        tabelBody.insertBefore(tr, tabelBody.firstChild);

        // Flash efek 3 detik lalu hapus highlight
        setTimeout(() => { tr.style.background = ''; }, 3000);

        // Tampilkan toast notifikasi di pojok kanan
        tampilToast(p.nama_pengirim, subjek);
    }

    function tampilToast(nama, subjek) {
        // Buat container toast kalau belum ada
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = 'position:fixed; bottom:25px; right:25px; z-index:9999; display:flex; flex-direction:column; gap:10px;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.style.cssText = 'background:#1e293b; color:white; padding:14px 18px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.3); min-width:280px; max-width:320px; animation:slideInToast 0.3s ease; border-left:4px solid #2563eb;';
        toast.innerHTML = `
            <div style="display:flex; align-items:flex-start; gap:10px;">
                <div style="width:36px; height:36px; background:#2563eb; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-weight:700;">
                    <i class="fas fa-envelope" style="font-size:14px;"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:600; font-size:13px; margin-bottom:2px;">Pesan Baru Masuk!</div>
                    <div style="font-size:12px; opacity:0.8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escHtml(nama)}: ${escHtml(subjek || '(tanpa subjek)')}</div>
                </div>
                <button onclick="this.closest('div[style]').remove()" style="background:none; border:none; color:rgba(255,255,255,0.5); font-size:16px; cursor:pointer; padding:0; line-height:1; flex-shrink:0;">×</button>
            </div>`;

        container.appendChild(toast);

        // Auto-hapus setelah 5 detik
        setTimeout(() => {
            toast.style.animation = 'slideOutToast 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function tampilNotifBrowser(jumlah, nama) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('📩 Pesan Baru (' + jumlah + ')', {
                body: 'Dari: ' + nama,
                icon: '<?php echo SITE_URL; ?>/uploads/logo/logo-sekolah.png'
            });
        } else if ('Notification' in window && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
    }

    function escHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str || ''));
        return div.innerHTML;
    }

    // Mulai polling
    setInterval(polling, INTERVAL);

    // Minta izin notifikasi browser saat halaman dibuka
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
})();
</script>

<style>
@keyframes fadeInRow {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes slideInToast {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes slideOutToast {
    from { opacity: 1; transform: translateX(0); }
    to   { opacity: 0; transform: translateX(30px); }
}
</style>