-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2025 at 01:52 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mieme`
--

-- --------------------------------------------------------

--
-- Table structure for table `chart`
--

CREATE TABLE `chart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `kode_menu` varchar(50) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `kode_menu` varchar(10) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` enum('Makanan','Minuman','Dessert') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `gambar` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`kode_menu`, `nama_menu`, `kategori`, `harga`, `gambar`, `deskripsi`) VALUES
('DST001', 'Tart', 'Dessert', '20000.00', 'tart.jpg', 'Kue tart lezat dengan lapisan krim lembut dan topping buah segar.'),
('DST002', 'kue strowberry', 'Dessert', '15000.00', 'Kue.jpg', 'Kue lembut dengan rasa manis strawberry yang menyegarkan.'),
('MKN001', 'Mie', 'Makanan', '25000.00', 'Mie.jpg', 'Mie kenyal dengan bumbu khas dan tambahan sayuran segar.'),
('MKN002', 'Mie Kuah', 'Makanan', '22000.00', 'Mie Kuah.jpg', 'Mie dengan kuah gurih yang kaya akan rempah-rempah.'),
('MKN003', 'Mie Chilie', 'Makanan', '15000.00', 'Mie Chili.jpg', 'Mie pedas dengan cita rasa khas cabai pilihan.'),
('MNM001', 'Es Teh', 'Minuman', '5000.00', 'Es Teh.jpg', 'Es teh manis dengan kesegaran teh pilihan yang pas dinikmati saat santai.'),
('MNM002', 'Es Campur', 'Minuman', '20000.00', 'es campur.jpg', 'Es Campur dengan buah yang segar serta manis yang pas ');

--
-- Triggers `menu`
--
DELIMITER $$
CREATE TRIGGER `before_insert_menu` BEFORE INSERT ON `menu` FOR EACH ROW BEGIN
    DECLARE kode_prefix VARCHAR(3);
    DECLARE last_kode INT;
    DECLARE new_kode VARCHAR(10);

    -- Tentukan prefix berdasarkan kategori
    IF NEW.kategori = 'Makanan' THEN
        SET kode_prefix = 'MKN';
    ELSEIF NEW.kategori = 'Minuman' THEN
        SET kode_prefix = 'MNM';
    ELSEIF NEW.kategori = 'Dessert' THEN
        SET kode_prefix = 'DST';
    END IF;

    -- Ambil nomor terakhir dari kategori yang sama
    SELECT COALESCE(MAX(CAST(SUBSTRING(kode_menu, 4, 3) AS UNSIGNED)), 0) + 1
    INTO last_kode
    FROM menu
    WHERE kode_menu LIKE CONCAT(kode_prefix, '%');

    -- Buat kode menu baru
    SET new_kode = CONCAT(kode_prefix, LPAD(last_kode, 3, '0'));

    -- Set kode_menu yang baru
    SET NEW.kode_menu = new_kode;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_price` int NOT NULL,
  `status` enum('pending','processed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `total_price`, `status`, `created_at`) VALUES
(1, 4, 80000, 'completed', '2025-03-30 09:30:32'),
(2, 4, 20000, 'completed', '2025-03-30 09:31:34'),
(3, 4, 60000, 'completed', '2025-03-30 09:34:17'),
(4, 5, 25000, 'completed', '2025-04-16 18:39:09'),
(5, 5, 45000, 'completed', '2025-04-16 18:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `kode_menu` varchar(50) NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `kode_menu`, `quantity`, `price`) VALUES
(3, 4, 'MKN001', 1, 25000),
(4, 5, 'DST001', 1, 20000),
(5, 5, 'MNM002', 1, 20000),
(6, 5, 'MNM001', 1, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `user_id`, `otp`, `expires_at`, `created_at`, `used`) VALUES
(1, 1, '123456', '2025-04-16 23:22:44', '2025-04-16 09:17:44', 0),
(2, 2, '234567', '2025-04-16 23:07:44', '2025-04-16 09:02:44', 0),
(3, 3, '345678', '2025-04-16 23:22:44', '2025-04-16 09:17:44', 1),
(4, 4, '456789', '2025-04-16 23:12:44', '2025-04-16 09:07:44', 1),
(5, 5, '198671', '2025-04-16 17:16:15', '2025-04-16 17:11:15', 0),
(6, 6, '555596', '2025-04-17 00:56:37', '2025-04-16 17:51:37', 1),
(7, 7, '766504', '2025-04-17 00:57:50', '2025-04-16 17:52:50', 1),
(8, 8, '205642', '2025-04-17 01:05:12', '2025-04-16 18:00:12', 1),
(9, 9, '949513', '2025-04-17 01:11:17', '2025-04-16 18:06:17', 1),
(10, 10, '590594', '2025-04-17 01:27:14', '2025-04-16 18:22:14', 1),
(11, 11, '765569', '2025-04-17 01:34:48', '2025-04-16 18:29:48', 1),
(12, 12, '564568', '2025-04-17 01:39:12', '2025-04-16 18:34:12', 1),
(13, 13, '094033', '2025-04-17 01:43:17', '2025-04-16 18:38:17', 1),
(14, 14, '903527', '2025-04-17 01:47:05', '2025-04-16 18:42:05', 1),
(15, 15, '694298', '2025-04-17 01:48:46', '2025-04-16 18:43:46', 1),
(16, 15, '130572', '2025-04-17 01:48:56', '2025-04-16 18:43:56', 1),
(17, 16, '765479', '2025-04-17 01:50:13', '2025-04-16 18:45:13', 1),
(18, 16, '534545', '2025-04-17 01:50:30', '2025-04-16 18:45:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('owner','admin','customer','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `phone`, `password`, `create_at`, `role`) VALUES
(1, 'owner', 'PT Keluarga Cemara', NULL, '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'owner'),
(2, 'admin', 'admin PT Keluarga Cemara', NULL, '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'admin'),
(3, 'staff', 'staff PT Keluarga Cemara', NULL, '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'staff'),
(4, 'el', 'Moch Azriel Maulana Racmadhani', NULL, '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'customer'),
(5, 'rstiannr', 'Resti Anggraini', '6285161882629', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-04-17 00:11:15', 'customer'),
(6, 'zeno', 'zeno si laptop', '6288805714979', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-04-17 00:51:37', 'customer'),
(7, 'r', 'r', '6288805714979', 'f61a20da9eaa68a9f06dbc1710b10ef0a67208b2059b1f576af6deac23c215f5', '2025-04-17 00:52:50', 'customer'),
(8, 'bb', 'aa', '6288805714979', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-04-17 01:00:12', 'customer'),
(9, 'qwertyu', 'Resti Anggraini', '6288805714979', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-04-17 01:06:17', 'customer'),
(10, 'tyu', 'fghjk', '6288805714979', 'b421c77b86cd09b54b60ab85d6741fd1d2df2ac1ddb185cd1da4378020083b82', '2025-04-17 01:22:14', 'customer'),
(11, 'dfghjmnbvg', 'xzasdxfcghbj', '6288805714979', 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', '2025-04-17 01:29:48', 'customer'),
(12, 'jksdchrfg', 'csdrf', '6288805714979', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', '2025-04-17 01:34:12', 'customer'),
(13, 'rstiasdfgthyjnnr', 'aa', '6288805714979', '8588310a98676af6e22563c1559e1ae20f85950792bdcd0c8f334867c54581cd', '2025-04-17 01:38:17', 'customer'),
(14, 'sderfgtyhu', 'sdxfcgvhjnk', '6288805714979', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', '2025-04-17 01:42:05', 'customer'),
(15, 'xdcfvgbhnjki', 'sdfcgvhj', '6288805714979', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', '2025-04-17 01:43:46', 'customer'),
(16, 'asdf', 'aa', '6288805714979', '65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5', '2025-04-17 01:45:13', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chart`
--
ALTER TABLE `chart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kode_menu` (`kode_menu`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`kode_menu`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `kode_menu` (`kode_menu`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chart`
--
ALTER TABLE `chart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chart`
--
ALTER TABLE `chart`
  ADD CONSTRAINT `chart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chart_ibfk_2` FOREIGN KEY (`kode_menu`) REFERENCES `menu` (`kode_menu`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`kode_menu`) REFERENCES `menu` (`kode_menu`) ON DELETE CASCADE;

--
-- Constraints for table `otp`
--
ALTER TABLE `otp`
  ADD CONSTRAINT `otp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
