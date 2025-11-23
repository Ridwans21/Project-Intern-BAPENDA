<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bap_system");

// Pastikan pengguna sudah login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Query untuk mendapatkan semua data dari tabel bap_entries
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
            margin-bottom: 20px; /* Memberikan jarak bawah antara header dan konten */
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
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
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
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            margin-right: 10px;  /* Menambah jarak antar tombol */
            margin-bottom: 10px; /* Menambah jarak antar tombol dan elemen di bawahnya */
            cursor: pointer;
            color: white;
            text-decoration: none;
            background-color: #0073e6;
            transition: background-color 0.3s ease;
            font-size: 0.9em;
        }
        .btn:hover {
            background-color: #005bb5;
        }
        /* Jarak di antara tombol approve/deny */
        .btn-approve {
        background-color: #28a745;
        margin-right: 10px; /* Memberikan jarak antara tombol approve dan deny */
        }
        .btn-deny {
            background-color: #dc3545;
        }
        /* Tambahkan margin di bawah teks yang perlu */
        .not-uploaded {
            color: #999;
            font-style: italic;
            margin-bottom: 10px; /* Memberikan jarak bawah antar teks */
        }
    </style>
</head>
<body>

<header>
    <a href="arsip.php" class="btn"><i class="fas fa-archive"></i>&nbsp;Arsip</a>
    <a href="logout.php" class="btn"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a> <!-- Tombol Logout -->
    Dashboard Monitoring BAP Entries
</header>

<div class="container">
    <table>
        <tr>
            <th>NOP</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>No Bayar</th>
            <th>Berita</th>
            <th>Status</th>
            <th>Dokumen KTP</th>
            <th>Surat Pernyataan</th>
            <th>Surat Permohonan</th>
            <th>Lihat Folder</th>
            <th>Keputusan</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nop']); ?></td>
            <td><?php echo htmlspecialchars($row['nik']); ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['no_bayar']); ?></td>
            <td><?php echo htmlspecialchars($row['berita']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if (!empty($row['ktp'])): ?>
                    <span>Telah Upload</span>
                <?php else: ?>
                    <span class="not-uploaded">Belum Upload</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['surat_pernyataan'])): ?>
                    <span>Telah Upload</span>
                <?php else: ?>
                    <span class="not-uploaded">Belum Upload</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['surat_permohonan'])): ?>
                    <span>Telah Upload</span>
                <?php else: ?>
                    <span class="not-uploaded">Belum Upload</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['ktp']) && !empty($row['surat_pernyataan']) && !empty($row['surat_permohonan'])): ?>
                    <a href="download.php?folder=<?php echo urlencode('uploads/' . $row['nop'] . '_' . $row['nik']); ?>" class="btn" title="Download Folder">Download Folder</a>
                <?php else: ?>
                    <span class="not-uploaded">Dokumen belum lengkap</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['status'] === 'Approved' && !empty($row['file_hasil'])): ?>
                    <!-- Jika status 'Approved' dan file hasil sudah diupload -->
                    <span>File hasil telah dikirimkan</span>
                <?php elseif ($row['status'] === 'Approved'): ?>
                    <!-- Jika status 'Approved' tapi file hasil belum diupload -->
                    <form method="POST" action="upload_file.php" enctype="multipart/form-data">
                        <input type="file" name="file" required>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" class="btn btn-upload">Upload File</button>
                    </form>
                <?php else: ?>
                    <!-- Tampilkan tombol approve/deny jika belum diapprove -->
                    <form method="POST" action="update_status.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-approve" title="Approve">Approve</button>
                    </form>
                    <form method="POST" action="update_status.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="action" value="deny" class="btn btn-deny" title="Deny">Deny</button>
                    </form>
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
