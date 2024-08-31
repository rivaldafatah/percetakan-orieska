-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Agu 2024 pada 22.27
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_orieska`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_code` varchar(20) DEFAULT NULL,
  `material_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `expense_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `expenses`
--

INSERT INTO `expenses` (`id`, `expense_code`, `material_name`, `quantity`, `unit`, `cost`, `expense_date`) VALUES
(1, 'PGLRN-00001', 'kertas stiker', 20, 'lembar', '5000.00', '2024-07-12 19:21:25'),
(2, 'PGLRN-00002', 'tinta', 20, 'botol', '10000.00', '2024-07-12 19:21:37'),
(3, 'PGLRN-00003', 'kertas f4', 500, 'lembar', '250000.00', '2024-08-16 18:09:18'),
(4, 'PGLRN-00004', 'kertas', 500, 'lembar', '500000.00', '2024-08-17 14:14:07'),
(5, 'PGLRN-00005', 'esteler', 500, 'rim', '20000.00', '2024-08-18 09:18:41'),
(6, 'PGLRN-00006', 'kertas A4 Foil', 1200, 'Rim', '200000.00', '2024-08-30 10:21:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `material_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `inventory`
--

INSERT INTO `inventory` (`id`, `material_code`, `name`, `description`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 'BHN-00001', 'kertas stiker', 'ini kertas stiker', 1980, '2024-07-12 19:23:46', '2024-08-16 17:47:36'),
(2, 'BHN-00002', 'tinta', 'ini tinta', 1400, '2024-07-12 19:24:03', '2024-08-30 15:17:13'),
(3, 'BHN-00003', 'luster', 'luster banner', 1400, '2024-07-16 01:11:42', '2024-08-17 11:37:25'),
(4, 'BHN-00004', 'Karton', 'Karton', 1850, '2024-07-16 01:12:12', '2024-08-18 10:22:59'),
(5, 'BHN-00005', 'Easy Banner', 'ini esay banner', 1702, '2024-07-16 01:13:17', '2024-08-30 15:17:13'),
(7, 'BHN-00007', 'Kartu Laminasi', 'ini kartu laminasi', 2890, '2024-07-16 01:15:18', '2024-08-16 17:48:23'),
(8, 'BHN-00008', 'Kertas HVS A4 70 Gram', 'A4', 6670, '2024-07-16 01:16:10', '2024-08-18 10:22:59'),
(9, 'BHN-00009', 'Kertas HVS polio 80 gram', 'hvs', 6180, '2024-07-16 01:16:45', '2024-08-16 17:48:36'),
(10, 'BHN-00010', 'Master Plat S', 'Ini Master Plat untuk membuat cetakan kertas', 20, '2024-08-16 17:46:24', '2024-08-16 17:49:29'),
(16, 'BHN-00012', 'thumbler 3', 'dfsdf', 400, '2024-08-18 10:12:43', '2024-08-25 09:42:27'),
(19, 'BHN-00019', 'testing 3', 'testing 3', 200, '2024-08-25 09:47:39', '2024-08-25 09:47:39'),
(20, 'BHN-00020', 'testing 20', 'testing', 100, '2024-08-25 09:48:42', '2024-08-25 09:48:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_code` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) NOT NULL,
  `shipping_method` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `tracking_number` varchar(255) DEFAULT NULL,
  `shipping_cost` decimal(10,2) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `rejection_notes` text DEFAULT NULL,
  `proofing_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `total`, `created_at`, `updated_at`, `address`, `shipping_method`, `payment_method`, `status`, `tracking_number`, `shipping_cost`, `payment_proof`, `rejection_notes`, `proofing_file`) VALUES
(1, 9, 'ORD-00001', '150000.00', '2024-08-17 11:35:53', '2024-08-17 14:02:09', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'kirim', 'bri', 'returned', 'JNT - 12231237923132', '12500.00', 'bukti.jpg', NULL, 'pexels-kovyrina-19342868.jpg'),
(2, 10, 'ORD-00002', '20000000.00', '2024-08-17 13:09:17', '2024-08-17 13:47:17', '', '', 'bri', 'shipped', 'JNT - 123123424234', '15000.00', '', NULL, NULL),
(3, 9, 'ORD-00003', '450000.00', '2024-08-18 08:19:39', '2024-08-18 11:43:05', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'kirim', 'bca', 'being_returned', '', '0.00', 'bukti.jpg', 'Gambar kurang jernih silahkan upload ulang file desain', 'james-chan-KWocLB1EHIc-unsplash (1).jpg'),
(4, 9, 'ORD-00004', '200000.00', '2024-08-18 11:28:35', '2024-08-18 11:28:35', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'kirim', 'bri', 'pending', NULL, NULL, 'bukti.jpg', NULL, NULL),
(5, 9, 'ORD-00005', '45000.00', '2024-08-18 20:34:19', '2024-08-30 15:33:56', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'ambil', 'bri', 'return_rejected', 'JNE - 1239817123', '12000.00', 'bukti.jpg', NULL, NULL),
(6, 9, 'ORD-00006', '45000.00', '2024-08-19 03:38:06', '2024-08-19 03:38:06', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'kirim', 'bca', 'pending', NULL, NULL, 'bukti.jpg', NULL, NULL),
(7, 9, 'ORD-00007', '45000.00', '2024-08-19 03:45:46', '2024-08-19 04:22:57', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'ambil', 'bri', 'completed', '', '0.00', 'bukti.jpg', NULL, NULL),
(8, 9, 'ORD-00008', '75000.00', '2024-08-30 15:02:28', '2024-08-31 20:22:11', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'kirim', 'bca', 'completed', '', '0.00', 'bukti.jpg', 'ini ditolak karena kamu gambarnya kurang bagus silahkan upload ulang dengan hd', 'kartunama.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `design_file` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `design_file`, `note`) VALUES
(1, 1, 28, 10, '15000.00', 'james-chan-KWocLB1EHIc-unsplash.jpg', NULL),
(2, 2, 9, 100, '200000.00', 'kartu undangan desain.jpg', 'mau pesen'),
(3, 3, 1, 100, '4500.00', 'kartu undangan desain (1).jpg', NULL),
(4, 4, 31, 10, '20000.00', 'pexels-kovyrina-19342868.jpg', NULL),
(5, 5, 2, 1, '45000.00', 'Xbanner.jpg', NULL),
(6, 6, 2, 1, '45000.00', 'Stiker.jpg', NULL),
(7, 7, 2, 1, '45000.00', 'kunnnnnnn.png', NULL),
(8, 8, 4, 1, '75000.00', 'dus obat.jpg', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company` tinyint(1) DEFAULT 0,
  `estimasi_pengerjaan` varchar(255) DEFAULT NULL,
  `min_order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `product_code`, `name`, `description`, `price`, `image`, `created_at`, `company`, `estimasi_pengerjaan`, `min_order`) VALUES
(1, 'PRD-00001', 'Kartu Undangan A4', 'Kartu undangan dengan ukuran a4 bisa dilipat 3\r\nper pcs = 10.000 (minimum order 100 pcs)', '4500.00', 'undangan.jpg', '2024-07-07 17:53:54', 0, '2 hari setelah pesanan disetujui', 100),
(2, 'PRD-00002', 'Banner 1 X 2', 'Banner berbahan Platisol berukuran 1x2 meter \r\nminimum order 1 pcs', '45000.00', 'banner1.jpg', '2024-07-07 18:51:06', 0, '1 hari setelah pesanan disetujui', 1),
(3, 'PRD-00003', 'Kartu Nama (Branded Desain)', 'Kartu Nama Berukuran e-KTP dengan desain brandid\r\n1 box isi 100 pcs = 300.000', '300000.00', 'kartunama.jpg', '2024-07-07 18:53:41', 0, '1 - 2 hari setelah pesanan disetujui', 1),
(4, 'PRD-00004', 'X-Banner 2 x 1', 'X Banner Berukuran 2 X 1 meter dengan berbahan luster\r\nLuster adalah bahan kertas yang kerap jadi favorit dalam industri percetakan, berkat kualitasnya yang memukau dalam mencetak foto resolusi tinggi.', '75000.00', 'Xbanner.jpg', '2024-07-07 18:59:10', 0, '1 hari setelah pesanan disetujui', 1),
(5, 'PRD-00005', 'Stiker Vinyl A3', 'Stiker berbahan vinyl berukuran A3 laminasi glossy/doff\r\nper pcs  = 5.000 minimum order 5 lembar A3', '5000.00', 'Stiker.jpg', '2024-07-07 19:02:06', 0, '1 hari setelah pesanan disetujui', 2),
(6, 'PRD-00006', 'Brosur Lipat A4', 'Berukuran A4 bisa lipat 3, HVS A4 70 gr 70 gsm 70gsm 70gram NATURAL\r\nminimum order 500 lembar', '25000.00', 'Brosur.jpg', '2024-07-07 19:05:00', 0, '1 - 2 hari setelah pesanan disetujui', 500),
(9, 'PRD-00007', 'Map rumah sakit', 'ini adalah map', '200000.00', 'map.jpg', '2024-07-11 16:29:16', 1, '6 hari setelah pesanan disetujui', 100),
(27, 'PRD-00008', 'esteh', 'ini estehmasibn', '10000.00', 'Salinan dari Black Doodle Tools for Generating Ideas Mind Map (1).png', '2024-08-07 09:04:08', 0, '2 hari setelah pesanan disetujui', 2),
(28, 'PRD-00009', 'Dus Kemasan', 'Dus Kemasan 12 dengan harga 15.000 Ukuran 10 x 15 CM', '15000.00', 'dus.jpg', '2024-08-16 13:24:39', 0, '2 hari setelah pesanan disetujui', 10),
(31, 'PRD-00012', 'klimir', 'ini klimir', '20000.00', 'kartu undangan desain.jpg', '2024-08-17 12:12:49', 0, '2 hari setelah pesanan disetujui', 10),
(34, 'PRD-00012', 'testing', 'testing', '120000.00', 'WhatsApp Image 2024-06-27 at 00.38.31_19890b3e.jpg', '2024-08-25 09:20:57', 1, '1 hari setelah pesanan disetujui', 1),
(35, 'PRD-00013', 'testing 2', 'testing 2', '12000.00', 'Salinan dari Black Doodle Tools for Generating Ideas Mind Map (1).png', '2024-08-25 09:21:33', 1, '1 hari setelah pesanan disetujui', 8),
(37, 'PRD-00037', 'testing 3', 'testing 3', '12000.00', 'WhatsApp Image 2024-06-27 at 00.38.31_19890b3e.jpg', '2024-08-25 09:38:12', 0, '1 hari setelah pesanan disetujui', 1),
(38, 'PRD-00038', 'Testing 123', 'ini adalah testing 123', '1200000.00', 'Salinan dari Black Doodle Tools for Generating Ideas Mind Map (1).png', '2024-08-25 15:30:59', 0, '1 hari setelah pesanan disetujui', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_materials`
--

CREATE TABLE `product_materials` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `product_materials`
--

INSERT INTO `product_materials` (`id`, `product_id`, `material_id`) VALUES
(15, 2, 5),
(16, 2, 2),
(17, 3, 2),
(18, 3, 8),
(19, 3, 3),
(20, 4, 5),
(21, 4, 2),
(22, 5, 1),
(23, 5, 2),
(24, 6, 8),
(25, 6, 2),
(26, 9, 4),
(27, 9, 2),
(31, 27, 5),
(32, 27, 9),
(33, 27, 7),
(34, 28, 4),
(35, 28, 2),
(36, 28, 3),
(40, 1, 8),
(41, 1, 4),
(42, 1, 2),
(51, 31, 10),
(52, 31, 9),
(53, 31, 5),
(63, 34, 16),
(64, 34, 2),
(65, 35, 10),
(66, 35, 5),
(68, 37, 1),
(81, 38, 1),
(82, 38, 3),
(83, 38, 4),
(84, 38, 16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `request_code` varchar(20) DEFAULT NULL,
  `material_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `requests`
--

INSERT INTO `requests` (`id`, `request_code`, `material_name`, `quantity`, `unit`, `request_date`) VALUES
(1, 'REQ-00001', 'karton', 20, 'lembar', '2024-08-12 23:30:01'),
(2, 'REQ-00002', 'luster', 100, 'lembar', '2024-08-16 18:00:27'),
(3, 'REQ-00003', 'kertass', 500, 'lembar', '2024-08-17 14:11:05'),
(4, 'REQ-00004', 'esteler', 20, 'rim', '2024-08-18 09:10:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `retur_code` varchar(20) NOT NULL,
  `reason` text DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','returned') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `user_id`, `retur_code`, `reason`, `proof_image`, `status`, `rejection_reason`, `created_at`) VALUES
(1, 1, 9, 'RTR-00001', 'ini rusak', 'barangrusak.jpg', 'returned', NULL, '2024-08-17 13:59:12'),
(2, 3, 9, 'RTR-00002', 'ini rusak', 'barangrusak.jpg', 'returned', NULL, '2024-08-18 08:34:21'),
(3, 3, 9, 'RTR-00003', 'rusak', 'barangrusak.jpg', 'returned', NULL, '2024-08-18 08:39:14'),
(4, 3, 9, 'RTR-00004', 'kjkaasdasd', 'barangrusak.jpg', 'returned', NULL, '2024-08-18 08:43:11'),
(5, 3, 9, 'RTR-00005', 'ini rusak', 'barangrusak.jpg', 'approved', NULL, '2024-08-18 11:36:38'),
(6, 5, 9, 'RTR-00006', 'rusak', 'barangrusak.jpg', 'approved', NULL, '2024-08-18 20:52:10'),
(7, 5, 9, 'RTR-00007', 'rusak', 'barangrusak.jpg', 'rejected', NULL, '2024-08-18 21:08:38'),
(8, 8, 9, 'RTR-00008', 'jalan cilisung kampung coblong', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 15:19:06'),
(9, 8, 9, 'RTR-00009', 'rusak', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 16:02:36'),
(10, 8, 9, 'RTR-00010', 'tes', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 16:07:17'),
(11, 8, 9, 'RTR-00011', 'tesssssss', 'dus obat.jpg', 'returned', NULL, '2024-08-30 17:26:03'),
(12, 8, 9, 'RTR-00012', '221212', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 17:27:45'),
(13, 8, 9, 'RTR-00013', 'testing 123455', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 17:33:38'),
(14, 8, 9, 'RTR-00014', 'disii', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 17:38:54'),
(15, 8, 9, 'RTR-00015', 'qwqw', 'barangrusak.jpg', 'returned', NULL, '2024-08-30 17:45:25'),
(16, 8, 9, 'RTR-00016', 'testing 20202020', 'barangrusak.jpg', 'returned', NULL, '2024-08-31 19:18:20'),
(17, 8, 9, 'RTR-00017', 'testing', 'barangrusak.jpg', 'returned', NULL, '2024-08-31 19:20:13'),
(18, 8, 9, 'RTR-00018', 'testing0000000', 'barangrusak.jpg', 'returned', NULL, '2024-08-31 19:27:04'),
(19, 8, 9, 'RTR-00019', 'lllll', 'barangrusak.jpg', 'returned', NULL, '2024-08-31 19:31:46'),
(20, 8, 9, 'RTR-00020', 'testing111111111', 'barangrusak.jpg', 'returned', 'barang seharusnya tidak seperti itu', '2024-08-31 19:47:14'),
(21, 8, 9, 'RTR-00021', 'asaa', 'barangrusak.jpg', 'returned', 'ini gaaaaaaaa', '2024-08-31 19:59:06'),
(22, 8, 9, 'RTR-00022', 'kksksksksksssss', 'dus obat.jpg', 'returned', NULL, '2024-08-31 20:02:58'),
(23, 8, 9, 'RTR-00023', 'sasaaaaaa', 'barangrusak.jpg', 'rejected', 'ini kesalahan dari kurir paket', '2024-08-31 20:04:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `return_shipments`
--

CREATE TABLE `return_shipments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `reship_code` varchar(20) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_address` text NOT NULL,
  `expedition` varchar(50) NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `return_shipments`
--

INSERT INTO `return_shipments` (`id`, `order_id`, `reship_code`, `sender_name`, `sender_address`, `expedition`, `return_date`) VALUES
(1, 1, 'RESHIP-00001', 'rivalda', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'jnt', '2024-08-17 14:00:51'),
(2, 3, 'RESHIP-00002', 'rivalda', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'jnt', '2024-08-18 08:43:29'),
(3, 3, 'RESHIP-00003', 'rivalda', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'anteraja', '2024-08-18 11:43:05'),
(4, 5, 'RESHIP-00004', 'rivalda', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'jnt', '2024-08-18 20:55:10'),
(5, 8, 'RESHIP-00005', 'rivalda', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'anteraja', '2024-08-30 15:20:12'),
(6, 8, 'RESHIP-00006', 'rivalda', 'sasas', 'jnt', '2024-08-31 20:03:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_code` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','individual','company','bagian_produksi','bagian_keuangan','bagian_pengiriman','bagian_praproduksi','pemilik') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `user_code`, `username`, `password`, `email`, `role`, `created_at`, `email_verified`, `verification_code`) VALUES
(1, 'USR-00001', 'admin', '$2y$10$RCdnJcjh8TyTPpVp0SXhU.pwQJPGh5rMMvR2yOvy6nALhFlZzQvaS', 'admin@gmail.com', 'admin', '2024-08-17 11:04:53', 1, NULL),
(2, 'USR-00002', 'dadan', '$2y$10$Y/67McbKW.CE97poVmrrxeR7NjPr/lDi1VUFjvA24nucxaRpmFj6q', 'danher@gmail.com', 'pemilik', '2024-08-17 11:05:19', 1, NULL),
(3, 'USR-00003', 'laras', '$2y$10$4wVtwuFCoiZbYAch4b6cK.qFOFWW9pNeo55QM/7AAI0wiEWCicelC', 'laras@gmail.com', 'bagian_keuangan', '2024-08-17 11:06:10', 1, NULL),
(4, 'USR-00004', 'mega', '$2y$10$AtuZSOQriukCgYIXHP8SnOIrWWuLVuEmPRu5zNAetsxvFJ1mtsOKy', 'mega@gmail.com', 'bagian_praproduksi', '2024-08-17 11:06:28', 1, NULL),
(5, 'USR-00005', 'babeh', '$2y$10$57Q6ex2ScRp/T46OVGJRM.JqdqsFJFRtuKbRjPFDJawRdCJPYLU5y', 'babeh@gmail.com', 'bagian_produksi', '2024-08-17 11:06:47', 1, NULL),
(7, 'USR-00007', 'pahri', '$2y$10$Yx7jXEes.J0RY10AO5pbHeKnu4rCMVZOAKhGyy6XMQLAD0ijVCifu', 'pahri@gmail.com', 'bagian_pengiriman', '2024-08-17 11:07:45', 1, NULL),
(9, 'USR-00009', 'rivalda', '$2y$10$9YMLJGKmylMgTRB8bdEY/.77e.qmCI/ottcC3j3nIVPQ6E/7tG/iq', 'rivaldarafalrachman2016@gmail.com', 'individual', '2024-08-17 11:34:38', 1, '9c8db5c803a9e4871e4ce742996de463'),
(10, 'USR-00010', 'tasya', '$2y$10$pbomQniCGwWS1pOF2kF7cutBilXzaxlfzyr6YI/t6a8HRFGbtwuV.', 'tasyaajaaa284@gmail.com', 'company', '2024-08-17 13:08:14', 1, 'ef0ffcd68d6062f4ffc5b69f90fd8de3'),
(12, 'USR-00012', 'ilmi', '$2y$10$BKxd5ObMLzpfvjcEOogGBOU0HhBKyUtTWsuPuNyUKXONROTifWWS.', 'ilmi@gmail.com', 'bagian_praproduksi', '2024-08-17 14:07:00', 0, NULL),
(14, 'USR-00014', 'danher', '$2y$10$8B1ACyZibka9eigLxbdaYe7ajmAoSgKXWsLViouvceF.Yo9oA.L0C', 'dadanhernawan70@gmail.com', 'company', '2024-08-18 07:53:46', 1, '5a9d7851d3fdcbade0817986c6e4a42b'),
(16, 'USR-00016', 'alvaro', '$2y$10$GhBXYyJsuYqTBwKa.Qi.Z..SN2cF5YWl/Mf3xu3j3izW5d4VlW1FS', 'alvarowinata27@gmail.com', 'individual', '2024-08-25 09:24:34', 1, 'e5f259c280eccc78d9d52c847b54216e');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_code` (`expense_code`);

--
-- Indeks untuk tabel `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `product_materials`
--
ALTER TABLE `product_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indeks untuk tabel `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_code` (`request_code`);

--
-- Indeks untuk tabel `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `return_shipments`
--
ALTER TABLE `return_shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_code` (`user_code`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT untuk tabel `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `return_shipments`
--
ALTER TABLE `return_shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `product_materials`
--
ALTER TABLE `product_materials`
  ADD CONSTRAINT `product_materials_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_materials_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `inventory` (`id`);

--
-- Ketidakleluasaan untuk tabel `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `return_shipments`
--
ALTER TABLE `return_shipments`
  ADD CONSTRAINT `return_shipments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
