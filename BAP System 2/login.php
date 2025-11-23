<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f2ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #0056b3;
            margin-bottom: 20px;
        }
        label {
            color: #333;
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #005bb5;
        }
        .error-message {
            color: red;
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php
    // Menampilkan pesan kesalahan jika ada
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'wrong_password') {
            echo '<div class="error-message">Password salah.</div>';
        } elseif ($_GET['error'] == 'user_not_found') {
            echo '<div class="error-message">Username tidak ditemukan.</div>';
        }
    }
    ?>
    <form method="POST" action="cek_login.php">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <input type="submit" value="Login" class="submit-btn">
    </form>
    <div class="footer">
        <p>Belum punya akun? <a href="register.php" style="color: #0073e6;">Daftar di sini</a></p>
    </div>
</div>

</body>
</html>
