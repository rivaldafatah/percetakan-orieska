-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Agu 2024 pada 21.01
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
(4, 'PGLRN-00004', 'kertas', 500, 'lembar', '500000.00', '2024-08-17 14:14:07');

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
(2, 'BHN-00002', 'tinta', 'ini tinta', 1521, '2024-07-12 19:24:03', '2024-08-17 11:37:25'),
(3, 'BHN-00003', 'luster', 'luster banner', 1400, '2024-07-16 01:11:42', '2024-08-17 11:37:25'),
(4, 'BHN-00004', 'Karton', 'Karton', 1860, '2024-07-16 01:12:12', '2024-08-17 11:37:25'),
(5, 'BHN-00005', 'Easy Banner', 'ini esay banner', 1812, '2024-07-16 01:13:17', '2024-08-16 17:48:08'),
(6, 'BHN-00006', 'tumbler', 'tumbler', 1500, '2024-07-16 01:14:32', '2024-08-16 17:48:16'),
(7, 'BHN-00007', 'Kartu Laminasi', 'ini kartu laminasi', 2890, '2024-07-16 01:15:18', '2024-08-16 17:48:23'),
(8, 'BHN-00008', 'Kertas HVS A4 70 Gram', 'A4', 6680, '2024-07-16 01:16:10', '2024-08-16 17:48:30'),
(9, 'BHN-00009', 'Kertas HVS polio 80 gram', 'hvs', 6180, '2024-07-16 01:16:45', '2024-08-16 17:48:36'),
(10, 'BHN-00010', 'Master Plat S', 'Ini Master Plat untuk membuat cetakan kertas', 20, '2024-08-16 17:46:24', '2024-08-16 17:49:29'),
(11, 'BHN-00011', 'perekat kertas', 'ini adalah perekat kertas', 20, '2024-08-17 13:39:27', '2024-08-17 13:39:27');

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
(2, 10, 'ORD-00002', '20000000.00', '2024-08-17 13:09:17', '2024-08-17 13:47:17', '', '', 'bri', 'shipped', 'JNT - 123123424234', '15000.00', '', NULL, NULL);

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
(2, 2, 9, 100, '200000.00', 'kartu undangan desain.jpg', 'mau pesen');

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
(29, 'PRD-00010', 'nasi padang', 'sfsdfdsfsdf', '10000.00', 'fotooo.jpg', '2024-08-16 13:49:59', 0, '2 hari setelah pesanan disetujui', 1),
(30, 'PRD-00011', 'Dus Kemasan Obat', 'Dus Kemasan obat ukuran 10x 15 cm 100 box', '4000.00', 'dus obat.jpg', '2024-08-17 07:31:32', 1, '2 hari setelah pesanan disetujui', 100),
(31, 'PRD-00012', 'klimir', 'ini klimir', '20000.00', 'kartu undangan desain.jpg', '2024-08-17 12:12:49', 0, '2 hari setelah pesanan disetujui', 10),
(32, 'PRD-00013', 'testing', 'ininiiniin', '10000.00', 'dus obat.jpg', '2024-08-17 13:56:56', 0, '2 hari setelah pesanan disetujui', 1);

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
(43, 29, 9),
(44, 29, 7),
(49, 30, 3),
(50, 30, 4),
(51, 31, 10),
(52, 31, 9),
(53, 31, 5),
(54, 32, 1);

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
(3, 'REQ-00003', 'kertass', 500, 'lembar', '2024-08-17 14:11:05');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `user_id`, `retur_code`, `reason`, `proof_image`, `status`, `created_at`) VALUES
(1, 1, 9, 'RTR-00001', 'ini rusak', 'barangrusak.jpg', 'returned', '2024-08-17 13:59:12');

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
(1, 1, 'RESHIP-00001', 'rivalda', 'Kampung Coblong No 60B Rt 03 / Rw 14 Desa Sukamenak Kecamatan Margahayu Kabupaten Bandung', 'jnt', '2024-08-17 14:00:51');

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
(2, 'USR-00002', 'dadan', '$2y$10$Y/67McbKW.CE97poVmrrxeR7NjPr/lDi1VUFjvA24nucxaRpmFj6q', 'dadanhernawan70@gmail.com', 'pemilik', '2024-08-17 11:05:19', 1, NULL),
(3, 'USR-00003', 'laras', '$2y$10$4wVtwuFCoiZbYAch4b6cK.qFOFWW9pNeo55QM/7AAI0wiEWCicelC', 'laras@gmail.com', 'bagian_keuangan', '2024-08-17 11:06:10', 1, NULL),
(4, 'USR-00004', 'mega', '$2y$10$AtuZSOQriukCgYIXHP8SnOIrWWuLVuEmPRu5zNAetsxvFJ1mtsOKy', 'mega@gmail.com', 'bagian_praproduksi', '2024-08-17 11:06:28', 1, NULL),
(5, 'USR-00005', 'babeh', '$2y$10$57Q6ex2ScRp/T46OVGJRM.JqdqsFJFRtuKbRjPFDJawRdCJPYLU5y', 'babeh@gmail.com', 'bagian_produksi', '2024-08-17 11:06:47', 1, NULL),
(7, 'USR-00007', 'pahri', '$2y$10$Yx7jXEes.J0RY10AO5pbHeKnu4rCMVZOAKhGyy6XMQLAD0ijVCifu', 'pahri@gmail.com', 'bagian_pengiriman', '2024-08-17 11:07:45', 1, NULL),
(9, 'USR-00009', 'rivalda', '$2y$10$9YMLJGKmylMgTRB8bdEY/.77e.qmCI/ottcC3j3nIVPQ6E/7tG/iq', 'rivaldarafalrachman2016@gmail.com', 'individual', '2024-08-17 11:34:38', 1, '9c8db5c803a9e4871e4ce742996de463'),
(10, 'USR-00010', 'tasya', '$2y$10$pbomQniCGwWS1pOF2kF7cutBilXzaxlfzyr6YI/t6a8HRFGbtwuV.', 'tasyaajaaa284@gmail.com', 'company', '2024-08-17 13:08:14', 1, 'ef0ffcd68d6062f4ffc5b69f90fd8de3'),
(11, 'USR-00011', 'alvaro', '$2y$10$87o7RZLkdWDfyMLTNde80u9zX2IM1uE5IWpZP0fr0pFbNDFH3NmA2', 'alvarowinata27@gmail.com', 'company', '2024-08-17 14:04:27', 1, '7ee5f819b7c7b9be6d24bfd772c69fe2'),
(12, 'USR-00012', 'ilmi', '$2y$10$BKxd5ObMLzpfvjcEOogGBOU0HhBKyUtTWsuPuNyUKXONROTifWWS.', 'ilmi@gmail.com', 'bagian_praproduksi', '2024-08-17 14:07:00', 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT untuk tabel `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `return_shipments`
--
ALTER TABLE `return_shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
