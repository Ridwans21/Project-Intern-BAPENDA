<?php
$conn = new mysqli("localhost", "root", "", "bap_system");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM bap_entries WHERE id = $id");
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nop = $_POST['nop'];
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $no_bayar = $_POST['no_bayar'];
    $berita = $_POST['berita'];
    $status = $_POST['status'];

    $sql = "UPDATE bap_entries SET nop='$nop', nik='$nik', nama='$nama', no_bayar='$no_bayar', berita='$berita', status='$status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui data: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
</head>
<body>

<h2>Edit Data</h2>
<form method="POST" action="edit.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
    NOP: <input type="text" name="nop" value="<?php echo htmlspecialchars($row['nop']); ?>"><br>
    NIK: <input type="text" name="nik" value="<?php echo htmlspecialchars($row['nik']); ?>"><br>
    Nama: <input type="text" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>"><br>
    No Bayar: <input type="text" name="no_bayar" value="<?php echo htmlspecialchars($row['no_bayar']); ?>"><br>
    Berita: <input type="text" name="berita" value="<?php echo htmlspecialchars($row['berita']); ?>"><br>
    Status: <input type="text" name="status" value="<?php echo htmlspecialchars($row['status']); ?>"><br>
    <button type="submit">Update Data</button>
</form>

</body>
</html>
