-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 08:18 PM
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
-- Database: `emenu`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon_image`, `is_active`) VALUES
(1, 'Makanan', NULL, 1),
(2, 'Minuman', NULL, 1),
(3, 'Cemilan', NULL, 1),
(4, 'Dessert', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `promo_price` int(11) DEFAULT 0,
  `promo_start` datetime DEFAULT NULL,
  `promo_end` datetime DEFAULT NULL,
  `promo_quota` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `discount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `promo_price`, `promo_start`, `promo_end`, `promo_quota`, `image`, `is_available`, `discount`) VALUES
(1, 1, 'Nasi Goreng Spesial', 'Nasi goreng dengan bumbu rahasia', 25000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(2, 1, 'Mie Goreng Jawa', 'Mie goreng khas bumbu medok', 22000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(3, 1, 'Ayam Geprek', 'Ayam krispi ditumbuk sambal bawang', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(4, 1, 'Sate Ayam Madura', '10 tusuk sate dengan bumbu kacang', 25000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(5, 1, 'Soto Ayam Lamongan', 'Soto ayam kuah kuning segar', 18000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(6, 1, 'Rendang Sapi', 'Daging sapi empuk bumbu padang', 30000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(7, 1, 'Ayam Bakar Madu', 'Ayam bakar manis gurih', 24000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(8, 1, 'Bakso Urat Spesial', 'Bakso sapi asli dengan kuah kaldu', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(9, 1, 'Mie Ayam Jamur', 'Mie ayam dengan topping jamur kecap', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(10, 1, 'Nasi Kuning Komplit', 'Nasi kuning dengan aneka lauk', 22000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(11, 1, 'Iga Bakar Penyet', 'Iga sapi dibakar dengan sambal penyet', 45000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(12, 1, 'Gado-Gado Betawi', 'Sayuran segar dengan saus kacang', 18000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(13, 1, 'Nasi Uduk', 'Nasi gurih santan', 12000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(14, 1, 'Bebek Goreng', 'Bebek goreng kremes bumbu meresap', 35000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(15, 1, 'Pempek Kapal Selam', 'Pempek isi telur dengan cuko', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(16, 2, 'Es Teh Manis', 'Teh melati diseduh segar', 5000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(17, 2, 'Es Jeruk Peras', 'Jeruk asli diperas segar', 8000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(18, 2, 'Kopi Susu Gula Aren', 'Kopi espresso dengan susu dan aren', 18000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(19, 2, 'Americano', 'Kopi hitam murni', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(20, 2, 'Matcha Latte', 'Susu segar dengan serbuk matcha', 22000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(21, 2, 'Thai Tea', 'Teh susu khas Thailand', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(22, 2, 'Lemon Tea', 'Teh rasa lemon segar', 10000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(23, 2, 'Jus Mangga', 'Jus mangga manis kental', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(24, 2, 'Jus Alpukat', 'Jus alpukat dengan susu cokelat', 18000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(25, 2, 'Soda Gembira', 'Sirup, susu, dan soda', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(26, 2, 'Cappuccino', 'Kopi dengan foam tebal', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(27, 2, 'Caffe Latte', 'Kopi susu lembut', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(28, 2, 'Wedang Jahe', 'Minuman jahe hangat', 10000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(29, 2, 'Milo Dinosaur', 'Susu cokelat milo ekstra bubuk', 18000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(30, 2, 'Air Mineral', 'Air putih botol', 5000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(31, 3, 'Kentang Goreng', 'Kentang goreng renyah', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(32, 3, 'Tempe Mendoan', 'Tempe goreng tepung setengah matang', 12000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(33, 3, 'Tahu Crispy', 'Tahu goreng garing rasa balado', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(34, 3, 'Singkong Keju', 'Singkong merekah dengan keju', 18000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(35, 3, 'Pisang Goreng', 'Pisang goreng manis', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(36, 4, 'Es Krim Sundae', 'Es krim vanilla dengan saus cokelat', 15000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(37, 4, 'Pudding Cokelat', 'Pudding lembut manis', 12000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(38, 4, 'Waffle Belgia', 'Waffle renyah di luar lembut di dalam', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(39, 4, 'Pancake', 'Pancake dengan sirup maple', 20000.00, 0, NULL, NULL, 0, NULL, 1, 0),
(40, 4, 'Salad Buah', 'Campuran buah segar dengan mayonaise', 25000.00, 2000, '2026-06-24 00:26:00', '2026-06-24 00:28:00', 0, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu_variants`
--

CREATE TABLE `menu_variants` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_variant_links`
--

CREATE TABLE `menu_variant_links` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `variant_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_variant_links`
--

INSERT INTO `menu_variant_links` (`id`, `menu_id`, `variant_group_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2),
(5, 3, 1),
(6, 3, 2),
(7, 16, 3),
(8, 16, 4),
(9, 16, 5),
(10, 18, 3),
(11, 18, 4),
(12, 18, 5),
(13, 20, 3),
(14, 20, 4),
(15, 20, 5),
(16, 21, 3),
(17, 21, 4),
(18, 21, 5),
(19, 31, 6),
(20, 35, 6),
(21, 38, 6),
(22, 39, 6),
(25, 40, 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT 'Guest',
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid',
  `order_status` enum('pending','cooking','served','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `item_status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item_variants`
--

CREATE TABLE `order_item_variants` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `variant_option_id` int(11) NOT NULL,
  `variant_name` varchar(100) NOT NULL,
  `extra_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `qr_code_string` varchar(100) NOT NULL,
  `status` enum('empty','occupied','billing') DEFAULT 'empty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `qr_code_string`, `status`) VALUES
(1, 'IND-1', 'QR-IND-1', 'empty'),
(2, 'IND-2', 'QR-IND-2', 'empty'),
(3, 'IND-3', 'QR-IND-3', 'empty'),
(4, 'IND-4', 'QR-IND-4', 'empty'),
(5, 'IND-5', 'QR-IND-5', 'empty'),
(6, 'OUT-1', 'QR-OUT-1', 'empty'),
(7, 'OUT-2', 'QR-OUT-2', 'empty'),
(8, 'OUT-3', 'QR-OUT-3', 'empty'),
(9, 'OUT-4', 'QR-OUT-4', 'empty'),
(10, 'OUT-5', 'QR-OUT-5', 'empty');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variant_groups`
--

CREATE TABLE `variant_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('radio','checkbox') DEFAULT 'radio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `variant_groups`
--

INSERT INTO `variant_groups` (`id`, `name`, `type`) VALUES
(1, 'Level Pedas', 'radio'),
(2, 'Tambahan Lauk', 'checkbox'),
(3, 'Ukuran Gelas', 'radio'),
(4, 'Tingkat Gula', 'radio'),
(5, 'Es / Panas', 'radio'),
(6, 'Pilihan Topping', 'checkbox');

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--

CREATE TABLE `variant_options` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `extra_price` decimal(10,2) DEFAULT 0.00,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `variant_options`
--

INSERT INTO `variant_options` (`id`, `group_id`, `name`, `extra_price`, `sort_order`) VALUES
(1, 1, 'Tidak Pedas', 0.00, 1),
(2, 1, 'Sedang', 0.00, 2),
(3, 1, 'Pedas', 0.00, 3),
(4, 1, 'Sangat Pedas', 0.00, 4),
(5, 2, 'Telur Ceplok', 5000.00, 1),
(6, 2, 'Telur Dadar', 5000.00, 2),
(7, 2, 'Kerupuk', 2000.00, 3),
(8, 3, 'Reguler', 0.00, 1),
(9, 3, 'Large', 5000.00, 2),
(10, 4, 'Normal Sugar', 0.00, 1),
(11, 4, 'Less Sugar', 0.00, 2),
(12, 4, 'No Sugar', 0.00, 3),
(13, 5, 'Dingin (Ice)', 0.00, 1),
(14, 5, 'Panas (Hot)', 0.00, 2),
(15, 6, 'Keju Parut', 4000.00, 1),
(16, 6, 'Susu Kental Manis', 2000.00, 2),
(17, 6, 'Cokelat Meises', 3000.00, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `menu_variants`
--
ALTER TABLE `menu_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_variant_links`
--
ALTER TABLE `menu_variant_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `variant_group_id` (`variant_group_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `order_item_variants`
--
ALTER TABLE `order_item_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `variant_option_id` (`variant_option_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variant_groups`
--
ALTER TABLE `variant_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `menu_variants`
--
ALTER TABLE `menu_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_variant_links`
--
ALTER TABLE `menu_variant_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_item_variants`
--
ALTER TABLE `order_item_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variant_groups`
--
ALTER TABLE `variant_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_variant_links`
--
ALTER TABLE `menu_variant_links`
  ADD CONSTRAINT `menu_variant_links_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_variant_links_ibfk_2` FOREIGN KEY (`variant_group_id`) REFERENCES `variant_groups` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
