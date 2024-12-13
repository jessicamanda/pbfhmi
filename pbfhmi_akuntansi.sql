-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 08:56 AM
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
-- Database: `pbfhmi_akuntansi`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `idx` int(10) NOT NULL,
  `nomor` int(5) NOT NULL,
  `akun` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `idx`, `nomor`, `akun`, `status`) VALUES
(0, 1, 102, 'Bank BCA', 1),
(0, 2, 103, 'Bank BRI', 1),
(0, 5, 100, 'Kas', 1),
(0, 6, 401, 'Pendapatan ', 1),
(0, 7, 501, 'Biaya Gaji Karyawan', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksibaru`
--

CREATE TABLE `transaksibaru` (
  `id` int(11) NOT NULL,
  `idtx` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `namaakun` varchar(100) NOT NULL,
  `debet` int(10) NOT NULL,
  `kredit` int(10) NOT NULL,
  `ket1` varchar(200) NOT NULL,
  `ket2` varchar(200) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksibaru`
--

INSERT INTO `transaksibaru` (`id`, `idtx`, `tgl`, `namaakun`, `debet`, `kredit`, `ket1`, `ket2`, `foto`) VALUES
(1, 1, '2024-02-06', 'Bank BCA', 500000, 0, 'test', 'test', 'Ban Bocor.png'),
(2, 1, '2024-02-06', 'Bank BCA', 0, 500000, 'test', 'test', 'Ban Bocor.png'),
(3, 2, '2023-01-19', 'Bank BRI', 1000000, 0, 'test', 'test', 'complete.png'),
(4, 2, '2023-01-19', 'Bank BRI', 0, 1000000, 'test', 'test', 'complete.png'),
(5, 3, '2024-12-13', 'Kas', 250000, 0, 'test', 'test', 'Ban Bocor.png'),
(6, 3, '2024-12-13', 'Kas', 0, 100000, 'test', 'test', 'Ban Bocor.png'),
(7, 4, '2024-11-30', 'Kas', 50000, 0, 'okee', 'okee', 'complete biru.png'),
(8, 4, '2024-11-30', 'Kas', 0, 25000, 'okee', 'okee', 'complete biru.png'),
(9, 5, '2024-12-01', 'Kas', 25000, 0, 'test kas', 'test kas', 'complete.png'),
(10, 5, '2024-12-01', 'Kas', 0, 10000, 'test kas', 'test kas', 'complete.png'),
(11, 6, '2024-12-01', 'Pendapatan ', 10000000, 0, 'Test Rekap Laba Rugi', 'Test Rekap Laba Rugi', 'complete.png'),
(12, 6, '2024-12-01', 'Pendapatan ', 0, 10000000, 'Test Rekap Laba Rugi', 'Test Rekap Laba Rugi', 'Ban Bocor.png'),
(13, 7, '2024-12-03', 'Biaya Gaji Karyawan', 2000000, 0, 'Test Gaji', 'Test Gaji', 'complete.png'),
(14, 7, '2024-12-03', 'Biaya Gaji Karyawan', 0, 2000000, 'Test Gaji', 'Test Gaji', 'complete.png'),
(15, 8, '2024-12-03', 'Bank BCA', 5000000, 0, 'Gaji Karyawan', 'Gaji Karyawan', 'Ban Bocor.png'),
(16, 8, '2024-12-03', 'Biaya Gaji Karyawan', 0, 2500000, 'Gaji Karyawan', 'Gaji Karyawan', 'Ban Bocor.png'),
(17, 9, '2024-11-22', 'Bank BRI', 50000000, 0, 'Gaji Karyawan', 'Gaji Karyawan', 'Ban Bocor.png'),
(18, 9, '2024-11-22', 'Biaya Gaji Karyawan', 0, 100000, 'Gaji Karyawan', 'Gaji Karyawan', 'Ban Bocor.png'),
(19, 10, '2024-11-28', 'Pendapatan ', 25000000, 0, 'Gaji Terakhir', 'Gaji Terakhir', 'Ban Bocor.png'),
(20, 10, '2024-11-28', 'Biaya Gaji Karyawan', 0, 1000000, 'Gaji Terakhir', 'Gaji Terakhir', 'Ban Bocor.png'),
(21, 11, '2024-12-04', 'Bank BRI', 2500000, 0, 'Test Pendapatan', 'Test Pendapatan', 'Ban Bocor.png'),
(22, 11, '2024-12-04', 'Pendapatan ', 0, 2500000, 'Test Pendapatan', 'Test Pendapatan', 'Ban Bocor.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`idx`);

--
-- Indexes for table `transaksibaru`
--
ALTER TABLE `transaksibaru`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaksibaru`
--
ALTER TABLE `transaksibaru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
