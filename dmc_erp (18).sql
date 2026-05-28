-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2026 at 10:17 AM
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

--
-- Dumping data for table `cash_advance_monthly_balances`
--

INSERT INTO `cash_advance_monthly_balances` (`id`, `year`, `month`, `opening_balance`, `released_total`, `expense_total`, `remaining_balance`, `remarks`, `prepared_by`, `finalized_at`, `created_at`, `updated_at`) VALUES
(1, 2026, 5, 264000.00, 134659.00, 0.00, 264000.00, NULL, 3, NULL, '2026-05-17 23:33:46', '2026-05-27 22:47:50'),
(2, 2026, 1, 284647.48, 0.00, -199230.00, 483877.48, NULL, 24, NULL, '2026-05-24 16:54:45', '2026-05-28 00:14:49'),
(3, 2026, 2, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-24 18:25:53', '2026-05-24 18:25:53'),
(4, 2026, 3, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-24 21:07:26', '2026-05-24 21:07:26');

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
(42, 'CA-1779951017-339', 14, 10000.00, 10000.00, 'aba savings deduction', '2026-01-01', '2026-01-01', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:50:17', '2026-05-27 22:50:17', '2026-05-27 22:50:17', NULL, '2026-05-27 22:50:17', '2026-05-27 22:50:17'),
(43, 'CA-1779951121-634', 13, 15000.00, 15000.00, 'opex', '2026-01-01', '2026-01-01', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:52:01', '2026-05-27 22:52:01', '2026-05-27 22:52:01', NULL, '2026-05-27 22:52:01', '2026-05-27 22:52:01'),
(44, 'CA-1779951331-531', 12, 8700.00, 8700.00, 'DMC Commision', '2026-01-02', '2026-01-02', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:55:31', '2026-05-27 22:55:31', '2026-05-27 22:55:31', NULL, '2026-05-27 22:55:31', '2026-05-27 22:55:31'),
(45, 'CA-1779951433-793', 12, 68459.00, 68459.00, 'Salary Jan 2', '2026-01-03', '2026-01-03', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:57:13', '2026-05-27 22:57:13', '2026-05-27 22:57:13', NULL, '2026-05-27 22:57:13', '2026-05-27 22:57:13'),
(46, 'CA-1779951458-975', 23, 2500.00, 2500.00, 'opex negros', '2026-01-04', '2026-01-04', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:57:38', '2026-05-27 22:57:38', '2026-05-27 22:57:38', NULL, '2026-05-27 22:57:38', '2026-05-27 22:57:38'),
(47, 'CA-1779951484-386', 9, 10000.00, 10000.00, 'truck dum to mla', '2026-01-04', '2026-01-04', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:58:04', '2026-05-27 22:58:04', '2026-05-27 22:58:04', NULL, '2026-05-27 22:58:04', '2026-05-27 22:58:04'),
(48, 'CA-1779951564-591', 9, 8000.00, 8000.00, 'dumaguete truck', '2026-01-04', '2026-01-04', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:59:24', '2026-05-27 22:59:24', '2026-05-27 22:59:24', NULL, '2026-05-27 22:59:24', '2026-05-27 22:59:24'),
(49, 'CA-1779951585-146', 15, 10000.00, 10000.00, 'opex', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 22:59:45', '2026-05-27 22:59:45', '2026-05-27 22:59:45', NULL, '2026-05-27 22:59:45', '2026-05-27 22:59:45'),
(50, 'CA-1779951714-652', 19, 20000.00, 20000.00, 'mayors permit occupancy', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:01:54', '2026-05-27 23:01:54', '2026-05-27 23:01:54', NULL, '2026-05-27 23:01:54', '2026-05-27 23:01:54'),
(51, 'CA-1779951764-732', 14, 23000.00, 23000.00, 'gilbert de sagun, quit claim', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:02:44', '2026-05-27 23:02:44', '2026-05-27 23:02:44', NULL, '2026-05-27 23:02:44', '2026-05-27 23:02:44'),
(52, 'CA-1779951811-885', 23, 5000.00, 5000.00, 'representation food negros', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:03:31', '2026-05-27 23:03:31', '2026-05-27 23:03:31', NULL, '2026-05-27 23:03:31', '2026-05-27 23:03:31'),
(53, 'CA-1779951845-825', 20, 2000.00, 2000.00, 'opex mamburao', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:04:05', '2026-05-27 23:04:05', '2026-05-27 23:04:05', NULL, '2026-05-27 23:04:05', '2026-05-27 23:04:05'),
(54, 'CA-1779951872-391', 12, 7000.00, 7000.00, 'bidding notary', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:04:32', '2026-05-27 23:04:32', '2026-05-27 23:04:32', NULL, '2026-05-27 23:04:32', '2026-05-27 23:04:32'),
(55, 'CA-1779951901-170', 12, 1200.01, 1200.01, 'dmc comm rice.ham', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:05:01', '2026-05-27 23:05:01', '2026-05-27 23:05:01', NULL, '2026-05-27 23:05:01', '2026-05-27 23:05:01'),
(56, 'CA-1779951932-606', 23, 4000.00, 4000.00, 'representation negros', '2026-01-05', '2026-01-05', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:05:32', '2026-05-27 23:05:32', '2026-05-27 23:05:32', NULL, '2026-05-27 23:05:32', '2026-05-27 23:05:32'),
(57, 'CA-1779951960-904', 19, 14519.00, 14519.00, 'qarah opex', '2026-01-06', '2026-01-06', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:06:00', '2026-05-27 23:06:00', '2026-05-27 23:06:00', NULL, '2026-05-27 23:06:00', '2026-05-27 23:06:00'),
(58, 'CA-1779952129-358', 16, 3000.00, 3000.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:08:49', '2026-05-27 23:08:49', '2026-05-27 23:08:49', NULL, '2026-05-27 23:08:49', '2026-05-27 23:08:49'),
(59, 'CA-1779952227-137', 20, 3500.00, 3500.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:10:27', '2026-05-27 23:10:27', '2026-05-27 23:10:27', NULL, '2026-05-27 23:10:27', '2026-05-27 23:10:27'),
(60, 'CA-1779952257-496', 9, 10000.00, 10000.00, 'truck negros', '2026-01-06', '2026-01-06', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:10:57', '2026-05-27 23:10:57', '2026-05-27 23:10:57', NULL, '2026-05-27 23:10:57', '2026-05-27 23:10:57'),
(61, 'CA-1779952285-878', 23, 2000.00, 2000.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:11:25', '2026-05-27 23:11:25', '2026-05-27 23:11:25', NULL, '2026-05-27 23:11:25', '2026-05-27 23:11:25'),
(62, 'CA-1779952322-529', 18, 4000.00, 4000.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:12:02', '2026-05-27 23:12:02', '2026-05-27 23:12:02', NULL, '2026-05-27 23:12:02', '2026-05-27 23:12:02'),
(63, 'CA-1779952353-291', 12, 36891.99, 36891.99, 'freight', '2026-01-07', '2026-01-07', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-27 23:12:33', '2026-05-27 23:12:33', '2026-05-27 23:12:33', NULL, '2026-05-27 23:12:33', '2026-05-27 23:12:33'),
(64, 'CA-1779954368-232', 24, 12000.00, 12000.00, 'outbound interbank transfer', '2026-01-04', '2026-01-04', 'approved', 'Manually Recorded', 24, 'STELLA C.', 'STELLA C.', '2026-05-27 23:46:08', '2026-05-27 23:46:08', '2026-05-27 23:46:08', NULL, '2026-05-27 23:46:08', '2026-05-27 23:46:08'),
(66, 'CA-1779956089-881', 24, 480000.00, 480000.00, 'check deposit from metro-better living russia', '2026-01-06', '2026-01-06', 'approved', 'Manual Credit Entry', 24, 'STELLA C.', 'STELLA C.', '2026-05-28 00:14:49', '2026-05-28 00:14:49', '2026-05-28 00:14:49', NULL, '2026-05-28 00:14:49', '2026-05-28 00:14:49');

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
(25, 'Visa', '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(27, 'Borrow', '2026-05-25 00:41:53', '2026-05-25 00:41:53'),
(28, 'Interest Expense', '2026-05-25 00:41:53', '2026-05-25 00:41:53'),
(29, 'Opex', '2026-05-25 00:41:53', '2026-05-25 00:41:53'),
(30, 'Returned', '2026-05-25 00:41:53', '2026-05-25 00:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"33e7dc5a-76e9-4a4d-b1da-d00a1c18c1a5\",\"displayName\":\"App\\\\Events\\\\CashAdvanceRequestSubmitted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:38:\\\"App\\\\Events\\\\CashAdvanceRequestSubmitted\\\":2:{s:11:\\\"requestData\\\";O:8:\\\"stdClass\\\":19:{s:2:\\\"id\\\";i:1;s:12:\\\"reference_no\\\";s:16:\\\"CA-20260522-2473\\\";s:12:\\\"requester_id\\\";i:2;s:16:\\\"requested_amount\\\";s:6:\\\"200.00\\\";s:15:\\\"approved_amount\\\";N;s:7:\\\"purpose\\\";s:23:\\\"food for representation\\\";s:12:\\\"request_date\\\";s:10:\\\"2026-05-22\\\";s:11:\\\"date_needed\\\";s:10:\\\"2026-05-22\\\";s:6:\\\"status\\\";s:7:\\\"pending\\\";s:18:\\\"accounting_remarks\\\";N;s:11:\\\"reviewed_by\\\";N;s:16:\\\"approved_by_name\\\";N;s:12:\\\"sent_by_name\\\";N;s:12:\\\"submitted_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";s:11:\\\"reviewed_at\\\";N;s:11:\\\"released_at\\\";N;s:20:\\\"liquidation_due_date\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";}s:11:\\\"requesterId\\\";i:2;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1779416797,\"delay\":null}', 0, NULL, 1779416797, 1779416797),
(2, 'default', '{\"uuid\":\"592342bc-58f3-4fe9-8fff-3f91a041d189\",\"displayName\":\"App\\\\Events\\\\CashAdvanceRequestSubmitted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:38:\\\"App\\\\Events\\\\CashAdvanceRequestSubmitted\\\":2:{s:11:\\\"requestData\\\";O:8:\\\"stdClass\\\":19:{s:2:\\\"id\\\";i:2;s:12:\\\"reference_no\\\";s:16:\\\"CA-20260522-5831\\\";s:12:\\\"requester_id\\\";i:2;s:16:\\\"requested_amount\\\";s:7:\\\"2000.00\\\";s:15:\\\"approved_amount\\\";N;s:7:\\\"purpose\\\";s:4:\\\"food\\\";s:12:\\\"request_date\\\";s:10:\\\"2026-05-22\\\";s:11:\\\"date_needed\\\";s:10:\\\"2026-05-22\\\";s:6:\\\"status\\\";s:7:\\\"pending\\\";s:18:\\\"accounting_remarks\\\";N;s:11:\\\"reviewed_by\\\";N;s:16:\\\"approved_by_name\\\";N;s:12:\\\"sent_by_name\\\";N;s:12:\\\"submitted_at\\\";s:19:\\\"2026-05-22 02:28:56\\\";s:11:\\\"reviewed_at\\\";N;s:11:\\\"released_at\\\";N;s:20:\\\"liquidation_due_date\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2026-05-22 02:28:56\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-05-22 02:28:56\\\";}s:11:\\\"requesterId\\\";i:2;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1779416937,\"delay\":null}', 0, NULL, 1779416937, 1779416937),
(3, 'default', '{\"uuid\":\"ad9ed04f-6c08-458e-a0ca-e2bd6bb6a92a\",\"displayName\":\"App\\\\Events\\\\CashAdvanceRequestDecisionMade\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:41:\\\"App\\\\Events\\\\CashAdvanceRequestDecisionMade\\\":4:{s:9:\\\"requestId\\\";s:1:\\\"2\\\";s:8:\\\"decision\\\";s:8:\\\"approved\\\";s:11:\\\"requestData\\\";O:8:\\\"stdClass\\\":19:{s:2:\\\"id\\\";i:2;s:12:\\\"reference_no\\\";s:16:\\\"CA-20260522-5831\\\";s:12:\\\"requester_id\\\";i:2;s:16:\\\"requested_amount\\\";s:7:\\\"2000.00\\\";s:15:\\\"approved_amount\\\";s:7:\\\"2000.00\\\";s:7:\\\"purpose\\\";s:4:\\\"food\\\";s:12:\\\"request_date\\\";s:10:\\\"2026-05-22\\\";s:11:\\\"date_needed\\\";s:10:\\\"2026-05-22\\\";s:6:\\\"status\\\";s:8:\\\"approved\\\";s:18:\\\"accounting_remarks\\\";s:2:\\\"ok\\\";s:11:\\\"reviewed_by\\\";i:3;s:16:\\\"approved_by_name\\\";s:10:\\\"Accounting\\\";s:12:\\\"sent_by_name\\\";s:10:\\\"Accounting\\\";s:12:\\\"submitted_at\\\";s:19:\\\"2026-05-22 02:28:56\\\";s:11:\\\"reviewed_at\\\";s:19:\\\"2026-05-22 02:29:41\\\";s:11:\\\"released_at\\\";s:19:\\\"2026-05-22 02:29:41\\\";s:20:\\\"liquidation_due_date\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2026-05-22 02:28:56\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-05-22 02:29:41\\\";}s:9:\\\"decidedBy\\\";s:10:\\\"Accounting\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1779416981,\"delay\":null}', 0, NULL, 1779416981, 1779416981),
(4, 'default', '{\"uuid\":\"006cb4c3-49aa-4f90-b269-9bea26af2cc5\",\"displayName\":\"App\\\\Events\\\\CashAdvanceRequestDecisionMade\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:41:\\\"App\\\\Events\\\\CashAdvanceRequestDecisionMade\\\":4:{s:9:\\\"requestId\\\";s:1:\\\"1\\\";s:8:\\\"decision\\\";s:8:\\\"rejected\\\";s:11:\\\"requestData\\\";O:8:\\\"stdClass\\\":19:{s:2:\\\"id\\\";i:1;s:12:\\\"reference_no\\\";s:16:\\\"CA-20260522-2473\\\";s:12:\\\"requester_id\\\";i:2;s:16:\\\"requested_amount\\\";s:6:\\\"200.00\\\";s:15:\\\"approved_amount\\\";N;s:7:\\\"purpose\\\";s:23:\\\"food for representation\\\";s:12:\\\"request_date\\\";s:10:\\\"2026-05-22\\\";s:11:\\\"date_needed\\\";s:10:\\\"2026-05-22\\\";s:6:\\\"status\\\";s:8:\\\"rejected\\\";s:18:\\\"accounting_remarks\\\";s:4:\\\"pass\\\";s:11:\\\"reviewed_by\\\";i:3;s:16:\\\"approved_by_name\\\";N;s:12:\\\"sent_by_name\\\";N;s:12:\\\"submitted_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";s:11:\\\"reviewed_at\\\";s:19:\\\"2026-05-22 02:40:11\\\";s:11:\\\"released_at\\\";N;s:20:\\\"liquidation_due_date\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-05-22 02:40:11\\\";}s:9:\\\"decidedBy\\\";s:10:\\\"Accounting\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1779417611,\"delay\":null}', 0, NULL, 1779417611, 1779417611),
(5, 'default', '{\"uuid\":\"90819cf8-b789-40fc-b9b7-d778c29eb665\",\"displayName\":\"App\\\\Events\\\\CashAdvanceRequestDecisionMade\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:41:\\\"App\\\\Events\\\\CashAdvanceRequestDecisionMade\\\":4:{s:9:\\\"requestId\\\";s:1:\\\"1\\\";s:8:\\\"decision\\\";s:8:\\\"rejected\\\";s:11:\\\"requestData\\\";O:8:\\\"stdClass\\\":19:{s:2:\\\"id\\\";i:1;s:12:\\\"reference_no\\\";s:16:\\\"CA-20260522-2473\\\";s:12:\\\"requester_id\\\";i:2;s:16:\\\"requested_amount\\\";s:6:\\\"200.00\\\";s:15:\\\"approved_amount\\\";N;s:7:\\\"purpose\\\";s:23:\\\"food for representation\\\";s:12:\\\"request_date\\\";s:10:\\\"2026-05-22\\\";s:11:\\\"date_needed\\\";s:10:\\\"2026-05-22\\\";s:6:\\\"status\\\";s:8:\\\"rejected\\\";s:18:\\\"accounting_remarks\\\";s:4:\\\"oass\\\";s:11:\\\"reviewed_by\\\";i:3;s:16:\\\"approved_by_name\\\";N;s:12:\\\"sent_by_name\\\";N;s:12:\\\"submitted_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";s:11:\\\"reviewed_at\\\";s:19:\\\"2026-05-22 02:40:11\\\";s:11:\\\"released_at\\\";N;s:20:\\\"liquidation_due_date\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2026-05-22 02:26:37\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-05-22 02:40:11\\\";}s:9:\\\"decidedBy\\\";s:10:\\\"Accounting\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1779417611,\"delay\":null}', 0, NULL, 1779417611, 1779417611);

-- --------------------------------------------------------

--
-- Table structure for table `liquidations`
--

CREATE TABLE `liquidations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
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
(1, 2, 'May 2026', 10000.00, 'approved', NULL, NULL, '2026-05-18 01:29:42', '2026-05-26 18:09:49', '2026-05-17 19:38:21', '2026-05-26 18:09:49'),
(2, 2, 'May 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-24 06:34:12', '2026-05-24 06:34:12'),
(3, 14, '2026-01', 10000.00, 'pending', NULL, NULL, '2026-05-24 18:47:00', NULL, '2026-05-24 18:47:00', '2026-05-24 20:47:53'),
(4, 13, 'May 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-24 20:45:56', '2026-05-24 20:45:56'),
(5, 13, '2026-01', 15000.00, 'pending', NULL, NULL, '2026-05-25 00:50:23', NULL, '2026-05-25 00:50:23', '2026-05-25 02:41:29'),
(6, 9, 'May 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-25 01:29:57', '2026-05-25 01:29:57'),
(7, 13, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 05:58:47', '2026-05-27 05:58:47'),
(8, 14, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 07:40:49', '2026-05-27 07:40:49'),
(9, 12, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 11:09:37', '2026-05-27 11:09:37'),
(10, 23, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 21:16:37', '2026-05-27 21:16:37'),
(11, 9, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 21:23:11', '2026-05-27 21:23:11'),
(12, 15, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 21:31:01', '2026-05-27 21:31:01'),
(13, 15, 'May 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 21:33:16', '2026-05-27 21:33:16'),
(14, 19, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 22:31:38', '2026-05-27 22:31:38'),
(15, 20, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 23:04:05', '2026-05-27 23:04:05'),
(16, 16, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 23:08:49', '2026-05-27 23:08:49'),
(17, 18, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 23:12:02', '2026-05-27 23:12:02'),
(18, NULL, 'May 2026', 1000.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 23:43:20', '2026-05-27 23:43:20'),
(19, NULL, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 23:46:08', '2026-05-27 23:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `liquidation_expenses`
--

CREATE TABLE `liquidation_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `liquidation_id` bigint(20) UNSIGNED NOT NULL,
  `cash_advance_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_date` date NOT NULL,
  `particular_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_type` enum('debit','credit') NOT NULL DEFAULT 'debit',
  `transaction_details` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liquidation_expenses`
--

INSERT INTO `liquidation_expenses` (`id`, `liquidation_id`, `cash_advance_request_id`, `expense_date`, `particular_id`, `category_id`, `transaction_type`, `transaction_details`, `description`, `amount`, `receipt_path`, `created_at`, `updated_at`) VALUES
(33, 7, NULL, '2026-01-01', NULL, 9, 'debit', 'Freight', NULL, 12000.00, NULL, '2026-05-27 22:52:32', '2026-05-27 22:52:32'),
(34, 7, NULL, '2026-01-01', NULL, 22, 'debit', 'Transportation', NULL, 3000.00, NULL, '2026-05-27 22:52:57', '2026-05-27 22:52:57'),
(35, 9, NULL, '2026-01-02', NULL, 6, 'debit', 'dmc commission', NULL, 8700.00, NULL, '2026-05-27 22:56:15', '2026-05-27 22:56:15');

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
(98, '2026_05_15_000003_simplify_particulars_structure', 1),
(100, '2026_05_18_043812_create_particulars_and_fix_liquidation_expenses', 2),
(101, '2026_05_18_045849_fix_liquidation_expenses_table', 3),
(102, '2026_05_15_000003_remove_particular_id_from_liquidation_expenses', 4),
(103, '2026_05_22_000003_add_receipt_path_to_liquidation_expenses', 5),
(104, '2026_05_25_000001_add_category_id_to_liquidation_expenses', 6),
(105, '2026_05_25_000002_remove_particular_id_from_liquidation_expenses', 7),
(106, '2026_05_25_000003_add_new_categories', 8),
(107, '2026_05_15_042915_create_jobs_table', 9),
(108, '2026_05_15_045328_create_failed_jobs_table', 9),
(109, '2026_05_22_000001_restore_particular_id_to_liquidation_expenses', 9),
(110, '2026_05_22_000002_allow_nullable_legacy_liquidation_category', 9),
(112, '2026_05_28_000004_add_cash_advance_request_id_to_liquidation_expenses', 10),
(113, '2026_05_28_080000_make_liquidations_user_nullable', 10);

-- --------------------------------------------------------

--
-- Table structure for table `particulars`
--

CREATE TABLE `particulars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `particular_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `particulars`
--

INSERT INTO `particulars` (`id`, `category_id`, `particular_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hotel', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(2, 1, 'Hostel', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(3, 1, 'Resort', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(4, 15, 'Office Supplies', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(5, 15, 'Stationery', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(6, 15, 'Equipment', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(7, 15, 'Furniture', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(8, 10, 'Gasoline', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(9, 10, 'Diesel', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(10, 10, 'Vehicle Fuel', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(11, 22, 'Taxi', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(12, 22, 'Bus', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(13, 22, 'Car Rental', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(14, 22, 'Airfare', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(15, 8, 'Meals', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(16, 8, 'Snacks', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(17, 8, 'Beverages', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(18, 8, 'Team Lunch', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(19, 23, 'Electricity', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(20, 23, 'Water', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(21, 23, 'Internet', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(22, 23, 'Phone', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(23, 24, 'Oil Change', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(24, 24, 'Tire Replacement', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(25, 24, 'Repairs', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(26, 24, 'Cleaning', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(27, 12, 'Hourly Labor', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(28, 12, 'Contractor', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(29, 12, 'Consultant', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(30, 6, 'Sales Commission', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(31, 6, 'Agent Fee', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(32, 6, 'Broker Fee', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(33, 5, 'Employee Advance', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(34, 5, 'Project Advance', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(35, 5, 'Working Capital', NULL, '2026-05-17 20:50:01', '2026-05-17 20:50:01'),
(36, 1, 'Hotel', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(37, 1, 'Hostel', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(38, 1, 'Resort', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(39, 15, 'Office Supplies', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(40, 15, 'Stationery', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(41, 15, 'Equipment', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(42, 15, 'Furniture', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(43, 10, 'Gasoline', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(44, 10, 'Diesel', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(45, 10, 'Vehicle Fuel', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(46, 22, 'Taxi', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(47, 22, 'Bus', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(48, 22, 'Car Rental', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(49, 22, 'Airfare', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(50, 8, 'Meals', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(51, 8, 'Snacks', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(52, 8, 'Beverages', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(53, 8, 'Team Lunch', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(54, 23, 'Electricity', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(55, 23, 'Water', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(56, 23, 'Internet', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(57, 23, 'Phone', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(58, 24, 'Oil Change', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(59, 24, 'Tire Replacement', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(60, 24, 'Repairs', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(61, 24, 'Cleaning', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(62, 12, 'Hourly Labor', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(63, 12, 'Contractor', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(64, 12, 'Consultant', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(65, 6, 'Sales Commission', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(66, 6, 'Agent Fee', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(67, 6, 'Broker Fee', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(68, 5, 'Employee Advance', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(69, 5, 'Project Advance', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(70, 5, 'Working Capital', NULL, '2026-05-17 21:00:23', '2026-05-17 21:00:23'),
(71, 14, 'Advances', NULL, '2026-05-18 01:21:54', '2026-05-18 01:21:54'),
(72, 17, 'Qarrah', NULL, '2026-05-18 01:29:40', '2026-05-18 01:29:40');

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
('j6g4sFrZhm9mkwmuiX7Kwh44u1M9dtghpJ0DMsls', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTklkOEVsQjJ2UXdPQk1hdDhPVHd3dTBpVHdWQ0pEMW42N1o1M0lubSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50aW5nL2xpcXVpZGF0ZS1leHBlbnNlcz9tb250aD0wMSZ5ZWFyPTIwMjYiO3M6NToicm91dGUiO3M6Mjk6ImFjY291bnRpbmcubGlxdWlkYXRlLWV4cGVuc2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjQ7fQ==', 1779956094),
('NUFXgpovyrw7Shh7cxNfsUiYhX2nqSB4HrpE49IQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.121.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2Q1RGZsT2dzNVR3N2NZakRqRHVKb3l6UXBIVU9MTFFtQ2ZGdDMyaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779954292);

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
(8, '202600008', 'David Lee', 'david.lee@dmc.com', '$2y$12$iX1pKPMGgfLhqNgilKrvl.RfUfTA7v1wdT0RQj8sco0KiOCVgI.Za', 2, '2026-05-14 23:36:36', '2026-05-14 23:36:36'),
(9, '202600009', 'ALEX A', 'AA202600009@dmc.com', '$2y$12$JDrofk2B1jpSFr.rNoiuYO/yx5r8c2HyCQcDzWJYdYXT3gK.YG.Zm', 2, '2026-05-24 15:33:20', '2026-05-24 15:33:20'),
(10, '202600010', 'ALEXANDER C.', 'AC202600010@dmc.com', '$2y$12$SgZFFMtNs31iRfU5uHXr8e1NjIw.cVJ5c0h.TYWexPGl0ce9m5uVe', 2, '2026-05-24 15:33:55', '2026-05-24 15:33:55'),
(11, '202600011', 'ANGEL V.', 'AV202600011@dmc.com', '$2y$12$kUHnWjStPR0M97L6iFaC7u.SqQabG43kng9Q71NSgxFJgkImZjbZa', 2, '2026-05-24 15:39:04', '2026-05-24 15:39:04'),
(12, '202600012', 'CHRISTINE R.', 'CR202600012@dmc.com', '$2y$12$OCnE1qIcsl.nKaKcSgKpa.5zFzBCynbwKuhZMTtLaSN08ey7SjS7y', 2, '2026-05-24 15:40:12', '2026-05-24 15:40:12'),
(13, '202600013', 'ESPENRANZA R.', 'ER202600013@dmc.com', '$2y$12$h5gxKjWp6ry3NB7HZKq6Su7SSnkJRhWlJywzQGtMt1BtTh7wVJ/8u', 2, '2026-05-24 15:40:26', '2026-05-24 15:40:26'),
(14, '202600014', 'GERLIE D.', 'GD202600014@dmc.com', '$2y$12$OcwZxzgGH.sp6qpGt7kiJuSXqBMeXskkF8ux4rIHuoGSHKB9O0mz2', 2, '2026-05-24 15:40:38', '2026-05-24 15:40:38'),
(15, '202600015', 'JENNIFER M.', 'JM202600015@dmc.com', '$2y$12$eBaPQugUV6HxXEg4gElDpemvcSTpmwbGf.bBxs/wYoPYPaVBZDpOy', 2, '2026-05-24 15:40:54', '2026-05-24 15:40:54'),
(16, '202600016', 'JINKY A.', 'JA202600016@dmc.com', '$2y$12$IzQf3jZV.Zxcieva0tjZtufZtVG5qTnlgbSF7QhyDTmk7Zy/8HAGC', 2, '2026-05-24 15:42:05', '2026-05-24 15:42:05'),
(17, '202600017', 'JOSEPH D.', 'JD202600017@dmc.com', '$2y$12$q9OyX3/DdVHzpFTQqRZEEOZ9EQBy6VfZrgy5NzChlIynuo2DbV6YS', 2, '2026-05-24 15:42:15', '2026-05-24 15:42:15'),
(18, '202600018', 'JOSHUA R.', 'JR202600018@dmc.com', '$2y$12$r.r23T9gj9nhiavp8nk5xuWsP0qudfsMlMK/4h/luG3iR5zcQbRR2', 2, '2026-05-24 15:43:09', '2026-05-24 15:43:09'),
(19, '202600019', 'MARIA M.', 'MM202600019@dmc.com', '$2y$12$LuqUG2w/HOiABBAgUk3jA.tzMRje37C0GTUypiw3nwLsjP9cl.26q', 2, '2026-05-24 15:43:19', '2026-05-24 15:43:19'),
(20, '202600020', 'MARICEL V.', 'MV202600020@dmc.com', '$2y$12$CBIpU2PsQYYlr6zAgFWCXO/m0hPhSZdEWDN2R5zFuq9mo5ml62Vtu', 2, '2026-05-24 15:43:33', '2026-05-24 15:43:33'),
(21, '202600021', 'NOEL E.', 'NE202600021@dmc.com', '$2y$12$sT5afHjHB67orjsKvBd5GONc8ZxM7PLrC/4I4mRgCzRqGiLZU9WQy', 2, '2026-05-24 15:44:47', '2026-05-24 15:44:47'),
(22, '202600022', 'PEARLY G.', 'PG202600022@dmc.com', '$2y$12$doZcTWep.MJgkK3I/FH18eSbwbyzRznZOhXw68rvB.gdDCjzknepO', 2, '2026-05-24 15:45:04', '2026-05-24 15:45:04'),
(23, '202600023', 'REGINALD Q.', 'RQ202600023@dmc.com', '$2y$12$MJTQtdHc0LFxQGELTY93L.oHh1F4TRM4atIQWWkNfUD5CNCXM2S/W', 2, '2026-05-24 15:46:23', '2026-05-24 15:46:23'),
(24, '202600024', 'STELLA C.', 'SC202600024@dmc.com', '$2y$12$VLhLKny6GGAye8n5DPfqHOHVNJizlDi1LVD7WKxXDaPM7dpT83xxK', 3, '2026-05-24 16:13:15', '2026-05-24 16:13:15');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `liquidations`
--
ALTER TABLE `liquidations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liquidations_status_cutoff_period_index` (`status`,`cutoff_period`),
  ADD KEY `liquidations_user_id_foreign` (`user_id`);

--
-- Indexes for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liquidation_expenses_liquidation_id_foreign` (`liquidation_id`),
  ADD KEY `liquidation_expenses_category_id_foreign` (`category_id`),
  ADD KEY `liquidation_expenses_particular_id_foreign` (`particular_id`),
  ADD KEY `liquidation_expenses_cash_advance_request_id_foreign` (`cash_advance_request_id`);

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
  ADD KEY `particulars_category_id_foreign` (`category_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cash_advance_requests`
--
ALTER TABLE `cash_advance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `cash_advance_request_attachments`
--
ALTER TABLE `cash_advance_request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_advance_request_audits`
--
ALTER TABLE `cash_advance_request_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `particulars`
--
ALTER TABLE `particulars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  ADD CONSTRAINT `liquidations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  ADD CONSTRAINT `liquidation_expenses_cash_advance_request_id_foreign` FOREIGN KEY (`cash_advance_request_id`) REFERENCES `cash_advance_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liquidation_expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `liquidation_expenses_liquidation_id_foreign` FOREIGN KEY (`liquidation_id`) REFERENCES `liquidations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liquidation_expenses_particular_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `particulars`
--
ALTER TABLE `particulars`
  ADD CONSTRAINT `particulars_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

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
