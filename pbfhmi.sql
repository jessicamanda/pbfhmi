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
-- Database: `pbfhmi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `tanggal_absen` date NOT NULL,
  `nama_karyawan` varchar(200) NOT NULL,
  `jam_kerja` time NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `telat_masuk` varchar(200) NOT NULL,
  `status_masuk` varchar(200) NOT NULL,
  `foto_absen` varchar(200) NOT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `tanggal_absen`, `nama_karyawan`, `jam_kerja`, `jam_masuk`, `jam_pulang`, `telat_masuk`, `status_masuk`, `foto_absen`, `latitude`, `longitude`) VALUES
(3, '2024-12-06', 'sales2', '00:00:00', '10:56:21', '00:00:00', '-25621', 'Telat', 'webcam-toy-photo2.jpg', NULL, NULL),
(6, '2024-12-10', 'sales', '00:00:00', '10:13:45', '00:00:00', '-21345', 'Telat', '17338003667041762499015742133882.jpg', '-8.1191099', '113.230183'),
(12, '2024-12-11', 'sales', '08:00:00', '10:05:09', '16:00:00', '-20509', 'Telat', '17338862960185570599615766023062.jpg', '-8.1192683', '113.2301836');

-- --------------------------------------------------------

--
-- Table structure for table `absensi_pulang`
--

CREATE TABLE `absensi_pulang` (
  `idplg` int(11) NOT NULL,
  `nama_lengkap` varchar(200) NOT NULL,
  `tanggal_absen_plg` date NOT NULL,
  `jam_pulang` time NOT NULL,
  `jam_kerja` varchar(200) NOT NULL,
  `foto_absen_plg` varchar(200) NOT NULL,
  `pulang_cepat` varchar(200) NOT NULL,
  `status_plg` varchar(200) NOT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi_pulang`
--

INSERT INTO `absensi_pulang` (`idplg`, `nama_lengkap`, `tanggal_absen_plg`, `jam_pulang`, `jam_kerja`, `foto_absen_plg`, `pulang_cepat`, `status_plg`, `latitude`, `longitude`) VALUES
(6, 'sales', '2024-12-11', '10:10:07', '08:00:00-16:00:00', 'Ban Bocor.png', '-21007', 'Pulang Cepat', '-8.1362944', '113.7278976');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `mq` varchar(200) NOT NULL,
  `margin` varchar(200) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `mq`, `margin`, `foto`, `created_at`, `updated_at`) VALUES
(23, 'asam mefenamat', '1', '7', 'CozyRoom (1).png', '2024-12-06 09:06:23', '2024-12-06 09:06:23'),
(24, 'paracetamol', '3', '7', '67525f1414f4b.jpg', '2024-12-06 09:07:33', '2024-12-06 09:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `nominal` varchar(200) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pembelian`, `tgl_bayar`, `nama_obat`, `nominal`, `foto`) VALUES
(32, 9, '2024-12-10', 'asam mefenamat', '3000000', 'checked-9-128.png'),
(33, 9, '2024-12-10', 'asam mefenamat', '24000', 'checked-9-128.png'),
(34, 10, '2024-12-11', 'paracetamol', '100000', 'WhatsApp Image 2024-09-05 at 10.16.56_621aca5f.jpg'),
(35, 10, '2024-12-11', 'paracetamol', '567890', 'benner.png');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `namasuplier` varchar(200) NOT NULL,
  `nohp` varchar(200) NOT NULL,
  `harga` varchar(200) NOT NULL,
  `jumlah` varchar(200) NOT NULL,
  `ppn` varchar(200) NOT NULL,
  `total` varchar(500) NOT NULL,
  `tipe` varchar(200) NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `tgl_exp` date NOT NULL,
  `no_batch` varchar(200) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `tgl`, `nama_obat`, `namasuplier`, `nohp`, `harga`, `jumlah`, `ppn`, `total`, `tipe`, `jatuh_tempo`, `tgl_exp`, `no_batch`, `status`, `created_at`, `updated_at`) VALUES
(9, '2024-12-10', 'asam mefenamat', 'yanto', '0987654321', '8000', '350', '8', '3024000', 'Non Pajak', '2024-12-31', '2024-12-31', '1', 'Sudah Datang', '2024-12-10 05:24:39', '0000-00-00 00:00:00'),
(12, '2024-12-11', 'paracetamol', 'yanto', '0987654321', '1000', '1', '9', '1090', 'Non Pajak', '2024-12-04', '2024-12-04', '1', 'Belum Datang', '2024-12-11 08:10:52', '2024-12-11 08:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `jam_kerja` varchar(200) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(500) NOT NULL,
  `nik` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `nama_lengkap`, `username`, `password`, `jam_masuk`, `jam_pulang`, `jam_kerja`, `tanggal_lahir`, `alamat`, `nik`) VALUES
