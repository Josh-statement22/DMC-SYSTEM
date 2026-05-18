-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 10:12 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `particulars_category` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `particulars_category`, `created_at`, `updated_at`) VALUES
(1, 'Accommodation', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(2, 'Bank Charges', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(3, 'Bid Docs Fee and other Documents', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(4, 'Building Expense', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(5, 'Cash Advance', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(6, 'Commission', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(7, 'Donation', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(8, 'Food', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(9, 'Freight', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(10, 'Fuel / Gas', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(11, 'Kairos', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(12, 'Labor', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(13, 'MAC', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(14, 'Advances', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(15, 'Office Expense', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(16, 'Parking', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(17, 'Qarrah', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(18, 'Representation', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(19, 'Salary', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(20, 'Taxes and Licences', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(21, 'Tollgate', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(22, 'Transportation', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(23, 'Utilities', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(24, 'Vehicle Maintenance', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(25, 'Visa', '2026-05-14 23:36:36', '2026-05-14 23:36:36');

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

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `liquidation_expenses`
--

CREATE TABLE `liquidation_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `liquidation_id` bigint(20) UNSIGNED NOT NULL,
  `expense_date` date NOT NULL,
  `transaction_type` enum('debit','credit') NOT NULL DEFAULT 'debit',
  `transaction_details` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `particular_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(78, '2026_02_24_033218_create_roles_table', 1),
(79, '2026_02_24_033254_create_users_table', 1),
(80, '2026_02_25_061517_create_cache_table', 1),
(81, '2026_02_25_create_sessions_table', 1),
(82, '2026_02_26_create_suppliers_table', 1),
(83, '2026_03_03_024156_create_categories_table', 1),
(84, '2026_03_03_024157_create_items_table', 1),
(85, '2026_03_03_024300_create_projects_table', 1),
(86, '2026_03_06_100000_create_liquidations_table', 1),
(87, '2026_03_06_100000_create_project_items_table', 1),
(88, '2026_03_06_120001_modify_projects_table_add_project_date', 1),
(89, '2026_03_06_120002_modify_project_items_table', 1),
(90, '2026_03_16_000000_create_particulars_table', 1),
(91, '2026_03_16_000001_create_liquidation_expenses_table', 1),
(92, '2026_04_13_000001_create_cash_advance_requests_tables', 1),
(93, '2026_04_13_000002_create_cash_advance_monthly_balances_table', 1),
(94, '2026_04_22_000003_add_actor_name_snapshots_to_cash_advance_requests_table', 1),
(95, '2026_05_15_000000_add_transaction_type_to_liquidation_expenses_table', 1),
(96, '2026_05_15_000001_create_particulars_table', 1),
(97, '2026_05_15_000002_update_liquidation_expenses_table', 1),
(98, '2026_05_15_000003_simplify_particulars_structure', 1);

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
(1, 'Superadmin', '2026-05-14 23:36:34', '2026-05-14 23:36:34'),
(2, 'Admin', '2026-05-14 23:36:34', '2026-05-14 23:36:34'),
(3, 'Accounting', '2026-05-14 23:36:34', '2026-05-14 23:36:34');

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
('h9TVhvvDLZzVXw1Aop5woJUwhiv0GjaoCWAkLSUG', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibktsbk5zODBTY2RBOGdxbHBpTGxHNHNPT0Rkclpud01aVnJpMVU1aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778830654),
('tStUuCX79ovR4pMii3kLYSRvJGI3voFhFnFGpmOp', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTXRiNUtBSmtTUDdKWk1qQ2NIWFNYV1ZnekhmY2JhZ3cxU0RVNTRYdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FjY291bnRpbmcvbGlxdWlkYXRlLWV4cGVuc2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50aW5nL2xpcXVpZGF0ZS1leHBlbnNlcyI7czo1OiJyb3V0ZSI7czoyOToiYWNjb3VudGluZy5saXF1aWRhdGUtZXhwZW5zZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1778830901);

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
(1, '202600001', 'Superadmin', 'superadmin@dmc.com', '$2y$12$i3k.ZhxQy2vLBMJNZsNGaOyAqpHwklkDpT/n9uEE80we6lEA1Z6Aa', 1, '2026-05-14 23:36:34', '2026-05-14 23:36:34'),
(2, '202600002', 'Admin', 'admin@dmc.com', '$2y$12$xaodwVgHkNIZCYrNIYjZQOeYt3y56573ueaHKTF0cvxZK8.YX9CNa', 2, '2026-05-14 23:36:34', '2026-05-14 23:36:34'),
(3, '202600003', 'Accounting', 'accounting@dmc.com', '$2y$12$CIEeoUWS/jCXHJEa3RvR8uDC8nW3.3Fl1j309qg.M7Yn9sMvlutJe', 3, '2026-05-14 23:36:35', '2026-05-14 23:36:35'),
(4, '202600004', 'John Doe', 'john.doe@dmc.com', '$2y$12$x9.FbE6qWUsusU2GNfu/jO0nQJF9maEfsTq.MK5jdXJYQ3ud9z9m2', 2, '2026-05-14 23:36:35', '2026-05-14 23:36:35'),
(5, '202600005', 'Jane Smith', 'jane.smith@dmc.com', '$2y$12$VYJpz0j4YTWkTQeCrNfKPu9Wy6N8adTex7yY67zTHTS4CI8Z2r7TC', 2, '2026-05-14 23:36:35', '2026-05-14 23:36:35'),
(6, '202600006', 'Robert Johnson', 'robert.johnson@dmc.com', '$2y$12$0.yg771qgOd4M5K8sbG6FePReTZKbMM/hsDOP4dv.i6auLRTzox/2', 2, '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(7, '202600007', 'Maria Garcia', 'maria.garcia@dmc.com', '$2y$12$lP4.tekzxNT8pnlJs0lQ5OPt44t3wwe5NFRKalgbMx3c9icSysVNq', 2, '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(8, '202600008', 'David Lee', 'david.lee@dmc.com', '$2y$12$iX1pKPMGgfLhqNgilKrvl.RfUfTA7v1wdT0RQj8sco0KiOCVgI.Za', 2, '2026-05-14 23:36:36', '2026-05-14 23:36:36');

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
  ADD KEY `cash_advance_request_audits_acted_by_foreign` (`acted_by`),
  ADD KEY `idx_cash_adv_req_acted_at` (`cash_advance_request_id`,`acted_at`),
  ADD KEY `idx_action_acted_at` (`action`,`acted_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_particulars_category_unique` (`particulars_category`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_category_id_foreign` (`category_id`),
  ADD KEY `items_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_categories_category_name_unique` (`category_name`);

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
  ADD KEY `liquidation_expenses_category_id_foreign` (`category_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_advance_requests`
--
ALTER TABLE `cash_advance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_advance_request_attachments`
--
ALTER TABLE `cash_advance_request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_advance_request_audits`
--
ALTER TABLE `cash_advance_request_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liquidations`
--
ALTER TABLE `liquidations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_items`
--
ALTER TABLE `project_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `liquidation_expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liquidation_expenses_liquidation_id_foreign` FOREIGN KEY (`liquidation_id`) REFERENCES `liquidations` (`id`) ON DELETE CASCADE;

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
