<?php
require_once __DIR__ . '/config/config.php';

echo "<h2>Debug Upload Path</h2>";
echo "BASE_PATH: <code>" . BASE_PATH . "</code><br>";
echo "UPLOAD_PATH: <code>" . UPLOAD_PATH . "</code><br>";
echo "Folder guru ada: " . (is_dir(UPLOAD_PATH . 'guru/') ? '✅ Ya' : '❌ Tidak') . "<br>";
echo "Folder guru writable: " . (is_writable(UPLOAD_PATH . 'guru/') ? '✅ Ya' : '❌ Tidak (chmod 777)') . "<br>";

echo "<h3>File di uploads/guru/</h3>";
$files = glob(UPLOAD_PATH . 'guru/*');
if ($files) {
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse'>";
    echo "<tr><th>File</th><th>Ukuran</th><th>Preview</th></tr>";
    foreach ($files as $f) {
        $name = basename($f);
        $size = round(filesize($f) / 1024, 1) . ' KB';
        echo "<tr>
            <td><code>$name</code></td>
            <td>$size</td>
            <td><img src='" . SITE_URL . "/uploads/guru/$name' style='height:50px;'></td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "❌ Tidak ada file di folder uploads/guru/<br>";
}

echo "<h3>Data Guru di Database</h3>";
$gurus = fetchAll("SELECT id, nama_lengkap, foto FROM guru ORDER BY id");
echo "<table border='1' cellpadding='8' style='border-collapse:collapse'>";
echo "<tr><th>ID</th><th>Nama</th><th>Kolom foto (DB)</th><th>File Ada?</th><th>Tampil</th></tr>";
foreach ($gurus as $g) {
    $file_exists = $g['foto'] ? (file_exists(UPLOAD_PATH . 'guru/' . $g['foto']) ? '✅' : '❌ file tidak ada') : '— (kosong)';
    $img = $g['foto'] ? "<img src='" . SITE_URL . "/uploads/guru/" . $g['foto'] . "' style='height:40px;'>" : '-';
    echo "<tr>
        <td>{$g['id']}</td>
        <td>{$g['nama_lengkap']}</td>
        <td><code>" . ($g['foto'] ?: '(null/kosong)') . "</code></td>
        <td>$file_exists</td>
        <td>$img</td>
    </tr>";
}
echo "</table>";
?>
