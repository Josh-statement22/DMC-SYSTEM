-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2026 at 12:53 AM
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
-- Database: `dmc_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project` varchar(255) NOT NULL,
  `item_number` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `project`, `item_number`, `item_name`, `item_description`, `supplier`, `quantity`, `price`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'School Renovation 2026', 'ITM-1001', 'LED Panel Light', '18W 2x2 LED panel ceiling light, daylight white', 'BrightSource Trading', 50, 850.00, 'items/projects/school_renovation_2026/led_panel_light.jpg', '2026-03-03 01:44:56', '2026-03-04 17:50:10'),
(2, 'Barangay Hall Upgrade', 'ITM-1003', 'Steel Door', 'Heavy duty steel security door 90cm x 210cm', 'PrimeBuild Hardware', 10, 7500.00, 'items/projects/barangay_hall_upgrade/steel_door.webp', '2026-03-03 01:48:16', '2026-03-04 21:08:23'),
(3, 'School Renovation 2026', 'ITM-1002', 'Electrical Wire', '2.0mm THHN copper wire, 150m roll', 'Metro Electrical Supply', 20, 3200.00, 'items/projects/school_renovation_2026/electrical_wire.jpg', '2026-03-03 05:23:39', '2026-03-04 17:58:48'),
(4, 'Hospital Expansion Phase 1', 'ITM-1005', 'PVC Pipe', '3-inch PVC pipe, 3 meters length', 'AquaFlow Industrial', 75, 650.00, 'items/projects/hospital_expansion_phase_1/pvc_pipe.png', '2026-03-03 05:40:10', '2026-03-04 21:17:13'),
(5, 'Barangay Hall Upgrade', 'ITM-1004', 'Ceramic Tiles', '60x60 polished ceramic floor tiles (box of 4 pcs)', 'TileHub Depot', 200, 480.00, 'items/projects/barangay_hall_upgrade/ceramic_tiles.jpg', '2026-03-04 16:00:13', '2026-03-04 21:14:42'),
(6, 'Hospital Expansion Phase 1', 'ITM-1006', 'Water Closet', 'Dual flush ceramic toilet set', 'SanitaryPro Supplies', 15, 5200.00, 'items/projects/hospital_expansion_phase_1/water_closet.png', '2026-03-04 16:02:51', '2026-03-04 21:17:23'),
(7, 'Road Repair Project 2026', 'ITM-1007', 'Portland Cement', '40kg Type 1 Portland cement', 'SolidMix Construction Supply', 300, 265.00, 'items/projects/1772672446154_4axjv5q8.jpg', '2026-03-04 17:00:52', '2026-03-04 17:00:52'),
(8, 'Road Repair Project 2026', 'ITM-1008', 'Rebar 10mm', '10mm x 6m deformed reinforcing bar', 'SteelCore Trading', 150, 310.00, 'items/projects/road_repair_project_2026/rebar_10mm.jpg', '2026-03-04 17:15:26', '2026-03-04 17:15:26'),
(9, 'Office Fit-Out Project', 'ITM-1009', 'Office Desk', '120cm laminated office desk with drawers', 'Workspace Solutions', 25, 4200.00, 'items/projects/office_fit-out_project/office_desk.jpg', '2026-03-04 17:17:37', '2026-03-04 17:17:37'),
(10, 'Office Fit-Out Project', 'ITM-1010', 'Air Conditioning Unit', '1.5HP split type inverter aircon', 'CoolAir Systems', 8, 28500.00, 'items/projects/office_fit-out_project/air_conditioning_unit.jpg', '2026-03-04 18:01:27', '2026-03-04 18:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(20, '2026_02_24_033218_create_roles_table', 1),
(21, '2026_02_24_033254_create_users_table', 1),
(22, '2026_02_25_061517_create_cache_table', 1),
(23, '2026_02_25_create_sessions_table', 1),
(24, '2026_03_03_024157_create_items_table', 1),
(25, '2026_03_03_024300_create_projects_table', 1),
(26, '2026_03_05_000000_add_image_to_items_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `created_at`, `updated_at`) VALUES
(1, 'School Renovation 2026', '2026-03-03 01:44:56', '2026-03-03 01:44:56'),
(2, 'Barangay Hall Upgrade', '2026-03-03 01:48:16', '2026-03-03 01:48:16'),
(4, 'Hospital Expansion Phase 1', '2026-03-03 05:40:10', '2026-03-03 05:40:10'),
(7, 'Road Repair Project 2026', '2026-03-04 17:00:52', '2026-03-04 17:00:52'),
(9, 'Office Fit-Out Project', '2026-03-04 17:17:37', '2026-03-04 17:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Superadmin', '2026-03-03 00:35:53', '2026-03-03 00:35:53'),
(2, 'Admin', '2026-03-03 00:35:53', '2026-03-03 00:35:53');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3EGLUZBRyVLscJY3NKVBfHEHNgTaBFwc8vl8wMqD', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ0xwcVgwZVp6MFpYMjlMWlBzcG14UEc1cFg0WEJUS1MwMkxFZXdxNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcmljZWxpc3QiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLnByaWNlbGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1772696132);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `name`, `email`, `password`, `role_id`, `created_at`, `updated_at`) VALUES
(1, '202600001', 'Joshua Lacambra', 'superadmin@dmc.com', '$2y$12$jApgh3.6uIFXNHnetjpd1ukHKbbAz7TMUhDb606YlBnRQoQzEm.b2', 1, '2026-03-03 00:35:53', '2026-03-04 23:14:33'),
(2, '202600002', 'Admin', 'admin@dmc.com', '$2y$12$kq1CYOlypF4327OGjeF0SeBUH54VKiQKcaWQpLNuW.VN7KKwqIs8u', 2, '2026-03-03 00:35:53', '2026-03-03 01:24:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_name_unique` (`project_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
