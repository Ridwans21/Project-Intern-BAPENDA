<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bap_system");

// Pastikan pengguna sudah login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Mengambil data yang sudah disetujui
$result = $conn->query("SELECT * FROM bap_entries WHERE status = 'Approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Data BAP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f2ff; /* Latar belakang biru muda */
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #0056b3; /* Warna biru tua */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #f8f9fa;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0073e6; /* Warna biru */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e6f7ff;
        }
        .btn-back {
            background-color: #0056b3;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>

<header>
    Arsip Data BAP yang Disetujui
</header>

<!-- Tombol Back -->
<div class="container">
    <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>
    <table>
        <tr>
            <th>NOP</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>No Bayar</th>
            <th>Berita</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nop']); ?></td>
            <td><?php echo htmlspecialchars($row['nik']); ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['no_bayar']); ?></td>
            <td><?php echo htmlspecialchars($row['berita']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
