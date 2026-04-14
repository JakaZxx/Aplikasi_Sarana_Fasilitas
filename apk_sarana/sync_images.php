<?php
// Script buat sinkron foto dari laravel ke native
$nativeDir = __DIR__ . '/public/lampiran';
$laravelDirs = [
    dirname(__DIR__) . '/apk_sarana/storage/app/public/lampiran',
    dirname(__DIR__) . '/apk_sarana/public/storage/lampiran',
    $_SERVER['DOCUMENT_ROOT'] . '/apk_sarana/storage/app/public/lampiran',
    $_SERVER['DOCUMENT_ROOT'] . '/apk_sarana/public/storage/lampiran'
];

echo "<pre>Sinkronisasi Foto\n\n";

if (!is_dir($nativeDir)) {
    mkdir($nativeDir, 0777, true);
}

$foundLaravel = false;
foreach ($laravelDirs as $ldir) {
    if (is_dir($ldir)) {
        $foundLaravel = $ldir;
        break;
    }
}

if ($foundLaravel) {
    echo "Folder Laravel: $foundLaravel\n";
    
    // Copy ke native
    $files = glob($foundLaravel . '/*.*');
    foreach ($files as $file) {
        $dest = $nativeDir . '/' . basename($file);
        if (!file_exists($dest)) {
            copy($file, $dest);
        }
    }
    
    // Copy balik ke laravel
    $filesNative = glob($nativeDir . '/*.*');
    foreach ($filesNative as $file) {
        $dest = $foundLaravel . '/' . basename($file);
        if (!file_exists($dest)) {
            copy($file, $dest);
        }
    }
    echo "Selesai.\n";
} else {
    echo "Folder Laravel ga ketemu.\n";
}
echo "</pre>";
