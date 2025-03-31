-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Mar 31, 2025 at 08:26 AM
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
('DST002', 'Tart', 'Dessert', '20000.00', 'tart.jpg', 'Kue tart lezat dengan lapisan krim lembut dan topping buah segar.'),
('DST003', 'kue strowberry', 'Dessert', '15000.00', 'Kue.jpg', 'Kue lembut dengan rasa manis strawberry yang menyegarkan.'),
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
(9, 4, 80000, 'completed', '2025-03-30 16:30:32'),
(10, 4, 20000, 'completed', '2025-03-30 16:31:34'),
(11, 4, 60000, 'completed', '2025-03-30 16:34:17');

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
(19, 9, 'MNM002', 1, 20000),
(20, 9, 'DST003', 1, 15000),
(21, 9, 'MKN001', 1, 25000),
(22, 9, 'DST002', 1, 20000),
(23, 10, 'MNM002', 1, 20000),
(24, 11, 'MKN001', 1, 25000),
(25, 11, 'DST003', 1, 15000),
(26, 11, 'DST002', 1, 20000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('owner','admin','customer','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `password`, `create_at`, `role`) VALUES
(1, 'owner', 'PT Keluarga Cemara', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'owner'),
(2, 'admin', 'admin PT Keluarga Cemara', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'admin'),
(3, 'staff', 'staff PT Keluarga Cemara', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'staff'),
(4, 'el', 'Moch Azriel Maulana Racmadhani', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2011-11-11 00:00:00', 'customer');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
