-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2026 at 10:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sarana`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspirasis`
--

CREATE TABLE `aspirasis` (
  `id_aspirasi` int(10) UNSIGNED NOT NULL,
  `id_pelaporan` int(10) UNSIGNED NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `status` enum('Menunggu','Proses','Selesai') NOT NULL DEFAULT 'Menunggu',
  `feedback` text DEFAULT NULL COMMENT 'Catatan tindakan dari teknisi/admin',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aspirasis`
--

INSERT INTO `aspirasis` (`id_aspirasi`, `id_pelaporan`, `id_kategori`, `status`, `feedback`, `created_at`, `updated_at`) VALUES
(4, 4, 2, 'Selesai', 'done.', '2026-04-08 08:50:10', '2026-04-08 08:52:32'),
(5, 5, 3, 'Proses', 'Kursi sedang diganti baru, mohon ditunggu.', '2026-04-08 08:57:35', '2026-04-08 08:58:20'),
(6, 6, 6, 'Menunggu', NULL, '2026-04-08 09:31:05', '2026-04-08 09:31:05'),
(7, 7, 8, 'Selesai', 'done', '2026-04-08 09:42:25', '2026-04-08 09:43:06'),
(8, 8, 5, 'Proses', 'menunggu', '2026-04-08 11:26:20', '2026-04-08 11:26:45');

-- --------------------------------------------------------

--
-- Table structure for table `input_aspirasis`
--

CREATE TABLE `input_aspirasis` (
  `id_pelaporan` int(10) UNSIGNED NOT NULL,
  `nis` varchar(10) NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `lampiran` varchar(500) DEFAULT NULL COMMENT 'Path file foto lampiran',
  `ket` varchar(500) NOT NULL COMMENT 'Keterangan / deskripsi kerusakan',
  `urgensi` enum('Mendesak','Standar','Rendah') NOT NULL DEFAULT 'Standar',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `input_aspirasis`
--

INSERT INTO `input_aspirasis` (`id_pelaporan`, `nis`, `id_kategori`, `lokasi`, `lampiran`, `ket`, `urgensi`, `created_at`, `updated_at`) VALUES
(4, '12345', 2, 'A.2.1', 'lampiran/1775613010_69d5b4523fde0.jpg', 'Kerusakan kabel listrik', 'Mendesak', '2026-04-08 08:50:10', '2026-04-08 08:50:10'),
(5, '098765', 3, 'F.2.1', 'lampiran/1775613455_69d5b60faa1e0.jpg', 'Ada kursi yang patah', 'Standar', '2026-04-08 08:57:35', '2026-04-08 08:57:35'),
(6, '098765', 6, 'C.2.4', 'lampiran/1775615465_69d5bde963afe.jpg', 'Proyektor rusak', 'Mendesak', '2026-04-08 09:31:05', '2026-04-08 09:31:05'),
(7, '098765', 8, 'C.2.2', 'lampiran/1775616145_69d5c0915ef59.jpg', 'Kunci pintu rusak, tidak bisa dikunci', 'Mendesak', '2026-04-08 09:42:25', '2026-04-08 09:42:25'),
(8, '098765', 5, 'P.2.1', 'lampiran/1775622379_69d5d8ebe20d2.jpg', 'RUSAK', 'Mendesak', '2026-04-08 11:26:20', '2026-04-08 11:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `kategoris`
--

CREATE TABLE `kategoris` (
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `ket_kategori` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategoris`
--

INSERT INTO `kategoris` (`id_kategori`, `ket_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Kebersihan', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(2, 'Kelistrikan', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(3, 'Meja & Kursi', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(4, 'Toilet', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(5, 'AC / Kipas Angin', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(6, 'Proyektor / LCD', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(7, 'Komputer / Lab', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(8, 'Jendela / Pintu', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(9, 'Lapangan / Area Olahraga', '2026-04-07 19:22:39', '2026-04-07 19:22:39'),
(10, 'Lain-lain', '2026-04-07 19:22:39', '2026-04-07 19:22:39');

-- --------------------------------------------------------

--
-- Table structure for table `siswas`
--

CREATE TABLE `siswas` (
  `nis` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswas`
--

INSERT INTO `siswas` (`nis`, `nama`, `kelas`, `password`, `created_at`, `updated_at`) VALUES
('098765', 'Kanaya Quinn', 'XII DKV 2', '$2y$10$OAKt9UcrGK//1glCbir2quqFPYmpdAfaOKkeReyBTkTsVNQZg632O', '2026-04-08 08:47:02', '2026-04-08 11:26:19'),
('12345', 'Jalu Zakaria Anwar', 'XII RPL 3', '$2y$10$JoWxW5V9sqnXbsq4pbdbyeqrhxc0OCxJSlbeNAqRuiLfSCKhPDY7i', '2026-04-08 08:47:02', '2026-04-08 08:50:10'),
('5555', 'Muhammad Resha', 'XII RPL 2', '$2y$10$KP2tZb6fBIiWP8N06FCAvud/oWXGBajsEDhgH672Qwk3dCd3PTU4W', '2026-04-08 09:13:57', '2026-04-08 09:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Menunggu','Proses','Setujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$lJoEglBWfPAoDKVgK4ljZelDg/SHoyQZW7e3dHqLqYYXPMOFodJDi', 'Setujui', '2026-04-07 19:22:39', '2026-04-07 20:19:49'),
(2, 'admin2', '$2y$10$Xcax.fOKgBRcYvq0BlKu.u8whJCbiqG/VoisgO7kORNFv3L/Tfj9W', 'Proses', '2026-04-07 20:57:00', '2026-04-07 20:57:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aspirasis`
--
ALTER TABLE `aspirasis`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD UNIQUE KEY `aspirasis_id_pelaporan_unique` (`id_pelaporan`) COMMENT 'Satu laporan hanya boleh punya satu record aspirasi',
  ADD KEY `asp_id_kategori_idx` (`id_kategori`),
  ADD KEY `asp_status_idx` (`status`);

--
-- Indexes for table `input_aspirasis`
--
ALTER TABLE `input_aspirasis`
  ADD PRIMARY KEY (`id_pelaporan`),
  ADD KEY `ia_nis_idx` (`nis`),
  ADD KEY `ia_id_kategori_idx` (`id_kategori`),
  ADD KEY `ia_created_at_idx` (`created_at`);

--
-- Indexes for table `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `kategoris_ket_kategori_unique` (`ket_kategori`);

--
-- Indexes for table `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aspirasis`
--
ALTER TABLE `aspirasis`
  MODIFY `id_aspirasi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `input_aspirasis`
--
ALTER TABLE `input_aspirasis`
  MODIFY `id_pelaporan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id_kategori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasis`
--
ALTER TABLE `aspirasis`
  ADD CONSTRAINT `fk_asp_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategoris` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asp_pelaporan` FOREIGN KEY (`id_pelaporan`) REFERENCES `input_aspirasis` (`id_pelaporan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `input_aspirasis`
--
ALTER TABLE `input_aspirasis`
  ADD CONSTRAINT `fk_ia_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategoris` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ia_nis` FOREIGN KEY (`nis`) REFERENCES `siswas` (`nis`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
