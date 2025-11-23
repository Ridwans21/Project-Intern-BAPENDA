<?php
// Mulai sesi
session_start();

// Koneksi database
$conn = new mysqli("localhost", "root", "", "bap_system");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>
