<?php
// Form Input Awal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nop = $_POST['nop'];
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $no_bayar = $_POST['no_bayar'];
    $berita = $_POST['berita'];

    // Insert data awal ke database
    $conn = new mysqli("localhost", "root", "", "bap_system");
    $stmt = $conn->prepare("INSERT INTO bap_entries (nop, nik, nama, no_bayar, berita, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssss", $nop, $nik, $nama, $no_bayar, $berita);
    $stmt->execute();
    $last_id = $stmt->insert_id;  // Dapatkan ID terakhir

    

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input BAP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f2ff;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            background-color: #0056b3;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar .brand {
            font-size: 20px;
            font-weight: bold;
        }
        .navbar .login-btn {
            background-color: white;
            color: #0056b3;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .navbar .login-btn:hover {
            background-color: #e6e6e6;
        }

        /* Form */
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 50px auto;
        }
        .form-container h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 30px;
        }
        label {
            color: #0056b3;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #005bb5;
        }
        .success-message {
            background-color: #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="brand">BAP System</div>
    <a href="login.php" class="login-btn">Login Staff</a>
</div>

<div class="form-container">
    <h2>Input Data BAP</h2>
    <form method="POST">
        <label>NOP :</label>
        <input type="text" name="nop" required><br>

        <label>NIK :</label>
        <input type="text" name="nik" required><br>

        <label>Nama :</label>
        <input type="text" name="nama" required><br>

        <label>No Bayar :</label>
        <input type="text" name="no_bayar" required><br>

        <label>Berita :</label>
        <textarea name="berita" required></textarea><br>

        <input type="submit" value="Submit" class="submit-btn">
    </form>

    <div class="text-center mt-3">
        <p>Sudah Isi Data? <a href="dashboard2.php">Lihat Status disini</a></p>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class='success-message'>
            Data berhasil disimpan. Tunggu keputusan admin.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
