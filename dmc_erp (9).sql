-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 09:23 AM
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
-- Table structure for table `cash_advance_monthly_balances`
--

CREATE TABLE `cash_advance_monthly_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `month` tinyint(3) UNSIGNED NOT NULL,
  `carryover_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `added_budget` decimal(12,2) NOT NULL DEFAULT 0.00,
  `opening_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `released_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `expense_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `prepared_by` bigint(20) UNSIGNED DEFAULT NULL,
  `finalized_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_advance_monthly_balances`
--

INSERT INTO `cash_advance_monthly_balances` (`id`, `year`, `month`, `carryover_balance`, `added_budget`, `opening_balance`, `released_total`, `expense_total`, `remaining_balance`, `remarks`, `prepared_by`, `finalized_at`, `created_at`, `updated_at`) VALUES
(1, 2026, 5, 0.00, 0.00, 222700.00, 0.00, 0.00, 222700.00, NULL, 3, NULL, '2026-05-06 23:33:14', '2026-05-06 23:33:14'),
(3, 2026, 1, 0.00, 0.00, 284647.48, 0.00, 0.00, 284647.48, NULL, 3, NULL, '2026-05-10 21:59:45', '2026-05-10 21:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `cash_advance_requests`
--

CREATE TABLE `cash_advance_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `requested_amount` decimal(12,2) NOT NULL,
  `approved_amount` decimal(12,2) DEFAULT NULL,
  `purpose` text NOT NULL,
  `request_date` date NOT NULL,
  `date_needed` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `accounting_remarks` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by_name` varchar(255) DEFAULT NULL,
  `sent_by_name` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL,
  `liquidation_due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_advance_requests`
--

INSERT INTO `cash_advance_requests` (`id`, `reference_no`, `requester_id`, `requested_amount`, `approved_amount`, `purpose`, `request_date`, `date_needed`, `status`, `accounting_remarks`, `reviewed_by`, `approved_by_name`, `sent_by_name`, `submitted_at`, `reviewed_at`, `released_at`, `liquidation_due_date`, `created_at`, `updated_at`) VALUES
(1, 'CA-20260417-4454', 2, 10000.00, 10000.00, 'opex', '2026-04-17', '2026-04-17', 'approved', NULL, 3, 'Joshua Benito', 'Joshua Benito', '2026-04-17 00:27:52', '2026-04-17 00:28:36', '2026-04-17 00:28:36', NULL, '2026-04-17 00:27:52', '2026-04-17 00:28:36'),
(2, 'CA-20260422-9533', 6, 10000.00, 10000.00, 'purchase', '2026-04-22', '2026-04-22', 'approved', 'Directly sent by accounting.', 3, 'Joshua Benito', 'Joshua Benito', '2026-04-21 16:00:00', '2026-04-21 23:30:50', '2026-04-21 23:30:50', NULL, '2026-04-21 23:30:50', '2026-04-21 23:30:50'),
(3, 'CA-20260422-8840', 4, 10000.00, 10000.00, 'purchase', '2026-04-22', '2026-04-22', 'approved', 'Directly sent by accounting.', 3, 'Joshua Benito', 'Joshua Benito', '2026-04-21 16:00:00', '2026-04-21 23:31:40', '2026-04-21 23:31:40', NULL, '2026-04-21 23:31:40', '2026-04-21 23:31:40'),
(4, 'CA-20260422-1942', 5, 10000.00, 10000.00, 'transportation', '2026-04-22', '2026-04-22', 'approved', NULL, 7, 'Dexter Morgan', 'Dexter Morgan', '2026-04-21 23:33:02', '2026-04-22 00:42:02', '2026-04-22 00:42:02', NULL, '2026-04-21 23:33:02', '2026-04-22 00:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `cash_advance_request_attachments`
--

CREATE TABLE `cash_advance_request_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_advance_request_id` bigint(20) UNSIGNED NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(120) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_advance_request_audits`
--

