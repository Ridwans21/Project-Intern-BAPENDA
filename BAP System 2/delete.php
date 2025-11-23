<?php
$conn = new mysqli("localhost", "root", "", "bap_system");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM bap_entries WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Terjadi kesalahan saat menghapus data: " . $conn->error;
    }
}

$conn->close();
?>
