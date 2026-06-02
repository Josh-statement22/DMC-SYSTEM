-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2026 at 02:08 AM
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
-- Table structure for table `accounting_budget_accounts`
--

CREATE TABLE `accounting_budget_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_type` varchar(40) NOT NULL,
  `reference` varchar(120) NOT NULL,
  `name` varchar(255) NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_budget_accounts`
--

INSERT INTO `accounting_budget_accounts` (`id`, `account_type`, `reference`, `name`, `opening_balance`, `meta`, `created_at`, `updated_at`) VALUES
(99, 'PARENT_BUDGET', 'cash-advance-request:758', 'Parent Budget #758', 0.00, NULL, '2026-05-31 12:05:52', '2026-05-31 12:25:32'),
(100, 'BUDGET_ALLOCATION_CLEARING', 'system:budget-allocation-clearing', 'Budget Allocation Clearing', 0.00, NULL, '2026-05-31 12:05:52', '2026-05-31 12:25:32'),
(102, 'BUDGET_USAGE_CLEARING', 'system:budget-usage-clearing', 'Budget Usage Clearing', 0.00, NULL, '2026-05-31 12:05:52', '2026-05-31 12:25:32'),
(123, 'LEGACY_RUNNING_BALANCE', 'month:2026-01', 'Legacy Running Balance January 2026', 0.00, NULL, '2026-05-31 12:06:38', '2026-05-31 12:25:32'),
(142, 'PARENT_BUDGET', 'cash-advance-request:757', 'Parent Budget #757', 0.00, NULL, '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(152, 'PARENT_BUDGET', 'cash-advance-request:756', 'Parent Budget #756', 0.00, NULL, '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(162, 'PARENT_BUDGET', 'cash-advance-request:755', 'Parent Budget #755', 0.00, NULL, '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(166, 'PARENT_BUDGET', 'cash-advance-request:754', 'Parent Budget #754', 0.00, NULL, '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(170, 'PARENT_BUDGET', 'cash-advance-request:753', 'Parent Budget #753', 0.00, NULL, '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(180, 'PARENT_BUDGET', 'cash-advance-request:751', 'Parent Budget #751', 0.00, NULL, '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(190, 'PARENT_BUDGET', 'cash-advance-request:747', 'Parent Budget #747', 0.00, NULL, '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(200, 'PARENT_BUDGET', 'cash-advance-request:759', 'Parent Budget #759', 0.00, NULL, '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(210, 'PARENT_BUDGET', 'cash-advance-request:765', 'Parent Budget #765', 0.00, NULL, '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(280, 'PARENT_BUDGET', 'cash-advance-request:768', 'Parent Budget #768', 0.00, NULL, '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(308, 'PARENT_BUDGET', 'cash-advance-request:769', 'Parent Budget #769', 0.00, NULL, '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(348, 'PARENT_BUDGET', 'cash-advance-request:776', 'Parent Budget #776', 0.00, NULL, '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(402, 'PARENT_BUDGET', 'cash-advance-request:782', 'Parent Budget #782', 0.00, NULL, '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(406, 'PARENT_BUDGET', 'cash-advance-request:784', 'Parent Budget #784', 0.00, NULL, '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(446, 'PARENT_BUDGET', 'cash-advance-request:786', 'Parent Budget #786', 0.00, NULL, '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(500, 'PARENT_BUDGET', 'cash-advance-request:788', 'Parent Budget #788', 0.00, NULL, '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(540, 'PARENT_BUDGET', 'cash-advance-request:789', 'Parent Budget #789', 0.00, NULL, '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(558, 'PARENT_BUDGET', 'cash-advance-request:792', 'Parent Budget #792', 0.00, NULL, '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(586, 'PARENT_BUDGET', 'cash-advance-request:803', 'Parent Budget #803', 0.00, NULL, '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(614, 'PARENT_BUDGET', 'cash-advance-request:867', 'Parent Budget #867', 0.00, NULL, '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(702, 'PARENT_BUDGET', 'cash-advance-request:869', 'Parent Budget #869', 0.00, NULL, '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(720, 'PARENT_BUDGET', 'cash-advance-request:872', 'Parent Budget #872', 0.00, NULL, '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(730, 'PARENT_BUDGET', 'cash-advance-request:879', 'Parent Budget #879', 0.00, NULL, '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(758, 'PARENT_BUDGET', 'cash-advance-request:892', 'Parent Budget #892', 0.00, NULL, '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(776, 'PARENT_BUDGET', 'cash-advance-request:904', 'Parent Budget #904', 0.00, NULL, '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(816, 'PARENT_BUDGET', 'cash-advance-request:914', 'Parent Budget #914', 0.00, NULL, '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(826, 'PARENT_BUDGET', 'cash-advance-request:926', 'Parent Budget #926', 0.00, NULL, '2026-05-31 15:17:51', '2026-05-31 15:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_budget_journal_entries`
--

CREATE TABLE `accounting_budget_journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `transaction_type` varchar(40) NOT NULL,
  `cash_advance_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `liquidation_expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_budget_journal_entries`
--

