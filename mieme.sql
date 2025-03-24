-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Mar 24, 2025 at 12:58 AM
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
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `kode_menu` varchar(10) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` enum('Makanan','Minuman','Dessert') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `gambar` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`kode_menu`, `nama_menu`, `kategori`, `harga`, `gambar`) VALUES
('DST002', 'Tart', 'Dessert', '20000.00', 'tart.jpg'),
('DST003', 'kue strowberry', 'Dessert', '15000.00', 'Kue.jpg'),
('MKN001', 'Mie', 'Makanan', '25000.00', 'Mie.jpg'),
('MKN002', 'Mie Kuah', 'Makanan', '22000.00', 'Mie Kuah.jpg'),
('MKN003', 'Mie Chilie', 'Makanan', '15000.00', 'Mie Chili.jpg'),
('MNM001', 'Es Teh', 'Minuman', '5000.00', 'Es Teh.jpg');

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `password`, `create_at`) VALUES
(1, 'ael', 'Moch Azriel Maulana Racmadhani', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2025-03-23 16:34:21'),
(2, 'azriel', 'Moch Azriel Maulana Racmadhani', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2025-03-23 16:42:46'),
(9, '', '', '5fd924625f6ab16a19cc9807c7c506ae1813490e4ba675f843d5a10e0baacdb8', '2025-03-23 16:47:49'),
(11, 'resti', 'resti anggraeni', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2025-03-24 04:39:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`kode_menu`);

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