CREATE TABLE `cash_advance_request_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_advance_request_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(60) NOT NULL,
  `old_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `acted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `acted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_advance_request_audits`
--

INSERT INTO `cash_advance_request_audits` (`id`, `cash_advance_request_id`, `action`, `old_status`, `new_status`, `remarks`, `acted_by`, `meta`, `acted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'submitted', NULL, 'pending', NULL, 2, '{\"requested_amount\":10000,\"purpose\":\"opex\"}', '2026-04-17 00:27:52', '2026-04-17 00:27:52', '2026-04-17 00:27:52'),
(2, 1, 'approved_and_released', 'pending', 'approved', NULL, 3, '{\"approved_amount\":10000}', '2026-04-17 00:28:36', '2026-04-17 00:28:36', '2026-04-17 00:28:36'),
(3, 2, 'sent_directly', NULL, 'approved', 'Direct send by accounting', 3, '{\"requested_amount\":10000,\"approved_amount\":10000,\"purpose\":\"purchase\"}', '2026-04-21 23:30:50', '2026-04-21 23:30:50', '2026-04-21 23:30:50'),
(4, 3, 'sent_directly', NULL, 'approved', 'Direct send by accounting', 3, '{\"requested_amount\":10000,\"approved_amount\":10000,\"purpose\":\"purchase\"}', '2026-04-21 23:31:40', '2026-04-21 23:31:40', '2026-04-21 23:31:40'),
(5, 4, 'submitted', NULL, 'pending', NULL, 5, '{\"requested_amount\":10000,\"purpose\":\"transportation\"}', '2026-04-21 23:33:02', '2026-04-21 23:33:02', '2026-04-21 23:33:02'),
(6, 4, 'approved_and_released', 'pending', 'approved', NULL, 7, '{\"approved_amount\":10000}', '2026-04-22 00:42:02', '2026-04-22 00:42:02', '2026-04-22 00:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Doors', '2026-03-07 04:24:31', '2026-03-07 04:24:31');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_number` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `item_description` text NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_number`, `item_name`, `category_id`, `item_description`, `supplier_id`, `price`, `quantity`, `purchase_date`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'SD-2026-001', 'Heavy Duty Steel Security Door', 1, '16-gauge reinforced steel door with anti-rust powder coating, 90cm x 210cm', 1, 7850.00, 12, '2026-03-06', 'items/heavy_duty_steel_security_door_69ac18ff858bb.jpg', '2026-03-07 04:24:31', '2026-03-07 04:24:31'),
(2, 'ITM-1001', 'Heavy Duty Steel Security Door', 1, 'Galvanized steel security door with multi-lock system and reinforced hinges', 2, 7650.00, 50, '2026-03-09', 'items/heavy_duty_steel_security_door_69ae2d9bbf2e1.jpg', '2026-03-08 18:16:59', '2026-03-08 18:16:59');

-- --------------------------------------------------------

--
-- Table structure for table `liquidations`
--

