-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 04:07 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `harga` varchar(200) NOT NULL,
  `jumlah` varchar(200) NOT NULL,
  `ppn` varchar(200) NOT NULL,
  `total` varchar(500) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `tgl_exp` date NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `harga` varchar(50) NOT NULL,
  `jumlah` varchar(50) NOT NULL,
  `sub_harga` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `deskripsi` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `harga_jual` int(11) NOT NULL,
  `tipe` varchar(200) NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `tgl_exp` date NOT NULL,
  `no_batch` varchar(200) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `no_nota` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `nama_obat` varchar(200) NOT NULL,
  `stok` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tempat_penyimpanan`
--

CREATE TABLE `tempat_penyimpanan` (
  `id_tempat` int(11) NOT NULL,
  `nama_tempat` varchar(200) NOT NULL,
  `deskripsi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_penjualan` int(11) NOT NULL,
  `tgl` varchar(50) NOT NULL,
  `nama_pelanggan` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `sales_id` int(11) DEFAULT NULL,
  `instansi` varchar(200) NOT NULL,
  `provinsi` varchar(50) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `kecamatan` varchar(50) NOT NULL,
  `kelurahan` varchar(50) NOT NULL,
  `kode_pos` varchar(50) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `no_nota` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `nohp` varchar(200) NOT NULL,
  `total` int(50) NOT NULL,
  `jatuh_tempo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_pembayaran`
--

CREATE TABLE `transaksi_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `nominal` varchar(200) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nohp` int(11) NOT NULL,
  `role` enum('ceo','purchasing','akuntansi','gudang','sales','pelanggan') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `provinsi` varchar(50) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `kecamatan` varchar(50) NOT NULL,
  `kelurahan` varchar(50) NOT NULL,
  `kode_pos` varchar(50) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `sipa` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama_lengkap`, `username`, `password`, `nohp`, `role`, `created_at`, `provinsi`, `kota`, `kecamatan`, `kelurahan`, `kode_pos`, `alamat`, `sipa`) VALUES
(1, 'ceo', 'ceo', '001', 0, 'ceo', '2024-12-02 14:59:10', '', '', '', '', '', '', ''),
(2, 'purchasing', 'purchasing', '002', 0, 'purchasing', '2024-12-05 09:28:16', '', '', '', '', '', '', ''),
(3, 'gudang', 'gudang', '003', 0, 'gudang', '2024-12-17 09:09:21', '', '', '', '', '', '', ''),
(4, 'akuntansi', 'akuntansi', '004', 0, 'akuntansi', '2024-12-17 09:13:43', '', '', '', '', '', '', ''),
(5, 'sales', 'sales', '005', 0, 'sales', '2024-12-17 09:32:17', '', '', '', '', '', '', '');

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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `transaksi_pembayaran`
--
ALTER TABLE `transaksi_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `absensi_pulang`
--
ALTER TABLE `absensi_pulang`
  MODIFY `idplg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tempat_penyimpanan`
--
ALTER TABLE `tempat_penyimpanan`
  MODIFY `id_tempat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `terima`
--
ALTER TABLE `terima`
  MODIFY `id_terima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `transaksi_pembayaran`
--
ALTER TABLE `transaksi_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
