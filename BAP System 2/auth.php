<?php
$authError = ''; // Variabel untuk menyimpan pesan error autentikasi

// Periksa apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nop = trim($_POST['nop']);
    $nik = trim($_POST['nik']);

    // Validasi input
    if (empty($nop) || empty($nik)) {
        $authError = "NOP dan NIK tidak boleh kosong.";
    } else {
        // Koneksi ke database
        $conn = new mysqli("localhost", "root", "", "bap_system");

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Persiapkan statement untuk menghindari SQL Injection
        $stmt = $conn->prepare("SELECT id FROM bap_entries WHERE nop = ? AND nik = ?");
        if ($stmt === false) {
            die("Gagal mempersiapkan statement: " . $conn->error);
        }
        
        $stmt->bind_param("ss", $nop, $nik);
        $stmt->execute();
        $stmt->store_result();

        // Cek apakah ada hasil yang cocok
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($entry_id);
            $stmt->fetch();
            // Jika NOP dan NIK benar, redirect ke halaman upload
            header("Location: upload.php?entry_id=" . $entry_id);
            exit;
        } else {
            // Pesan error jika NOP atau NIK salah
            $authError = "NOP atau NIK salah. Silakan coba lagi.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f2ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        .form-container h2 {
            color: #0056b3;
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #0056b3;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #0073e6;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            width: calc(100% - 20px);
            padding: 15px;
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #005bb5;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Autentikasi Pengguna</h2>
    <form method="POST">
        <label>NOP:</label>
        <input type="text" name="nop" required>

        <label>NIK:</label>
        <input type="text" name="nik" required>

        <input type="submit" value="Masuk">
    </form>
    <?php if ($authError): ?>
        <div class="error-message"><?php echo htmlspecialchars($authError); ?></div>
    <?php endif; ?>
</div>

</body>
</html>
