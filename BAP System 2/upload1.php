<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bap_system");

// Pastikan pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Ambil semua data dari tabel bap_entries
$result = $conn->query("SELECT * FROM bap_entries");
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
        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            margin-right: 5px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            background-color: #0073e6;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #005bb5;
        }
        .not-uploaded {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

<header>
    <a href="arsip.php" class="btn"><i class="bi bi-archive-fill"></i>&nbsp;Arsip</a>
    <a href="logout.php" class="btn"><i class="bi bi-box-arrow-right"></i>&nbsp;Logout</a> <!-- Tombol Logout -->
    Dashboard Monitoring BAP Entries
</header>

<div class="container">
    <table>
        <tr>
            <th>Nama</th>
            <th>No Bayar</th>
            <th>Berita</th>
            <th>Status</th>
            <th>Dokumen KTP</th>
            <th>Surat Pernyataan</th>
            <th>Surat Permohonan</th>
            <th>Keputusan</th>
            <th>Aksi</th> <!-- Tambahkan kolom untuk Aksi -->
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['no_bayar']); ?></td>
            <td><?php echo htmlspecialchars($row['berita']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if (!empty($row['ktp']) && file_exists('uploads/' . basename($row['ktp']))): ?>
                    <a class="btn" href="uploads/<?php echo basename($row['ktp']); ?>" target="_blank">Lihat KTP</a>
                <?php else: ?>
                    <span class="not-uploaded">Belum Upload</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['surat_pernyataan']) && file_exists('uploads/' . basename($row['surat_pernyataan']))): ?>
                    <a class="btn" href="uploads/<?php echo basename($row['surat_pernyataan']); ?>" target="_blank">Lihat Surat Pernyataan</a>
                <?php else: ?>
                    <span class="not-uploaded">Belum Upload</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['surat_permohonan']) && file_exists('uploads/' . basename($row['surat_permohonan']))): ?>
                    <a class="btn" href="uploads/<?php echo basename($row['surat_permohonan']); ?>" target="_blank">Lihat Surat Permohonan</a>
                <?php else: ?>
                    <span class="not-uploaded">Belum Upload</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['status'] === 'Approved'): ?>
                    <span>Disetujui</span>
                <?php else: ?>
                    <form method="POST" action="update_status.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="action" value="approve" class="btn">Approve</button>
                    </form>
                    <form method="POST" action="update_status.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="action" value="deny" class="btn">Deny</button>
                    </form>
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" action="send_letter.php" style="display: inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <button type="submit" class="btn">Kirim Surat Hasil</button>
                </form>
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
