<?php
// Proses keputusan approve atau reject
if (isset($_POST['decision']) && isset($_POST['entry_id'])) {
    $decision = $_POST['decision'];
    $entry_id = $_POST['entry_id'];

    $conn = new mysqli("localhost", "root", "", "bap_system");

    if ($decision === 'approve') {
        // Jika approve, update status dan arahkan ke form upload dokumen
        $stmt = $conn->prepare("UPDATE bap_entries SET status = 'Approved' WHERE id = ?");
        $stmt->bind_param("i", $entry_id);
        $stmt->execute();
        $stmt->close();

        header("Location: upload.php?entry_id={$entry_id}"); // Redirect ke halaman upload
        exit();
    } else {
        // Jika reject, update status dan beri pesan penolakan
        $stmt = $conn->prepare("UPDATE bap_entries SET status = 'Rejected' WHERE id = ?");
        $stmt->bind_param("i", $entry_id);
        $stmt->execute();
        $stmt->close();

        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Penolakan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f2ff; /* Latar belakang biru muda */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }
        .message-container h2 {
            color: #0056b3;
            margin-bottom: 10px;
        }
        .message-container p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .back-btn {
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <div class='message-container'>
        <h2>Penolakan Berita Acara</h2>
        <p>Berita Acara ditolak. Silakan periksa kembali data Anda.</p>
        <a href='index.php' class='back-btn'>Kembali</a>
    </div>
</body>
</html>";
    }

    $conn->close();
}
?>