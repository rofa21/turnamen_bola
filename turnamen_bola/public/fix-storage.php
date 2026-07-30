<?php
// fix-storage.php in public_html

$target = '/home/piaa8977/turnamen_bola/storage/app/public';
$link = __DIR__ . '/storage';

echo "<div style='background:#1e1e1e;color:#00ff66;padding:20px;font-family:monospace;border-radius:10px;'>";
echo "<h2>🛠️ Memperbaiki Akses Foto & Dokumen...</h2><hr><pre>";

// Ensure target folder exists & permissions 775
if (!file_exists($target)) {
    mkdir($target, 0775, true);
    echo "✅ Folder storage target dibuat: $target\n";
}

if (file_exists($link)) {
    if (is_link($link)) {
        unlink($link);
        echo "ℹ️ Symlink lama dihapus.\n";
    } else {
        rename($link, $link . '_old_' . time());
        echo "ℹ️ Folder lama di-rename.\n";
    }
}

if (@symlink($target, $link)) {
    echo "✅ SYMLINK BERHASIL DIBUAT!\n   $link --> $target\n\n";
} else {
    echo "⚠️ Symlink tidak dapat dibuat oleh server (beberapa hosting melarang symlink).\n";
    echo "   Tenang, Route File Finder bawaan Laravel akan menangani pembukaan foto otomatis!\n\n";
}

// Check permission
chmod($target, 0775);
echo "✅ Permissions folder storage diset ke 775!\n";
echo "🎉 SELESAI! Silakan coba klik/buka foto pemain kembali di website.\n";
echo "</pre></div>";
