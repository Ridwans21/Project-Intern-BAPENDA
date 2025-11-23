<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bap_system");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah pengguna ada di database (admin atau staff)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Periksa password (tanpa hash, sesuai permintaan)
        if ($password === $user['password']) {
            // Set session berdasarkan peran
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: dashboard.php"); // Redirect ke dashboard admin
            } elseif ($user['role'] == 'staff') {
                header("Location: dashboard.php"); // Redirect ke dashboard staff
            }
            exit();
        } else {
            // Redirect ke halaman login dengan parameter error
            header("Location: login.php?error=wrong_password");
            exit();
        }
    } else {
        // Redirect ke halaman login dengan parameter error
        header("Location: login.php?error=user_not_found");
        exit();
    }
}
?>
