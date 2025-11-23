-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 25 Sep 2024 pada 06.27
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bap_system`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bap_entries`
--

CREATE TABLE `bap_entries` (
  `id` int(11) NOT NULL,
  `nop` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_bayar` varchar(255) NOT NULL,
  `berita` text NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL,
  `ktp` varchar(255) DEFAULT NULL,
  `surat_pernyataan` varchar(255) DEFAULT NULL,
  `surat_permohonan` varchar(255) DEFAULT NULL,
  `document_uploaded` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bap_entries`
--

INSERT INTO `bap_entries` (`id`, `nop`, `nik`, `nama`, `no_bayar`, `berita`, `status`, `ktp`, `surat_pernyataan`, `surat_permohonan`, `document_uploaded`) VALUES
(27, '012', '012', 'nama', '00', '11', 'Approved', 'photo_2024-09-25_09-48-50 (3).jpg', 'photo_2024-09-25_09-48-50 (2).jpg', 'photo_2024-09-25_09-48-50.jpg', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('staff','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'rozaan', '12345', 'admin'),
(2, 'Key', 'key', 'admin'),
(3, 'admin', '$2y$10$8PD1HTpXwDZTtCDSImij5edzSZgrfFHdzRNT7Aj/aEFh9usnS7H0W', 'admin'),
(4, 'Kukuh', 'kukuh', ''),
(5, 'Jamet', '$2y$10$rAA1StGKcraNS3Kx3NL6p.vrrb5xoukdeple3CoisuU4Aedi6qoee', ''),
(6, 'syahri', '12345', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bap_entries`
--
ALTER TABLE `bap_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bap_entries`
--
ALTER TABLE `bap_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