CREATE TABLE `liquidations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cutoff_period` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liquidations`
--

INSERT INTO `liquidations` (`id`, `user_id`, `cutoff_period`, `amount`, `status`, `remarks`, `document_path`, `submitted_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'April 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-04-12 21:11:10', '2026-04-12 21:11:10'),
(2, 5, 'April 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-04-21 23:32:25', '2026-04-21 23:32:25'),
(3, 6, 'April 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-04-22 00:49:32', '2026-04-22 00:49:32'),
(4, 2, 'May 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-05 22:54:48', '2026-05-05 22:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `liquidation_expenses`
--

CREATE TABLE `liquidation_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `liquidation_id` bigint(20) UNSIGNED NOT NULL,
  `expense_date` date NOT NULL,
  `particular_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_details` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liquidation_expenses`
--

INSERT INTO `liquidation_expenses` (`id`, `liquidation_id`, `expense_date`, `particular_id`, `transaction_details`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(3, 1, '2026-04-17', 18, 'transfer to ******', 'negros representation', 5000.00, '2026-04-17 00:30:08', '2026-04-17 00:30:08'),
(4, 1, '2026-04-17', 2, 'transfer to ****', 'bank', 1500.00, '2026-04-17 00:30:42', '2026-04-17 00:30:42'),
(5, 4, '2026-01-21', 15, 'operation expenses', 'for new set of table and desk', 7185.00, '2026-05-05 23:02:38', '2026-05-05 23:02:38');

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
(9, '2026_03_06_100001_modify_items_table_for_supplier_name', 1),
(10, '2026_03_06_120000_modify_items_table_add_supplier_id', 1),
(21, '2026_02_24_033218_create_roles_table', 2),
(22, '2026_02_24_033254_create_users_table', 2),
(23, '2026_02_25_061517_create_cache_table', 2),
(24, '2026_02_25_create_sessions_table', 2),
(25, '2026_02_26_create_suppliers_table', 2),
(26, '2026_03_03_024156_create_categories_table', 2),
(27, '2026_03_03_024157_create_items_table', 3),
(28, '2026_03_03_024300_create_projects_table', 3),
(29, '2026_03_06_100000_create_project_items_table', 3),
(30, '2026_03_06_120001_modify_projects_table_add_project_date', 3),
(31, '2026_03_06_120002_modify_project_items_table', 3),
(32, '2026_03_06_100000_create_liquidations_table', 4),
(33, '2026_03_16_000000_create_particulars_table', 5),
(34, '2026_03_16_000001_create_liquidation_expenses_table', 6),
(35, '2026_04_13_000001_create_cash_advance_requests_tables', 7),
(36, '2026_04_13_000002_create_cash_advance_monthly_balances_table', 8),
(37, '2026_04_22_000003_add_actor_name_snapshots_to_cash_advance_requests_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `particulars`
--

CREATE TABLE `particulars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `particulars_category` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `particulars`
--

INSERT INTO `particulars` (`id`, `particulars_category`, `created_at`, `updated_at`) VALUES
(1, 'Accommodation', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(2, 'Bank Charges', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(3, 'Bid Docs Fee and other Documents', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(4, 'Building Expense', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(5, 'Cash Advance', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(6, 'Commission', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(7, 'Donation', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(8, 'Food', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(9, 'Freight', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(10, 'Fuel / Gas', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(11, 'Kairos', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(12, 'Labor', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(13, 'MAC Advances\r\n', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(15, 'Office Expense', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(16, 'Parking', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(17, 'Qarrah', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(18, 'Representation', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(19, 'Salary', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(20, 'Taxes and Licences', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(21, 'Tollgate', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(22, 'Transportation', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(23, 'Utilities', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(24, 'Vehicle Maintenance', '2026-03-15 21:43:20', '2026-03-15 21:43:20'),
(25, 'Visa', '2026-03-15 21:43:20', '2026-03-15 21:43:20');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_date`, `created_at`, `updated_at`) VALUES
(1, 'FLM Robinsons', '2026-03-09', '2026-03-08 17:36:23', '2026-03-08 17:36:23'),
(2, 'City Hall Building', '2026-03-09', '2026-03-08 18:13:21', '2026-03-08 18:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `project_items`
--

CREATE TABLE `project_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_items`
--

INSERT INTO `project_items` (`id`, `project_id`, `item_id`, `supplier_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 6, 7850.00, '2026-03-08 18:12:35', '2026-03-08 18:12:35');

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
(1, 'Superadmin', '2026-03-07 03:56:01', '2026-03-07 03:56:01'),
(2, 'Admin', '2026-03-07 03:56:01', '2026-03-07 03:56:01'),
(3, 'Accounting', '2026-03-15 22:52:15', '2026-03-15 22:52:15');

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
('xn9Q07jUIhHd1xbNGA3xVnPq0uNTC08oEpHDYEIW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOG1xTGtuTjRnNDA1Y1prZ1hVSDA3dko1Q2VXNkpUWmFudkJSdUVmNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdXBlcmFkbWluL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoyMDoic3VwZXJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1778481790),
('YkZcV2SAwax4MV2uPWOmkUJ3kWNixjqSa5t4Gt0I', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidmpoaHpXY3Q1WWptaVRjY3RSZW14QXRQaXdaTlU4aGg4dmkyNnNPcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50aW5nL2xpcXVpZGF0aW9uIjtzOjU6InJvdXRlIjtzOjIyOiJhY2NvdW50aW5nLmxpcXVpZGF0aW9uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1778147585);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `phone_number`, `address`, `created_at`, `updated_at`) VALUES
(1, 'PrimeBuild Steel Supplies', '+63 917 845 2231', '45 Industrial Rd., Valenzuela City, Metro Manila', '2026-03-07 04:05:41', '2026-03-07 04:24:31'),
(2, 'Titan Steel Trading', '+63 918 665 1293', '88 Hardware St., Caloocan City, Metro Manila', '2026-03-08 18:16:59', '2026-03-08 18:16:59');

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
(1, '202600001', 'Superadmin', 'superadmin@example.com', '$2y$12$gDeIyxdYWLa8whqM7wzBFOfhnjry3YTuJXUgfOTn/cmStjlnrMC6O', 1, '2026-03-07 03:56:02', '2026-03-07 03:56:02'),
(2, '202600002', 'Admin', 'admin@example.com', '$2y$12$8SAAnfz9AeZlHW1CLWGsT.NdLWErBd1AsUv.B2I/OLlNW4DGRSIBu', 2, '2026-03-07 03:56:02', '2026-03-07 03:56:02'),
(3, '202600003', 'Joshua Benito', 'JB2026000003@dmc.com', '$2y$12$4skZy5m.g.axC3hrM7QrGelVApRegjAVJvPdHfTjj.rI23VcVToAa', 3, '2026-03-15 23:37:33', '2026-04-05 17:44:08'),
(4, '202600004', 'John Doe', 'JD202600004@dmc.com', '$2y$12$mn4JUrl.xpC33JJ2oAv1Du/SJ3QdzGg.s9ecEuSaDwAAP9GBeXqrm', 2, '2026-03-15 23:51:15', '2026-03-15 23:51:15'),
(5, '202600005', 'Juan Dela Cruz', 'JDC202600005@dmc.com', '$2y$12$9WO.J4I8ZmRpDbhtqawezOPdGCEUxp1vIiAWlJzIwyJvKFpKmf7Ky', 2, '2026-04-21 23:23:38', '2026-04-21 23:24:01'),
(6, '202600006', 'Jane Smith', 'JS202600006@dmc.com', '$2y$12$R21MTZEuBeUaO4uXZR62re2epDhOYKx/S2cY50oIeeW.1z3ucC67S', 2, '2026-04-21 23:25:26', '2026-04-21 23:25:26'),
(7, '202600007', 'Dexter Morgan', 'DM202600007@dmc.com', '$2y$12$jn7iHOVmKpvrb0WDALMgtO.NOJjqFhG7dKue8eb7xMQ1QpOij8akG', 3, '2026-04-22 00:41:13', '2026-04-22 00:41:13');

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
-- Indexes for table `cash_advance_monthly_balances`
--
ALTER TABLE `cash_advance_monthly_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cash_advance_monthly_balances_year_month_unique` (`year`,`month`),
  ADD KEY `cash_advance_monthly_balances_prepared_by_foreign` (`prepared_by`),
  ADD KEY `cash_advance_monthly_balances_year_month_finalized_at_index` (`year`,`month`,`finalized_at`);

--
-- Indexes for table `cash_advance_requests`
--
ALTER TABLE `cash_advance_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cash_advance_requests_reference_no_unique` (`reference_no`),
  ADD KEY `cash_advance_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `cash_advance_requests_status_request_date_index` (`status`,`request_date`),
  ADD KEY `cash_advance_requests_requester_id_status_index` (`requester_id`,`status`),
  ADD KEY `cash_advance_requests_released_at_index` (`released_at`);

--
-- Indexes for table `cash_advance_request_attachments`
--
ALTER TABLE `cash_advance_request_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_advance_request_attachments_cash_advance_request_id_foreign` (`cash_advance_request_id`),
  ADD KEY `cash_advance_request_attachments_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `cash_advance_request_audits`
--
ALTER TABLE `cash_advance_request_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_advance_request_audits_cash_advance_request_id_foreign` (`cash_advance_request_id`),
  ADD KEY `cash_advance_request_audits_acted_by_foreign` (`acted_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_category_name_unique` (`category_name`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_category_id_foreign` (`category_id`),
  ADD KEY `items_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `liquidations`
--
ALTER TABLE `liquidations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liquidations_user_id_foreign` (`user_id`),
  ADD KEY `liquidations_status_cutoff_period_index` (`status`,`cutoff_period`);

--
-- Indexes for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liquidation_expenses_liquidation_id_foreign` (`liquidation_id`),
  ADD KEY `liquidation_expenses_particular_id_foreign` (`particular_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `particulars`
--
ALTER TABLE `particulars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `particulars_particulars_category_unique` (`particulars_category`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_name_unique` (`project_name`);

--
-- Indexes for table `project_items`
--
ALTER TABLE `project_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_items_item_id_foreign` (`item_id`),
  ADD KEY `project_items_project_id_foreign` (`project_id`),
  ADD KEY `project_items_supplier_id_foreign` (`supplier_id`);

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
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_supplier_name_unique` (`supplier_name`);

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
-- AUTO_INCREMENT for table `cash_advance_monthly_balances`
--
ALTER TABLE `cash_advance_monthly_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cash_advance_requests`
--
ALTER TABLE `cash_advance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cash_advance_request_attachments`
--
ALTER TABLE `cash_advance_request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_advance_request_audits`
--
ALTER TABLE `cash_advance_request_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `liquidations`
--
ALTER TABLE `liquidations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `particulars`
--
ALTER TABLE `particulars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_items`
--
ALTER TABLE `project_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_advance_monthly_balances`
--
ALTER TABLE `cash_advance_monthly_balances`
  ADD CONSTRAINT `cash_advance_monthly_balances_prepared_by_foreign` FOREIGN KEY (`prepared_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_advance_requests`
--
ALTER TABLE `cash_advance_requests`
  ADD CONSTRAINT `cash_advance_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_advance_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_advance_request_attachments`
--
ALTER TABLE `cash_advance_request_attachments`
  ADD CONSTRAINT `cash_advance_request_attachments_cash_advance_request_id_foreign` FOREIGN KEY (`cash_advance_request_id`) REFERENCES `cash_advance_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_advance_request_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_advance_request_audits`
--
ALTER TABLE `cash_advance_request_audits`
  ADD CONSTRAINT `cash_advance_request_audits_acted_by_foreign` FOREIGN KEY (`acted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_advance_request_audits_cash_advance_request_id_foreign` FOREIGN KEY (`cash_advance_request_id`) REFERENCES `cash_advance_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `liquidations`
--
ALTER TABLE `liquidations`
  ADD CONSTRAINT `liquidations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  ADD CONSTRAINT `liquidation_expenses_liquidation_id_foreign` FOREIGN KEY (`liquidation_id`) REFERENCES `liquidations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liquidation_expenses_particular_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars` (`id`);

--
-- Constraints for table `project_items`
--
ALTER TABLE `project_items`
  ADD CONSTRAINT `project_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_items_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
