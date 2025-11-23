<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bap_system");

// Pastikan pengguna sudah login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Periksa apakah file telah diunggah
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $id = $_POST['id']; // Ambil ID dari entri BAP

    // Ambil detail file yang diunggah
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_error = $_FILES['file']['error'];

    // Set folder penyimpanan
    $upload_dir = 'uploads/' . $id; // Folder penyimpanan berdasarkan ID entri
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
    }

    // Path tujuan file yang akan diunggah
    $destination = $upload_dir . '/' . $file_name;

    // Periksa apakah ada error pada file
    if ($file_error === 0) {
        // Batasan ukuran file (misalnya 2MB)
        if ($file_size <= 2000000) {
            // Pindahkan file ke lokasi penyimpanan
            if (move_uploaded_file($file_tmp, $destination)) {
                // Update status file di database
                $stmt = $conn->prepare("UPDATE bap_entries SET uploaded_file = ? WHERE id = ?");
                $stmt->bind_param("si", $file_name, $id);
                
                if ($stmt->execute()) {
                    // Redirect dengan pesan sukses
                    $_SESSION['success'] = "File berhasil diunggah.";
                    header("Location: dashboard.php");
                } else {
                    $_SESSION['error'] = "Gagal memperbarui status di database.";
                    header("Location: dashboard.php");
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Gagal mengunggah file.";
                header("Location: dashboard.php");
            }
        } else {
            $_SESSION['error'] = "Ukuran file terlalu besar. Maksimum 2MB.";
            header("Location: dashboard.php");
        }
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat mengunggah file.";
        header("Location: dashboard.php");
    }
} else {
    $_SESSION['error'] = "Tidak ada file yang diunggah.";
    header("Location: dashboard.php");
}

$conn->close();
?>
