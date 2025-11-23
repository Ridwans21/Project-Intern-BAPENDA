<?php
// Memastikan folder parameter ada
if (!isset($_GET['folder'])) {
    die("Folder parameter is missing.");
}

$folder = urldecode($_GET['folder']);
$base_dir = 'uploads/'; // Direktori utama uploads

// Menghilangkan prefix 'uploads/' jika ada
if (strpos($folder, 'uploads/') === 0) {
    $folder = substr($folder, strlen('uploads/'));
}

// Path absolut dari folder yang ingin di-download
$folder_path = realpath($base_dir . $folder);

// Validasi bahwa folder tersebut berada di dalam direktori uploads
if ($folder_path === false || !is_dir($folder_path)) {
    die("Invalid folder path.");
}

if (strpos($folder_path, realpath($base_dir)) !== 0) {
    die("Invalid folder path.");
}

// Nama file ZIP yang akan dibuat
$zip_file = tempnam(sys_get_temp_dir(), 'zip');

// Membuat file ZIP
$zip = new ZipArchive;
if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
    die("Failed to create ZIP file.");
}

// Fungsi untuk menambahkan file ke dalam ZIP
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folder_path),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    if (!$file->isDir()) {
        $file_path = $file->getRealPath();
        $relative_path = substr($file_path, strlen($folder_path) + 1);
        if ($zip->addFile($file_path, $relative_path)) {
            // Uncomment the line below for debugging
            // echo "File $relative_path berhasil ditambahkan ke dalam ZIP.<br>";
        } else {
            // Uncomment the line below for debugging
            // echo "Gagal menambahkan file $relative_path ke dalam ZIP.<br>";
        }
    }
}

$zip->close(); // Tutup file ZIP

// Mengirim file ZIP ke pengguna
if (file_exists($zip_file)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($folder) . '.zip"');
    header('Content-Length: ' . filesize($zip_file));
    readfile($zip_file);
    
    // Hapus file ZIP sementara setelah diunduh
    unlink($zip_file);
    exit();
} else {
    die("ZIP file not created.");
}
?>
