-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Jul 2024 pada 17.37
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
  `material_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `expense_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `expenses`
--

INSERT INTO `expenses` (`id`, `material_name`, `quantity`, `unit`, `cost`, `expense_date`) VALUES
(1, 'kertas stiker', 20, 'lembar', '5000.00', '2024-07-12 19:21:25'),
(2, 'tinta', 20, 'botol', '10000.00', '2024-07-12 19:21:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `description`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 'kertas stiker', 'ini kertas stiker', 500, '2024-07-12 19:23:46', '2024-07-22 17:28:52'),
(2, 'tinta', 'ini tinta', 200, '2024-07-12 19:24:03', '2024-07-16 04:19:51'),
(3, 'luster', 'luster banner', 500, '2024-07-16 01:11:42', '2024-07-16 01:11:42'),
(4, 'Karton', 'Karton', 300, '2024-07-16 01:12:12', '2024-07-16 04:19:51'),
(5, 'Easy Banner', 'ini esay banner', 800, '2024-07-16 01:13:17', '2024-07-22 13:44:41'),
(6, 'tumbler', 'tumbler', 300, '2024-07-16 01:14:32', '2024-07-16 01:14:32'),
(7, 'Kartu Laminasi', 'ini kartu laminasi', 3000, '2024-07-16 01:15:18', '2024-07-16 01:15:18'),
(8, 'Kertas HVS A4 70 Gram', 'A4', 10000, '2024-07-16 01:16:10', '2024-07-16 01:16:10'),
(9, 'Kertas HVS polio 80 gram', 'hvs', 8000, '2024-07-16 01:16:45', '2024-07-16 01:16:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) NOT NULL,
  `shipping_method` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `tracking_number` varchar(255) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `created_at`, `updated_at`, `address`, `shipping_method`, `payment_method`, `status`, `tracking_number`, `payment_proof`) VALUES
(1, 16, '350000.00', '2024-07-22 13:43:36', '2024-07-22 14:47:20', 'Saya pesan untuk undangan', 'kirim', 'bca', 'return_rejected', '', 'bukti bayar toefl.jpg'),
(2, 3, '20000000.00', '2024-07-22 17:06:16', '2024-07-22 17:32:25', '', '', 'bri', 'return_rejected', '21413224', 'bukti bayar toefl.jpg');

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
(1, 1, 1, 100, '3500.00', 'kartu undangan desain.jpg', NULL),
(2, 2, 9, 100, '200000.00', 'Array', 'pesan sensdndfdkgfgfghgh');

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

--
-- Dumping data untuk tabel `pertanyaan`
--

INSERT INTO `pertanyaan` (`id`, `nama`, `email`, `deskripsi`, `tanggal`) VALUES
(1, 'Rivalda', 'rivaldarafalrachman2016@gmail.com', 'Bisakah saya memesan percetakan disini dengan harga murah?', '2024-07-06 17:43:16'),
(2, 'Yoga', 'yoga@gmail.com', 'apakah bisa membuat nota disini?', '2024-07-07 13:34:16'),
(3, 'Dimas', 'dimas@gmail.com', 'apakah saya bisa melakukan pencetakan brosur disini dengan harga murah??????', '2024-07-16 04:46:00'),
(4, 'rizki pahlevi', 'pahlevi@gmail.com', 'apakah apakahhhh', '2024-07-16 04:46:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company` tinyint(1) DEFAULT 0,
  `estimasi_pengerjaan` varchar(50) DEFAULT NULL,
  `min_order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`, `company`, `estimasi_pengerjaan`, `min_order`) VALUES
(1, 'Kartu Undangan A4', 'Kartu undangan dengan ukuran a4 bisa dilipat 3\r\nper pcs = 10.000 (minimum order 100 pcs)', '3500.00', 'undangan.jpg', '2024-07-07 17:53:54', 0, '2 HARI', 100),
(2, 'Banner 1 X 2', 'Banner berbahan Platisol berukuran 1x2 meter \r\nminimum order 1 pcs', '45000.00', 'banner1.jpg', '2024-07-07 18:51:06', 0, '1 HARI', 1),
(3, 'Kartu Nama (Branded Desain)', 'Kartu Nama Berukuran e-KTP dengan desain brandid\r\n1 box isi 100 pcs = 300.000', '300000.00', 'kartunama.jpg', '2024-07-07 18:53:41', 0, '1 -2 HARI', 1),
(4, 'X-Banner 2 x 1', 'X Banner Berukuran 2 X 1 meter dengan berbahan luster\r\nLuster adalah bahan kertas yang kerap jadi favorit dalam industri percetakan, berkat kualitasnya yang memukau dalam mencetak foto resolusi tinggi.', '75000.00', 'Xbanner.jpg', '2024-07-07 18:59:10', 0, '1 HARI', 1),
(5, 'Stiker Vinyl A3', 'Stiker berbahan vinyl berukuran A3 laminasi glossy/doff\r\nper pcs  = 5.000 minimum order 5 lembar A3', '5000.00', 'Stiker.jpg', '2024-07-07 19:02:06', 0, '1 HARI', 2),
(6, 'Brosur Lipat A4', 'Berukuran A4 bisa lipat 3, HVS A4 70 gr 70 gsm 70gsm 70gram NATURAL\r\nminimum order 500 lembar', '43500.00', 'Brosur.jpg', '2024-07-07 19:05:00', 0, '3- 4 hari', 498),
(9, 'Map rumah sakit', 'ini adalah map', '200000.00', 'map.jpg', '2024-07-11 16:29:16', 1, '6 HARI', 100),
(11, 'tumbler custom desain', 'tumbler desain custom dibuat dengan bahan uv (minimal order 1 pcs botol)', '65000.00', 'tumbler.jpg', '2024-07-15 14:00:47', 0, '2 - 3 HARI', 1),
(12, 'Dus Kemasan Desain Cetakan', 'Dus kemasan makanan berukuran 14 x 14 x 6\r\nharga per pcs = Rp 7500 (minimal order 50 pcs)', '7500.00', 'dus.jpg', '2024-07-15 16:02:19', 0, '2 HARI', 50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `material_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `requests`
--

INSERT INTO `requests` (`id`, `material_name`, `quantity`, `unit`, `request_date`) VALUES
(1, 'kertas stiker', 20, 'lembar', '2024-07-12 19:20:12'),
(2, 'tinta', 2, 'botol', '2024-07-12 19:20:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `user_id`, `reason`, `proof_image`, `status`, `created_at`) VALUES
(1, 1, 16, 'Ada kesalahan ini pak', 'tumbler.jpg', 'approved', '2024-07-22 13:45:27'),
(2, 1, 16, 'ini ada error', 'james-chan-KWocLB1EHIc-unsplash.jpg', 'rejected', '2024-07-22 14:45:00'),
(3, 2, 3, 'ini kenapa ya', 'james-chan-KWocLB1EHIc-unsplash.jpg', 'rejected', '2024-07-22 17:31:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','individual','company','bagian_produksi','bagian_keuangan','bagian_pengiriman') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(3, 'limijati', '$2y$10$8afTRxXC/Qnby3iRoPB36OEp9IV/N/GdsIGiZNMaOqF7GEu0rGSBO', 'limijati@gmail.com', 'company', '2024-06-30 13:04:48'),
(7, 'alex', '$2y$10$9z2VjigD.YT.5v.bWHt3Hej6LEp1ToDWs6A5KSPbrRqps0jj1SBBO', 'alex@gmail.com', 'individual', '2024-06-30 19:36:59'),
(8, 'arto', '$2y$10$U1YSWaqb5SBTBQtWh0nk.eZogNJsbzAGTDuSybJQ6IpeyVnPnsMdm', 'arto@gmail.com', 'individual', '2024-06-30 19:39:38'),
(10, 'lia', '$2y$10$68j1Buf514ZzfK1qXuPmeuWz53n.6jruoxOajnwA8wyaVWDTTgQam', 'lia@gmail.com', 'individual', '2024-06-30 19:43:14'),
(13, 'Amalio', '$2y$10$87Ut9HKQxFuN.JPUQewyN./rsqxp2bw5o3VrYxj5WADZ87TRAkHnm', 'amalio@gmail.com', 'company', '2024-07-01 19:38:30'),
(14, 'ilmi', '$2y$10$cRt4v5kecHtXRA34muSuvOoj1cZaqyUs00pTLD0Ib7bVgbQDFWh5q', 'ilmi@gmail.com', 'individual', '2024-07-01 19:49:29'),
(15, 'planetban', '$2y$10$fwJxJFUjPd/OKnFKDFPmB.uNrm6Ewjbn/JXxnQqjJjd1DZyqyCIrm', 'planet@gmai.com', 'company', '2024-07-02 02:13:32'),
(16, 'najma', '$2y$10$xZqGSavgQlvaRWqDh.LwI.bZqLel10xGMbVgZWjhJFt.gnDEE997G', 'najma@gmail.com', 'individual', '2024-07-03 18:59:10'),
(17, 'admin', '$2y$10$v5L0LbhwpcnQwFlwDepRauglHzL.XfYX7.GuQluLORRMWIY28ldv6', 'admin@gmail.com', 'admin', '2024-07-03 20:30:03'),
(19, 'dalex', '$2y$10$raSlaAv5KZXu2lFfGxZGDucvGw028avaO48B/Kfnhl3ML7PYQxd.C', 'dalexganteng@gmail.com', 'admin', '2024-07-04 05:38:47'),
(20, 'Rivalda', '$2y$10$ynPeFF6CaTYJp/kwQjhSkuSIb1qwof8QZeVB61U6ZaWWSamVQlvm.', 'rivaldarafalrachman2016@gmail.com', 'admin', '2024-07-04 06:23:29'),
(22, 'Yoga', '$2y$10$8xcdCLyzm2GC5ezZhoZfZu4iClpnaI2Fq4houw6HDk91EY7G7AYMa', 'yoga@gmail.com', 'admin', '2024-07-07 12:13:49'),
(24, 'Ahas', '$2y$10$iJGtflursIQhcGsFdVWe0e/G9hCkvI9Vf.kbwuoyfeD.qLtHiZifW', 'ahas@gmail.com', 'company', '2024-07-10 11:17:45'),
(25, 'ikiganteng', '$2y$10$dFisyp5omz73BYMflgLg4ObojOVneQ18JSVDKm19Bg5XM/Al7QqNq', 'ikiganteng@gmail.com', 'admin', '2024-07-10 11:18:47'),
(26, 'laras', '$2y$10$Crkvw9ijzrRDWaXpKd6uwOCkdR1tBghJoHg4ORLeNhagydbc0/OJ2', 'laras@gmail.com', 'bagian_keuangan', '2024-07-12 15:33:22'),
(27, 'babeh', '$2y$10$cTVYHBj/g825rgTLONtD7OB/JAACKTzMFM8yoP4qwnVkMLAiZ7ivm', 'babeh@gmail.com', 'bagian_produksi', '2024-07-12 15:34:57'),
(28, 'pahri', '$2y$10$V1BL9vquNtmnqT.dZHD5Ou/ahp.CeXyhBEHjRda6awm5bWWL.K0iW', 'pahri@gmail.com', 'bagian_pengiriman', '2024-07-12 15:35:28'),
(29, 'nina', '$2y$10$4l.ZSKJ9URfR3VxXylVxMujXq431RSwrqZT/AIeD20Aky8krK21hm', 'ninanurhasanah968@gmail.com', 'individual', '2024-07-15 08:36:18'),
(30, 'mega', '$2y$10$qQElx2GSOS9WhfmS9AQh8e0aa6SXYnw7k1VKDqiaqSw.OyNvtE3zK', 'mega@gmail.com', 'bagian_produksi', '2024-07-19 14:01:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

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
-- Indeks untuk tabel `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
-- Ketidakleluasaan untuk tabel `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