INSERT INTO `accounting_budget_journal_entries` (`id`, `reference_no`, `transaction_type`, `cash_advance_request_id`, `liquidation_expense_id`, `description`, `created_by`, `meta`, `created_at`, `updated_at`) VALUES
(41, 'cash-advance-request:758:budget-allocation', 'BUDGET_ALLOCATION', 758, NULL, 'Parent Budget initialized', 3, '{\"parent_amount\":2000}', '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(42, 'liquidation-expense:70:budget-usage', 'BUDGET_USAGE', 758, 70, 'Budget usage recorded', 3, '{\"expense_amount\":840}', '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(43, 'liquidation-expense:71:budget-usage', 'BUDGET_USAGE', 758, 71, 'Budget usage recorded', 3, '{\"expense_amount\":300}', '2026-05-31 12:06:13', '2026-05-31 12:06:13'),
(44, 'liquidation-expense:72:budget-usage', 'BUDGET_USAGE', 758, 72, 'Budget usage recorded', 3, '{\"expense_amount\":1021}', '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(45, 'wallet-transfer:758:c37318d2-5c65-4a9b-a6a9-fc79e27be51a', 'LEGACY_TRANSFER', 758, NULL, 'Deprecated automatic transfer retained for audit history', 3, '{\"source_account_id\":123,\"destination_account_id\":99}', '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(46, 'budget-variance-reversal:45', 'LEGACY_TRANSFER_REVERSAL', 758, NULL, 'Reversal of legacy automatic transfer. Budget overspending is a variance, not a cash movement.', 3, '{\"reverses_journal_entry_id\":45,\"reason\":\"Separate actual cash from budget variance\"}', '2026-05-31 12:23:57', '2026-05-31 12:23:57'),
(52, 'cash-advance-request:757:budget-allocation', 'BUDGET_ALLOCATION', 757, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(53, 'liquidation-expense:74:budget-usage', 'BUDGET_USAGE', 757, 74, 'Budget usage recorded', 3, '{\"budget_usage\":14074}', '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(54, 'liquidation-expense:75:budget-usage', 'BUDGET_USAGE', 757, 75, 'Budget usage recorded', 3, '{\"budget_usage\":688}', '2026-05-31 12:32:35', '2026-05-31 12:32:35'),
(55, 'cash-advance-request:756:budget-allocation', 'BUDGET_ALLOCATION', 756, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":3500}', '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(56, 'liquidation-expense:76:budget-usage', 'BUDGET_USAGE', 756, 76, 'Budget usage recorded', 3, '{\"budget_usage\":1098}', '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(57, 'liquidation-expense:77:budget-usage', 'BUDGET_USAGE', 756, 77, 'Budget usage recorded', 3, '{\"budget_usage\":1519}', '2026-05-31 12:33:27', '2026-05-31 12:33:27'),
(58, 'cash-advance-request:755:budget-allocation', 'BUDGET_ALLOCATION', 755, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":2016}', '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(59, 'liquidation-expense:78:budget-usage', 'BUDGET_USAGE', 755, 78, 'Budget usage recorded', 3, '{\"budget_usage\":2016}', '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(60, 'cash-advance-request:754:budget-allocation', 'BUDGET_ALLOCATION', 754, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":1000}', '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(61, 'liquidation-expense:79:budget-usage', 'BUDGET_USAGE', 754, 79, 'Budget usage recorded', 3, '{\"budget_usage\":1000}', '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(62, 'cash-advance-request:753:budget-allocation', 'BUDGET_ALLOCATION', 753, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":3000}', '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(63, 'liquidation-expense:80:budget-usage', 'BUDGET_USAGE', 753, 80, 'Budget usage recorded', 3, '{\"budget_usage\":899}', '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(64, 'liquidation-expense:81:budget-usage', 'BUDGET_USAGE', 753, 81, 'Budget usage recorded', 3, '{\"budget_usage\":2101}', '2026-05-31 12:35:11', '2026-05-31 12:35:11'),
(65, 'cash-advance-request:751:budget-allocation', 'BUDGET_ALLOCATION', 751, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":14519}', '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(66, 'liquidation-expense:82:budget-usage', 'BUDGET_USAGE', 751, 82, 'Budget usage recorded', 3, '{\"budget_usage\":6409}', '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(67, 'liquidation-expense:83:budget-usage', 'BUDGET_USAGE', 751, 83, 'Budget usage recorded', 3, '{\"budget_usage\":8110}', '2026-05-31 12:36:08', '2026-05-31 12:36:08'),
(68, 'cash-advance-request:747:budget-allocation', 'BUDGET_ALLOCATION', 747, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":2000}', '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(69, 'liquidation-expense:84:budget-usage', 'BUDGET_USAGE', 747, 84, 'Budget usage recorded', 3, '{\"budget_usage\":800}', '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(70, 'liquidation-expense:85:budget-usage', 'BUDGET_USAGE', 747, 85, 'Budget usage recorded', 3, '{\"budget_usage\":1200}', '2026-05-31 12:37:35', '2026-05-31 12:37:35'),
(71, 'cash-advance-request:759:budget-allocation', 'BUDGET_ALLOCATION', 759, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":4000}', '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(72, 'liquidation-expense:86:budget-usage', 'BUDGET_USAGE', 759, 86, 'Budget usage recorded', 3, '{\"budget_usage\":570}', '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(73, 'liquidation-expense:87:budget-usage', 'BUDGET_USAGE', 759, 87, 'Budget usage recorded', 3, '{\"budget_usage\":3500}', '2026-05-31 12:40:16', '2026-05-31 12:40:16'),
(74, 'cash-advance-request:765:budget-allocation', 'BUDGET_ALLOCATION', 765, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(75, 'liquidation-expense:88:budget-usage', 'BUDGET_USAGE', 765, 88, 'Budget usage recorded', 3, '{\"budget_usage\":267.28}', '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(76, 'liquidation-expense:89:budget-usage', 'BUDGET_USAGE', 765, 89, 'Budget usage recorded', 3, '{\"budget_usage\":6539}', '2026-05-31 12:42:25', '2026-05-31 12:42:25'),
(77, 'liquidation-expense:90:budget-usage', 'BUDGET_USAGE', 765, 90, 'Budget usage recorded', 3, '{\"budget_usage\":1300}', '2026-05-31 12:42:38', '2026-05-31 12:42:38'),
(78, 'liquidation-expense:91:budget-usage', 'BUDGET_USAGE', 765, 91, 'Budget usage recorded', 3, '{\"budget_usage\":300}', '2026-05-31 12:42:56', '2026-05-31 12:42:56'),
(79, 'liquidation-expense:92:budget-usage', 'BUDGET_USAGE', 765, 92, 'Budget usage recorded', 3, '{\"budget_usage\":5513}', '2026-05-31 12:43:11', '2026-05-31 12:43:11'),
(80, 'liquidation-expense:93:budget-usage', 'BUDGET_USAGE', 765, 93, 'Budget usage recorded', 3, '{\"budget_usage\":4120.72}', '2026-05-31 12:43:33', '2026-05-31 12:43:33'),
(81, 'liquidation-expense:94:budget-usage', 'BUDGET_USAGE', 765, 94, 'Budget usage recorded', 3, '{\"budget_usage\":1960}', '2026-05-31 12:43:53', '2026-05-31 12:43:53'),
(82, 'cash-advance-request:768:budget-allocation', 'BUDGET_ALLOCATION', 768, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":5000}', '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(83, 'liquidation-expense:95:budget-usage', 'BUDGET_USAGE', 768, 95, 'Budget usage recorded', 3, '{\"budget_usage\":512.72}', '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(84, 'liquidation-expense:96:budget-usage', 'BUDGET_USAGE', 768, 96, 'Budget usage recorded', 3, '{\"budget_usage\":1009}', '2026-05-31 12:46:26', '2026-05-31 12:46:26'),
(85, 'liquidation-expense:97:budget-usage', 'BUDGET_USAGE', 768, 97, 'Budget usage recorded', 3, '{\"budget_usage\":2000}', '2026-05-31 12:46:40', '2026-05-31 12:46:40'),
(86, 'liquidation-expense:98:budget-usage', 'BUDGET_USAGE', 768, 98, 'Budget usage recorded', 3, '{\"budget_usage\":1545}', '2026-05-31 12:46:57', '2026-05-31 12:46:57'),
(87, 'cash-advance-request:769:budget-allocation', 'BUDGET_ALLOCATION', 769, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":5000}', '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(88, 'liquidation-expense:99:budget-usage', 'BUDGET_USAGE', 769, 99, 'Budget usage recorded', 3, '{\"budget_usage\":450}', '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(89, 'liquidation-expense:100:budget-usage', 'BUDGET_USAGE', 769, 100, 'Budget usage recorded', 3, '{\"budget_usage\":1000}', '2026-05-31 12:47:34', '2026-05-31 12:47:34'),
(90, 'liquidation-expense:101:budget-usage', 'BUDGET_USAGE', 769, 101, 'Budget usage recorded', 3, '{\"budget_usage\":2471}', '2026-05-31 12:47:49', '2026-05-31 12:47:49'),
(91, 'liquidation-expense:102:budget-usage', 'BUDGET_USAGE', 769, 102, 'Budget usage recorded', 3, '{\"budget_usage\":360}', '2026-05-31 12:48:03', '2026-05-31 12:48:03'),
(92, 'liquidation-expense:103:budget-usage', 'BUDGET_USAGE', 769, 103, 'Budget usage recorded', 3, '{\"budget_usage\":719}', '2026-05-31 12:48:18', '2026-05-31 12:48:18'),
(93, 'cash-advance-request:776:budget-allocation', 'BUDGET_ALLOCATION', 776, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(94, 'liquidation-expense:104:budget-usage', 'BUDGET_USAGE', 776, 104, 'Budget usage recorded', 3, '{\"budget_usage\":6000}', '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(95, 'liquidation-expense:105:budget-usage', 'BUDGET_USAGE', 776, 105, 'Budget usage recorded', 3, '{\"budget_usage\":110}', '2026-05-31 14:42:22', '2026-05-31 14:42:22'),
(96, 'liquidation-expense:106:budget-usage', 'BUDGET_USAGE', 776, 106, 'Budget usage recorded', 3, '{\"budget_usage\":240}', '2026-05-31 14:42:34', '2026-05-31 14:42:34'),
(97, 'liquidation-expense:107:budget-usage', 'BUDGET_USAGE', 776, 107, 'Budget usage recorded', 3, '{\"budget_usage\":667}', '2026-05-31 14:42:46', '2026-05-31 14:42:46'),
(98, 'liquidation-expense:108:budget-usage', 'BUDGET_USAGE', 776, 108, 'Budget usage recorded', 3, '{\"budget_usage\":983}', '2026-05-31 14:42:59', '2026-05-31 14:42:59'),
(99, 'liquidation-expense:109:budget-usage', 'BUDGET_USAGE', 776, 109, 'Budget usage recorded', 3, '{\"budget_usage\":2000}', '2026-05-31 14:43:07', '2026-05-31 14:43:07'),
(100, 'cash-advance-request:782:budget-allocation', 'BUDGET_ALLOCATION', 782, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":5000}', '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(101, 'liquidation-expense:110:budget-usage', 'BUDGET_USAGE', 782, 110, 'Budget usage recorded', 3, '{\"budget_usage\":5080}', '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(102, 'cash-advance-request:784:budget-allocation', 'BUDGET_ALLOCATION', 784, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":5000}', '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(103, 'liquidation-expense:111:budget-usage', 'BUDGET_USAGE', 784, 111, 'Budget usage recorded', 3, '{\"budget_usage\":4043}', '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(104, 'liquidation-expense:112:budget-usage', 'BUDGET_USAGE', 784, 112, 'Budget usage recorded', 3, '{\"budget_usage\":209}', '2026-05-31 14:45:48', '2026-05-31 14:45:48'),
(105, 'liquidation-expense:113:budget-usage', 'BUDGET_USAGE', 784, 113, 'Budget usage recorded', 3, '{\"budget_usage\":2280}', '2026-05-31 14:46:01', '2026-05-31 14:46:01'),
(106, 'liquidation-expense:114:budget-usage', 'BUDGET_USAGE', 784, 114, 'Budget usage recorded', 3, '{\"budget_usage\":2500}', '2026-05-31 14:46:22', '2026-05-31 14:46:22'),
(107, 'liquidation-expense:115:budget-usage', 'BUDGET_USAGE', 784, 115, 'Budget usage recorded', 3, '{\"budget_usage\":5968}', '2026-05-31 14:46:38', '2026-05-31 14:46:38'),
(108, 'cash-advance-request:786:budget-allocation', 'BUDGET_ALLOCATION', 786, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(109, 'liquidation-expense:116:budget-usage', 'BUDGET_USAGE', 786, 116, 'Budget usage recorded', 3, '{\"budget_usage\":7964}', '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(110, 'liquidation-expense:117:budget-usage', 'BUDGET_USAGE', 786, 117, 'Budget usage recorded', 3, '{\"budget_usage\":1816}', '2026-05-31 14:47:23', '2026-05-31 14:47:23'),
(111, 'liquidation-expense:118:budget-usage', 'BUDGET_USAGE', 786, 118, 'Budget usage recorded', 3, '{\"budget_usage\":220}', '2026-05-31 14:47:33', '2026-05-31 14:47:33'),
(112, 'liquidation-expense:119:budget-usage', 'BUDGET_USAGE', 786, 119, 'Budget usage recorded', 3, '{\"budget_usage\":1562}', '2026-05-31 14:47:50', '2026-05-31 14:47:50'),
(113, 'liquidation-expense:120:budget-usage', 'BUDGET_USAGE', 786, 120, 'Budget usage recorded', 3, '{\"budget_usage\":7186}', '2026-05-31 14:48:03', '2026-05-31 14:48:03'),
(114, 'liquidation-expense:121:budget-usage', 'BUDGET_USAGE', 786, 121, 'Budget usage recorded', 3, '{\"budget_usage\":1252}', '2026-05-31 14:48:17', '2026-05-31 14:48:17'),
(115, 'cash-advance-request:788:budget-allocation', 'BUDGET_ALLOCATION', 788, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(116, 'liquidation-expense:122:budget-usage', 'BUDGET_USAGE', 788, 122, 'Budget usage recorded', 3, '{\"budget_usage\":4043}', '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(117, 'liquidation-expense:123:budget-usage', 'BUDGET_USAGE', 788, 123, 'Budget usage recorded', 3, '{\"budget_usage\":209}', '2026-05-31 14:48:47', '2026-05-31 14:48:47'),
(118, 'liquidation-expense:124:budget-usage', 'BUDGET_USAGE', 788, 124, 'Budget usage recorded', 3, '{\"budget_usage\":2280}', '2026-05-31 14:48:56', '2026-05-31 14:48:56'),
(119, 'liquidation-expense:125:budget-usage', 'BUDGET_USAGE', 788, 125, 'Budget usage recorded', 3, '{\"budget_usage\":2500}', '2026-05-31 14:49:05', '2026-05-31 14:49:05'),
(120, 'liquidation-expense:126:budget-usage', 'BUDGET_USAGE', 788, 126, 'Budget usage recorded', 3, '{\"budget_usage\":5968}', '2026-05-31 14:49:17', '2026-05-31 14:49:17'),
(121, 'cash-advance-request:789:budget-allocation', 'BUDGET_ALLOCATION', 789, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(122, 'liquidation-expense:127:budget-usage', 'BUDGET_USAGE', 789, 127, 'Budget usage recorded', 3, '{\"budget_usage\":1562}', '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(123, 'liquidation-expense:128:budget-usage', 'BUDGET_USAGE', 789, 128, 'Budget usage recorded', 3, '{\"budget_usage\":7186}', '2026-05-31 14:50:08', '2026-05-31 14:50:08'),
(124, 'liquidation-expense:129:budget-usage', 'BUDGET_USAGE', 789, 129, 'Budget usage recorded', 3, '{\"budget_usage\":1252}', '2026-05-31 14:50:23', '2026-05-31 14:50:23'),
(125, 'cash-advance-request:792:budget-allocation', 'BUDGET_ALLOCATION', 792, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(126, 'liquidation-expense:130:budget-usage', 'BUDGET_USAGE', 792, 130, 'Budget usage recorded', 3, '{\"budget_usage\":490}', '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(127, 'liquidation-expense:131:budget-usage', 'BUDGET_USAGE', 792, 131, 'Budget usage recorded', 3, '{\"budget_usage\":5150}', '2026-05-31 14:51:28', '2026-05-31 14:51:28'),
(128, 'liquidation-expense:132:budget-usage', 'BUDGET_USAGE', 792, 132, 'Budget usage recorded', 3, '{\"budget_usage\":60}', '2026-05-31 14:51:48', '2026-05-31 14:51:48'),
(129, 'liquidation-expense:133:budget-usage', 'BUDGET_USAGE', 792, 133, 'Budget usage recorded', 3, '{\"budget_usage\":4309}', '2026-05-31 14:52:02', '2026-05-31 14:52:02'),
(130, 'cash-advance-request:803:budget-allocation', 'BUDGET_ALLOCATION', 803, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":5000}', '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(131, 'liquidation-expense:134:budget-usage', 'BUDGET_USAGE', 803, 134, 'Budget usage recorded', 3, '{\"budget_usage\":2018}', '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(132, 'liquidation-expense:135:budget-usage', 'BUDGET_USAGE', 803, 135, 'Budget usage recorded', 3, '{\"budget_usage\":114}', '2026-05-31 14:56:54', '2026-05-31 14:56:54'),
(133, 'liquidation-expense:136:budget-usage', 'BUDGET_USAGE', 803, 136, 'Budget usage recorded', 3, '{\"budget_usage\":1170}', '2026-05-31 14:57:07', '2026-05-31 14:57:07'),
(134, 'liquidation-expense:137:budget-usage', 'BUDGET_USAGE', 803, 137, 'Budget usage recorded', 3, '{\"budget_usage\":1698}', '2026-05-31 14:57:24', '2026-05-31 14:57:24'),
(135, 'cash-advance-request:867:budget-allocation', 'BUDGET_ALLOCATION', 867, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(136, 'liquidation-expense:138:budget-usage', 'BUDGET_USAGE', 867, 138, 'Budget usage recorded', 3, '{\"budget_usage\":680}', '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(137, 'liquidation-expense:139:budget-usage', 'BUDGET_USAGE', 867, 139, 'Budget usage recorded', 3, '{\"budget_usage\":33}', '2026-05-31 14:58:08', '2026-05-31 14:58:08'),
(138, 'liquidation-expense:140:budget-usage', 'BUDGET_USAGE', 867, 140, 'Budget usage recorded', 3, '{\"budget_usage\":3000}', '2026-05-31 14:58:16', '2026-05-31 14:58:16'),
(139, 'liquidation-expense:141:budget-usage', 'BUDGET_USAGE', 867, 141, 'Budget usage recorded', 3, '{\"budget_usage\":150}', '2026-05-31 14:58:23', '2026-05-31 14:58:23'),
(140, 'liquidation-expense:142:budget-usage', 'BUDGET_USAGE', 867, 142, 'Budget usage recorded', 3, '{\"budget_usage\":270}', '2026-05-31 14:58:31', '2026-05-31 14:58:31'),
(141, 'liquidation-expense:143:budget-usage', 'BUDGET_USAGE', 867, 143, 'Budget usage recorded', 3, '{\"budget_usage\":360}', '2026-05-31 14:58:40', '2026-05-31 14:58:40'),
(142, 'liquidation-expense:144:budget-usage', 'BUDGET_USAGE', 867, 144, 'Budget usage recorded', 3, '{\"budget_usage\":60}', '2026-05-31 14:58:51', '2026-05-31 14:58:51'),
(143, 'liquidation-expense:145:budget-usage', 'BUDGET_USAGE', 867, 145, 'Budget usage recorded', 3, '{\"budget_usage\":5447}', '2026-05-31 14:59:00', '2026-05-31 14:59:00'),
(144, 'cash-advance-request:869:budget-allocation', 'BUDGET_ALLOCATION', 869, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":13444}', '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(145, 'liquidation-expense:146:budget-usage', 'BUDGET_USAGE', 869, 146, 'Budget usage recorded', 3, '{\"budget_usage\":401}', '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(146, 'liquidation-expense:147:budget-usage', 'BUDGET_USAGE', 869, 147, 'Budget usage recorded', 3, '{\"budget_usage\":9706}', '2026-05-31 14:59:36', '2026-05-31 14:59:36'),
(147, 'liquidation-expense:148:budget-usage', 'BUDGET_USAGE', 869, 148, 'Budget usage recorded', 3, '{\"budget_usage\":3337}', '2026-05-31 14:59:44', '2026-05-31 14:59:44'),
(148, 'cash-advance-request:872:budget-allocation', 'BUDGET_ALLOCATION', 872, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(149, 'liquidation-expense:149:budget-usage', 'BUDGET_USAGE', 872, 149, 'Budget usage recorded', 3, '{\"budget_usage\":7185}', '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(150, 'liquidation-expense:150:budget-usage', 'BUDGET_USAGE', 872, 150, 'Budget usage recorded', 3, '{\"budget_usage\":3000}', '2026-05-31 15:00:13', '2026-05-31 15:00:13'),
(151, 'cash-advance-request:879:budget-allocation', 'BUDGET_ALLOCATION', 879, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(152, 'liquidation-expense:151:budget-usage', 'BUDGET_USAGE', 879, 151, 'Budget usage recorded', 3, '{\"budget_usage\":2509}', '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(153, 'liquidation-expense:152:budget-usage', 'BUDGET_USAGE', 879, 152, 'Budget usage recorded', 3, '{\"budget_usage\":509}', '2026-05-31 15:01:34', '2026-05-31 15:01:34'),
(154, 'liquidation-expense:153:budget-usage', 'BUDGET_USAGE', 879, 153, 'Budget usage recorded', 3, '{\"budget_usage\":3930}', '2026-05-31 15:01:46', '2026-05-31 15:01:46'),
(155, 'liquidation-expense:154:budget-usage', 'BUDGET_USAGE', 879, 154, 'Budget usage recorded', 3, '{\"budget_usage\":3052}', '2026-05-31 15:01:57', '2026-05-31 15:01:57'),
(156, 'cash-advance-request:892:budget-allocation', 'BUDGET_ALLOCATION', 892, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(157, 'liquidation-expense:155:budget-usage', 'BUDGET_USAGE', 892, 155, 'Budget usage recorded', 3, '{\"budget_usage\":6900}', '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(158, 'liquidation-expense:156:budget-usage', 'BUDGET_USAGE', 892, 156, 'Budget usage recorded', 3, '{\"budget_usage\":1872}', '2026-05-31 15:04:04', '2026-05-31 15:04:04'),
(159, 'liquidation-expense:157:budget-usage', 'BUDGET_USAGE', 892, 157, 'Budget usage recorded', 3, '{\"budget_usage\":1228}', '2026-05-31 15:04:18', '2026-05-31 15:04:18'),
(160, 'cash-advance-request:904:budget-allocation', 'BUDGET_ALLOCATION', 904, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(161, 'liquidation-expense:158:budget-usage', 'BUDGET_USAGE', 904, 158, 'Budget usage recorded', 3, '{\"budget_usage\":150}', '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(162, 'liquidation-expense:159:budget-usage', 'BUDGET_USAGE', 904, 159, 'Budget usage recorded', 3, '{\"budget_usage\":1509}', '2026-05-31 15:12:26', '2026-05-31 15:12:26'),
(163, 'liquidation-expense:160:budget-usage', 'BUDGET_USAGE', 904, 160, 'Budget usage recorded', 3, '{\"budget_usage\":1376}', '2026-05-31 15:12:38', '2026-05-31 15:12:38'),
(164, 'liquidation-expense:161:budget-usage', 'BUDGET_USAGE', 904, 161, 'Budget usage recorded', 3, '{\"budget_usage\":588}', '2026-05-31 15:12:53', '2026-05-31 15:12:53'),
(165, 'liquidation-expense:162:budget-usage', 'BUDGET_USAGE', 904, 162, 'Budget usage recorded', 3, '{\"budget_usage\":1077}', '2026-05-31 15:13:05', '2026-05-31 15:13:05'),
(166, 'cash-advance-request:914:budget-allocation', 'BUDGET_ALLOCATION', 914, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":20208}', '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(167, 'liquidation-expense:163:budget-usage', 'BUDGET_USAGE', 914, 163, 'Budget usage recorded', 3, '{\"budget_usage\":18378}', '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(168, 'liquidation-expense:164:budget-usage', 'BUDGET_USAGE', 914, 164, 'Budget usage recorded', 3, '{\"budget_usage\":1830}', '2026-05-31 15:16:26', '2026-05-31 15:16:26'),
(169, 'cash-advance-request:926:budget-allocation', 'BUDGET_ALLOCATION', 926, NULL, 'Parent Budget initialized', 3, '{\"allocated_budget\":10000}', '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(170, 'liquidation-expense:165:budget-usage', 'BUDGET_USAGE', 926, 165, 'Budget usage recorded', 3, '{\"budget_usage\":2700}', '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(171, 'liquidation-expense:166:budget-usage', 'BUDGET_USAGE', 926, 166, 'Budget usage recorded', 3, '{\"budget_usage\":588.72}', '2026-05-31 15:18:02', '2026-05-31 15:18:02'),
(172, 'liquidation-expense:167:budget-usage', 'BUDGET_USAGE', 926, 167, 'Budget usage recorded', 3, '{\"budget_usage\":1417.28}', '2026-05-31 15:18:15', '2026-05-31 15:18:15');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_budget_journal_lines`
--

CREATE TABLE `accounting_budget_journal_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_budget_journal_lines`
--

INSERT INTO `accounting_budget_journal_lines` (`id`, `journal_entry_id`, `account_id`, `amount`, `created_at`, `updated_at`) VALUES
(81, 41, 99, 2000.00, '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(82, 41, 100, -2000.00, '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(83, 42, 99, -840.00, '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(84, 42, 102, 840.00, '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(85, 43, 99, -300.00, '2026-05-31 12:06:13', '2026-05-31 12:06:13'),
(86, 43, 102, 300.00, '2026-05-31 12:06:13', '2026-05-31 12:06:13'),
(87, 44, 99, -1021.00, '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(88, 44, 102, 1021.00, '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(89, 45, 123, -161.00, '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(90, 45, 99, 161.00, '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(91, 46, 123, 161.00, '2026-05-31 12:23:57', '2026-05-31 12:23:57'),
(92, 46, 99, -161.00, '2026-05-31 12:23:57', '2026-05-31 12:23:57'),
(103, 52, 142, 10000.00, '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(104, 52, 100, -10000.00, '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(105, 53, 142, -14074.00, '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(106, 53, 102, 14074.00, '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(107, 54, 142, -688.00, '2026-05-31 12:32:35', '2026-05-31 12:32:35'),
(108, 54, 102, 688.00, '2026-05-31 12:32:35', '2026-05-31 12:32:35'),
(109, 55, 152, 3500.00, '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(110, 55, 100, -3500.00, '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(111, 56, 152, -1098.00, '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(112, 56, 102, 1098.00, '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(113, 57, 152, -1519.00, '2026-05-31 12:33:27', '2026-05-31 12:33:27'),
(114, 57, 102, 1519.00, '2026-05-31 12:33:27', '2026-05-31 12:33:27'),
(115, 58, 162, 2016.00, '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(116, 58, 100, -2016.00, '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(117, 59, 162, -2016.00, '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(118, 59, 102, 2016.00, '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(119, 60, 166, 1000.00, '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(120, 60, 100, -1000.00, '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(121, 61, 166, -1000.00, '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(122, 61, 102, 1000.00, '2026-05-31 12:34:26', '2026-05-31 12:34:26'),
(123, 62, 170, 3000.00, '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(124, 62, 100, -3000.00, '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(125, 63, 170, -899.00, '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(126, 63, 102, 899.00, '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(127, 64, 170, -2101.00, '2026-05-31 12:35:11', '2026-05-31 12:35:11'),
(128, 64, 102, 2101.00, '2026-05-31 12:35:11', '2026-05-31 12:35:11'),
(129, 65, 180, 14519.00, '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(130, 65, 100, -14519.00, '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(131, 66, 180, -6409.00, '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(132, 66, 102, 6409.00, '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(133, 67, 180, -8110.00, '2026-05-31 12:36:08', '2026-05-31 12:36:08'),
(134, 67, 102, 8110.00, '2026-05-31 12:36:08', '2026-05-31 12:36:08'),
(135, 68, 190, 2000.00, '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(136, 68, 100, -2000.00, '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(137, 69, 190, -800.00, '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(138, 69, 102, 800.00, '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(139, 70, 190, -1200.00, '2026-05-31 12:37:35', '2026-05-31 12:37:35'),
(140, 70, 102, 1200.00, '2026-05-31 12:37:35', '2026-05-31 12:37:35'),
(141, 71, 200, 4000.00, '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(142, 71, 100, -4000.00, '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(143, 72, 200, -570.00, '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(144, 72, 102, 570.00, '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(145, 73, 200, -3500.00, '2026-05-31 12:40:16', '2026-05-31 12:40:16'),
(146, 73, 102, 3500.00, '2026-05-31 12:40:16', '2026-05-31 12:40:16'),
(147, 74, 210, 10000.00, '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(148, 74, 100, -10000.00, '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(149, 75, 210, -267.28, '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(150, 75, 102, 267.28, '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(151, 76, 210, -6539.00, '2026-05-31 12:42:25', '2026-05-31 12:42:25'),
(152, 76, 102, 6539.00, '2026-05-31 12:42:25', '2026-05-31 12:42:25'),
(153, 77, 210, -1300.00, '2026-05-31 12:42:38', '2026-05-31 12:42:38'),
(154, 77, 102, 1300.00, '2026-05-31 12:42:38', '2026-05-31 12:42:38'),
(155, 78, 210, -300.00, '2026-05-31 12:42:56', '2026-05-31 12:42:56'),
(156, 78, 102, 300.00, '2026-05-31 12:42:56', '2026-05-31 12:42:56'),
(157, 79, 210, -5513.00, '2026-05-31 12:43:11', '2026-05-31 12:43:11'),
(158, 79, 102, 5513.00, '2026-05-31 12:43:11', '2026-05-31 12:43:11'),
(159, 80, 210, -4120.72, '2026-05-31 12:43:33', '2026-05-31 12:43:33'),
(160, 80, 102, 4120.72, '2026-05-31 12:43:33', '2026-05-31 12:43:33'),
(161, 81, 210, -1960.00, '2026-05-31 12:43:53', '2026-05-31 12:43:53'),
(162, 81, 102, 1960.00, '2026-05-31 12:43:53', '2026-05-31 12:43:53'),
(163, 82, 280, 5000.00, '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(164, 82, 100, -5000.00, '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(165, 83, 280, -512.72, '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(166, 83, 102, 512.72, '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(167, 84, 280, -1009.00, '2026-05-31 12:46:26', '2026-05-31 12:46:26'),
(168, 84, 102, 1009.00, '2026-05-31 12:46:26', '2026-05-31 12:46:26'),
(169, 85, 280, -2000.00, '2026-05-31 12:46:40', '2026-05-31 12:46:40'),
(170, 85, 102, 2000.00, '2026-05-31 12:46:40', '2026-05-31 12:46:40'),
(171, 86, 280, -1545.00, '2026-05-31 12:46:57', '2026-05-31 12:46:57'),
(172, 86, 102, 1545.00, '2026-05-31 12:46:57', '2026-05-31 12:46:57'),
(173, 87, 308, 5000.00, '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(174, 87, 100, -5000.00, '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(175, 88, 308, -450.00, '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(176, 88, 102, 450.00, '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(177, 89, 308, -1000.00, '2026-05-31 12:47:34', '2026-05-31 12:47:34'),
(178, 89, 102, 1000.00, '2026-05-31 12:47:34', '2026-05-31 12:47:34'),
(179, 90, 308, -2471.00, '2026-05-31 12:47:49', '2026-05-31 12:47:49'),
(180, 90, 102, 2471.00, '2026-05-31 12:47:49', '2026-05-31 12:47:49'),
(181, 91, 308, -360.00, '2026-05-31 12:48:03', '2026-05-31 12:48:03'),
(182, 91, 102, 360.00, '2026-05-31 12:48:03', '2026-05-31 12:48:03'),
(183, 92, 308, -719.00, '2026-05-31 12:48:18', '2026-05-31 12:48:18'),
(184, 92, 102, 719.00, '2026-05-31 12:48:18', '2026-05-31 12:48:18'),
(185, 93, 348, 10000.00, '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(186, 93, 100, -10000.00, '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(187, 94, 348, -6000.00, '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(188, 94, 102, 6000.00, '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(189, 95, 348, -110.00, '2026-05-31 14:42:22', '2026-05-31 14:42:22'),
(190, 95, 102, 110.00, '2026-05-31 14:42:22', '2026-05-31 14:42:22'),
(191, 96, 348, -240.00, '2026-05-31 14:42:34', '2026-05-31 14:42:34'),
(192, 96, 102, 240.00, '2026-05-31 14:42:34', '2026-05-31 14:42:34'),
(193, 97, 348, -667.00, '2026-05-31 14:42:46', '2026-05-31 14:42:46'),
(194, 97, 102, 667.00, '2026-05-31 14:42:46', '2026-05-31 14:42:46'),
(195, 98, 348, -983.00, '2026-05-31 14:42:59', '2026-05-31 14:42:59'),
(196, 98, 102, 983.00, '2026-05-31 14:42:59', '2026-05-31 14:42:59'),
(197, 99, 348, -2000.00, '2026-05-31 14:43:07', '2026-05-31 14:43:07'),
(198, 99, 102, 2000.00, '2026-05-31 14:43:07', '2026-05-31 14:43:07'),
(199, 100, 402, 5000.00, '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(200, 100, 100, -5000.00, '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(201, 101, 402, -5080.00, '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(202, 101, 102, 5080.00, '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(203, 102, 406, 5000.00, '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(204, 102, 100, -5000.00, '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(205, 103, 406, -4043.00, '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(206, 103, 102, 4043.00, '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(207, 104, 406, -209.00, '2026-05-31 14:45:48', '2026-05-31 14:45:48'),
(208, 104, 102, 209.00, '2026-05-31 14:45:48', '2026-05-31 14:45:48'),
(209, 105, 406, -2280.00, '2026-05-31 14:46:01', '2026-05-31 14:46:01'),
(210, 105, 102, 2280.00, '2026-05-31 14:46:01', '2026-05-31 14:46:01'),
(211, 106, 406, -2500.00, '2026-05-31 14:46:22', '2026-05-31 14:46:22'),
(212, 106, 102, 2500.00, '2026-05-31 14:46:22', '2026-05-31 14:46:22'),
(213, 107, 406, -5968.00, '2026-05-31 14:46:38', '2026-05-31 14:46:38'),
(214, 107, 102, 5968.00, '2026-05-31 14:46:38', '2026-05-31 14:46:38'),
(215, 108, 446, 10000.00, '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(216, 108, 100, -10000.00, '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(217, 109, 446, -7964.00, '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(218, 109, 102, 7964.00, '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(219, 110, 446, -1816.00, '2026-05-31 14:47:23', '2026-05-31 14:47:23'),
(220, 110, 102, 1816.00, '2026-05-31 14:47:23', '2026-05-31 14:47:23'),
(221, 111, 446, -220.00, '2026-05-31 14:47:33', '2026-05-31 14:47:33'),
(222, 111, 102, 220.00, '2026-05-31 14:47:33', '2026-05-31 14:47:33'),
(223, 112, 446, -1562.00, '2026-05-31 14:47:50', '2026-05-31 14:47:50'),
(224, 112, 102, 1562.00, '2026-05-31 14:47:50', '2026-05-31 14:47:50'),
(225, 113, 446, -7186.00, '2026-05-31 14:48:03', '2026-05-31 14:48:03'),
(226, 113, 102, 7186.00, '2026-05-31 14:48:03', '2026-05-31 14:48:03'),
(227, 114, 446, -1252.00, '2026-05-31 14:48:17', '2026-05-31 14:48:17'),
(228, 114, 102, 1252.00, '2026-05-31 14:48:17', '2026-05-31 14:48:17'),
(229, 115, 500, 10000.00, '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(230, 115, 100, -10000.00, '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(231, 116, 500, -4043.00, '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(232, 116, 102, 4043.00, '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(233, 117, 500, -209.00, '2026-05-31 14:48:47', '2026-05-31 14:48:47'),
(234, 117, 102, 209.00, '2026-05-31 14:48:47', '2026-05-31 14:48:47'),
(235, 118, 500, -2280.00, '2026-05-31 14:48:56', '2026-05-31 14:48:56'),
(236, 118, 102, 2280.00, '2026-05-31 14:48:56', '2026-05-31 14:48:56'),
(237, 119, 500, -2500.00, '2026-05-31 14:49:05', '2026-05-31 14:49:05'),
(238, 119, 102, 2500.00, '2026-05-31 14:49:05', '2026-05-31 14:49:05'),
(239, 120, 500, -5968.00, '2026-05-31 14:49:17', '2026-05-31 14:49:17'),
(240, 120, 102, 5968.00, '2026-05-31 14:49:17', '2026-05-31 14:49:17'),
(241, 121, 540, 10000.00, '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(242, 121, 100, -10000.00, '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(243, 122, 540, -1562.00, '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(244, 122, 102, 1562.00, '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(245, 123, 540, -7186.00, '2026-05-31 14:50:08', '2026-05-31 14:50:08'),
(246, 123, 102, 7186.00, '2026-05-31 14:50:08', '2026-05-31 14:50:08'),
(247, 124, 540, -1252.00, '2026-05-31 14:50:23', '2026-05-31 14:50:23'),
(248, 124, 102, 1252.00, '2026-05-31 14:50:23', '2026-05-31 14:50:23'),
(249, 125, 558, 10000.00, '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(250, 125, 100, -10000.00, '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(251, 126, 558, -490.00, '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(252, 126, 102, 490.00, '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(253, 127, 558, -5150.00, '2026-05-31 14:51:28', '2026-05-31 14:51:28'),
(254, 127, 102, 5150.00, '2026-05-31 14:51:28', '2026-05-31 14:51:28'),
(255, 128, 558, -60.00, '2026-05-31 14:51:48', '2026-05-31 14:51:48'),
(256, 128, 102, 60.00, '2026-05-31 14:51:48', '2026-05-31 14:51:48'),
(257, 129, 558, -4309.00, '2026-05-31 14:52:02', '2026-05-31 14:52:02'),
(258, 129, 102, 4309.00, '2026-05-31 14:52:02', '2026-05-31 14:52:02'),
(259, 130, 586, 5000.00, '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(260, 130, 100, -5000.00, '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(261, 131, 586, -2018.00, '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(262, 131, 102, 2018.00, '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(263, 132, 586, -114.00, '2026-05-31 14:56:54', '2026-05-31 14:56:54'),
(264, 132, 102, 114.00, '2026-05-31 14:56:54', '2026-05-31 14:56:54'),
(265, 133, 586, -1170.00, '2026-05-31 14:57:07', '2026-05-31 14:57:07'),
(266, 133, 102, 1170.00, '2026-05-31 14:57:07', '2026-05-31 14:57:07'),
(267, 134, 586, -1698.00, '2026-05-31 14:57:24', '2026-05-31 14:57:24'),
(268, 134, 102, 1698.00, '2026-05-31 14:57:24', '2026-05-31 14:57:24'),
(269, 135, 614, 10000.00, '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(270, 135, 100, -10000.00, '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(271, 136, 614, -680.00, '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(272, 136, 102, 680.00, '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(273, 137, 614, -33.00, '2026-05-31 14:58:08', '2026-05-31 14:58:08'),
(274, 137, 102, 33.00, '2026-05-31 14:58:08', '2026-05-31 14:58:08'),
(275, 138, 614, -3000.00, '2026-05-31 14:58:16', '2026-05-31 14:58:16'),
(276, 138, 102, 3000.00, '2026-05-31 14:58:16', '2026-05-31 14:58:16'),
(277, 139, 614, -150.00, '2026-05-31 14:58:23', '2026-05-31 14:58:23'),
(278, 139, 102, 150.00, '2026-05-31 14:58:23', '2026-05-31 14:58:23'),
(279, 140, 614, -270.00, '2026-05-31 14:58:31', '2026-05-31 14:58:31'),
(280, 140, 102, 270.00, '2026-05-31 14:58:31', '2026-05-31 14:58:31'),
(281, 141, 614, -360.00, '2026-05-31 14:58:40', '2026-05-31 14:58:40'),
(282, 141, 102, 360.00, '2026-05-31 14:58:40', '2026-05-31 14:58:40'),
(283, 142, 614, -60.00, '2026-05-31 14:58:51', '2026-05-31 14:58:51'),
(284, 142, 102, 60.00, '2026-05-31 14:58:51', '2026-05-31 14:58:51'),
(285, 143, 614, -5447.00, '2026-05-31 14:59:00', '2026-05-31 14:59:00'),
(286, 143, 102, 5447.00, '2026-05-31 14:59:00', '2026-05-31 14:59:00'),
(287, 144, 702, 13444.00, '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(288, 144, 100, -13444.00, '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(289, 145, 702, -401.00, '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(290, 145, 102, 401.00, '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(291, 146, 702, -9706.00, '2026-05-31 14:59:36', '2026-05-31 14:59:36'),
(292, 146, 102, 9706.00, '2026-05-31 14:59:36', '2026-05-31 14:59:36'),
(293, 147, 702, -3337.00, '2026-05-31 14:59:44', '2026-05-31 14:59:44'),
(294, 147, 102, 3337.00, '2026-05-31 14:59:44', '2026-05-31 14:59:44'),
(295, 148, 720, 10000.00, '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(296, 148, 100, -10000.00, '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(297, 149, 720, -7185.00, '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(298, 149, 102, 7185.00, '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(299, 150, 720, -3000.00, '2026-05-31 15:00:13', '2026-05-31 15:00:13'),
(300, 150, 102, 3000.00, '2026-05-31 15:00:13', '2026-05-31 15:00:13'),
(301, 151, 730, 10000.00, '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(302, 151, 100, -10000.00, '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(303, 152, 730, -2509.00, '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(304, 152, 102, 2509.00, '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(305, 153, 730, -509.00, '2026-05-31 15:01:34', '2026-05-31 15:01:34'),
(306, 153, 102, 509.00, '2026-05-31 15:01:34', '2026-05-31 15:01:34'),
(307, 154, 730, -3930.00, '2026-05-31 15:01:46', '2026-05-31 15:01:46'),
(308, 154, 102, 3930.00, '2026-05-31 15:01:46', '2026-05-31 15:01:46'),
(309, 155, 730, -3052.00, '2026-05-31 15:01:57', '2026-05-31 15:01:57'),
(310, 155, 102, 3052.00, '2026-05-31 15:01:57', '2026-05-31 15:01:57'),
(311, 156, 758, 10000.00, '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(312, 156, 100, -10000.00, '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(313, 157, 758, -6900.00, '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(314, 157, 102, 6900.00, '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(315, 158, 758, -1872.00, '2026-05-31 15:04:04', '2026-05-31 15:04:04'),
(316, 158, 102, 1872.00, '2026-05-31 15:04:04', '2026-05-31 15:04:04'),
(317, 159, 758, -1228.00, '2026-05-31 15:04:18', '2026-05-31 15:04:18'),
(318, 159, 102, 1228.00, '2026-05-31 15:04:18', '2026-05-31 15:04:18'),
(319, 160, 776, 10000.00, '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(320, 160, 100, -10000.00, '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(321, 161, 776, -150.00, '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(322, 161, 102, 150.00, '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(323, 162, 776, -1509.00, '2026-05-31 15:12:26', '2026-05-31 15:12:26'),
(324, 162, 102, 1509.00, '2026-05-31 15:12:26', '2026-05-31 15:12:26'),
(325, 163, 776, -1376.00, '2026-05-31 15:12:38', '2026-05-31 15:12:38'),
(326, 163, 102, 1376.00, '2026-05-31 15:12:38', '2026-05-31 15:12:38'),
(327, 164, 776, -588.00, '2026-05-31 15:12:53', '2026-05-31 15:12:53'),
(328, 164, 102, 588.00, '2026-05-31 15:12:53', '2026-05-31 15:12:53'),
(329, 165, 776, -1077.00, '2026-05-31 15:13:05', '2026-05-31 15:13:05'),
(330, 165, 102, 1077.00, '2026-05-31 15:13:05', '2026-05-31 15:13:05'),
(331, 166, 816, 20208.00, '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(332, 166, 100, -20208.00, '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(333, 167, 816, -18378.00, '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(334, 167, 102, 18378.00, '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(335, 168, 816, -1830.00, '2026-05-31 15:16:26', '2026-05-31 15:16:26'),
(336, 168, 102, 1830.00, '2026-05-31 15:16:26', '2026-05-31 15:16:26'),
(337, 169, 826, 10000.00, '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(338, 169, 100, -10000.00, '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(339, 170, 826, -2700.00, '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(340, 170, 102, 2700.00, '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(341, 171, 826, -588.72, '2026-05-31 15:18:02', '2026-05-31 15:18:02'),
(342, 171, 102, 588.72, '2026-05-31 15:18:02', '2026-05-31 15:18:02'),
(343, 172, 826, -1417.28, '2026-05-31 15:18:15', '2026-05-31 15:18:15'),
(344, 172, 102, 1417.28, '2026-05-31 15:18:15', '2026-05-31 15:18:15');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_legacy_wallet_funding_sources`
--

CREATE TABLE `accounting_legacy_wallet_funding_sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `source_account_id` bigint(20) UNSIGNED NOT NULL,
  `priority` smallint(5) UNSIGNED NOT NULL DEFAULT 100,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 2026, 1, 274647.48, 0.00, 157221.50, 117425.98, NULL, 3, NULL, '2026-05-24 16:54:45', '2026-05-31 20:01:49'),
(3, 2026, 2, 117425.98, 0.00, 37045.61, 80380.37, NULL, 24, NULL, '2026-05-24 18:25:53', '2026-06-01 15:13:42'),
(4, 2026, 3, 31096.37, 0.00, -100876.13, 131972.50, NULL, 3, NULL, '2026-05-24 21:07:26', '2026-06-01 15:38:55');

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
  `purpose` text DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_advance_requests`
--

INSERT INTO `cash_advance_requests` (`id`, `reference_no`, `requester_id`, `requested_amount`, `approved_amount`, `purpose`, `request_date`, `date_needed`, `status`, `accounting_remarks`, `reviewed_by`, `approved_by_name`, `sent_by_name`, `submitted_at`, `reviewed_at`, `released_at`, `liquidation_due_date`, `created_at`, `updated_at`, `category`) VALUES
(736, 'IMP-20260529074102-QBHTNV', 14, 10000.00, 10000.00, 'aba savings deduction', '2026-01-01', '2026-01-01', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 17:18:24', 'Salary'),
(737, 'IMP-20260529074102-GM34SN', 13, 15000.00, 15000.00, 'opex', '2026-01-01', '2026-01-01', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 17:20:33', NULL),
(738, 'IMP-20260529074102-FYGUAA', 12, 8700.00, 8700.00, 'dmc commission', '2026-01-02', '2026-01-02', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 10:06:23', 'Commission'),
(739, 'IMP-20260529074102-LZVVAF', 12, 68459.00, 68459.00, 'salary jan 2', '2026-01-03', '2026-01-03', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 02:30:20', 'Salary'),
(740, 'IMP-20260529074102-TO4J1B', 23, 2500.00, 2500.00, 'opex negros', '2026-01-04', '2026-01-04', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(741, 'IMP-20260529074102-21HA4Q', 9, 10000.00, 10000.00, 'truck dum to mla', '2026-01-04', '2026-01-04', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(742, 'IMP-20260529074102-3FX7OM', 2, 12000.00, 12000.00, 'outbound interbank transfer', '2026-01-04', '2026-01-04', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 10:11:20', 'Donation'),
(743, 'IMP-20260529074102-TNLG2F', 9, 8000.00, 8000.00, 'dumaguete truck', '2026-01-04', '2026-01-04', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(744, 'IMP-20260529074102-2HI0PJ', 19, 20000.00, 20000.00, 'mayors permit', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:38:45', 'Taxes and Licences'),
(745, 'IMP-20260529074102-TUYTAR', 14, 23000.00, 23000.00, 'gilbert de sagun quit claim', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:38:02', 'Salary'),
(746, 'IMP-20260529074102-JK4PMA', 23, 5000.00, 5000.00, 'representation food negros', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:37:50', 'Representation'),
(747, 'IMP-20260529074102-CYDQT4', 20, 2000.00, 2000.00, 'opex mamburao', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(748, 'IMP-20260529074102-BLZOPL', 12, 7000.00, 7000.00, 'bidding notary', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:37:03', 'Bid Docs Fee and other Documents'),
(749, 'IMP-20260529074102-IH9NTW', 12, 1200.00, 1200.00, 'dmc comm rice ham', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:36:53', 'Commission'),
(750, 'IMP-20260529074102-XV47X2', 23, 4000.00, 4000.00, 'representation negros', '2026-01-05', '2026-01-05', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:36:37', 'Representation'),
(751, 'IMP-20260529074102-NVGBOT', 19, 14519.00, 14519.00, 'Qarah opex', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(752, 'IMP-20260529074102-4KZUBS', 2, 480000.00, 480000.00, 'check deposti from METRO-BETTER LIVING RUSSIA', '2026-01-06', '2026-01-06', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(753, 'IMP-20260529074102-SYGURG', 16, 3000.00, 3000.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(754, 'IMP-20260529074102-EKVLGP', 2, 1000.00, 1000.00, 'outbound interbank transfer', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(755, 'IMP-20260529074102-UMIXES', 2, 2016.00, 2016.00, 'outbound interbank transfer', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(756, 'IMP-20260529074102-VIG5H8', 20, 3500.00, 3500.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(757, 'IMP-20260529074102-66EGON', 9, 10000.00, 10000.00, 'truck negros', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(758, 'IMP-20260529074102-BBVMAJ', 23, 2000.00, 2000.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 11:16:24', NULL),
(759, 'IMP-20260529074102-T7SVSN', 18, 4000.00, 4000.00, 'opex', '2026-01-06', '2026-01-06', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(760, 'IMP-20260529074102-7SOMDY', 12, 36829.00, 36829.00, 'freight', '2026-01-07', '2026-01-07', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:40:46', 'Freight'),
(761, 'IMP-20260529074102-OSF7VR', 12, 20000.00, 20000.00, 'borrow from CHRISTINE R.', '2026-01-07', '2026-01-07', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(762, 'IMP-20260529074102-7CZTQ2', 14, 80000.00, 80000.00, 'dmc comm negros tanda', '2026-01-07', '2026-01-07', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:41:17', 'Commission'),
(763, 'IMP-20260529074102-TFHLYW', 19, 9625.00, 9625.00, 'loan interest ps bank', '2026-01-07', '2026-01-07', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:41:32', 'Bank Charges'),
(764, 'IMP-20260529074102-1TUKIC', 19, 864.00, 864.00, 'loan interest ps bank', '2026-01-07', '2026-01-07', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:41:36', 'Bank Charges'),
(765, 'IMP-20260529074102-WUX0OT', 15, 10000.00, 10000.00, 'opex', '2026-01-07', '2026-01-07', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(766, 'IMP-20260529074102-IN4CWU', 16, 5300.00, 5300.00, 'opex', '2026-01-07', '2026-01-07', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:44:23', 'Opex'),
(767, 'IMP-20260529074102-IUWZJY', 10, 30000.00, 30000.00, 'borrow', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:45:23', 'Kairos'),
(768, 'IMP-20260529074102-KXWNEY', 15, 5000.00, 5000.00, 'opex', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(769, 'IMP-20260529074102-W3RUWC', 18, 5000.00, 5000.00, 'opex', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(770, 'IMP-20260529074102-T8BWPT', 19, 33184.00, 33184.00, 'opex', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:48:42', 'Opex'),
(771, 'IMP-20260529074102-GY6TDJ', 12, 100000.00, 100000.00, 'borrow', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:48:52', 'Borrow'),
(772, 'IMP-20260529074102-ICVW8U', 12, 56691.00, 56691.00, 'borrow from CHRSINTE R.', '2026-01-08', '2026-01-08', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:49:03', NULL),
(773, 'IMP-20260529074102-NK3FGQ', 12, 50000.00, 50000.00, 'borrow', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 12:50:10', 'Borrow'),
(774, 'IMP-20260529074102-JFNKCC', 2, 2016.00, 2016.00, 'outbound interbank transfer', '2026-01-08', '2026-01-08', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 14:41:09', 'MAC'),
(775, 'IMP-20260529074102-TZOHBT', 19, 80000.00, 80000.00, 'borrow', '2026-01-09', '2026-01-09', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 14:41:22', 'Borrow'),
(776, 'IMP-20260529074102-SLY3BW', 13, 10000.00, 10000.00, 'opex', '2026-01-09', '2026-01-09', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(777, 'IMP-20260529074102-RN5OQV', 19, 2500.00, 2500.00, 'bday jm, tin food dmc', '2026-01-09', '2026-01-09', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 14:43:40', 'Representation'),
(778, 'IMP-20260529074102-IQHP2T', 21, 6500.00, 6500.00, 'donation seed', '2026-01-09', '2026-01-09', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 14:43:55', 'Donation'),
(779, 'IMP-20260529074102-DGG5LT', 19, 80000.00, 80000.00, 'returned', '2026-01-09', '2026-01-09', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL),
(780, 'IMP-20260529074102-48WGJS', 2, 3300.00, 3300.00, 'outbank interbank transfer', '2026-01-10', '2026-01-10', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:41:02', '2026-05-28 23:41:02', '2026-05-28 23:41:02', NULL, '2026-05-28 23:41:02', '2026-05-31 14:44:09', 'Office Expense'),
(781, 'IMP-20260529075439-LDKSNR', 2, 17510.00, 17510.00, 'outbank interbank transfer', '2026-01-10', '2026-01-10', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:44:15', 'Office Expense'),
(782, 'IMP-20260529075439-O9XJHD', 18, 5000.00, 5000.00, 'opex', '2026-01-10', '2026-01-10', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(783, 'IMP-20260529075439-4TVU28', 14, 95053.00, 95053.00, 'payroll jan 9', '2026-01-10', '2026-01-10', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:45:22', 'Salary'),
(784, 'IMP-20260529075439-SZOV8C', 15, 5000.00, 5000.00, 'opex', '2026-01-12', '2026-01-12', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(785, 'IMP-20260529075439-PYTNCW', 15, 6000.00, 6000.00, 'opex', '2026-01-12', '2026-01-12', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:47:02', 'Opex'),
(786, 'IMP-20260529075439-K21QPZ', 13, 10000.00, 10000.00, 'opex', '2026-01-13', '2026-01-13', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(787, 'IMP-20260529075439-SCOHQ9', 12, 50000.00, 50000.00, 'borrw', '2026-01-13', '2026-01-13', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(788, 'IMP-20260529075439-B6WYLH', 15, 10000.00, 10000.00, 'opex', '2026-01-14', '2026-01-14', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(789, 'IMP-20260529075439-NELS9C', 13, 10000.00, 10000.00, 'opex', '2026-01-14', '2026-01-14', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(790, 'IMP-20260529075439-5GNJPQ', 2, 10356.00, 10356.00, 'outbound interbank transfer', '2026-01-14', '2026-01-14', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:50:47', 'Office Expense'),
(791, 'IMP-20260529075439-BUTVJB', 14, 10000.00, 10000.00, 'CA jrm savings', '2026-01-14', '2026-01-14', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:50:58', 'Salary'),
(792, 'IMP-20260529075439-GAIOCK', 18, 10000.00, 10000.00, 'opex', '2026-01-15', '2026-01-15', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(793, 'IMP-20260529075439-H2MQ4L', 14, 5621.00, 5621.00, 'salary maricel last pay', '2026-01-15', '2026-01-15', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:52:13', 'Salary'),
(794, 'IMP-20260529075439-0ZYETW', 2, 26358.00, 26358.00, 'outbound interbank transger ', '2026-01-17', '2026-01-17', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:52:24', 'Returned'),
(795, 'IMP-20260529075439-BHFABE', 2, 8876.00, 8876.00, 'outbound interbank transger ', '2026-01-17', '2026-01-17', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:52:31', 'Returned'),
(796, 'IMP-20260529075439-VH5PSU', 21, 2500.00, 2500.00, 'seed bs jan 16', '2026-01-18', '2026-01-18', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:52:42', 'Donation'),
(797, 'IMP-20260529075439-JED4LL', 11, 2880.00, 2880.00, 'china purchase - angel personal', '2026-01-19', '2026-01-19', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(798, 'IMP-20260529075439-YLJBGV', 11, 2880.00, 2880.00, 'return', '2026-01-19', '2026-01-19', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:55:40', 'Returned'),
(799, 'IMP-20260529075439-EDKSWR', 21, 5000.00, 5000.00, 'outbound interbank transger', '2026-01-20', '2026-01-20', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:55:46', 'Donation'),
(800, 'IMP-20260529075439-YCKPGM', 22, 1000.00, 1000.00, 'mad adv grocery', '2026-01-20', '2026-01-20', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:56:16', 'MAC'),
(801, 'IMP-20260529075439-MEOXI6', 2, 480000.00, 480000.00, 'check deposit from metro- better living russia', '2026-01-20', '2026-01-20', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(802, 'IMP-20260529075439-IS7WD3', 10, 40000.00, 40000.00, 'opex kairos', '2026-01-20', '2026-01-20', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:56:30', 'Kairos'),
(803, 'IMP-20260529075439-Y8QNKS', 13, 5000.00, 5000.00, 'opex', '2026-01-20', '2026-01-20', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL),
(804, 'IMP-20260529075439-BNLLVT', 15, 6650.00, 6650.00, 'offic supply bondpaper', '2026-01-20', '2026-01-20', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-28 23:54:39', '2026-05-28 23:54:39', '2026-05-28 23:54:39', NULL, '2026-05-28 23:54:39', '2026-05-31 14:57:51', 'Office Expense'),
(867, 'IMP-20260529084937-FYVLVW', 14, 10000.00, 10000.00, 'opex', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(868, 'IMP-20260529084937-KEXYL1', 12, 19200.00, 19200.00, 'bidding docs notary', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 14:59:14', 'Bid Docs Fee and other Documents'),
(869, 'IMP-20260529084937-ZRSATU', 12, 13444.00, 13444.00, 'opex', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(870, 'IMP-20260529084937-L17TDV', 12, 200000.00, 200000.00, 'borrow cmr', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 14:59:53', 'Borrow'),
(871, 'IMP-20260529084937-8OSWFB', 12, 100000.00, 100000.00, 'borrow', '2026-01-21', '2026-01-21', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(872, 'IMP-20260529084937-GMMETL', 15, 10000.00, 10000.00, 'opex ', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(873, 'IMP-20260529084937-K2KIFY', 14, 5000.00, 5000.00, 'accomo stela,grasya,jink,jeni', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:00:24', 'Accommodation'),
(874, 'IMP-20260529084937-NSIVOY', 14, 2500.00, 2500.00, 'CA savings ECR', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:00:32', 'Salary'),
(875, 'IMP-20260529084937-XY3ASU', 14, 10000.00, 10000.00, 'CA savings JRM', '2026-01-21', '2026-01-21', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:00:38', 'Cash Advance'),
(876, 'IMP-20260529084937-BQEBII', 19, 28011.00, 28011.00, 'china travel opex', '2026-01-22', '2026-01-22', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:00:47', 'Office Expense'),
(877, 'IMP-20260529084937-UV7VUV', 19, 26867.00, 26867.00, 'team building exp opex', '2026-01-22', '2026-01-22', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:00:57', 'Opex'),
(878, 'IMP-20260529084937-JMAMN1', 19, 10110.00, 10110.00, 'diesel', '2026-01-22', '2026-01-22', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:01:18', 'Fuel / Gas'),
(879, 'IMP-20260529084937-YFYVGK', 15, 10000.00, 10000.00, 'opex', '2026-01-22', '2026-01-22', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(880, 'IMP-20260529084937-45DYRS', 12, 100000.00, 100000.00, 'borrow', '2026-01-22', '2026-01-22', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:02:11', 'Borrow'),
(881, 'IMP-20260529084937-HYLZ1X', 15, 5150.00, 5150.00, 'office equipment, printer', '2026-01-22', '2026-01-22', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:02:22', 'Office Expense'),
(882, 'IMP-20260529084937-SURSIY', 19, 16000.00, 16000.00, 'freight capiz', '2026-01-23', '2026-01-23', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-31 15:02:27', 'Freight'),
(883, 'IMP-20260529084937-KNZUJR', 19, 26358.00, 26358.00, '3k rmb shoes', '2026-01-23', '2026-01-23', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(884, 'IMP-20260529084937-L5BQHO', 19, 8876.00, 8876.00, 'return 1k rmb shoes', '2026-01-23', '2026-01-23', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:49:37', '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL, '2026-05-29 00:49:37', '2026-05-29 00:49:37', NULL),
(885, 'IMP-20260529085406-FBWNBJ', 2, 788.00, 788.00, 'card payment @netflix.com', '2026-01-24', '2026-01-24', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:02:39', 'MAC'),
(886, 'IMP-20260529085406-RFLXJ0', 14, 9176.00, 9176.00, 'salary last pay maecy', '2026-01-24', '2026-01-24', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:02:53', 'Salary'),
(887, 'IMP-20260529085406-NE3K91', 19, 10762.00, 10762.00, 'Motor JR repair CA', '2026-01-24', '2026-01-24', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:03:03', 'Salary'),
(889, 'IMP-20260529085406-7TEXCA', 12, 45000.00, 45000.00, 'borrow', '2026-01-24', '2026-01-24', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:03:15', 'Borrow'),
(890, 'IMP-20260529085406-ZUHDS9', 21, 2500.00, 2500.00, 'donation seed bs', '2026-01-25', '2026-01-25', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:03:35', 'Donation'),
(891, 'IMP-20260529085406-Z3ATKE', 21, 10000.00, 10000.00, 'donation seed church rent', '2026-01-25', '2026-01-25', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:03:38', 'Donation'),
(892, 'IMP-20260529085406-PAZI2H', 15, 10000.00, 10000.00, 'opex', '2026-01-26', '2026-01-26', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL),
(893, 'IMP-20260529085406-SLP9LY', 12, 200000.00, 200000.00, 'borrow', '2026-01-26', '2026-01-26', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL),
(894, 'IMP-20260529085406-NFK8VP', 12, 100000.00, 100000.00, 'borrow', '2026-01-26', '2026-01-26', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL),
(895, 'IMP-20260529085406-W7YUBX', 12, 100000.00, 100000.00, 'borrow', '2026-01-26', '2026-01-26', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:06:20', 'Borrow'),
(896, 'IMP-20260529085406-VADU9U', 12, 100000.00, 100000.00, 'borrow', '2026-01-26', '2026-01-26', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 00:54:06', '2026-05-29 00:54:06', '2026-05-29 00:54:06', NULL, '2026-05-29 00:54:06', '2026-05-31 15:06:35', 'Borrow'),
(897, 'CA-1780045097-760', 14, 21244.50, 21244.50, 'salary jinky last pay', '2026-01-24', '2026-01-24', 'approved', 'Manually Recorded', 3, 'Accounting', 'Accounting', '2026-05-29 00:58:17', '2026-05-29 00:58:17', '2026-05-29 00:58:17', NULL, '2026-05-29 00:58:17', '2026-05-31 15:03:27', 'Salary'),
(898, 'IMP-20260529090112-FKSTGK', 14, 10000.00, 10000.00, 'opex', '2026-01-27', '2026-01-27', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:06:46', 'Opex'),
(899, 'IMP-20260529090112-GMZ0F1', 2, 300000.00, 300000.00, 'check deposit from metro-living Russia', '2026-01-27', '2026-01-27', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(900, 'IMP-20260529090112-VIBHOV', 12, 200000.00, 200000.00, 'borrow', '2026-01-27', '2026-01-27', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:06:55', 'Borrow'),
(901, 'IMP-20260529090112-2PQPHR', 19, 4000.00, 4000.00, 'donation deped pangasinan', '2026-01-27', '2026-01-27', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:07:06', 'Representation'),
(902, 'IMP-20260529090112-NQN3CQ', 14, 13.00, 13.00, 'excess 5k', '2026-01-27', '2026-01-27', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(903, 'IMP-20260529090112-7BR8DH', 2, 10000.00, 10000.00, 'outbound interbank transfer', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(904, 'IMP-20260529090112-V6Y65W', 15, 10000.00, 10000.00, 'opex', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(905, 'IMP-20260529090112-ITBRHA', 22, 1500.00, 1500.00, 'mac adv', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:13:35', 'MAC'),
(906, 'IMP-20260529090112-MHJYU7', 22, 500.00, 500.00, 'mac adv', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:13:41', 'MAC'),
(907, 'IMP-20260529090112-Y81DB6', 12, 107293.00, 107293.00, 'borrow cmr playground', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:13:52', 'Borrow'),
(908, 'IMP-20260529090112-TPCXZJ', 2, 462761.00, 462761.00, 'check deposit metro-better living Russia', '2026-01-28', '2026-01-28', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(909, 'IMP-20260529090112-LBXYS7', 12, 107293.00, 107293.00, NULL, '2026-01-28', '2026-01-28', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(910, 'IMP-20260529090112-BOHNQR', 12, 100000.00, 100000.00, 'borrow', '2026-01-28', '2026-01-28', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(911, 'IMP-20260529090112-DXBM3B', 12, 300000.00, 300000.00, 'purchases', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(912, 'IMP-20260529090112-ITJJ35', 19, 9177.00, 9177.00, 'freight solid', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:15:55', 'Freight'),
(913, 'IMP-20260529090112-YSSINH', 19, 14390.00, 14390.00, 'freight td', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:16:03', 'Freight'),
(914, 'IMP-20260529090112-UJ1LUE', 19, 20208.00, 20208.00, 'opex', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(915, 'IMP-20260529090112-UNJZIQ', 19, 7700.00, 7700.00, 'bldg exp', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:16:44', 'Building Expense'),
(916, 'IMP-20260529090112-YACPSH', 17, 100.00, 100.00, 'trial', '2026-01-28', '2026-01-28', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:16:52', 'Opex'),
(917, 'IMP-20260529090112-AOTFJA', 14, 20000.00, 20000.00, 'savings kung', '2026-01-29', '2026-01-29', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-31 15:16:57', 'Salary'),
(918, 'IMP-20260529090112-QPCXMC', 12, 162761.00, 162761.00, 'purchases', '2026-01-29', '2026-01-29', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:01:12', '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL, '2026-05-29 01:01:12', '2026-05-29 01:01:12', NULL),
(919, 'IMP-20260529090240-NPEANA', 2, 415174.00, 415174.00, 'Check deposit from metro-better living Russia', '2026-01-30', '2026-01-30', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL),
(920, 'IMP-20260529090240-FOZS29', 12, 415174.00, 415174.00, 'purchases CMR', '2026-01-30', '2026-01-30', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL),
(921, 'IMP-20260529090240-CHBNSS', 10, 30000.00, 30000.00, 'kairos opex', '2026-01-31', '2026-01-31', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-31 15:17:20', 'Kairos'),
(922, 'IMP-20260529090240-QND0CL', 19, 11600.00, 11600.00, 'isuzu maint', '2026-01-31', '2026-01-31', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-31 15:17:27', 'Vehicle Maintenance'),
(923, 'IMP-20260529090240-ICXPKO', 19, 62100.00, 62100.00, 'freight sarangani', '2026-01-31', '2026-01-31', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-31 15:17:32', 'Freight'),
(924, 'IMP-20260529090240-TLGV1P', 19, 10196.00, 10196.00, 'tandag davao freight', '2026-01-31', '2026-01-31', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-31 15:17:37', 'Freight'),
(925, 'IMP-20260529090240-RMM6UW', 12, 45000.00, 45000.00, 'borrow', '2026-01-31', '2026-01-31', 'approved', 'Manual Credit Entry - Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL),
(926, 'IMP-20260529090240-NHUIAB', 15, 10000.00, 10000.00, 'opex', '2026-01-31', '2026-01-31', 'approved', 'Imported from Excel', 3, 'Accounting', 'Accounting', '2026-05-29 01:02:40', '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL, '2026-05-29 01:02:40', '2026-05-29 01:02:40', NULL),
(1307, 'IMP-20260601231341-4VNNZJ', 2, 15000.00, 15000.00, 'Outbound interbank transfer, bantayan freight', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1308, 'IMP-20260601231341-SNO4HJ', 19, 13000.00, 13000.00, 'capiz freight', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1309, 'IMP-20260601231341-D8W4K0', 14, 10000.00, 10000.00, 'opex', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1310, 'IMP-20260601231341-GCDOXU', 13, 10000.00, 10000.00, 'opex', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1311, 'IMP-20260601231341-KPCYOG', 15, 10000.00, 10000.00, 'opex', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1312, 'IMP-20260601231341-JXXORL', 19, 20000.00, 20000.00, 'opex travel china', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1313, 'IMP-20260601231341-LKG6FB', 19, 17520.00, 17520.00, 'opex china travel', '2026-02-01', '2026-02-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1314, 'IMP-20260601231341-ZEVZ9L', 25, 3200.00, 3200.00, 'freight saragani lot e', '2026-02-02', '2026-02-02', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1315, 'IMP-20260601231341-H8XO1S', 14, 10000.00, 10000.00, 'opex', '2026-02-02', '2026-02-02', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1316, 'IMP-20260601231341-UEQPH8', 12, 15000.00, 15000.00, 'borrow', '2026-02-03', '2026-02-03', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1317, 'IMP-20260601231341-JJBESM', 13, 15000.00, 15000.00, 'bantayan freight', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1318, 'IMP-20260601231341-1YCMPN', 2, 350000.00, 350000.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-03', '2026-02-03', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1319, 'IMP-20260601231341-MJB5DL', 2, 232417.00, 232417.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-03', '2026-02-03', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1320, 'IMP-20260601231341-URAHX5', 12, 232417.00, 232417.00, 'purchases', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1321, 'IMP-20260601231341-DNXBST', 12, 15000.00, 15000.00, 'borrow freight', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1322, 'IMP-20260601231341-JPESCP', 12, 14000.00, 14000.00, 'bidding notary', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1323, 'IMP-20260601231341-WTFW9I', 14, 16768.00, 16768.00, 'OT january', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1324, 'IMP-20260601231341-WMC0TQ', 2, 5300.00, 5300.00, 'Capiz Freight', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1325, 'IMP-20260601231341-94UG3I', 14, 20000.00, 20000.00, 'motor CA', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1326, 'IMP-20260601231341-5DJAOF', 25, 10000.00, 10000.00, 'freight mamburao', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1327, 'IMP-20260601231341-OR7689', 18, 3000.00, 3000.00, 'opex', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1328, 'IMP-20260601231341-GDFRMO', 18, 7100.00, 7100.00, 'freight saragani', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1329, 'IMP-20260601231341-KB0IEG', 24, 3000.00, 3000.00, 'freight.saragani', '2026-02-03', '2026-02-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1330, 'IMP-20260601231341-WMEGL1', 24, 6000.00, 6000.00, 'antique freight', '2026-02-04', '2026-02-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1331, 'IMP-20260601231341-SDC5PB', 2, 310238.00, 310238.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-04', '2026-02-04', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1332, 'IMP-20260601231341-MORJSI', 12, 310238.00, 310238.00, 'Purchases', '2026-02-04', '2026-02-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1333, 'IMP-20260601231341-OVUHIK', 18, 10000.00, 10000.00, 'bohol freight sports', '2026-02-04', '2026-02-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1334, 'IMP-20260601231341-X2NJZX', 28, 2200.00, 2200.00, 'saragani freight truck', '2026-02-04', '2026-02-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1335, 'IMP-20260601231341-ETBG2H', 24, 35000.00, 35000.00, 'saragani freight solid', '2026-02-04', '2026-02-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1336, 'IMP-20260601231341-UPJQ9C', 14, 15000.00, 15000.00, 'savings CA JRM', '2026-02-04', '2026-02-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1337, 'IMP-20260601231341-NAWMBQ', 14, 10000.00, 10000.00, 'opex', '2026-02-06', '2026-02-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1338, 'IMP-20260601231341-ENKIUR', 15, 10000.00, 10000.00, 'opex', '2026-02-06', '2026-02-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1339, 'IMP-20260601231341-C0G8AU', 2, 181549.00, 181549.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-06', '2026-02-06', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1340, 'IMP-20260601231341-DHPVYV', 12, 181549.00, 181549.00, 'purchases cmr', '2026-02-06', '2026-02-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1341, 'IMP-20260601231341-TODPIF', 9, 9700.00, 9700.00, 'freight saragani solid', '2026-02-06', '2026-02-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL);
INSERT INTO `cash_advance_requests` (`id`, `reference_no`, `requester_id`, `requested_amount`, `approved_amount`, `purpose`, `request_date`, `date_needed`, `status`, `accounting_remarks`, `reviewed_by`, `approved_by_name`, `sent_by_name`, `submitted_at`, `reviewed_at`, `released_at`, `liquidation_due_date`, `created_at`, `updated_at`, `category`) VALUES
(1342, 'IMP-20260601231341-VY0OPW', 13, 10000.00, 10000.00, 'opex', '2026-02-07', '2026-02-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1343, 'IMP-20260601231341-OXWDT4', 10, 20000.00, 20000.00, 'opex', '2026-02-07', '2026-02-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1344, 'IMP-20260601231341-XHXYAK', 19, 24269.00, 24269.00, 'telecom mac', '2026-02-07', '2026-02-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1345, 'IMP-20260601231341-6MQJVD', 14, 6000.00, 6000.00, 'perfomance bonus 2025', '2026-02-07', '2026-02-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1346, 'IMP-20260601231341-QWXNMQ', 19, 2794.00, 2794.00, 'batangas water bill dec', '2026-02-09', '2026-02-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1347, 'IMP-20260601231341-UIFMJM', 29, 9800.00, 9800.00, 'china visa', '2026-02-09', '2026-02-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1348, 'IMP-20260601231341-IK25N5', 12, 80000.00, 80000.00, 'borrow purch / returned already', '2026-02-09', '2026-02-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1349, 'IMP-20260601231341-YE3JA4', 9, 4800.00, 4800.00, 'saragani freight', '2026-02-09', '2026-02-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1350, 'IMP-20260601231341-2ZHJF5', 21, 7500.00, 7500.00, 'seed donation', '2026-02-09', '2026-02-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1351, 'IMP-20260601231341-NLW7PS', 2, 379141.00, 379141.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-10', '2026-02-10', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1352, 'IMP-20260601231341-UO43ZQ', 12, 379141.00, 379141.00, NULL, '2026-02-10', '2026-02-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1353, 'IMP-20260601231341-BNF8XU', 12, 80000.00, 80000.00, 'borrow', '2026-02-10', '2026-02-10', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1354, 'IMP-20260601231341-326MPW', 2, 1936.00, 1936.00, 'Outbound interbank transfer', '2026-02-10', '2026-02-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1355, 'IMP-20260601231341-QYMHKA', 14, 10000.00, 10000.00, 'opex', '2026-02-11', '2026-02-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1356, 'IMP-20260601231341-XJHVPN', 18, 7000.00, 7000.00, 'opex', '2026-02-12', '2026-02-12', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1357, 'IMP-20260601231341-KHPWH9', 2, 250000.00, 250000.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-12', '2026-02-12', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1358, 'IMP-20260601231341-YYN1AP', 19, 40209.00, 40209.00, 'freight', '2026-02-12', '2026-02-12', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1359, 'IMP-20260601231341-VHRV4U', 19, 18000.00, 18000.00, 'acctg hezekiah', '2026-02-13', '2026-02-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1360, 'IMP-20260601231341-27PDNH', 19, 21454.00, 21454.00, 'office rental 3 company', '2026-02-13', '2026-02-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1361, 'IMP-20260601231341-4PHRTU', 2, 136229.00, 136229.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-13', '2026-02-13', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1362, 'IMP-20260601231341-G5JCQC', 12, 136229.00, 136229.00, 'purchases cmr returned already', '2026-02-13', '2026-02-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1363, 'IMP-20260601231341-XP5BYW', 2, 1156.00, 1156.00, 'mac ad', '2026-02-13', '2026-02-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1364, 'IMP-20260601231341-JHRNPE', 13, 5000.00, 5000.00, 'opex', '2026-02-13', '2026-02-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1365, 'IMP-20260601231341-79GRCV', 15, 5000.00, 5000.00, 'opex', '2026-02-16', '2026-02-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1366, 'IMP-20260601231341-BP1HFN', 21, 3000.00, 3000.00, 'seed bs', '2026-02-16', '2026-02-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1367, 'IMP-20260601231341-NQ5FBA', 14, 10000.00, 10000.00, 'opex', '2026-02-17', '2026-02-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1368, 'IMP-20260601231341-RFIFXT', 19, 24758.00, 24758.00, 'freight', '2026-02-17', '2026-02-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1369, 'IMP-20260601231341-I2ZJ1D', 13, 10000.00, 10000.00, 'opex', '2026-02-18', '2026-02-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1370, 'IMP-20260601231341-07WPIE', 18, 5000.00, 5000.00, 'opex', '2026-02-18', '2026-02-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1371, 'IMP-20260601231341-EJRLLU', 12, 7400.00, 7400.00, 'biddocs notary', '2026-02-19', '2026-02-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1372, 'IMP-20260601231341-D1VQ03', 12, 8060.00, 8060.00, 'opex', '2026-02-19', '2026-02-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1373, 'IMP-20260601231341-YLKETD', 25, 3500.00, 3500.00, 'mac adv', '2026-02-19', '2026-02-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1374, 'IMP-20260601231341-LFFQUV', 2, 2000.00, 2000.00, 'Outbound interbank transfer, mac advance', '2026-02-19', '2026-02-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1375, 'IMP-20260601231341-XYMZFL', 15, 10000.00, 10000.00, 'opex', '2026-02-19', '2026-02-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1376, 'IMP-20260601231341-CF6MQH', 2, 262286.00, 262286.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-20', '2026-02-20', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1377, 'IMP-20260601231341-WD8GLV', 2, 494457.00, 494457.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-20', '2026-02-20', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1378, 'IMP-20260601231341-XGOTPO', 12, 262286.00, 262286.00, 'purchases', '2026-02-20', '2026-02-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1379, 'IMP-20260601231341-2AKZWO', 14, 10000.00, 10000.00, 'opex', '2026-02-20', '2026-02-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1380, 'IMP-20260601231341-T93JBJ', 19, 27351.00, 27351.00, 'freight saramgani camsur', '2026-02-20', '2026-02-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1381, 'IMP-20260601231341-MEZ4E5', 2, 20000.00, 20000.00, 'Outbound interbank transfer, Cebu Freight', '2026-02-21', '2026-02-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1382, 'IMP-20260601231341-43UA1A', 19, 89460.00, 89460.00, 'TD freight', '2026-02-21', '2026-02-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1383, 'IMP-20260601231341-Q1DSXC', 10, 30000.00, 30000.00, 'kairos opex', '2026-02-21', '2026-02-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1384, 'IMP-20260601231341-J5SFUS', 13, 20500.00, 20500.00, 'freight bantayan cebu', '2026-02-23', '2026-02-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1385, 'IMP-20260601231341-SDRLH6', 19, 20000.00, 20000.00, 'borrow survey', '2026-02-23', '2026-02-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1386, 'IMP-20260601231341-VU3MFO', 18, 7000.00, 7000.00, 'opex', '2026-02-23', '2026-02-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1387, 'IMP-20260601231341-LSNUON', 2, 788.00, 788.00, 'card payment at netflix', '2026-02-24', '2026-02-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1388, 'IMP-20260601231341-EEDBY0', 21, 3000.00, 3000.00, 'seed bs', '2026-02-24', '2026-02-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1389, 'IMP-20260601231341-96FP0V', 2, 12000.00, 12000.00, 'Outbound interbank transfer, seed bs', '2026-02-24', '2026-02-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1390, 'IMP-20260601231341-MDPJGR', 19, 14417.00, 14417.00, 'freight delivery', '2026-02-24', '2026-02-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1391, 'IMP-20260601231341-9TUVBO', 24, 5000.00, 5000.00, 'opex', '2026-02-24', '2026-02-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1392, 'IMP-20260601231341-QMZ1RY', 13, 10000.00, 10000.00, 'opex', '2026-02-24', '2026-02-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1393, 'IMP-20260601231341-FRPZF7', 24, 7000.00, 7000.00, 'opex', '2026-02-25', '2026-02-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1394, 'IMP-20260601231341-LZG10L', 2, 203143.00, 203143.00, 'METRO - BETTER LIVING RUSSIA', '2026-02-25', '2026-02-25', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1395, 'IMP-20260601231341-LJQCGI', 19, 37260.00, 37260.00, 'freight TD kung', '2026-02-25', '2026-02-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1396, 'IMP-20260601231341-0F2XEG', 12, 203143.00, 203143.00, 'purchases', '2026-02-25', '2026-02-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1397, 'IMP-20260601231341-BAH9QR', 19, 16394.00, 16394.00, 'vehicle maint.Tires', '2026-02-25', '2026-02-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1398, 'IMP-20260601231341-PJZZSL', 19, 31824.61, 31824.61, 'opex', '2026-02-25', '2026-02-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1399, 'IMP-20260601231341-EBXWLB', 19, 131950.00, 131950.00, 'freight TD', '2026-02-25', '2026-02-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1400, 'IMP-20260601231341-AGYNG2', 24, 7000.00, 7000.00, 'opex', '2026-02-26', '2026-02-26', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1401, 'IMP-20260601231341-HCVFVD', 26, 6000.00, 6000.00, 'mac adv towel', '2026-02-27', '2026-02-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1402, 'IMP-20260601231341-BMIR0J', 15, 10000.00, 10000.00, 'opex', '2026-02-27', '2026-02-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1403, 'IMP-20260601231341-CFLW2S', 19, 12889.00, 12889.00, 'diesel', '2026-02-27', '2026-02-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1404, 'IMP-20260601231341-FQEP5H', 19, 18245.00, 18245.00, 'freight', '2026-02-28', '2026-02-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1405, 'IMP-20260601231341-BIXG0X', 19, 4000.00, 4000.00, 'OT aba CA feb', '2026-02-28', '2026-02-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:13:41', '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL, '2026-06-01 15:13:41', '2026-06-01 15:13:41', NULL),
(1846, 'IMP-20260601233854-JUHFX7', 24, 5.90, 5.90, 'Outbound interbank transfer', '2026-03-01', '2026-03-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1847, 'IMP-20260601233854-CCZVLF', 24, 3000.00, 3000.00, 'Outbound interbank transfer', '2026-03-01', '2026-03-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1848, 'IMP-20260601233854-8AQAOU', 24, 5000.00, 5000.00, 'Transter to STELLA C. GoTyme Bank, Account No. 2250, opex', '2026-03-02', '2026-03-02', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Opex'),
(1849, 'IMP-20260601233854-ACFAG9', 24, 300000.00, 300000.00, 'Check deposit from METRO-BETTER LIVING RUSSIA Account No. 0573, Check No. 8300045292', '2026-03-02', '2026-03-02', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1850, 'IMP-20260601233854-DHRLYJ', 24, 149038.00, 149038.00, 'Check deposit from METRO-BETTER LIVING RUSSIA Account No.0573, Check No. 8300045294', '2026-03-02', '2026-03-02', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1851, 'IMP-20260601233854-AJAN3D', 12, 149038.00, 149038.00, 'Transfer to CHRISTINE R. GoTyme Bank, Mobile No. +639056, purchases', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1852, 'IMP-20260601233854-AQXNJE', 15, 5000.00, 5000.00, 'Transfer to JENNIFER M. GoTyme Bank. Mobile No. +631977, opex', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Opex'),
(1853, 'IMP-20260601233854-XKSH0Q', 18, 3.00, 3.00, 'Transfer to JOSHUA R. GoTyme Bank, Mobile No: +63 9801, opex', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Opex'),
(1854, 'IMP-20260601233854-FFMBYV', 19, 27.26, 27.26, 'Transfer to MARIA M. GoTyme Bank, Mobile No. +63 4377, opex', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Opex'),
(1855, 'IMP-20260601233854-LE6YGO', 19, 38957.00, 38957.00, 'Transfer to MARIA M. GoTyme Bank, Mobile No. +63 4377, freight TD', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Freight'),
(1856, 'IMP-20260601233854-Z8O6TE', 19, 18606.00, 18606.00, 'Transfer to MARIA M. GoTyme Bank, Mobile No. +634377, remitances kairos qarah', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Kairos'),
(1857, 'IMP-20260601233854-WJJQF8', 19, 90000.00, 90000.00, 'Transfer to MARIA M. GoTyme Bank Mobile No. +63-4377, freight solid corabato', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Freight'),
(1858, 'IMP-20260601233854-PIQVMM', 24, 5.00, 5.00, 'Transfer to STELLA C. GoTyme Bank, Mobile No. +631281, opex', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', 'Opex'),
(1859, 'IMP-20260601233854-JDSNII', 24, 2000.00, 2000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, freight sold', '2026-03-03', '2026-03-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1860, 'IMP-20260601233854-WMDFJC', 24, 2000.00, 2000.00, 'Transfer from JANICE B GoTyme Bank, Account No ******2340', '2026-03-03', '2026-03-03', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1861, 'IMP-20260601233854-BXBMQI', 15, 5000.00, 5000.00, 'Transfer to JENNIFER M GoTyme Bank, Mobile No +63******1977, opex', '2026-03-04', '2026-03-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1862, 'IMP-20260601233854-7JZJIA', 14, 5000.00, 5000.00, 'Transfer to GERLIE D GoTyme Bank, Mobile No +63******5300, salary leti', '2026-03-04', '2026-03-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1863, 'IMP-20260601233854-MHNI9F', 12, 50000.00, 50000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, borrow', '2026-03-04', '2026-03-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1864, 'IMP-20260601233854-8MC1EX', 21, 10000.00, 10000.00, 'Transfer to NOEL E GoTyme Bank, Mobile No +63******5082, seed church', '2026-03-04', '2026-03-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1865, 'IMP-20260601233854-ULJPDP', 24, 3999.00, 3999.00, 'Qr Payment', '2026-03-04', '2026-03-04', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1866, 'IMP-20260601233854-SKRN4F', 19, 9150.83, 9150.83, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, salary last pay cindy', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1867, 'IMP-20260601233854-IFM0UL', 24, 7000.00, 7000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1868, 'IMP-20260601233854-72JX7I', 24, 158223.00, 158223.00, 'Check deposit from METRO – BETTER LIVING RUSSIA Account No ******0573, Check No. 8300045301', '2026-03-05', '2026-03-05', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1869, 'IMP-20260601233854-8OLH4B', 12, 158223.00, 158223.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, purchases', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1870, 'IMP-20260601233854-7R3FCR', 18, 5000.00, 5000.00, 'Transfer to JOSHUA R GoTyme Bank, Mobile No +63******9801, opex', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1871, 'IMP-20260601233854-F0KWZM', 19, 23890.00, 23890.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, freight charge', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1872, 'IMP-20260601233854-V1GXHQ', 12, 50000.00, 50000.00, 'Transfer from CHRISTINE R GoTyme Bank, Account No ******3577, borrow', '2026-03-05', '2026-03-05', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1873, 'IMP-20260601233854-ATU1OZ', 12, 3000.00, 3000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, cash adv. calley savings', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1874, 'IMP-20260601233854-XVNWLY', 12, 9195.00, 9195.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, cash advance regie savings', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1875, 'IMP-20260601233854-WGYHJY', 19, 25629.88, 25629.88, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, freight sold', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1876, 'IMP-20260601233854-4AFHHE', 19, 5636.00, 5636.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, telecom mac', '2026-03-05', '2026-03-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1877, 'IMP-20260601233854-JIM6NT', 24, 300000.00, 300000.00, 'Check deposit from METRO - BETTER LIVING RUSSIA Account No ******0573, Check No. 8300045302', '2026-03-06', '2026-03-06', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1878, 'IMP-20260601233854-TXZD5U', 10, 30000.00, 30000.00, 'Transfer to ALEXANDER C GoTyme Bank, Mobile No +63******7430, kairos opex', '2026-03-07', '2026-03-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1879, 'IMP-20260601233854-DFITKD', 12, 100000.00, 100000.00, 'Transfer to CHRISTINE R GoTyme Bank, Account No ******3577, borrow', '2026-03-07', '2026-03-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1880, 'IMP-20260601233854-CPXTNH', 19, 5228.00, 5228.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, freight labor', '2026-03-07', '2026-03-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1881, 'IMP-20260601233854-JNI4NT', 12, 13670.00, 13670.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, OT feb savings', '2026-03-07', '2026-03-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1882, 'IMP-20260601233854-GHY9B4', 19, 21999.00, 21999.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, OT feb', '2026-03-07', '2026-03-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1883, 'IMP-20260601233854-KTSNIB', 19, 4000.00, 4000.00, 'Transfer from MARIA M GoTyme Bank, Account No ******8870, return CA ABA', '2026-03-07', '2026-03-07', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1884, 'IMP-20260601233854-KIXDXP', 24, 2500.00, 2500.00, 'Transfer to DARREL C GoTyme Bank, Mobile No +63******2031, mac adv', '2026-03-08', '2026-03-08', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1885, 'IMP-20260601233854-CIKZ4F', 24, 3500.00, 3500.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, accommodation', '2026-03-08', '2026-03-08', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1886, 'IMP-20260601233854-7VCGPH', 19, 32000.00, 32000.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, freight capiz', '2026-03-09', '2026-03-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1887, 'IMP-20260601233854-BP7UFP', 24, 8000.00, 8000.00, 'Transfer to RHODORA C GoTyme Bank, Account No ******4627, hr salary', '2026-03-09', '2026-03-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1888, 'IMP-20260601233854-QTO7GL', 24, 7000.00, 7000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-10', '2026-03-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1889, 'IMP-20260601233854-SD7MVA', 24, 248317.00, 248317.00, 'Check deposit from METRO - BETTER LIVING RUSSIA Account No ******0573, Check No. 8300045306', '2026-03-10', '2026-03-10', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1890, 'IMP-20260601233854-SGFP6P', 12, 248317.00, 248317.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, purchases', '2026-03-10', '2026-03-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1891, 'IMP-20260601233854-RLLSQI', 24, 5000.00, 5000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-10', '2026-03-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1892, 'IMP-20260601233854-AXS0OM', 18, 5000.00, 5000.00, 'Transfer to JOSHUA R GoTyme Bank, Mobile No +63******9801, opex', '2026-03-10', '2026-03-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1893, 'IMP-20260601233854-FIHIZR', 19, 37038.00, 37038.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, freight', '2026-03-11', '2026-03-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1894, 'IMP-20260601233854-KIYNZJ', 12, 100000.00, 100000.00, 'Transfer from CHRISTINE R GoTyme Bank, Account No ******3577, borrow', '2026-03-11', '2026-03-11', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1895, 'IMP-20260601233854-IG66PG', 24, 5000.00, 5000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-11', '2026-03-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1896, 'IMP-20260601233854-YGRDAL', 19, 10877.00, 10877.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, mac Adv', '2026-03-11', '2026-03-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1897, 'IMP-20260601233854-NBC6UU', 24, 4000.00, 4000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, accommodation', '2026-03-11', '2026-03-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1898, 'IMP-20260601233854-ER6L1H', 15, 7000.00, 7000.00, 'Transfer to JENNIFER M GoTyme Bank, Mobile No +63******1977, opex', '2026-03-11', '2026-03-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1899, 'IMP-20260601233854-DQZMRJ', 12, 16065.00, 16065.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, bid docs', '2026-03-12', '2026-03-12', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1900, 'IMP-20260601233854-KU2EOR', 12, 4000.00, 4000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, CA Leenth hospital emergency', '2026-03-12', '2026-03-12', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1901, 'IMP-20260601233854-VMGT4S', 24, 1190.00, 1190.00, 'Card payment at GOOGLE \"Google One Card ****7944\"', '2026-03-13', '2026-03-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1902, 'IMP-20260601233854-DS2HPY', 12, 50000.00, 50000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, borrow', '2026-03-13', '2026-03-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1903, 'IMP-20260601233854-E45S2T', 19, 12000.00, 12000.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, office rental', '2026-03-13', '2026-03-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1904, 'IMP-20260601233854-BHQAQN', 21, 5000.00, 5000.00, 'Transfer to NOEL E GoTyme Bank, Mobile No +63******5082, seed', '2026-03-13', '2026-03-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1905, 'IMP-20260601233854-AS6UOX', 10, 30000.00, 30000.00, 'Transfer to ALEXANDER C GoTyme Bank, Mobile No +63******7430, kairos opex', '2026-03-14', '2026-03-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1906, 'IMP-20260601233854-WQKOOV', 18, 5000.00, 5000.00, 'Transfer to JOSHUA R GoTyme Bank, Mobile No +63******9801, opex', '2026-03-15', '2026-03-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1907, 'IMP-20260601233854-SCLKIN', 24, 1.00, 1.00, 'Transfer to HERBERT B GoTyme Bank, Account No ******5297, trial', '2026-03-16', '2026-03-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1908, 'IMP-20260601233854-D3R68K', 24, 250000.00, 250000.00, 'Check deposit from METRO - BETTER LIVING RUSSIA Account No ******0573, Check No. 8300045305', '2026-03-16', '2026-03-16', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1909, 'IMP-20260601233854-AMYH7H', 24, 2000.00, 2000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, opex', '2026-03-16', '2026-03-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1910, 'IMP-20260601233854-JOZUUY', 24, 4000.00, 4000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, accommodation rental wh', '2026-03-17', '2026-03-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1911, 'IMP-20260601233854-VRCNV0', 19, 38786.00, 38786.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, freight', '2026-03-17', '2026-03-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1912, 'IMP-20260601233854-BRO5AX', 12, 5000.00, 5000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, supreme court comi', '2026-03-17', '2026-03-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1913, 'IMP-20260601233854-XNSZPW', 18, 5000.00, 5000.00, 'Transfer to JOSHUA R GoTyme Bank, Mobile No +63******9801, opex', '2026-03-17', '2026-03-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1914, 'IMP-20260601233854-ZOTUI2', 12, 6600.00, 6600.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, opex', '2026-03-17', '2026-03-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1915, 'IMP-20260601233854-IRIZJE', 24, 5000.00, 5000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-17', '2026-03-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1916, 'IMP-20260601233854-BOA4XH', 15, 6000.00, 6000.00, 'Transfer to JENNIFER M GoTyme Bank, Mobile No +63******1977, opex', '2026-03-18', '2026-03-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1917, 'IMP-20260601233854-QH5X2A', 24, 4000.00, 4000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, accommodation', '2026-03-18', '2026-03-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1918, 'IMP-20260601233854-EAEOOM', 19, 17389.00, 17389.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, opex', '2026-03-19', '2026-03-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1919, 'IMP-20260601233854-GYW5PX', 9, 2800.00, 2800.00, 'Transfer to ALEX A GoTyme Bank, Account No ******7471, mac adv', '2026-03-19', '2026-03-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1920, 'IMP-20260601233854-WHQ6ZK', 24, 12000.00, 12000.00, 'Outbound interbank transfer', '2026-03-19', '2026-03-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1921, 'IMP-20260601233854-EWQCMH', 21, 2500.00, 2500.00, 'Transfer to NOEL E GoTyme Bank, Mobile No +63******5082, seed bs', '2026-03-19', '2026-03-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1922, 'IMP-20260601233854-KQMXQQ', 24, 1500.00, 1500.00, 'Transfer to FERMIN N GoTyme Bank, Account No ******1793, mac adv', '2026-03-19', '2026-03-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1923, 'IMP-20260601233854-Q5XJDZ', 19, 5000.00, 5000.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, bldg maint. pest control', '2026-03-20', '2026-03-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1924, 'IMP-20260601233854-WVOWIC', 19, 9000.00, 9000.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, office exp atty. jinky', '2026-03-20', '2026-03-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1925, 'IMP-20260601233854-IMA4CO', 12, 50000.00, 50000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, borrow', '2026-03-21', '2026-03-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1926, 'IMP-20260601233854-TADFQQ', 10, 10000.00, 10000.00, 'Transfer to ALEXANDER C GoTyme Bank, Mobile No +63******7430, opex', '2026-03-21', '2026-03-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1927, 'IMP-20260601233854-TTN5ID', 24, 5000.00, 5000.00, 'Outbound interbank transfer', '2026-03-22', '2026-03-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1928, 'IMP-20260601233854-ARIWSF', 24, 2400.00, 2400.00, 'Transfer to DAVID C GoTyme Bank, Account No ******9031, mac adv', '2026-03-22', '2026-03-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1929, 'IMP-20260601233854-LJJGHA', 24, 7000.00, 7000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1930, 'IMP-20260601233854-GAQIB8', 18, 5000.00, 5000.00, 'Transfer to JOSHUA R GoTyme Bank, Mobile No +63******9801, opex', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1931, 'IMP-20260601233854-KSB5PZ', 15, 5000.00, 5000.00, 'Transfer to JENNIFER M GoTyme Bank, Mobile No +63******1977, opex', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL);
INSERT INTO `cash_advance_requests` (`id`, `reference_no`, `requester_id`, `requested_amount`, `approved_amount`, `purpose`, `request_date`, `date_needed`, `status`, `accounting_remarks`, `reviewed_by`, `approved_by_name`, `sent_by_name`, `submitted_at`, `reviewed_at`, `released_at`, `liquidation_due_date`, `created_at`, `updated_at`, `category`) VALUES
(1932, 'IMP-20260601233854-LMXNVV', 24, 10000.00, 10000.00, 'Transfer to JOHN C GoTyme Bank, Account No ******7376, mamburao delivery', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1933, 'IMP-20260601233854-ERFHF4', 24, 7000.00, 7000.00, 'Transfer to RHODORA C GoTyme Bank, Account No ******4627, hr pt march bal', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1934, 'IMP-20260601233854-G1XFQO', 24, 3000.00, 3000.00, 'Transfer to RHODORA C GoTyme Bank, Account No ******4627, hr pt april bal', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1935, 'IMP-20260601233854-LIVYBE', 12, 5000.00, 5000.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, CA allan savings', '2026-03-23', '2026-03-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1936, 'IMP-20260601233854-A4ISYE', 24, 788.00, 788.00, 'Card payment at Netflix.com, Card ****7944', '2026-03-24', '2026-03-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1937, 'IMP-20260601233854-EEEYYS', 24, 4000.00, 4000.00, 'Transfer to JOHN C GoTyme Bank, Account No ******7375, delivery mamburao', '2026-03-24', '2026-03-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1938, 'IMP-20260601233854-T2S7BR', 24, 200000.00, 200000.00, 'Check deposit from METRO - BETTER LIVING RUSSIA Account No ******0573, Check No. 8300045309', '2026-03-24', '2026-03-24', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1939, 'IMP-20260601233854-0ZYODW', 19, 48576.00, 48576.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, diesel delivery', '2026-03-24', '2026-03-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1940, 'IMP-20260601233854-IRA20S', 12, 50000.00, 50000.00, 'Transfer from CHRISTINE R GoTyme Bank, Account No ******3577, borrow', '2026-03-24', '2026-03-24', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1941, 'IMP-20260601233854-SRVBLX', 19, 39892.00, 39892.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, opex', '2026-03-24', '2026-03-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1942, 'IMP-20260601233854-G5UG4X', 24, 5000.00, 5000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-24', '2026-03-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1943, 'IMP-20260601233854-CAYST1', 15, 7000.00, 7000.00, 'Transfer to JENNIFER M GoTyme Bank, Mobile No +63******1977, opex', '2026-03-24', '2026-03-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1944, 'IMP-20260601233854-TB43X9', 24, 5000.00, 5000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, opex delivery camsur', '2026-03-25', '2026-03-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1945, 'IMP-20260601233854-4LZTR6', 19, 8000.00, 8000.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, kairos lot payment', '2026-03-25', '2026-03-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1946, 'IMP-20260601233854-71FDKW', 24, 2000.00, 2000.00, 'Transfer to DAVID C GoTyme Bank, Mobile No +63******8806, mac adv', '2026-03-25', '2026-03-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1947, 'IMP-20260601233854-NT11JI', 18, 4000.00, 4000.00, 'Transfer to JOSHUA R GoTyme Bank, Mobile No +63******9801, opex', '2026-03-25', '2026-03-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1948, 'IMP-20260601233854-KEVLTV', 24, 10000.00, 10000.00, 'Transfer to JANICE B GoTyme Bank, Account No ******2340, camsur delivery', '2026-03-25', '2026-03-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1949, 'IMP-20260601233854-DGEXXE', 24, 5000.00, 5000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-26', '2026-03-26', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1950, 'IMP-20260601233854-GNOVEM', 19, 24220.00, 24220.00, 'Transfer to MARIA M GoTyme Bank, Mobile No +63******4377, delivery', '2026-03-27', '2026-03-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1951, 'IMP-20260601233854-GAN6WB', 12, 2500.00, 2500.00, 'Transfer to CHRISTINE R GoTyme Bank, Mobile No +63******9056, ABA CA savings', '2026-03-27', '2026-03-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(1952, 'IMP-20260601233854-PVTFMC', 24, 7000.00, 7000.00, 'Transfer to STELLA C GoTyme Bank, Mobile No +63******1281, opex', '2026-03-27', '2026-03-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 15:38:54', '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL, '2026-06-01 15:38:54', '2026-06-01 15:38:54', NULL),
(2214, 'IMP-20260602000627-61LG9F', 24, 594.03, 594.03, 'Card payment', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2215, 'IMP-20260602000627-E2GGL7', 24, 300000.00, 300000.00, 'Metrobank', '2026-04-01', '2026-04-01', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2216, 'IMP-20260602000627-PJMTNZ', 19, 11000.00, 11000.00, 'Capiz Commi', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Commission'),
(2217, 'IMP-20260602000627-VSLWTM', 19, 23541.00, 23541.00, 'March OT', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2218, 'IMP-20260602000627-KLATJ8', 12, 12670.00, 12670.00, 'March OT Savings', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2219, 'IMP-20260602000627-CSWCRG', 19, 23167.00, 23167.00, 'Erma Last Pay', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2220, 'IMP-20260602000627-D1ZWMO', 24, 3000.00, 3000.00, 'BS Seed', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2221, 'IMP-20260602000627-5R9PYD', 24, 3434.17, 3434.17, 'Card payment', '2026-04-01', '2026-04-01', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2222, 'IMP-20260602000627-HQYIRI', 24, 1000.00, 1000.00, 'Mac advance', '2026-04-02', '2026-04-02', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2223, 'IMP-20260602000627-3CULSB', 24, 500.00, 500.00, 'Mac advance', '2026-04-02', '2026-04-02', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2224, 'IMP-20260602000627-IFY87P', 24, 612.75, 612.75, NULL, '2026-04-03', '2026-04-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2225, 'IMP-20260602000627-AJATOD', 24, 358.40, 358.40, NULL, '2026-04-03', '2026-04-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2226, 'IMP-20260602000627-UYKDOI', 24, 2774.70, 2774.70, NULL, '2026-04-03', '2026-04-03', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2227, 'IMP-20260602000627-SIMA7K', 12, 10000.00, 10000.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2228, 'IMP-20260602000627-3KA6YZ', 12, 25000.00, 25000.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2229, 'IMP-20260602000627-3MJDXD', 24, 7458.00, 7458.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2230, 'IMP-20260602000627-YU0WAB', 24, 1017.00, 1017.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2231, 'IMP-20260602000627-QSUM3K', 24, 400.00, 400.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2232, 'IMP-20260602000627-KHKIQX', 24, 180.00, 180.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Parking'),
(2233, 'IMP-20260602000627-6OLFHR', 24, 945.00, 945.00, NULL, '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2234, 'IMP-20260602000627-AH5PPL', 19, 18000.00, 18000.00, 'Batanes', '2026-04-05', '2026-04-05', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2235, 'IMP-20260602000627-DWF20R', 12, 30000.00, 30000.00, NULL, '2026-04-06', '2026-04-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2236, 'IMP-20260602000627-FOMJ5W', 19, 8539.00, 8539.00, NULL, '2026-04-06', '2026-04-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Telecom'),
(2237, 'IMP-20260602000627-8YXJKO', 12, 4300.00, 4300.00, 'Notary', '2026-04-06', '2026-04-06', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2238, 'IMP-20260602000627-JHILE6', 12, 15000.00, 15000.00, 'Kho Shipping Lines (KSL) Incorporated', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2239, 'IMP-20260602000627-PW48XW', 13, 1487.25, 1487.25, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2240, 'IMP-20260602000627-JTGXXI', 13, 1852.75, 1852.75, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2241, 'IMP-20260602000627-BT0RD2', 13, 1060.00, 1060.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2242, 'IMP-20260602000627-DQVN92', 13, 600.00, 600.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Labor'),
(2243, 'IMP-20260602000627-QC3OOT', 19, 6000.00, 6000.00, 'rent', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Qarrah'),
(2244, 'IMP-20260602000627-JMRWPO', 19, 686.00, 686.00, 'utilities', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Qarrah'),
(2245, 'IMP-20260602000627-Q1FOJX', 19, 6000.00, 6000.00, 'rent', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2246, 'IMP-20260602000627-FYOGNM', 19, 1947.00, 1947.00, 'maynilad', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Utilities'),
(2247, 'IMP-20260602000627-EADY8H', 19, 6000.00, 6000.00, 'internet', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Utilities'),
(2248, 'IMP-20260602000627-6YEHJF', 19, 215.00, 215.00, 'lalamove (assestment tool)', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2249, 'IMP-20260602000627-MNDPLG', 19, 1100.00, 1100.00, 'Jaymes Salary', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2250, 'IMP-20260602000627-9HVSFP', 19, 4949.00, 4949.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2251, 'IMP-20260602000627-THXDMZ', 24, 1065.00, 1065.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2252, 'IMP-20260602000627-MFQIS7', 24, 150.00, 150.00, 'Jaymes Transpo', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2253, 'IMP-20260602000627-EDXOVF', 24, 2180.00, 2180.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2254, 'IMP-20260602000627-XVPQYJ', 24, 1000.00, 1000.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2255, 'IMP-20260602000627-M6I7MU', 24, 500.00, 500.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2256, 'IMP-20260602000627-V7X09Y', 24, 105.00, 105.00, NULL, '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2257, 'IMP-20260602000627-UROORJ', 24, 2000.00, 2000.00, 'Philippine Red Cross', '2026-04-07', '2026-04-07', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2258, 'IMP-20260602000627-6WEJVE', 12, 50000.00, 50000.00, NULL, '2026-04-08', '2026-04-08', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2259, 'IMP-20260602000627-7UWKDB', 12, 4000.00, 4000.00, 'Training Opex', '2026-04-08', '2026-04-08', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2260, 'IMP-20260602000627-1QKSP4', 24, 3000.00, 3000.00, 'BS Seed', '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2261, 'IMP-20260602000627-ZUER3F', 24, 6000.00, 6000.00, 'Meal Allan, Joshua', '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2262, 'IMP-20260602000627-LKDSMY', 24, 550.00, 550.00, NULL, '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2263, 'IMP-20260602000627-OJKT3A', 24, 2568.00, 2568.00, NULL, '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2264, 'IMP-20260602000627-88SAZU', 24, 1240.00, 1240.00, NULL, '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2265, 'IMP-20260602000627-MLYWS8', 24, 3599.00, 3599.00, NULL, '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2266, 'IMP-20260602000627-UVR6F0', 24, 270.00, 270.00, NULL, '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2267, 'IMP-20260602000627-SLZZH6', 24, 773.00, 773.00, NULL, '2026-04-09', '2026-04-09', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2268, 'IMP-20260602000627-AM6RH2', 24, 4500.00, 4500.00, 'outbound interbank', '2026-04-10', '2026-04-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2269, 'IMP-20260602000627-LQHBNR', 12, 50000.00, 50000.00, NULL, '2026-04-10', '2026-04-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2270, 'IMP-20260602000627-XPM3MY', 24, 20000.00, 20000.00, NULL, '2026-04-10', '2026-04-10', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2271, 'IMP-20260602000627-UCDFFU', 13, 5912.75, 5912.75, NULL, '2026-04-11', '2026-04-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2272, 'IMP-20260602000627-B2G3BR', 13, 1188.25, 1188.25, NULL, '2026-04-11', '2026-04-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Labor'),
(2273, 'IMP-20260602000627-13CRMS', 13, 599.00, 599.00, NULL, '2026-04-11', '2026-04-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2274, 'IMP-20260602000627-9XKS7O', 13, 2300.00, 2300.00, NULL, '2026-04-11', '2026-04-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2275, 'IMP-20260602000627-6UVAGF', 24, 10000.00, 10000.00, NULL, '2026-04-11', '2026-04-11', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2276, 'IMP-20260602000627-RLGFUU', 24, 4000.00, 4000.00, 'gcash', '2026-04-12', '2026-04-12', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2277, 'IMP-20260602000627-YWEGHH', 24, 6000.00, 6000.00, 'Jesus Faith Christian Church', '2026-04-12', '2026-04-12', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2278, 'IMP-20260602000627-GTTSVD', 24, 16000.00, 16000.00, 'Inbound interbank', '2026-04-13', '2026-04-13', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2279, 'IMP-20260602000627-Y6YUBB', 24, 200000.00, 200000.00, 'Metrobank', '2026-04-13', '2026-04-13', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2280, 'IMP-20260602000627-NGXDGY', 24, 1.00, 1.00, 'trial', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2281, 'IMP-20260602000627-4CEMIL', 24, 16000.00, 16000.00, 'Inbound interbank', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2282, 'IMP-20260602000627-RJOVLW', 12, 5000.00, 5000.00, 'Jennie Savings', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2283, 'IMP-20260602000627-ZZXLK9', 24, 3170.00, 3170.00, 'Nono', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2284, 'IMP-20260602000627-3IIBHH', 24, 830.00, 830.00, 'Nono', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2285, 'IMP-20260602000627-DNSNVM', 24, 1200.00, 1200.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2286, 'IMP-20260602000627-PDNSGJ', 24, 2100.00, 2100.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2287, 'IMP-20260602000627-RDWCSY', 24, 700.00, 700.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2288, 'IMP-20260602000627-YEEJA8', 24, 2000.00, 2000.00, 'M\' Tine', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2289, 'IMP-20260602000627-HVXSXQ', 19, 12500.00, 12500.00, 'Purchases Capiz Grass', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2290, 'IMP-20260602000627-MXT5SP', 19, 1800.00, 1800.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2291, 'IMP-20260602000627-EPGLPX', 19, 2728.00, 2728.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2292, 'IMP-20260602000627-3QOHSJ', 19, 2460.00, 2460.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2293, 'IMP-20260602000627-6KOYQC', 19, 1200.00, 1200.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2294, 'IMP-20260602000627-DCHNB6', 15, 2000.00, 2000.00, 'Autosweep CRV 1192 RFID (216786)', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2295, 'IMP-20260602000627-XV3KU9', 15, 1000.00, 1000.00, 'Autosweep Civic Nibe 6967 RFID (053649)', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2296, 'IMP-20260602000627-YZEPD9', 15, 1500.00, 1500.00, 'Autosweep NEU 3777 RFID (050761)', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2297, 'IMP-20260602000627-IMFURN', 15, 500.00, 500.00, 'notary for DENR Omnibus', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2298, 'IMP-20260602000627-O1V7IQ', 24, 2700.00, 2700.00, 'gcash', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2299, 'IMP-20260602000627-BOSIUC', 12, 14200.00, 14200.00, 'Dole and Surigao', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2300, 'IMP-20260602000627-KINKTC', 12, 1500.00, 1500.00, 'Kho Shipping Lines (KSL) Incorporated', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2301, 'IMP-20260602000627-NMGEDJ', 12, 300.00, 300.00, 'Polambato Arrastre Services Inc', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2302, 'IMP-20260602000627-GBHFWD', 12, 660.00, 660.00, 'Republic of the Philippines City og Bogo Office of the treasurer', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2303, 'IMP-20260602000627-X3EVRV', 12, 7600.00, 7600.00, 'Jazul Gasoline Station', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Parking'),
(2304, 'IMP-20260602000627-J6KWNT', 12, 258.00, 258.00, 'Kho Shipping Lines (KSL) Incorporated', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2305, 'IMP-20260602000627-AYPLWC', 12, 4431.00, 4431.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2306, 'IMP-20260602000627-DMRZHR', 12, 251.00, 251.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2307, 'IMP-20260602000627-MIX0EQ', 24, 3000.00, 3000.00, 'Salary Prof Fee', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2308, 'IMP-20260602000627-3VNO35', 12, 15000.00, 15000.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2309, 'IMP-20260602000627-O85YZT', 12, 6800.00, 6800.00, 'Zia Treatment', '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'seed'),
(2310, 'IMP-20260602000627-WPYMSP', 12, 8000.00, 8000.00, NULL, '2026-04-13', '2026-04-13', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2311, 'IMP-20260602000627-7MUEPV', 12, 12400.00, 12400.00, 'Office Staff', '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2312, 'IMP-20260602000627-S6XGA0', 12, 11000.00, 11000.00, NULL, '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2313, 'IMP-20260602000627-KGJRPJ', 19, 5533.00, 5533.00, 'Office Equipment', '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2314, 'IMP-20260602000627-PIYUPK', 24, 1000.00, 1000.00, 'Calley', '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2315, 'IMP-20260602000627-OQSBKY', 24, 1525.00, 1525.00, NULL, '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2316, 'IMP-20260602000627-AMPHSR', 24, 1775.00, 1775.00, NULL, '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2317, 'IMP-20260602000627-LHNROG', 24, 700.00, 700.00, NULL, '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2318, 'IMP-20260602000627-VVUCJW', 24, 5250.00, 5250.00, 'Outbound Interbank', '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2319, 'IMP-20260602000627-1QOEK6', 24, 920.00, 920.00, 'Outbound Interbank', '2026-04-14', '2026-04-14', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2320, 'IMP-20260602000627-OC6XVN', 13, 411.75, 411.75, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2321, 'IMP-20260602000627-UNPS07', 13, 1975.25, 1975.25, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2322, 'IMP-20260602000627-LBVL6K', 13, 6470.00, 6470.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2323, 'IMP-20260602000627-O2RPGF', 13, 100.00, 100.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2324, 'IMP-20260602000627-FMKLUU', 13, 1000.00, 1000.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Labor'),
(2325, 'IMP-20260602000627-9ALYFI', 13, 43.00, 43.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2326, 'IMP-20260602000627-66BOJR', 12, 1200.00, 1200.00, 'Food', '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2327, 'IMP-20260602000627-05OHP7', 12, 72.00, 72.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bank Charges'),
(2328, 'IMP-20260602000627-UYW09Q', 12, 1770.00, 1770.00, 'Allan/ Joseph/ Aba', '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Cash Advance'),
(2329, 'IMP-20260602000627-5BFDET', 12, 1129.00, 1129.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2330, 'IMP-20260602000627-SZRCSG', 12, 5829.00, 5829.00, NULL, '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2331, 'IMP-20260602000627-9PZ3X0', 19, 12500.00, 12500.00, 'returned capiz commi', '2026-04-15', '2026-04-15', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2332, 'IMP-20260602000627-2IFQ5W', 24, 1300.00, 1300.00, 'vehicle maintenance', '2026-04-15', '2026-04-15', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Vehicle Maintenance'),
(2333, 'IMP-20260602000627-JI79TJ', 24, 4500.00, 4500.00, 'P. Basbas Gcash', '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2334, 'IMP-20260602000627-YP5RUD', 24, 350000.00, 350000.00, 'Metrobank', '2026-04-16', '2026-04-16', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2335, 'IMP-20260602000627-EPCFHM', 12, 50000.00, 50000.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2336, 'IMP-20260602000627-V0HR5E', 24, 1000.00, 1000.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2337, 'IMP-20260602000627-Z3WWAJ', 19, 2959.00, 2959.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2338, 'IMP-20260602000627-DN87LK', 19, 1000.00, 1000.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2339, 'IMP-20260602000627-TUY6DH', 24, 3258.00, 3258.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2340, 'IMP-20260602000627-D5VCH4', 24, 1172.00, 1172.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2341, 'IMP-20260602000627-HHVOC9', 24, 475.00, 475.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Vehicle Maintenance'),
(2342, 'IMP-20260602000627-MZSUI0', 24, 95.00, 95.00, NULL, '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2343, 'IMP-20260602000627-BAT661', 24, 5000.00, 5000.00, 'm. gcash', '2026-04-16', '2026-04-16', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2344, 'IMP-20260602000627-SIOPS8', 19, 26000.00, 26000.00, 'Saranggani', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Commission'),
(2345, 'IMP-20260602000627-PFTAL9', 13, 800.00, 800.00, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2346, 'IMP-20260602000627-2BNLFJ', 13, 615.75, 615.75, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2347, 'IMP-20260602000627-61JXA3', 13, 1284.25, 1284.25, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2348, 'IMP-20260602000627-WOKDA6', 13, 4000.00, 4000.00, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2349, 'IMP-20260602000627-CTXZJS', 13, 300.00, 300.00, 'CHAT GPT', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2350, 'IMP-20260602000627-WD33E7', 19, 4780.00, 4780.00, 'KUNG (motor less for DAR)', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2351, 'IMP-20260602000627-TESPZZ', 19, 1500.00, 1500.00, 'Jay Fulguerinas', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2352, 'IMP-20260602000627-TZOG3Z', 12, 2500.00, 2500.00, 'Savings Kung', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2353, 'IMP-20260602000627-Z58JQI', 24, 2150.00, 2150.00, 'Calley', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2354, 'IMP-20260602000627-QJKBKH', 24, 850.00, 850.00, 'Calley', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation');
INSERT INTO `cash_advance_requests` (`id`, `reference_no`, `requester_id`, `requested_amount`, `approved_amount`, `purpose`, `request_date`, `date_needed`, `status`, `accounting_remarks`, `reviewed_by`, `approved_by_name`, `sent_by_name`, `submitted_at`, `reviewed_at`, `released_at`, `liquidation_due_date`, `created_at`, `updated_at`, `category`) VALUES
(2355, 'IMP-20260602000627-CYJWN1', 24, 1000.00, 1000.00, 'Nono', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2356, 'IMP-20260602000627-FJM0KO', 24, 1000.00, 1000.00, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2357, 'IMP-20260602000627-AXF3QD', 24, 2500.00, 2500.00, 'Seed BS', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2358, 'IMP-20260602000627-MZDXG6', 24, 2000.00, 2000.00, 'Seed Church anniv', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2359, 'IMP-20260602000627-VHNOWT', 15, 403.00, 403.00, 'Autosweep NEU 3777 RFID (050761)', '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2360, 'IMP-20260602000627-EYO6VY', 15, 600.00, 600.00, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2361, 'IMP-20260602000627-ZJXAZO', 15, 3997.00, 3997.00, NULL, '2026-04-17', '2026-04-17', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2362, 'IMP-20260602000627-MBXECB', 19, 77976.00, 77976.00, 'Payroll', '2026-04-18', '2026-04-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2363, 'IMP-20260602000627-PFTFKV', 12, 24575.00, 24575.00, 'Payroll Mac CHRISTINE R.', '2026-04-18', '2026-04-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2364, 'IMP-20260602000627-CUEUUK', 24, 20000.00, 20000.00, 'Kairos Opex', '2026-04-18', '2026-04-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2365, 'IMP-20260602000627-DJ6SRH', 19, 5000.00, 5000.00, 'kairos opex Feeds', '2026-04-18', '2026-04-18', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2366, 'IMP-20260602000627-SFIVKR', 24, 800.00, 800.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2367, 'IMP-20260602000627-MLJ6RQ', 24, 1680.00, 1680.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2368, 'IMP-20260602000627-RPIDLM', 24, 2520.00, 2520.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2369, 'IMP-20260602000627-PHXXDM', 19, 4170.00, 4170.00, 'Joseph Salary', '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2370, 'IMP-20260602000627-HFG1CG', 24, 5000.00, 5000.00, 'Jesus Faith Christian Church', '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2371, 'IMP-20260602000627-ETFZ89', 13, 2500.00, 2500.00, 'Calley', '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2372, 'IMP-20260602000627-RF47AF', 13, 600.00, 600.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2373, 'IMP-20260602000627-0B70WM', 13, 620.00, 620.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2374, 'IMP-20260602000627-PDNQF9', 13, 1635.00, 1635.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2375, 'IMP-20260602000627-BH3U4R', 13, 955.00, 955.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Labor'),
(2376, 'IMP-20260602000627-TUTGO7', 13, 599.00, 599.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Telecom'),
(2377, 'IMP-20260602000627-SECP7G', 13, 91.00, 91.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2378, 'IMP-20260602000627-FJ7SA7', 24, 5500.00, 5500.00, NULL, '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2379, 'IMP-20260602000627-WYWIZN', 24, 1000.00, 1000.00, 'Baon Darrel', '2026-04-19', '2026-04-19', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2380, 'IMP-20260602000627-NO8NB6', 15, 2000.00, 2000.00, 'Autosweep RFID Civic (053649)', '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2381, 'IMP-20260602000627-P7YUCH', 15, 1350.00, 1350.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2382, 'IMP-20260602000627-RWDAFF', 15, 4650.00, 4650.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2383, 'IMP-20260602000627-I5KPQZ', 15, 2000.00, 2000.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2384, 'IMP-20260602000627-Y1XWFZ', 24, 2500.00, 2500.00, 'Mac Darrel Tutor', '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2385, 'IMP-20260602000627-VSPPB1', 24, 5000.00, 5000.00, 'm. gcash', '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2386, 'IMP-20260602000627-ICJCJK', 24, 1613.00, 1613.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2387, 'IMP-20260602000627-3LK7NF', 24, 500.00, 500.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2388, 'IMP-20260602000627-DBV62B', 24, 2600.00, 2600.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2389, 'IMP-20260602000627-4YBFWJ', 24, 350.00, 350.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2390, 'IMP-20260602000627-7J6FJ6', 24, 27.00, 27.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bank Charges'),
(2391, 'IMP-20260602000627-T8HJZE', 24, 110.00, 110.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Parking'),
(2392, 'IMP-20260602000627-J9HD2Q', 24, 1800.00, 1800.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2393, 'IMP-20260602000627-78ZMAH', 19, 5000.00, 5000.00, 'Camsur Commission', '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Commission'),
(2394, 'IMP-20260602000627-WWIL4T', 12, 15000.00, 15000.00, 'MAC salary to ADC', '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2395, 'IMP-20260602000627-RII9H9', 24, 630.00, 630.00, NULL, '2026-04-20', '2026-04-20', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2396, 'IMP-20260602000627-0TTZ5G', 19, 12800.00, 12800.00, 'Bohol Star Press Marketing Inc', '2026-04-21', '2026-04-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Qarrah'),
(2397, 'IMP-20260602000627-PDUDBP', 24, 1000.00, 1000.00, 'Outbound Interbank', '2026-04-21', '2026-04-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2398, 'IMP-20260602000627-DGFKPQ', 12, 10000.00, 10000.00, 'Delivery', '2026-04-21', '2026-04-21', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2399, 'IMP-20260602000627-GPNF6Z', 13, 8500.00, 8500.00, 'Mats', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2400, 'IMP-20260602000627-30J2AW', 12, 4209.00, 4209.00, 'Notary', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2401, 'IMP-20260602000627-FCCGCC', 12, 4609.00, 4609.00, 'Notary', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2402, 'IMP-20260602000627-CKB7U2', 12, 6509.00, 6509.00, 'Notary', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2403, 'IMP-20260602000627-OGKDJE', 12, 6609.00, 6609.00, 'Notary', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2404, 'IMP-20260602000627-4MRS40', 12, 700.00, 700.00, 'Notary', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bid Docs Fee and other Documents'),
(2405, 'IMP-20260602000627-IBSLCP', 12, 50000.00, 50000.00, NULL, '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2406, 'IMP-20260602000627-NWHIG5', 12, 10000.00, 10000.00, NULL, '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2407, 'IMP-20260602000627-S7VKAJ', 24, 500.00, 500.00, NULL, '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2408, 'IMP-20260602000627-EULW8T', 24, 300.00, 300.00, 'STELLA C.', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2409, 'IMP-20260602000627-2XR8K5', 24, 2464.00, 2464.00, 'STELLA C.', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2410, 'IMP-20260602000627-RUAHRV', 24, 236.00, 236.00, 'STELLA C.', '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2411, 'IMP-20260602000627-KF2AJJ', 24, 500.00, 500.00, NULL, '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2412, 'IMP-20260602000627-2QUYNY', 24, 750.00, 750.00, NULL, '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2413, 'IMP-20260602000627-ZLD9K0', 24, 250.00, 250.00, NULL, '2026-04-22', '2026-04-22', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Parking'),
(2414, 'IMP-20260602000627-JCYJRA', 24, 2500.00, 2500.00, 'SEED bs', '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2415, 'IMP-20260602000627-GJGYCJ', 24, 10000.00, 10000.00, 'Seed Travel UAE', '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2416, 'IMP-20260602000627-3JNK08', 19, 5000.00, 5000.00, NULL, '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2417, 'IMP-20260602000627-UQKWLL', 19, 15000.00, 15000.00, NULL, '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2418, 'IMP-20260602000627-SSV5EB', 12, 5000.00, 5000.00, 'Ca Savings Jr', '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2419, 'IMP-20260602000627-VB8RSW', 12, 5000.00, 5000.00, 'Ca Savings MARIA M.', '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2420, 'IMP-20260602000627-FXINZJ', 24, 4000.00, 4000.00, 'R. Mendoza GCash', '2026-04-23', '2026-04-23', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2421, 'IMP-20260602000627-19WCFL', 24, 788.00, 788.00, 'Netflix', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2422, 'IMP-20260602000627-VWVNSR', 24, 350000.00, 350000.00, 'Metrobank', '2026-04-24', '2026-04-24', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2423, 'IMP-20260602000627-GIXWN1', 19, 7934.00, 7934.00, 'BATANGAS', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2424, 'IMP-20260602000627-KUIKT3', 19, 1442.00, 1442.00, 'BATANGAS', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2425, 'IMP-20260602000627-AX7LC9', 19, 800.00, 800.00, 'BATANGAS', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2426, 'IMP-20260602000627-LHOZGF', 19, 15000.00, 15000.00, NULL, '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2427, 'IMP-20260602000627-1D5Z46', 19, 5000.00, 5000.00, NULL, '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2428, 'IMP-20260602000627-PAGZYR', 13, 16000.00, 16000.00, 'Tandag Commission', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Commission'),
(2429, 'IMP-20260602000627-GNDUVZ', 24, 2900.00, 2900.00, 'Calley', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2430, 'IMP-20260602000627-4PHUL9', 24, 600.00, 600.00, 'Calley', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Accommodation'),
(2431, 'IMP-20260602000627-Y47CP9', 24, 720.00, 720.00, 'Nono', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2432, 'IMP-20260602000627-BXHLWW', 24, 280.00, 280.00, 'Nono', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Vehicle Maintenance'),
(2433, 'IMP-20260602000627-ZXEJS7', 24, 1482.00, 1482.00, NULL, '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2434, 'IMP-20260602000627-ENBLTP', 24, 1000.00, 1000.00, NULL, '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2435, 'IMP-20260602000627-FLOS5L', 24, 18.00, 18.00, NULL, '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bank Charges'),
(2436, 'IMP-20260602000627-8CNZQP', 19, 1660.00, 1660.00, 'Bidding CDO', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2437, 'IMP-20260602000627-UI7V26', 19, 1500.00, 1500.00, 'Bidding Bohol', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2438, 'IMP-20260602000627-ZUMTH6', 19, 1449.00, 1449.00, NULL, '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2439, 'IMP-20260602000627-IJIYQV', 19, 407.00, 407.00, 'Braille', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2440, 'IMP-20260602000627-FE9QRJ', 19, 3796.00, 3796.00, 'Shopee Trim', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2441, 'IMP-20260602000627-0GEYJW', 19, 900.00, 900.00, 'lalamove (palawan)', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2442, 'IMP-20260602000627-EPGEEY', 19, 1058.00, 1058.00, 'utilities', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2443, 'IMP-20260602000627-EVGSV1', 19, 7000.00, 7000.00, 'trcuk', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2444, 'IMP-20260602000627-Q72WUF', 19, 5000.00, 5000.00, 'Barrow 4-23-26', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2445, 'IMP-20260602000627-EPKWUY', 24, 1000.00, 1000.00, 'Outbound Interbank', '2026-04-24', '2026-04-24', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2446, 'IMP-20260602000627-8L7ITN', 24, 6000.00, 6000.00, 'Rent Batangas Office', '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2447, 'IMP-20260602000627-RTLTPP', 24, 5970.00, 5970.00, 'HI-Precision Diagnostic', '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2448, 'IMP-20260602000627-MDXFAG', 24, 30.00, 30.00, NULL, '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Bank Charges'),
(2449, 'IMP-20260602000627-Y1UN63', 24, 6000.00, 6000.00, 'Mac Gcash', '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2450, 'IMP-20260602000627-RIEG1H', 24, 10000.00, 10000.00, 'Kairos Opex', '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2451, 'IMP-20260602000627-AIKRN3', 19, 72369.00, 72369.00, NULL, '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2452, 'IMP-20260602000627-3DQVUM', 12, 9575.00, 9575.00, 'Salary CHRISTINE R.', '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2453, 'IMP-20260602000627-FXZ1RQ', 12, 20000.00, 20000.00, NULL, '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2454, 'IMP-20260602000627-0ACD9F', 19, 20000.00, 20000.00, NULL, '2026-04-25', '2026-04-25', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2455, 'IMP-20260602000627-HQW6LR', 24, 5000.00, 5000.00, 'Jesus Faith Christian Church', '2026-04-26', '2026-04-26', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Donation'),
(2456, 'IMP-20260602000627-6HJZ89', 24, 3000.00, 3000.00, 'Outbound Interbank', '2026-04-26', '2026-04-26', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2457, 'IMP-20260602000627-6GVKC2', 12, 10000.00, 10000.00, 'Allan Savings', '2026-04-26', '2026-04-26', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2458, 'IMP-20260602000627-WFTQ8Q', 24, 2000.00, 2000.00, NULL, '2026-04-26', '2026-04-26', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2459, 'IMP-20260602000627-CFZRMR', 24, 1500.00, 1500.00, 'Outbound Interbank', '2026-04-27', '2026-04-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2460, 'IMP-20260602000627-7UCLKM', 12, 3000.00, 3000.00, 'CA Savings aba', '2026-04-27', '2026-04-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2461, 'IMP-20260602000627-4KW6MC', 24, 1.00, 1.00, 'testing', '2026-04-27', '2026-04-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2462, 'IMP-20260602000627-GUYYM2', 13, 4130.00, 4130.00, NULL, '2026-04-27', '2026-04-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2463, 'IMP-20260602000627-SFPY8M', 13, 870.00, 870.00, NULL, '2026-04-27', '2026-04-27', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2464, 'IMP-20260602000627-P2RLMV', 24, 875.00, 875.00, 'QR Payment', '2026-04-28', '2026-04-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2465, 'IMP-20260602000627-70HZEW', 24, 1000.00, 1000.00, 'Outbound Interbank', '2026-04-28', '2026-04-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2466, 'IMP-20260602000627-ZQYVYN', 24, 7000.00, 7000.00, 'Salary Pt Hr', '2026-04-28', '2026-04-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2467, 'IMP-20260602000627-BPZZST', 13, 4600.00, 4600.00, 'commission Cebu', '2026-04-28', '2026-04-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2468, 'IMP-20260602000627-LF7S0O', 13, 15400.00, 15400.00, 'commission Cebu', '2026-04-28', '2026-04-28', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2469, 'IMP-20260602000627-GII39W', 24, 560.00, 560.00, NULL, '2026-04-29', '2026-04-29', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Transportation'),
(2470, 'IMP-20260602000627-BJK8VL', 24, 3363.00, 3363.00, NULL, '2026-04-29', '2026-04-29', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2471, 'IMP-20260602000627-CNCSEK', 24, 577.00, 577.00, NULL, '2026-04-29', '2026-04-29', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2472, 'IMP-20260602000627-AAVCVD', 24, 200.00, 200.00, NULL, '2026-04-29', '2026-04-29', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Parking'),
(2473, 'IMP-20260602000627-XCBNF6', 24, 300.00, 300.00, NULL, '2026-04-29', '2026-04-29', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Representation'),
(2474, 'IMP-20260602000627-TEI4CI', 19, 4500.00, 4500.00, 'opex', '2026-04-29', '2026-04-29', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL),
(2475, 'IMP-20260602000627-DUPCYD', 24, 2350.00, 2350.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Office Expense'),
(2476, 'IMP-20260602000627-C4F7LN', 24, 150.00, 150.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Fuel / Gas'),
(2477, 'IMP-20260602000627-8OIOHK', 24, 500.00, 500.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary'),
(2478, 'IMP-20260602000627-8JOLSB', 24, 200000.00, 200000.00, 'Metrobank', '2026-04-30', '2026-04-30', 'approved', 'Manual Credit Entry - Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Credit'),
(2479, 'IMP-20260602000627-GMJO2J', 19, 5700.00, 5700.00, 'opex kairos', '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Kairos'),
(2480, 'IMP-20260602000627-6UEEWS', 19, 3017.00, 3017.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2481, 'IMP-20260602000627-5H7FC6', 19, 500.00, 500.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2482, 'IMP-20260602000627-XIYJ5D', 19, 5402.00, 5402.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Freight'),
(2483, 'IMP-20260602000627-EYVIBN', 12, 10000.00, 10000.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2484, 'IMP-20260602000627-E8HUR7', 15, 2000.00, 2000.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2485, 'IMP-20260602000627-QJQDPX', 15, 1000.00, 1000.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Tollgate'),
(2486, 'IMP-20260602000627-Q7YCPS', 19, 3201.00, 3201.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'MAC Advances'),
(2487, 'IMP-20260602000627-Z70N3V', 19, 20000.00, 20000.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Borrow'),
(2488, 'IMP-20260602000627-6MWG6K', 19, 4620.00, 4620.00, NULL, '2026-04-30', '2026-04-30', 'approved', 'Imported from Excel', 24, 'STELLA C.', 'STELLA C.', '2026-06-01 16:06:27', '2026-06-01 16:06:27', '2026-06-01 16:06:27', NULL, '2026-06-01 16:06:27', '2026-06-01 16:06:27', 'Salary');

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
(30, 'Returned', '2026-05-25 00:41:53', '2026-05-25 00:41:53'),
(31, 'Adjustment', '2026-05-31 11:06:24', '2026-05-31 11:06:24'),
(32, 'Credit', '2026-06-01 15:56:19', '2026-06-01 15:56:19'),
(33, 'MAC Advances', '2026-06-01 15:56:19', '2026-06-01 15:56:19'),
(34, 'Telecom', '2026-06-01 15:56:19', '2026-06-01 15:56:19'),
(35, 'seed', '2026-06-01 15:56:19', '2026-06-01 15:56:19');

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
(2, 2, 'May 2026', 2239655.00, 'submitted', NULL, NULL, '2026-05-31 03:18:48', NULL, '2026-05-24 06:34:12', '2026-05-31 03:18:48'),
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
(19, NULL, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-27 23:46:08', '2026-05-27 23:46:08'),
(20, 10, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-28 01:09:30', '2026-05-28 01:09:30'),
(21, 21, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-28 05:49:32', '2026-05-28 05:49:32'),
(22, 11, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-28 05:50:29', '2026-05-28 05:50:29'),
(23, 22, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-28 05:51:33', '2026-05-28 05:51:33'),
(24, 17, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-28 06:20:04', '2026-05-28 06:20:04'),
(25, 13, 'February 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-28 21:27:55', '2026-05-28 21:27:55'),
(26, 2, 'May 2026', 2236155.00, 'rejected', 'sdasd', NULL, '2026-05-31 15:57:22', NULL, '2026-05-31 09:55:41', '2026-05-31 16:03:46'),
(27, 2, 'June 2026', 0.00, 'rejected', 'wala lang', NULL, '2026-05-31 16:03:01', NULL, '2026-05-31 10:23:32', '2026-05-31 16:03:23'),
(36, 2, 'January 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-31 12:34:06', '2026-05-31 12:34:06'),
(37, 2, 'May 2026', 0.00, 'pending', NULL, NULL, NULL, NULL, '2026-05-31 15:57:58', '2026-05-31 15:57:58'),
(38, 2, 'June 2026', 0.00, 'approved', NULL, NULL, '2026-05-31 16:34:21', '2026-05-31 16:34:34', '2026-05-31 16:06:10', '2026-05-31 16:34:34'),
(39, 2, 'June 2026', 0.00, 'submitted', NULL, NULL, '2026-05-31 16:36:17', NULL, '2026-05-31 16:35:22', '2026-05-31 16:36:17');

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
(44, 7, 737, '2026-01-01', NULL, 9, 'debit', NULL, NULL, 12000.00, NULL, '2026-05-31 10:04:56', '2026-05-31 10:04:56'),
(45, 7, 737, '2026-01-01', NULL, 22, 'debit', NULL, NULL, 3000.00, NULL, '2026-05-31 10:05:22', '2026-05-31 10:05:22'),
(46, 10, 740, '2026-01-04', NULL, 10, 'debit', NULL, NULL, 915.00, NULL, '2026-05-31 10:07:01', '2026-05-31 10:07:01'),
(47, 10, 740, '2026-01-04', NULL, 1, 'debit', NULL, NULL, 1100.00, NULL, '2026-05-31 10:08:01', '2026-05-31 10:08:01'),
(48, 10, 740, '2026-01-04', NULL, 15, 'debit', NULL, NULL, 485.00, NULL, '2026-05-31 10:08:33', '2026-05-31 10:08:33'),
(49, 11, 741, '2026-01-04', NULL, 8, 'debit', NULL, NULL, 680.00, NULL, '2026-05-31 10:09:14', '2026-05-31 10:09:14'),
(50, 11, 741, '2026-01-04', NULL, 9, 'debit', NULL, NULL, 3070.00, NULL, '2026-05-31 10:09:41', '2026-05-31 10:09:41'),
(51, 11, 741, '2026-01-04', NULL, 10, 'debit', NULL, NULL, 6050.00, NULL, '2026-05-31 10:10:08', '2026-05-31 10:10:08'),
(52, 11, 741, '2026-01-04', NULL, 16, 'debit', NULL, NULL, 50.00, NULL, '2026-05-31 10:10:27', '2026-05-31 10:10:27'),
(53, 11, 741, '2026-01-04', NULL, 22, 'debit', NULL, NULL, 150.00, NULL, '2026-05-31 10:10:45', '2026-05-31 10:10:45'),
(54, 11, 743, '2026-01-04', NULL, 9, 'debit', NULL, NULL, 4188.00, NULL, '2026-05-31 10:11:54', '2026-05-31 10:11:54'),
(55, 11, 743, '2026-01-04', NULL, 10, 'debit', NULL, NULL, 3812.00, NULL, '2026-05-31 10:12:27', '2026-05-31 10:12:27'),
(57, 26, NULL, '2026-05-07', NULL, 2, 'debit', 'sdasd', 'asd', 1000.00, NULL, '2026-05-31 10:26:37', '2026-05-31 10:26:37'),
(70, 10, 758, '2026-01-06', NULL, 1, 'debit', NULL, NULL, 840.00, NULL, '2026-05-31 12:05:52', '2026-05-31 12:05:52'),
(71, 10, 758, '2026-01-06', NULL, 15, 'debit', NULL, NULL, 300.00, NULL, '2026-05-31 12:06:13', '2026-05-31 12:06:13'),
(72, 10, 758, '2026-01-06', NULL, 22, 'debit', NULL, NULL, 1021.00, NULL, '2026-05-31 12:06:38', '2026-05-31 12:06:38'),
(74, 11, 757, '2026-01-06', NULL, 9, 'debit', NULL, NULL, 14074.00, NULL, '2026-05-31 12:32:19', '2026-05-31 12:32:19'),
(75, 11, 757, '2026-01-06', NULL, 10, 'debit', NULL, NULL, 688.00, NULL, '2026-05-31 12:32:35', '2026-05-31 12:32:35'),
(76, 15, 756, '2026-01-06', NULL, 18, 'debit', NULL, NULL, 1098.00, NULL, '2026-05-31 12:33:09', '2026-05-31 12:33:09'),
(77, 15, 756, '2026-01-06', NULL, 21, 'debit', NULL, NULL, 1519.00, NULL, '2026-05-31 12:33:27', '2026-05-31 12:33:27'),
(80, 16, 753, '2026-01-06', NULL, 1, 'debit', NULL, NULL, 899.00, NULL, '2026-05-31 12:34:56', '2026-05-31 12:34:56'),
(81, 16, 753, '2026-01-06', NULL, 22, 'debit', NULL, NULL, 2101.00, NULL, '2026-05-31 12:35:11', '2026-05-31 12:35:11'),
(82, 14, 751, '2026-01-05', NULL, 1, 'debit', NULL, NULL, 6409.00, NULL, '2026-05-31 12:35:48', '2026-05-31 12:35:48'),
(83, 14, 751, '2026-01-05', NULL, 20, 'debit', NULL, NULL, 8110.00, NULL, '2026-05-31 12:36:08', '2026-05-31 12:36:08'),
(84, 15, 747, '2026-01-05', NULL, 1, 'debit', NULL, NULL, 800.00, NULL, '2026-05-31 12:37:21', '2026-05-31 12:37:21'),
(85, 15, 747, '2026-01-05', NULL, 22, 'debit', NULL, NULL, 1200.00, NULL, '2026-05-31 12:37:35', '2026-05-31 12:37:35'),
(86, 17, 759, '2026-01-06', NULL, 15, 'debit', NULL, NULL, 570.00, NULL, '2026-05-31 12:40:00', '2026-05-31 12:40:00'),
(87, 17, 759, '2026-01-06', NULL, 22, 'debit', NULL, NULL, 3500.00, NULL, '2026-05-31 12:40:16', '2026-05-31 12:40:16'),
(88, 12, 765, '2026-01-07', NULL, 9, 'debit', NULL, NULL, 267.28, NULL, '2026-05-31 12:42:12', '2026-05-31 12:42:12'),
(89, 12, 765, '2026-01-07', NULL, 10, 'debit', NULL, NULL, 6539.00, NULL, '2026-05-31 12:42:25', '2026-05-31 12:42:25'),
(90, 12, 765, '2026-01-07', NULL, 13, 'debit', NULL, NULL, 1300.00, NULL, '2026-05-31 12:42:38', '2026-05-31 12:42:38'),
(91, 12, 765, '2026-01-07', NULL, 17, 'debit', NULL, NULL, 300.00, NULL, '2026-05-31 12:42:56', '2026-05-31 12:42:56'),
(92, 12, 765, '2026-01-07', NULL, 22, 'debit', NULL, NULL, 5513.00, NULL, '2026-05-31 12:43:11', '2026-05-31 12:43:11'),
(93, 12, 765, '2026-01-07', NULL, 23, 'debit', NULL, NULL, 4120.72, NULL, '2026-05-31 12:43:33', '2026-05-31 12:43:33'),
(94, 12, 765, '2026-01-07', NULL, 24, 'debit', NULL, NULL, 1960.00, NULL, '2026-05-31 12:43:53', '2026-05-31 12:43:53'),
(95, 12, 768, '2026-01-08', NULL, 8, 'debit', NULL, NULL, 512.72, NULL, '2026-05-31 12:46:00', '2026-05-31 12:46:00'),
(96, 12, 768, '2026-01-08', NULL, 10, 'debit', NULL, NULL, 1009.00, NULL, '2026-05-31 12:46:26', '2026-05-31 12:46:26'),
(97, 12, 768, '2026-01-08', NULL, 20, 'debit', NULL, NULL, 2000.00, NULL, '2026-05-31 12:46:40', '2026-05-31 12:46:40'),
(98, 12, 768, '2026-01-08', NULL, 22, 'debit', NULL, NULL, 1545.00, NULL, '2026-05-31 12:46:57', '2026-05-31 12:46:57'),
(99, 17, 769, '2026-01-08', NULL, 1, 'debit', NULL, NULL, 450.00, NULL, '2026-05-31 12:47:21', '2026-05-31 12:47:21'),
(100, 17, 769, '2026-01-08', NULL, 10, 'debit', NULL, NULL, 1000.00, NULL, '2026-05-31 12:47:34', '2026-05-31 12:47:34'),
(101, 17, 769, '2026-01-08', NULL, 15, 'debit', NULL, NULL, 2471.00, NULL, '2026-05-31 12:47:49', '2026-05-31 12:47:49'),
(102, 17, 769, '2026-01-08', NULL, 16, 'debit', NULL, NULL, 360.00, NULL, '2026-05-31 12:48:03', '2026-05-31 12:48:03'),
(103, 17, 769, '2026-01-08', NULL, 22, 'debit', NULL, NULL, 719.00, NULL, '2026-05-31 12:48:18', '2026-05-31 12:48:18'),
(104, 7, 776, '2026-01-09', NULL, 1, 'debit', NULL, NULL, 6000.00, NULL, '2026-05-31 14:41:51', '2026-05-31 14:41:51'),
(105, 7, 776, '2026-01-09', NULL, 15, 'debit', NULL, NULL, 110.00, NULL, '2026-05-31 14:42:22', '2026-05-31 14:42:22'),
(106, 7, 776, '2026-01-09', NULL, 16, 'debit', NULL, NULL, 240.00, NULL, '2026-05-31 14:42:34', '2026-05-31 14:42:34'),
(107, 7, 776, '2026-01-09', NULL, 18, 'debit', NULL, NULL, 667.00, NULL, '2026-05-31 14:42:46', '2026-05-31 14:42:46'),
(108, 7, 776, '2026-01-09', NULL, 22, 'debit', NULL, NULL, 983.00, NULL, '2026-05-31 14:42:59', '2026-05-31 14:42:59'),
(109, 7, 776, '2026-01-09', NULL, 23, 'debit', NULL, NULL, 2000.00, NULL, '2026-05-31 14:43:07', '2026-05-31 14:43:07'),
(110, 17, 782, '2026-01-10', NULL, 1, 'debit', NULL, NULL, 5080.00, NULL, '2026-05-31 14:45:05', '2026-05-31 14:45:05'),
(111, 12, 784, '2026-01-12', NULL, 9, 'debit', NULL, NULL, 4043.00, NULL, '2026-05-31 14:45:36', '2026-05-31 14:45:36'),
(112, 12, 784, '2026-01-12', NULL, 12, 'debit', NULL, NULL, 209.00, NULL, '2026-05-31 14:45:48', '2026-05-31 14:45:48'),
(113, 12, 784, '2026-01-12', NULL, 15, 'debit', NULL, NULL, 2280.00, NULL, '2026-05-31 14:46:01', '2026-05-31 14:46:01'),
(114, 12, 784, '2026-01-12', NULL, 21, 'debit', NULL, NULL, 2500.00, NULL, '2026-05-31 14:46:22', '2026-05-31 14:46:22'),
(115, 12, 784, '2026-01-12', NULL, 22, 'debit', NULL, NULL, 5968.00, NULL, '2026-05-31 14:46:38', '2026-05-31 14:46:38'),
(116, 7, 786, '2026-01-13', NULL, 9, 'debit', NULL, NULL, 7964.00, NULL, '2026-05-31 14:47:12', '2026-05-31 14:47:12'),
(117, 7, 786, '2026-01-13', NULL, 15, 'debit', NULL, NULL, 1816.00, NULL, '2026-05-31 14:47:23', '2026-05-31 14:47:23'),
(118, 7, 786, '2026-01-13', NULL, 22, 'debit', NULL, NULL, 220.00, NULL, '2026-05-31 14:47:33', '2026-05-31 14:47:33'),
(119, 7, 786, '2026-01-13', NULL, 1, 'debit', NULL, NULL, 1562.00, NULL, '2026-05-31 14:47:50', '2026-05-31 14:47:50'),
(120, 7, 786, '2026-01-13', NULL, 9, 'debit', NULL, NULL, 7186.00, NULL, '2026-05-31 14:48:03', '2026-05-31 14:48:03'),
(121, 7, 786, '2026-01-13', NULL, 22, 'debit', NULL, NULL, 1252.00, NULL, '2026-05-31 14:48:17', '2026-05-31 14:48:17'),
(122, 12, 788, '2026-01-14', NULL, 9, 'debit', NULL, NULL, 4043.00, NULL, '2026-05-31 14:48:38', '2026-05-31 14:48:38'),
(123, 12, 788, '2026-01-14', NULL, 12, 'debit', NULL, NULL, 209.00, NULL, '2026-05-31 14:48:47', '2026-05-31 14:48:47'),
(124, 12, 788, '2026-01-14', NULL, 15, 'debit', NULL, NULL, 2280.00, NULL, '2026-05-31 14:48:56', '2026-05-31 14:48:56'),
(125, 12, 788, '2026-01-14', NULL, 21, 'debit', NULL, NULL, 2500.00, NULL, '2026-05-31 14:49:05', '2026-05-31 14:49:05'),
(126, 12, 788, '2026-01-14', NULL, 22, 'debit', NULL, NULL, 5968.00, NULL, '2026-05-31 14:49:17', '2026-05-31 14:49:17'),
(127, 7, 789, '2026-01-14', NULL, 1, 'debit', NULL, NULL, 1562.00, NULL, '2026-05-31 14:49:59', '2026-05-31 14:49:59'),
(128, 7, 789, '2026-01-14', NULL, 9, 'debit', NULL, NULL, 7186.00, NULL, '2026-05-31 14:50:08', '2026-05-31 14:50:08'),
(129, 7, 789, '2026-01-14', NULL, 22, 'debit', NULL, NULL, 1252.00, NULL, '2026-05-31 14:50:23', '2026-05-31 14:50:23'),
(130, 17, 792, '2026-01-15', NULL, 10, 'debit', NULL, NULL, 490.00, NULL, '2026-05-31 14:51:10', '2026-05-31 14:51:10'),
(131, 17, 792, '2026-01-15', NULL, 15, 'debit', NULL, NULL, 5150.00, NULL, '2026-05-31 14:51:28', '2026-05-31 14:51:28'),
(132, 17, 792, '2026-01-15', NULL, 16, 'debit', NULL, NULL, 60.00, NULL, '2026-05-31 14:51:48', '2026-05-31 14:51:48'),
(133, 17, 792, '2026-01-15', NULL, 22, 'debit', NULL, NULL, 4309.00, NULL, '2026-05-31 14:52:02', '2026-05-31 14:52:02'),
(134, 7, 803, '2026-01-20', NULL, 1, 'debit', NULL, NULL, 2018.00, NULL, '2026-05-31 14:56:45', '2026-05-31 14:56:45'),
(135, 7, 803, '2026-01-20', NULL, 15, 'debit', NULL, NULL, 114.00, NULL, '2026-05-31 14:56:54', '2026-05-31 14:56:54'),
(136, 7, 803, '2026-01-20', NULL, 18, 'debit', NULL, NULL, 1170.00, NULL, '2026-05-31 14:57:07', '2026-05-31 14:57:07'),
(137, 7, 803, '2026-01-20', NULL, 22, 'debit', NULL, NULL, 1698.00, NULL, '2026-05-31 14:57:23', '2026-05-31 14:57:23'),
(138, 8, 867, '2026-01-21', NULL, 1, 'debit', NULL, NULL, 680.00, NULL, '2026-05-31 14:58:02', '2026-05-31 14:58:02'),
(139, 8, 867, '2026-01-21', NULL, 2, 'debit', NULL, NULL, 33.00, NULL, '2026-05-31 14:58:08', '2026-05-31 14:58:08'),
(140, 8, 867, '2026-01-21', NULL, 5, 'debit', NULL, NULL, 3000.00, NULL, '2026-05-31 14:58:16', '2026-05-31 14:58:16'),
(141, 8, 867, '2026-01-21', NULL, 8, 'debit', NULL, NULL, 150.00, NULL, '2026-05-31 14:58:23', '2026-05-31 14:58:23'),
(142, 8, 867, '2026-01-21', NULL, 10, 'debit', NULL, NULL, 270.00, NULL, '2026-05-31 14:58:31', '2026-05-31 14:58:31'),
(143, 8, 867, '2026-01-21', NULL, 15, 'debit', NULL, NULL, 360.00, NULL, '2026-05-31 14:58:40', '2026-05-31 14:58:40'),
(144, 8, 867, '2026-01-21', NULL, 16, 'debit', NULL, NULL, 60.00, NULL, '2026-05-31 14:58:51', '2026-05-31 14:58:51'),
(145, 8, 867, '2026-01-21', NULL, 22, 'debit', NULL, NULL, 5447.00, NULL, '2026-05-31 14:58:59', '2026-05-31 14:58:59'),
(146, 9, 869, '2026-01-21', NULL, 2, 'debit', NULL, NULL, 401.00, NULL, '2026-05-31 14:59:26', '2026-05-31 14:59:26'),
(147, 9, 869, '2026-01-21', NULL, 15, 'debit', NULL, NULL, 9706.00, NULL, '2026-05-31 14:59:36', '2026-05-31 14:59:36'),
(148, 9, 869, '2026-01-21', NULL, 22, 'debit', NULL, NULL, 3337.00, NULL, '2026-05-31 14:59:44', '2026-05-31 14:59:44'),
(149, 12, 872, '2026-01-21', NULL, 15, 'debit', NULL, NULL, 7185.00, NULL, '2026-05-31 15:00:03', '2026-05-31 15:00:03'),
(150, 12, 872, '2026-01-21', NULL, 21, 'debit', NULL, NULL, 3000.00, NULL, '2026-05-31 15:00:13', '2026-05-31 15:00:13'),
(151, 12, 879, '2026-01-22', NULL, 12, 'debit', NULL, NULL, 2509.00, NULL, '2026-05-31 15:01:26', '2026-05-31 15:01:26'),
(152, 12, 879, '2026-01-22', NULL, 16, 'debit', NULL, NULL, 509.00, NULL, '2026-05-31 15:01:34', '2026-05-31 15:01:34'),
(153, 12, 879, '2026-01-22', NULL, 21, 'debit', NULL, NULL, 3930.00, NULL, '2026-05-31 15:01:46', '2026-05-31 15:01:46'),
(154, 12, 879, '2026-01-22', NULL, 22, 'debit', NULL, NULL, 3052.00, NULL, '2026-05-31 15:01:57', '2026-05-31 15:01:57'),
(155, 12, 892, '2026-01-26', NULL, 15, 'debit', NULL, NULL, 6900.00, NULL, '2026-05-31 15:03:55', '2026-05-31 15:03:55'),
(156, 12, 892, '2026-01-26', NULL, 21, 'debit', NULL, NULL, 1872.00, NULL, '2026-05-31 15:04:04', '2026-05-31 15:04:04'),
(157, 12, 892, '2026-01-26', NULL, 22, 'debit', NULL, NULL, 1228.00, NULL, '2026-05-31 15:04:18', '2026-05-31 15:04:18'),
(158, 12, 904, '2026-01-28', NULL, 8, 'debit', NULL, NULL, 150.00, NULL, '2026-05-31 15:12:15', '2026-05-31 15:12:15'),
(159, 12, 904, '2026-01-28', NULL, 12, 'debit', NULL, NULL, 1509.00, NULL, '2026-05-31 15:12:26', '2026-05-31 15:12:26'),
(160, 12, 904, '2026-01-28', NULL, 15, 'debit', NULL, NULL, 1376.00, NULL, '2026-05-31 15:12:38', '2026-05-31 15:12:38'),
(161, 12, 904, '2026-01-28', NULL, 21, 'debit', NULL, NULL, 588.00, NULL, '2026-05-31 15:12:53', '2026-05-31 15:12:53'),
(162, 12, 904, '2026-01-28', NULL, 22, 'debit', NULL, NULL, 1077.00, NULL, '2026-05-31 15:13:05', '2026-05-31 15:13:05'),
(163, 14, 914, '2026-01-28', NULL, 4, 'debit', NULL, NULL, 18378.00, NULL, '2026-05-31 15:16:14', '2026-05-31 15:16:14'),
(164, 14, 914, '2026-01-28', NULL, 22, 'debit', NULL, NULL, 1830.00, NULL, '2026-05-31 15:16:26', '2026-05-31 15:16:26'),
(165, 12, 926, '2026-01-31', NULL, 9, 'debit', NULL, NULL, 2700.00, NULL, '2026-05-31 15:17:51', '2026-05-31 15:17:51'),
(166, 12, 926, '2026-01-31', NULL, 15, 'debit', NULL, NULL, 588.72, NULL, '2026-05-31 15:18:02', '2026-05-31 15:18:02'),
(167, 12, 926, '2026-01-31', NULL, 21, 'debit', NULL, NULL, 1417.28, NULL, '2026-05-31 15:18:15', '2026-05-31 15:18:15'),
(170, 27, NULL, '2026-06-01', NULL, 12, 'debit', 'adasd', 'asdas', 500.00, NULL, '2026-05-31 16:02:58', '2026-05-31 16:02:58'),
(171, 38, NULL, '2026-06-01', NULL, 3, 'debit', 'sadas', 'asddsad', 500.00, 'liquidation-receipts/6fQkt2lWZiuless5qmurJhr1GpD54JDdBiuh3cEI.jpg', '2026-05-31 16:32:50', '2026-05-31 16:32:50'),
(172, 39, NULL, '2026-06-01', NULL, 2, 'debit', 'asd', NULL, 123.00, 'liquidation-receipts/VTf87LyrH9eTIOVf5MqMKq99a0pBksoo4y0eWFVj.jpg', '2026-05-31 16:36:15', '2026-05-31 16:36:15');

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
(113, '2026_05_28_080000_make_liquidations_user_nullable', 10),
(114, '2026_05_29_000001_add_category_to_cash_advance_requests_table', 11),
(115, '2026_05_29_000002_allow_nullable_cash_advance_request_purpose', 12),
(116, '2026_05_31_000001_backfill_cash_advance_request_id_on_liquidation_expenses', 13),
(117, '2026_06_01_000001_add_adjustment_category', 14),
(118, '2026_06_01_000002_create_accounting_wallet_ledger_tables', 15),
(119, '2026_06_01_000003_convert_wallet_ledger_to_budget_variance', 16),
(120, '2026_06_01_000004_normalize_budget_ledger_account_labels', 17);

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
('MW6mgJD3RKYEkMJ4laWHpwFkvX3UDHR7994bxjDy', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSnRtWVV1SDZwRWF2dUNzUllLZ2N4Z0MxQVI1a2FSVW1RMkh2NW1yZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50aW5nL2xpcXVpZGF0ZS1leHBlbnNlcz9tb250aD0wMyZ5ZWFyPTIwMjYiO3M6NToicm91dGUiO3M6Mjk6ImFjY291bnRpbmcubGlxdWlkYXRlLWV4cGVuc2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjQ7fQ==', 1780358895),
('pt8T8XqBAOxnSmWTBD20UPXBT8lyxkPTet4KudCn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.122.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFVNY0Zsd2tnUDh5aDk5T2ZNUDdhdUtDckVQMWtnZkFkZHE0bXBDSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1780350361);

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
(9, '202600009', 'ALEX A.', 'AA202600009@dmc.com', '$2y$12$JDrofk2B1jpSFr.rNoiuYO/yx5r8c2HyCQcDzWJYdYXT3gK.YG.Zm', 2, '2026-05-24 15:33:20', '2026-06-01 14:55:41'),
(10, '202600010', 'ALEXANDER C.', 'AC202600010@dmc.com', '$2y$12$SgZFFMtNs31iRfU5uHXr8e1NjIw.cVJ5c0h.TYWexPGl0ce9m5uVe', 2, '2026-05-24 15:33:55', '2026-05-24 15:33:55'),
(11, '202600011', 'ANGEL V.', 'AV202600011@dmc.com', '$2y$12$kUHnWjStPR0M97L6iFaC7u.SqQabG43kng9Q71NSgxFJgkImZjbZa', 2, '2026-05-24 15:39:04', '2026-05-24 15:39:04'),
(12, '202600012', 'CHRISTINE R.', 'CR202600012@dmc.com', '$2y$12$OCnE1qIcsl.nKaKcSgKpa.5zFzBCynbwKuhZMTtLaSN08ey7SjS7y', 2, '2026-05-24 15:40:12', '2026-05-24 15:40:12'),
(13, '202600013', 'ESPERANZA R.', 'ER202600013@dmc.com', '$2y$12$h5gxKjWp6ry3NB7HZKq6Su7SSnkJRhWlJywzQGtMt1BtTh7wVJ/8u', 2, '2026-05-24 15:40:26', '2026-06-01 15:07:29'),
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
(24, '202600024', 'STELLA C.', 'SC202600024@dmc.com', '$2y$12$VLhLKny6GGAye8n5DPfqHOHVNJizlDi1LVD7WKxXDaPM7dpT83xxK', 3, '2026-05-24 16:13:15', '2026-05-24 16:13:15'),
(25, '202600025', 'LENNETH R.', 'LR202600025@dmc.com', '$2y$12$VLhLKny6GGAye8n5DPfqHOHVNJizlDi1LVD7WKxXDaPM7dpT83xxK', 2, '2026-05-24 16:13:15', '2026-05-24 16:13:15'),
(26, '202600026', 'KIMBERLY G.', 'KG202600026@dmc.com', '$2y$12$Ed0WTaAb59widKba3qvLQeoCGkQx9yNlyf9AyT4IWpstIHM9BT4LK', 2, '2026-06-01 14:37:09', '2026-06-01 14:37:09'),
(28, '202600028', 'JHON R.', 'JR202600028@dmc.com', '$2y$12$UdAhdWlWy30QGApD8CD9GeIVTKTBMxvI2eAusru8B7MFH2g2SWToG', 2, '2026-06-01 14:38:33', '2026-06-01 14:38:33'),
(29, '202600029', 'CINDY G.', 'CG202600029@dmc.com', '$2y$12$dulax25hAIKyfAAebZnScuRv/DQeqGNChyprIoVSqwTz.HnEk2XGa', 2, '2026-06-01 15:08:01', '2026-06-01 15:08:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_budget_accounts`
--
ALTER TABLE `accounting_budget_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_wallet_accounts_type_reference_unique` (`account_type`,`reference`);

--
-- Indexes for table `accounting_budget_journal_entries`
--
ALTER TABLE `accounting_budget_journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_journal_entries_reference_no_unique` (`reference_no`),
  ADD KEY `accounting_journal_entries_created_by_foreign` (`created_by`),
  ADD KEY `accounting_journal_entries_transaction_type_created_at_index` (`transaction_type`,`created_at`),
  ADD KEY `accounting_journal_entries_cash_advance_request_id_index` (`cash_advance_request_id`),
  ADD KEY `accounting_journal_entries_liquidation_expense_id_index` (`liquidation_expense_id`);

--
-- Indexes for table `accounting_budget_journal_lines`
--
ALTER TABLE `accounting_budget_journal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_journal_lines_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `accounting_journal_lines_account_id_journal_entry_id_index` (`account_id`,`journal_entry_id`);

--
-- Indexes for table `accounting_legacy_wallet_funding_sources`
--
ALTER TABLE `accounting_legacy_wallet_funding_sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_wallet_funding_destination_source_unique` (`destination_account_id`,`source_account_id`),
  ADD KEY `accounting_wallet_funding_sources_source_account_id_foreign` (`source_account_id`),
  ADD KEY `accounting_wallet_funding_sources_enabled_priority_index` (`enabled`,`priority`);

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
-- AUTO_INCREMENT for table `accounting_budget_accounts`
--
ALTER TABLE `accounting_budget_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844;

--
-- AUTO_INCREMENT for table `accounting_budget_journal_entries`
--
ALTER TABLE `accounting_budget_journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `accounting_budget_journal_lines`
--
ALTER TABLE `accounting_budget_journal_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `accounting_legacy_wallet_funding_sources`
--
ALTER TABLE `accounting_legacy_wallet_funding_sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cash_advance_monthly_balances`
--
ALTER TABLE `cash_advance_monthly_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cash_advance_requests`
--
ALTER TABLE `cash_advance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2489;

--
-- AUTO_INCREMENT for table `cash_advance_request_attachments`
--
ALTER TABLE `cash_advance_request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_advance_request_audits`
--
ALTER TABLE `cash_advance_request_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `liquidation_expenses`
--
ALTER TABLE `liquidation_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting_budget_journal_entries`
--
ALTER TABLE `accounting_budget_journal_entries`
  ADD CONSTRAINT `accounting_journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `accounting_budget_journal_lines`
--
ALTER TABLE `accounting_budget_journal_lines`
  ADD CONSTRAINT `accounting_journal_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounting_budget_accounts` (`id`),
  ADD CONSTRAINT `accounting_journal_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `accounting_budget_journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `accounting_legacy_wallet_funding_sources`
--
ALTER TABLE `accounting_legacy_wallet_funding_sources`
  ADD CONSTRAINT `accounting_wallet_funding_sources_destination_account_id_foreign` FOREIGN KEY (`destination_account_id`) REFERENCES `accounting_budget_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounting_wallet_funding_sources_source_account_id_foreign` FOREIGN KEY (`source_account_id`) REFERENCES `accounting_budget_accounts` (`id`) ON DELETE CASCADE;

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
