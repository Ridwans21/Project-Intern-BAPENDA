<?php
// Menghubungkan ke database
$conn = new mysqli("localhost", "root", "", "bap_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nop = $_POST['nop'];
    $nik = $_POST['nik'];

    // Memeriksa apakah NOP dan NIK valid
    $stmt = $conn->prepare("SELECT id FROM bap_entries WHERE nop = ? AND nik = ?");
    $stmt->bind_param("ss", $nop, $nik);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // NOP dan NIK valid, lanjutkan ke halaman upload
        header("Location: upload.php?nop=$nop&nik=$nik");
        exit;
    } else {
        $errorMessage = "NOP dan NIK tidak valid.";
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
    <title>Autentikasi</title>
</head>
<body>

<h2>Autentikasi</h2>
<form method="POST">
    <label for="nop">NOP:</label>
    <input type="text" name="nop" required>
    <br>
    <label for="nik">NIK:</label>
    <input type="text" name="nik" required>
    <br>
    <input type="submit" value="Verifikasi">
</form>
<?php if ($errorMessage): ?>
    <div style="color:red;"><?php echo $errorMessage; ?></div>
<?php endif; ?>

</body>
</html>
