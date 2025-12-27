-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 27, 2025 at 02:43 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_retensi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_service`
--

CREATE TABLE `customer_service` (
  `id_cs` int NOT NULL,
  `id_produk` int NOT NULL,
  `id_kanal` int NOT NULL,
  `id_tim` int NOT NULL,
  `nik` varchar(50) NOT NULL,
  `nama_cs` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_service`
--

INSERT INTO `customer_service` (`id_cs`, `id_produk`, `id_kanal`, `id_tim`, `nik`, `nama_cs`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2345', 'Ro\'fatul Fuad', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(2, 1, 2, 1, '2346', 'Jihan Chaerunisa', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(3, 1, 2, 1, '2347', 'Indry Faradilla', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(4, 1, 2, 1, '2348', 'Evin Tri Hapsari', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(5, 1, 2, 1, '2349', 'Wanto', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(6, 1, 2, 1, '2350', 'Nisa Riftianah', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(7, 1, 2, 1, '2351', 'Musta\'in Amri', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(8, 1, 2, 1, '2352', 'Dinda Cynthia Rizky Yuli', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(9, 1, 2, 1, '2353', 'Tia Monika Sari Saragih', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(10, 1, 2, 1, '2354', 'Niki Puja Pratini', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(11, 1, 2, 1, '2355', 'Mayang Purwati', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(12, 1, 2, 1, '2356', 'Inarotul Ummah', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(13, 1, 2, 1, '2357', 'Septi Prihatini', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(14, 1, 2, 1, '2358', 'Novita Anggraeni', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(15, 1, 2, 1, '2359', 'Etha Putri Wandani', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(16, 2, 1, 2, '2360', 'Eka Rismawati', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(17, 2, 1, 2, '2361', 'Eva Andrianingsih', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(18, 2, 1, 2, '2362', 'Anita Cahya Istifarin', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(19, 2, 1, 2, '2363', 'Septiadi Nugroho', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(20, 2, 1, 2, '2364', 'Siti Cholifah', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(21, 2, 1, 2, '2365', 'Muhamad Fajri Juliardhi', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(22, 2, 1, 2, '2366', 'Intan Indri Fitriani', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(23, 2, 1, 2, '2367', 'Citra Dewi', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(24, 2, 1, 2, '2368', 'Sabrina Ashafahani Afrialitha', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(25, 2, 1, 2, '2369', 'Amalia Kresnawijaya', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
(26, 2, 1, 2, '2370', 'Rizky Millynia Yanuar', '2025-12-21 15:59:18', '2025-12-21 15:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `kanal`
--

CREATE TABLE `kanal` (
  `id_kanal` int NOT NULL,
  `nama_kanal` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kanal`
--

INSERT INTO `kanal` (`id_kanal`, `nama_kanal`, `created_at`, `updated_at`) VALUES
(1, 'Meta', '2025-12-21 15:51:37', '2025-12-21 15:51:37'),
(2, 'WhatsApp', '2025-12-21 15:51:44', '2025-12-21 15:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `konversi`
--

CREATE TABLE `konversi` (
  `id_konversi` int NOT NULL,
  `id_cs` int NOT NULL,
  `id_sub_kriteria` int NOT NULL,
  `id_range` int DEFAULT NULL,
  `nilai_asli` decimal(10,2) NOT NULL COMMENT 'Nilai asli sebelum konversi',
  `nilai_konversi` decimal(5,2) NOT NULL COMMENT 'Nilai setelah konversi/gap',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `konversi`
--

INSERT INTO `konversi` (`id_konversi`, `id_cs`, `id_sub_kriteria`, `id_range`, `nilai_asli`, `nilai_konversi`, `created_at`, `updated_at`) VALUES
(388, 1, 1, 5, 6612.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(389, 1, 2, 8, 92.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(390, 1, 3, 13, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(391, 1, 4, 16, 5.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(392, 2, 1, 2, 85994.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(393, 2, 2, 9, 71.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(394, 2, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(395, 2, 4, 18, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(396, 3, 1, 5, 20201.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(397, 3, 2, 10, 60.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(398, 3, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(399, 3, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(400, 4, 1, 2, 97498.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(401, 4, 2, 7, 107.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(402, 4, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(403, 4, 4, 19, 1.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(404, 5, 1, 5, 8277.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(405, 5, 2, 10, 63.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(406, 5, 3, 13, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(407, 5, 4, 16, 6.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(408, 6, 1, 3, 70406.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(409, 6, 2, 10, 16.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(410, 6, 3, 15, 6.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(411, 6, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(412, 7, 1, 3, 78667.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(413, 7, 2, 7, 100.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(414, 7, 3, 14, 4.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(415, 7, 4, 18, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(416, 8, 1, 1, 105274.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(417, 8, 2, 10, 68.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(418, 8, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(419, 8, 4, 16, 5.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(420, 9, 1, 5, 23885.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(421, 9, 2, 10, 56.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(422, 9, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(423, 9, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(424, 10, 1, 5, 7502.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(425, 10, 2, 10, 68.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(426, 10, 3, 14, 5.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(427, 10, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(428, 11, 1, 5, 29513.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(429, 11, 2, 10, 2.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(430, 11, 3, 15, 6.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(431, 11, 4, 19, 1.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(432, 12, 1, 1, 114818.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(433, 12, 2, 9, 77.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(434, 12, 3, 13, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(435, 12, 4, 16, 5.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(436, 13, 1, 4, 39134.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(437, 13, 2, 10, 3.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(438, 13, 3, 11, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(439, 13, 4, 18, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(440, 14, 1, 4, 30979.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(441, 14, 2, 10, 19.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(442, 14, 3, 14, 5.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(443, 14, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(444, 15, 1, 1, 104821.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(445, 15, 2, 9, 80.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(446, 15, 3, 12, 1.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(447, 15, 4, 18, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(448, 16, 1, 2, 97202.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(449, 16, 2, 10, 1.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(450, 16, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(451, 16, 4, 18, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(452, 17, 1, 4, 33825.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(453, 17, 2, 8, 89.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(454, 17, 3, 15, 6.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(455, 17, 4, 16, 6.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(456, 18, 1, 2, 91771.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(457, 18, 2, 10, 1.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(458, 18, 3, 13, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(459, 18, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(460, 19, 1, 4, 47843.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(461, 19, 2, 10, 25.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(462, 19, 3, 14, 4.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(463, 19, 4, 17, 4.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(464, 20, 1, 3, 56460.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(465, 20, 2, 6, 110.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(466, 20, 3, 13, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(467, 20, 4, 18, 2.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(468, 21, 1, 4, 42389.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(469, 21, 2, 10, 20.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(470, 21, 3, 11, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(471, 21, 4, 16, 5.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(472, 22, 1, 3, 74949.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(473, 22, 2, 9, 77.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(474, 22, 3, 12, 1.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(475, 22, 4, 16, 5.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(476, 23, 1, 5, 29054.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(477, 23, 2, 10, 36.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(478, 23, 3, 12, 1.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(479, 23, 4, 18, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(480, 24, 1, 1, 109783.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(481, 24, 2, 8, 89.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(482, 24, 3, 13, 3.00, 3.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(483, 24, 4, 16, 5.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(484, 25, 1, 2, 85340.00, 4.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(485, 25, 2, 9, 70.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(486, 25, 3, 14, 4.00, 2.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(487, 25, 4, 16, 6.00, 1.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(488, 26, 1, 1, 140000.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(489, 26, 2, 6, 120.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(490, 26, 3, 11, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(491, 26, 4, 20, 0.00, 5.00, '2025-12-22 13:44:47', '2025-12-22 13:44:47');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `jenis_kriteria` enum('core_factor','secondary_factor') NOT NULL,
  `deskripsi` text,
  `status_approval` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama_kriteria`, `bobot`, `jenis_kriteria`, `deskripsi`, `status_approval`, `approved_by`, `approved_at`, `rejection_note`, `created_at`, `updated_at`) VALUES
(1, 'P1', 'Performa', 90.00, 'core_factor', '', 'approved', 2, '2025-12-27 03:24:11', 'fawf', '2025-12-21 14:35:40', '2025-12-27 14:34:34'),
(2, 'K1', 'Kedisiplinan', 10.00, 'secondary_factor', '', 'approved', 2, '2025-12-27 03:20:48', NULL, '2025-12-21 14:35:48', '2025-12-27 03:20:48');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL,
  `id_cs` int NOT NULL,
  `id_sub_kriteria` int NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `periode` varchar(20) NOT NULL COMMENT 'Format: YYYY-MM',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_cs`, `id_sub_kriteria`, `nilai`, `periode`, `created_at`, `updated_at`) VALUES
(386, 1, 1, 6612.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(387, 1, 2, 92.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(388, 1, 3, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(389, 1, 4, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(390, 2, 1, 85994.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(391, 2, 2, 71.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(392, 2, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(393, 2, 4, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(394, 3, 1, 20201.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(395, 3, 2, 60.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(396, 3, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(397, 3, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(398, 4, 1, 97498.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(399, 4, 2, 107.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(400, 4, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(401, 4, 4, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(402, 5, 1, 8277.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(403, 5, 2, 63.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(404, 5, 3, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(405, 5, 4, 6.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(406, 6, 1, 70406.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(407, 6, 2, 16.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(408, 6, 3, 6.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(409, 6, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(410, 7, 1, 78667.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(411, 7, 2, 100.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(412, 7, 3, 4.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(413, 7, 4, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(414, 8, 1, 105274.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(415, 8, 2, 68.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(416, 8, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(417, 8, 4, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(418, 9, 1, 23885.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(419, 9, 2, 56.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(420, 9, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(421, 9, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(422, 10, 1, 7502.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(423, 10, 2, 68.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(424, 10, 3, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(425, 10, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(426, 11, 1, 29513.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(427, 11, 2, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(428, 11, 3, 6.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(429, 11, 4, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(430, 12, 1, 114818.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(431, 12, 2, 77.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(432, 12, 3, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(433, 12, 4, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(434, 13, 1, 39134.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(435, 13, 2, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(436, 13, 3, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(437, 13, 4, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(438, 14, 1, 30979.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(439, 14, 2, 19.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(440, 14, 3, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(441, 14, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(442, 15, 1, 104821.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(443, 15, 2, 80.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(444, 15, 3, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(445, 15, 4, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(446, 16, 1, 97202.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(447, 16, 2, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(448, 16, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(449, 16, 4, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(450, 17, 1, 33825.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(451, 17, 2, 89.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(452, 17, 3, 6.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(453, 17, 4, 6.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(454, 18, 1, 91771.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(455, 18, 2, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(456, 18, 3, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(457, 18, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(458, 19, 1, 47843.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(459, 19, 2, 25.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(460, 19, 3, 4.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(461, 19, 4, 4.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(462, 20, 1, 56460.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(463, 20, 2, 110.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(464, 20, 3, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(465, 20, 4, 2.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(466, 21, 1, 42389.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(467, 21, 2, 20.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(468, 21, 3, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(469, 21, 4, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(470, 22, 1, 74949.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(471, 22, 2, 77.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(472, 22, 3, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(473, 22, 4, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(474, 23, 1, 29054.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(475, 23, 2, 36.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(476, 23, 3, 1.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(477, 23, 4, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(478, 24, 1, 109783.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(479, 24, 2, 89.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(480, 24, 3, 3.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(481, 24, 4, 5.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(482, 25, 1, 85340.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(483, 25, 2, 70.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(484, 25, 3, 4.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(485, 25, 4, 6.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(486, 26, 1, 140000.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(487, 26, 2, 120.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(488, 26, 3, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19'),
(489, 26, 4, 0.00, '2025-12', '2025-12-22 13:39:19', '2025-12-22 13:39:19');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_user` int NOT NULL,
  `id_atasan` int DEFAULT NULL,
  `nik` varchar(50) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','junior_manager','supervisor','leader') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_user`, `id_atasan`, `nik`, `nama_pengguna`, `email`, `password`, `level`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ADM001', 'Administrator', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-12-21 14:34:46', '2025-12-21 14:34:46'),
(2, NULL, '1223', 'Junior Manager', 'jmtest@example.com', '$2y$10$tPO86v6aOryjCjjVowjaWeU0Pf3qgFVXFMQZwfMe7suXV9Q49fdsu', 'junior_manager', '2025-12-21 15:52:24', '2025-12-21 15:52:24'),
(3, 2, '1224', 'Super Visior', 'spv@example.com', '$2y$10$hV2vOlthHPDQm8qQIj68oOD8SvX4/XZPtIf9Drndk4plnLcLQjLpK', 'supervisor', '2025-12-21 15:52:52', '2025-12-21 15:52:52'),
(4, NULL, '1225', 'Berliana', 'berliana@example.com', '$2y$10$IiaIgCBL9gryDnG68ICF..KfhGPDVfR3mARLHVAL2s7lXPDcn73z.', 'leader', '2025-12-21 15:53:11', '2025-12-27 13:39:40'),
(5, NULL, '1226', 'Joko', 'joko@example.com', '$2y$10$cx.Fu1EZtI1EqrhWGI31eeuM./gSN4bcmj77epZNY6ahQ/eiU2pD6', 'leader', '2025-12-21 15:53:32', '2025-12-21 15:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `sku_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `sku_produk`, `nama_produk`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'BIO-01', 'Bio Insuleaf', '', 'f4e6586f9dafdb3bc5b1f87a9c565716.png', '2025-12-21 15:50:59', '2025-12-21 15:50:59'),
(2, 'NUT-01', 'Nutriflakes', '', '100e99dc644284eac4f24c4e5da1d586.jpg', '2025-12-21 15:51:19', '2025-12-21 15:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `range`
--

CREATE TABLE `range` (
  `id_range` int NOT NULL,
  `id_sub_kriteria` int NOT NULL,
  `batas_atas` decimal(10,2) DEFAULT NULL COMMENT 'NULL for open range (≥)',
  `batas_bawah` decimal(10,2) DEFAULT NULL COMMENT 'NULL for open range (≤)',
  `nilai_range` decimal(5,2) NOT NULL COMMENT 'Nilai konversi untuk range ini (1-5)',
  `keterangan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `range`
--

INSERT INTO `range` (`id_range`, `id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 100000.00, 5.00, 'Sangat Baik', '2025-12-21 14:37:23', '2025-12-21 14:37:36'),
(2, 1, 99999.00, 80000.00, 4.00, 'Baik', '2025-12-21 15:02:25', '2025-12-21 15:02:25'),
(3, 1, 79999.00, 50000.00, 3.00, 'Cukup', '2025-12-21 15:03:55', '2025-12-21 15:03:55'),
(4, 1, 49999.00, 30000.00, 2.00, 'Kurang', '2025-12-21 15:04:24', '2025-12-21 15:04:24'),
(5, 1, 29999.00, NULL, 1.00, 'Sangat Kurang', '2025-12-21 15:16:30', '2025-12-21 15:16:30'),
(6, 2, NULL, 110.00, 5.00, 'Sangat Baik', '2025-12-21 15:21:03', '2025-12-21 15:21:03'),
(7, 2, 109.00, 100.00, 4.00, 'Baik', '2025-12-21 15:21:25', '2025-12-21 15:21:25'),
(8, 2, 99.00, 85.00, 3.00, 'Cukup', '2025-12-21 15:21:55', '2025-12-21 15:21:55'),
(9, 2, 84.00, 70.00, 2.00, 'Kurang', '2025-12-21 15:22:47', '2025-12-21 15:22:47'),
(10, 2, 69.00, NULL, 1.00, 'Sangat Kurang', '2025-12-21 15:23:08', '2025-12-21 15:23:08'),
(11, 3, 0.00, 0.00, 5.00, 'Sangat Baik', '2025-12-21 15:26:12', '2025-12-21 15:26:12'),
(12, 3, 1.00, 1.00, 4.00, 'Baik', '2025-12-21 15:26:39', '2025-12-21 15:26:39'),
(13, 3, 3.00, 2.00, 3.00, 'Cukup', '2025-12-21 15:28:44', '2025-12-21 15:28:44'),
(14, 3, 5.00, 4.00, 2.00, 'Kurang', '2025-12-21 15:29:12', '2025-12-21 15:29:12'),
(15, 3, NULL, 5.00, 1.00, 'Sangat Kurang', '2025-12-21 15:30:13', '2025-12-21 15:30:13'),
(16, 4, NULL, 5.00, 1.00, 'Sangat Kurang', '2025-12-21 15:47:00', '2025-12-21 15:47:00'),
(17, 4, 5.00, 4.00, 2.00, 'Kurang', '2025-12-21 15:48:53', '2025-12-21 15:48:53'),
(18, 4, 3.00, 2.00, 3.00, 'Cukup', '2025-12-21 15:49:14', '2025-12-22 13:21:25'),
(19, 4, 1.00, 1.00, 4.00, 'Baik', '2025-12-21 15:49:35', '2025-12-21 15:49:35'),
(20, 4, 0.00, 0.00, 5.00, 'Sangat Baik', '2025-12-21 15:49:50', '2025-12-21 15:49:50');

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE `ranking` (
  `id_ranking` int NOT NULL,
  `id_produk` int NOT NULL,
  `id_cs` int NOT NULL COMMENT 'Foreign key ke customer_service',
  `nilai_akhir` decimal(10,2) NOT NULL COMMENT 'Hasil akhir perhitungan Profile Matching',
  `peringkat` int NOT NULL,
  `periode` varchar(20) NOT NULL COMMENT 'Format: YYYY-MM atau custom',
  `status` enum('draft','pending_leader','approved_leader','rejected_leader','pending_supervisor','approved_supervisor','rejected_supervisor','published','archived') NOT NULL DEFAULT 'draft',
  `approved_by_leader` int DEFAULT NULL,
  `approved_at_leader` timestamp NULL DEFAULT NULL,
  `leader_note` text,
  `approved_by_supervisor` int DEFAULT NULL,
  `approved_at_supervisor` timestamp NULL DEFAULT NULL,
  `supervisor_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ranking`
--

INSERT INTO `ranking` (`id_ranking`, `id_produk`, `id_cs`, `nilai_akhir`, `peringkat`, `periode`, `status`, `approved_by_leader`, `approved_at_leader`, `leader_note`, `approved_by_supervisor`, `approved_at_supervisor`, `supervisor_note`, `created_at`, `updated_at`) VALUES
(183, 2, 26, 5.00, 1, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(184, 1, 4, 3.95, 2, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(185, 2, 20, 3.90, 3, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(186, 2, 24, 3.80, 4, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(187, 1, 15, 3.50, 5, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(188, 1, 7, 3.40, 6, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(189, 1, 12, 3.35, 7, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(190, 1, 2, 3.00, 8, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(191, 1, 8, 2.90, 9, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(192, 2, 25, 2.85, 10, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(193, 2, 18, 2.65, 11, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(194, 2, 16, 2.55, 12, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(195, 2, 22, 2.50, 13, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(196, 2, 17, 2.35, 14, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(197, 1, 6, 2.10, 15, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(198, 1, 1, 2.00, 16, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(199, 1, 13, 1.75, 17, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(200, 1, 14, 1.70, 18, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(201, 2, 21, 1.65, 19, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(202, 2, 19, 1.55, 20, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(203, 1, 9, 1.30, 21, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(204, 1, 3, 1.30, 22, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(205, 1, 10, 1.25, 23, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(206, 2, 23, 1.25, 24, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(207, 1, 11, 1.15, 25, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47'),
(208, 1, 5, 1.10, 26, '2025-12', 'published', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 13:44:47', '2025-12-22 13:44:47');

-- --------------------------------------------------------

--
-- Table structure for table `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `nama_sub_kriteria` varchar(100) NOT NULL,
  `bobot_sub` decimal(5,2) NOT NULL,
  `target` decimal(5,2) NOT NULL COMMENT 'Nilai target/standar yang diharapkan',
  `keterangan` text,
  `status_approval` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `nama_sub_kriteria`, `bobot_sub`, `target`, `keterangan`, `status_approval`, `approved_by`, `approved_at`, `rejection_note`, `created_at`, `updated_at`) VALUES
(1, 1, 'KPI (Key Perfomance Indeks)', 50.00, 0.00, '', 'draft', NULL, NULL, NULL, '2025-12-21 14:36:00', '2025-12-21 14:36:00'),
(2, 1, 'Rasio Ketercapaian Target', 40.00, 0.00, '', 'draft', NULL, NULL, NULL, '2025-12-21 14:36:11', '2025-12-21 14:36:11'),
(3, 2, 'Absensi', 5.00, 0.00, '', 'draft', NULL, NULL, NULL, '2025-12-21 14:36:29', '2025-12-21 14:36:29'),
(4, 2, 'Keterlambatan', 5.00, 0.00, '', 'draft', NULL, NULL, NULL, '2025-12-21 14:36:41', '2025-12-21 14:36:41');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_scope`
--

CREATE TABLE `supervisor_scope` (
  `id_scope` int NOT NULL,
  `id_supervisor` int NOT NULL,
  `id_kanal` int NOT NULL,
  `id_produk` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supervisor_scope`
--

INSERT INTO `supervisor_scope` (`id_scope`, `id_supervisor`, `id_kanal`, `id_produk`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2025-12-21 15:52:52', '2025-12-21 15:52:52'),
(2, 3, 1, 2, '2025-12-21 15:52:52', '2025-12-21 15:52:52'),
(3, 3, 2, 1, '2025-12-21 15:52:52', '2025-12-21 15:52:52'),
(4, 3, 2, 2, '2025-12-21 15:52:52', '2025-12-21 15:52:52');

-- --------------------------------------------------------

--
-- Table structure for table `tim`
--

CREATE TABLE `tim` (
  `id_tim` int NOT NULL,
  `id_leader` int DEFAULT NULL,
  `id_supervisor` int DEFAULT NULL,
  `nama_tim` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tim`
--

INSERT INTO `tim` (`id_tim`, `id_leader`, `id_supervisor`, `nama_tim`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 'Tim Alpha', '2025-12-21 15:53:55', '2025-12-21 15:53:55'),
(2, 5, 3, 'Tim Beta', '2025-12-21 15:54:04', '2025-12-21 15:54:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_service`
--
ALTER TABLE `customer_service`
  ADD PRIMARY KEY (`id_cs`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `fk_cs_produk` (`id_produk`),
  ADD KEY `fk_cs_kanal` (`id_kanal`),
  ADD KEY `fk_cs_tim` (`id_tim`),
  ADD KEY `idx_cs_tim` (`id_tim`);

--
-- Indexes for table `kanal`
--
ALTER TABLE `kanal`
  ADD PRIMARY KEY (`id_kanal`);

--
-- Indexes for table `konversi`
--
ALTER TABLE `konversi`
  ADD PRIMARY KEY (`id_konversi`),
  ADD KEY `fk_konversi_cs` (`id_cs`),
  ADD KEY `fk_konversi_subkriteria` (`id_sub_kriteria`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD UNIQUE KEY `kode_kriteria` (`kode_kriteria`),
  ADD KEY `fk_kriteria_approver` (`approved_by`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `fk_nilai_cs` (`id_cs`),
  ADD KEY `fk_nilai_subkriteria` (`id_sub_kriteria`),
  ADD KEY `idx_periode` (`periode`),
  ADD KEY `idx_nilai_periode` (`created_at`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_pengguna_level` (`level`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `sku_produk` (`sku_produk`);

--
-- Indexes for table `range`
--
ALTER TABLE `range`
  ADD PRIMARY KEY (`id_range`),
  ADD KEY `fk_range_subkriteria` (`id_sub_kriteria`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_ranking`),
  ADD KEY `fk_ranking_produk` (`id_produk`),
  ADD KEY `fk_ranking_cs` (`id_cs`),
  ADD KEY `idx_periode` (`periode`),
  ADD KEY `idx_peringkat` (`peringkat`),
  ADD KEY `fk_ranking_leader` (`approved_by_leader`),
  ADD KEY `fk_ranking_supervisor` (`approved_by_supervisor`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `fk_subkriteria_kriteria` (`id_kriteria`),
  ADD KEY `fk_subkriteria_approver` (`approved_by`);

--
-- Indexes for table `supervisor_scope`
--
ALTER TABLE `supervisor_scope`
  ADD PRIMARY KEY (`id_scope`),
  ADD KEY `fk_scope_supervisor` (`id_supervisor`),
  ADD KEY `fk_scope_kanal` (`id_kanal`),
  ADD KEY `fk_scope_produk` (`id_produk`),
  ADD KEY `idx_scope_composite` (`id_supervisor`,`id_produk`,`id_kanal`);

--
-- Indexes for table `tim`
--
ALTER TABLE `tim`
  ADD PRIMARY KEY (`id_tim`),
  ADD KEY `fk_tim_leader` (`id_leader`),
  ADD KEY `fk_tim_supervisor` (`id_supervisor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_service`
--
ALTER TABLE `customer_service`
  MODIFY `id_cs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `kanal`
--
ALTER TABLE `kanal`
  MODIFY `id_kanal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `konversi`
--
ALTER TABLE `konversi`
  MODIFY `id_konversi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=492;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=490;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `range`
--
ALTER TABLE `range`
  MODIFY `id_range` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id_ranking` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supervisor_scope`
--
ALTER TABLE `supervisor_scope`
  MODIFY `id_scope` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tim`
--
ALTER TABLE `tim`
  MODIFY `id_tim` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_service`
--
ALTER TABLE `customer_service`
  ADD CONSTRAINT `fk_cs_kanal` FOREIGN KEY (`id_kanal`) REFERENCES `kanal` (`id_kanal`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cs_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cs_tim` FOREIGN KEY (`id_tim`) REFERENCES `tim` (`id_tim`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `konversi`
--
ALTER TABLE `konversi`
  ADD CONSTRAINT `fk_konversi_cs` FOREIGN KEY (`id_cs`) REFERENCES `customer_service` (`id_cs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_konversi_subkriteria` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD CONSTRAINT `fk_kriteria_approver` FOREIGN KEY (`approved_by`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `fk_nilai_cs` FOREIGN KEY (`id_cs`) REFERENCES `customer_service` (`id_cs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nilai_subkriteria` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `range`
--
ALTER TABLE `range`
  ADD CONSTRAINT `fk_range_subkriteria` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `fk_ranking_cs` FOREIGN KEY (`id_cs`) REFERENCES `customer_service` (`id_cs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ranking_leader` FOREIGN KEY (`approved_by_leader`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ranking_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ranking_supervisor` FOREIGN KEY (`approved_by_supervisor`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `fk_subkriteria_approver` FOREIGN KEY (`approved_by`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_subkriteria_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supervisor_scope`
--
ALTER TABLE `supervisor_scope`
  ADD CONSTRAINT `fk_scope_kanal` FOREIGN KEY (`id_kanal`) REFERENCES `kanal` (`id_kanal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scope_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scope_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `pengguna` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tim`
--
ALTER TABLE `tim`
  ADD CONSTRAINT `fk_tim_leader` FOREIGN KEY (`id_leader`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tim_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
