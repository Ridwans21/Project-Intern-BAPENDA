<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bap_system");

// Pastikan pengguna sudah login sebagai admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Ambil informasi entri berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM bap_entries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $entry = $result->fetch_assoc();
    
    if ($entry) {
        // Logika untuk mengirim surat hasil (misalnya, mengirim email atau mengupdate status)
        // Contoh pengiriman email (sesuaikan dengan pengaturan email Anda)
        $to = "user@example.com"; // Ganti dengan email pengguna yang sebenarnya
        $subject = "Surat Hasil untuk " . htmlspecialchars($entry['nama']);
        $message = "Berikut adalah surat hasil untuk entri BAP Anda:\n\n";
        $message .= "Nama: " . htmlspecialchars($entry['nama']) . "\n";
        $message .= "No Bayar: " . htmlspecialchars($entry['no_bayar']) . "\n";
        $message .= "Berita: " . htmlspecialchars($entry['berita']) . "\n";
        $message .= "Status: " . htmlspecialchars($entry['status']) . "\n\n";
        $message .= "Terima kasih.";

        // Mengirim email
        if (mail($to, $subject, $message)) {
            echo "Surat hasil berhasil dikirim ke " . htmlspecialchars($entry['nama']) . ".";
        } else {
            echo "Gagal mengirim surat hasil. Silakan coba lagi.";
        }
    } else {
        echo "Entri tidak ditemukan.";
    }

    $stmt->close();
}

// Ambil semua entri untuk ditampilkan dalam form
$result = $conn->query("SELECT * FROM bap_entries");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kirim Surat Hasil</title>
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
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0073e6;
            color: white;
        }
        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #0073e6;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>

<header>
    Form Kirim Surat Hasil
</header>

<div class="container">
    <form action="" method="post">
        <label for="entry">Pilih Entri:</label>
        <select name="id" required>
            <option value="">-- Pilih Entri --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nama']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn">Kirim Surat Hasil</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
