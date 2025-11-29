-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Nov 2025 pada 13.01
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indomaret_rpl4`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_produk` smallint(4) NOT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `sub_total` mediumint(8) DEFAULT NULL,
  `harga_satuan` int(6) NOT NULL,
  `diskon` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_transaksi`, `id_produk`, `kuantitas`, `sub_total`, `harga_satuan`, `diskon`) VALUES
(35314, 1, 3, 25840, 9500, 0),
(35314, 2, 1, 8300, 8300, 0),
(35314, 3, 1, 27300, 27300, 0),
(35314, 4, 1, 8300, 8300, 0),
(35314, 5, 1, 21500, 21500, 0),
(35314, 6, 1, 22500, 22500, 0),
(35315, 1, 1, 6840, 9500, 0),
(35315, 7, 1, 30500, 30500, 0),
(35317, 1, 2, 13680, 9500, 0),
(35317, 5, 1, 21500, 21500, 0),
(35317, 9, 10, 28000, 3500, 0),
(35318, 7, 1, 30500, 30500, 0),
(35318, 8, 3, 33750, 12500, 0),
(35319, 2, 5, 41500, 8300, 0),
(35319, 9, 20, 56000, 3500, 0),
(35320, 3, 3, 81900, 27300, 0),
(35320, 9, 10, 28000, 2800, 0),
(35321, 3, 2, 54600, 27300, 0),
(35321, 21, 1, 15000, 15000, 0),
(35322, 1, 9, 85500, 9500, 0),
(35322, 2, 2, 16600, 8300, 0),
(35322, 27, 3, 71400, 23800, 0),
(35325, 1, 2, 9500, 0, 19000),
(35325, 7, 2, 30500, 0, 61000),
(35326, 9, 3, 11200, 20, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasir`
--

CREATE TABLE `kasir` (
  `id` char(3) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `Status` enum('Aktif','Tidak Aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kasir`
--

INSERT INTO `kasir` (`id`, `nama`, `Status`) VALUES
('01', 'RAHMA', 'Aktif'),
('02', 'TANTOWI', 'Aktif'),
('03', 'GANGGA', 'Aktif'),
('04', 'KAI', 'Tidak Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` smallint(4) NOT NULL,
  `id_voucher` char(6) DEFAULT NULL,
  `nama` varchar(35) DEFAULT NULL,
  `harga_satuan` int(6) DEFAULT NULL,
  `stok` smallint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `id_voucher`, `nama`, `harga_satuan`, `stok`) VALUES
(1, NULL, 'KOPIKO CANDY PCK 150 NEW', 9500, 57),
(2, NULL, 'NBATI RCHOCO WFR 127', 8300, 88),
(3, NULL, 'GILLETE CATR. VECTOR2', 27300, 295),
(4, NULL, 'KOPIKO CANDY PCK 8\'S', 8300, 100),
(5, NULL, 'ROMANO CLGN UNO 100ML', 21500, 240),
(6, NULL, 'RXNA MEN DEO ULTR 45', 22500, 89),
(7, NULL, 'HANDBODY CITRA 250ML FRESH', 30500, 248),
(8, 'VCH002', 'PEPSODENT WHITE 190G', 12500, 150),
(9, 'VCH003', 'INDOMIE GORENG 85G', 3500, 487),
(21, NULL, 'SilverQueen 65g', 15000, 135),
(24, 'VCH005', 'Teh Botol Sosro 350ml', 4500, 300),
(27, 'VCH004', 'Sunsilk Shampoo 170ml', 28000, 124);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode` varchar(10) DEFAULT NULL,
  `total` int(8) DEFAULT NULL,
  `id_kasir` char(3) DEFAULT NULL,
  `bayar` int(7) NOT NULL DEFAULT 0,
  `kembalian` int(5) NOT NULL DEFAULT 0,
  `status` enum('Sudah Bayar','Belum Bayar') NOT NULL DEFAULT 'Belum Bayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `tanggal`, `kode`, `total`, `id_kasir`, `bayar`, `kembalian`, `status`) VALUES
(35314, '2025-11-12 00:47:21', '35314', 113740, '01', 120000, 6260, 'Sudah Bayar'),
(35315, '2025-08-24 05:53:27', '35315', 37340, '02', 0, 0, 'Belum Bayar'),
(35317, '2025-08-24 05:53:27', '35317', 63180, '02', 0, 0, 'Belum Bayar'),
(35318, '2025-08-24 05:53:27', '35318', 64250, '03', 0, 0, 'Belum Bayar'),
(35319, '2025-08-24 05:53:27', '35319', 97500, '01', 0, 0, 'Belum Bayar'),
(35320, '2025-10-20 03:56:58', 'TRX0020', 109900, '01', 0, 0, 'Belum Bayar'),
(35321, '2025-10-20 03:56:39', 'TRX0021', 69600, '03', 0, 0, 'Belum Bayar'),
(35322, '2025-10-22 00:10:17', 'TRX0001', 173500, '01', 0, 0, 'Belum Bayar'),
(35325, '2025-10-29 01:02:16', 'TRX00022', 70500, '02', 100000, 60000, 'Sudah Bayar'),
(35326, '2025-10-29 01:20:14', 'TRX0001', 11200, '02', 15000, 3800, 'Sudah Bayar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `voucher`
--

CREATE TABLE `voucher` (
  `id` char(6) NOT NULL,
  `nama` varchar(35) DEFAULT NULL,
  `diskon` double DEFAULT NULL,
  `maks_diskon` int(7) DEFAULT NULL,
  `tanggal_kadaluarsa` datetime DEFAULT NULL,
  `status` enum('active','not_active') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `voucher`
--

INSERT INTO `voucher` (`id`, `nama`, `diskon`, `maks_diskon`, `tanggal_kadaluarsa`, `status`) VALUES
('VCH001', 'Diskon Kopiko', 28, 10000, '2025-12-31 23:59:59', 'active'),
('VCH002', 'Diskon Pepsodent 10%', 10, 5000, '2025-12-31 23:59:59', 'active'),
('VCH003', 'Diskon Indomie 20%', 20, 2000, '2025-12-31 23:59:59', 'active'),
('VCH004', 'diiskon Sunsillk', 15, 10000, '2026-09-16 11:16:09', 'active'),
('VCH005', 'Teh Botol', 5, 5000, '2027-09-23 11:16:09', 'active');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_transaksi`,`id_produk`),
  ADD KEY `id_transaksi` (`id_transaksi`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_voucher` (`id_voucher`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id_kasir`) USING BTREE;

--
-- Indeks untuk tabel `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35327;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_voucher`) REFERENCES `voucher` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_kasir`) REFERENCES `kasir` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