(4, 'sales 1', 'sales1', 'sales1', '08:00:00', '16:00:00', '08:00:00-16:00:00', '1986-06-17', 'yoso', 987654321),
(5, 'sales', 'sales', 'sales', '08:00:00', '16:00:00', '08:00:00-16:00:00', '2024-12-06', 'lmj', 987654321),
(6, 'sales2', 'sales2', 'sales2', '10:49:00', '16:49:00', '', '2024-12-06', 'lmj', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `nama_obat` varchar(200) NOT NULL,
  `stok` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`nama_obat`, `stok`, `id`) VALUES
('asam mefenamat', 300, 10),
('paracetamol', 400, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tempat_penyimpanan`
--

CREATE TABLE `tempat_penyimpanan` (
  `id_tempat` int(11) NOT NULL,
  `nama_tempat` varchar(200) NOT NULL,
  `deskripsi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tempat_penyimpanan`
--

INSERT INTO `tempat_penyimpanan` (`id_tempat`, `nama_tempat`, `deskripsi`) VALUES
(2, 'kulkas', 'kk'),
(3, 'gudang', 'gudanggggg');

-- --------------------------------------------------------

--
-- Table structure for table `terima`
--

CREATE TABLE `terima` (
  `id_terima` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `tgl_terima` date NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `jumlah_terima` varchar(200) NOT NULL,
  `id_tempat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terima`
--

INSERT INTO `terima` (`id_terima`, `id_pembelian`, `tgl_terima`, `nama_obat`, `jumlah_terima`, `id_tempat`) VALUES
(78, 9, '2024-12-11', 'asam mefenamat', '10', 2),
(79, 9, '2024-12-11', 'asam mefenamat', '340', 3),
(80, 10, '2024-12-11', 'paracetamol', '490', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_penjualan` int(11) NOT NULL,
  `tgl` varchar(50) NOT NULL,
  `nama_pelanggan` varchar(200) NOT NULL,
  `instansi` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `no_nota` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `nohp` varchar(200) NOT NULL,
  `total` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_penjualan`, `tgl`, `nama_pelanggan`, `instansi`, `alamat`, `no_nota`, `status`, `nohp`, `total`) VALUES
(17, '2024-12-11', 'jessica', 'solv', 'yoso', '20241211045903', 'ACC', '081230812369', 32000),
(18, '2024-12-13', 'jessica', 'solv', 'yoso', '20241213032230', 'Diproses', '081230812369', 858000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_produk`
--

CREATE TABLE `transaksi_produk` (
  `no_nota` varchar(200) NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `jumlah` int(50) NOT NULL,
  `sub_total` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_produk`
--

INSERT INTO `transaksi_produk` (`no_nota`, `nama_obat`, `jumlah`, `sub_total`) VALUES
('20241211045903', 'asam mefenamat', 4, 32000),
('20241213032230', 'paracetamol', 90, 90000),
('20241213032230', 'asam mefenamat', 96, 768000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(13) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` enum('ceo','purchasing','akuntansi','gudang','sales','pelanggan') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama_lengkap`, `username`, `password`, `role`, `created_at`) VALUES
('1', 'ceo', 'ceo', '001', 'ceo', '2024-12-02 14:59:10'),
('1', 'ceo', 'ceo', '001', 'ceo', '2024-12-02 14:59:14'),
('2', 'sales', 'sales', 'sales', 'sales', '2024-12-05 09:28:16'),
('', 'saless', 'saless', 'saless', 'ceo', '2024-12-06 10:47:49'),
('', 'sales2', 'sales2', 'sales2', 'sales', '2024-12-06 10:49:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `absensi_pulang`
--
ALTER TABLE `absensi_pulang`
  ADD PRIMARY KEY (`idplg`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tempat_penyimpanan`
--
ALTER TABLE `tempat_penyimpanan`
  ADD PRIMARY KEY (`id_tempat`);

--
-- Indexes for table `terima`
--
ALTER TABLE `terima`
  ADD PRIMARY KEY (`id_terima`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `absensi_pulang`
--
ALTER TABLE `absensi_pulang`
  MODIFY `idplg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tempat_penyimpanan`
--
ALTER TABLE `tempat_penyimpanan`
  MODIFY `id_tempat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `terima`
--
ALTER TABLE `terima`
  MODIFY `id_terima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
