<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "bap_system");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Jalankan query dan cek hasilnya
$result = $conn->query("SELECT * FROM bap_entries");
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f2ff;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #0056b3;
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
            padding: 15px; /* Menambahkan padding lebih besar */
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: middle; /* Vertically align text */
        }
        th {
            background-color: #0073e6;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e6f7ff;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #0073e6;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #005bb5;
        }
        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            margin-right: 5px;
            margin-bottom: 10px; /* Tambahkan margin bawah */
            display: inline-block; /* Agar button sejajar */
            cursor: pointer;
            text-align: center;
            color: white;
        }
        .btn-complete {
            background-color: #28a745;
        }
        .btn-view {
            background-color: #0073e6; /* Biru untuk tombol lihat dokumen */
        }
        .btn-view:hover {
            background-color: #005bb5;
        }
        .btn-action {
            background-color: #ffcc00; /* Warna kuning untuk aksi */
        }
        /* Menambahkan jarak antar elemen di dalam .col-md */
        .col-md {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }
        .col-md .btn {
            margin-right: 10px; /* Memberikan jarak antar tombol di atas */
        }
        /* Untuk responsif pada layar kecil */
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 15px;
            }
            table, th, td {
                font-size: 14px; /* Perkecil font untuk layar lebih kecil */
            }
            th, td {
                padding: 10px; /* Kurangi padding pada layar kecil */
            }
        }
    </style>
</head>
<body>

<header>
    Dashboard Monitoring BAP Entries
</header>
<div class="container">
    <div class="col-md">
        <a href="index.php" class="btn btn-primary"><i class="fas fa-user-plus"></i>&nbsp;Tambah Data</a>
    </div>
</div>

<div class="container">
    <table>
        <tr>
            <th>Nama</th>
            <th>No Bayar</th>
            <th>Berita</th>
            <th>Status</th>
            <th>Action</th>
            <th>Surat Hasil</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['no_bayar']); ?></td>
            <td><?php echo htmlspecialchars($row['berita']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if ($row['status'] === 'Approved' && intval($row['document_uploaded']) === 0): ?>
                    <a href="upload.php?id=<?php echo $row['id']; ?>" class="btn btn-action">Lengkapi Dokumen</a>
                <?php elseif (intval($row['document_uploaded']) === 1): ?>
                    <span class="btn btn-complete">Dokumen Lengkap</span>
                <?php else: ?>
                    <span>Menunggu Persetujuan</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['uploaded_file'])): ?>
                    <a href="uploads/<?php echo htmlspecialchars($row['id'] . '/' . $row['uploaded_file']); ?>" class="btn btn-view" target="_blank">Lihat File</a>
                <?php else: ?>
                    <span>Belum Ada File</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
