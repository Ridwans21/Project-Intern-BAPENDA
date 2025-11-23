<?php
// Mengaktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "bap_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan ada ID dari entry
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Cek apakah ID tersebut ada di database dan ambil NOP dan NIK-nya
    $stmt = $conn->prepare("SELECT nop, nik FROM bap_entries WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Pesan error jika prepare gagal
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Entry ID tidak ditemukan di database.");
    }

    // Ambil NOP dan NIK dari hasil query
    $row = $result->fetch_assoc();
    $nop = $row['nop']; // NOP dari database
    $nik = $row['nik']; // NIK dari database
    $stmt->close();
} else {
    die("ID tidak ditemukan.");
}

// Cek apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nama direktori utama sesuai dengan NOP dan NIK
    $upload_dir = 'uploads/' . $nop . '_' . $nik . '/';

    // Buat direktori jika belum ada
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            die("Gagal membuat direktori upload untuk NOP: $nop dan NIK: $nik.");
        }
    }

    // Variabel untuk menyimpan nama file
    $ktp_file = $surat_pernyataan_file = $surat_permohonan_file = null;

    // Loop untuk menangani tiga file yang diupload
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["file$i"]) && $_FILES["file$i"]['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES["file$i"]['tmp_name'];
            $file_name = basename($_FILES["file$i"]['name']); // Mengambil nama file saja

            // Nama file tujuan lengkap
            $file_path = $upload_dir . $file_name;

            // Pindahkan file ke direktori tujuan
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Simpan nama file untuk update ke database
                if ($i == 1) {
                    $ktp_file = $file_name; // File 1 adalah KTP
                } elseif ($i == 2) {
                    $surat_pernyataan_file = $file_name; // File 2 adalah Surat Pernyataan
                } elseif ($i == 3) {
                    $surat_permohonan_file = $file_name; // File 3 adalah Surat Permohonan
                }
            } else {
                echo "Gagal memindahkan file $file_name ke $file_path. Periksa izin direktori.<br>";
            }
        }
    }

    // Update database untuk menandakan bahwa dokumen telah diupload
    $stmt = $conn->prepare("UPDATE bap_entries SET document_uploaded = 1, ktp = ?, surat_pernyataan = ?, surat_permohonan = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Pesan error jika prepare gagal
    }
    $stmt->bind_param("sssi", $ktp_file, $surat_pernyataan_file, $surat_permohonan_file, $id);

    if ($stmt->execute()) {
        echo "<div style='color: green;'>Dokumen berhasil diupload dan status diupdate menjadi dokumen lengkap.</div><br>";
    } else {
        echo "Gagal memperbarui database untuk dokumen: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f2ff;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #0056b3;
        }
        .upload-form {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }
        input[type="file"] {
            margin-bottom: 15px;
        }
        button {
            background-color: #0073e6;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #005bb5;
        }
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Upload Dokumen</h1>
    <div class="upload-form">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="file1">File KTP:</label>
            <input type="file" name="file1" id="file1" required>

            <label for="file2">File Surat Pernyataan:</label>
            <input type="file" name="file2" id="file2" required>

            <label for="file3">File Surat Permohonan:</label>
            <input type="file" name="file3" id="file3" required>

            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
