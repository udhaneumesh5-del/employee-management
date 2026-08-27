-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2026 at 08:29 AM
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
-- Database: `employee_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `performed_by` varchar(255) NOT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `employee_name`, `action`, `performed_by`, `performed_at`, `created_at`, `updated_at`) VALUES
(1, 'Rohan Patil', 'Updated', 'Admin User', '2026-07-23 05:53:44', '2026-07-23 05:53:44', '2026-07-23 05:53:44'),
(2, 'guddu pawar', 'Created', 'HR User', '2026-07-24 05:20:55', '2026-07-24 05:20:55', '2026-07-24 05:20:55'),
(3, 'Rahul Sharma', 'Updated', 'Admin User', '2026-08-12 02:29:08', '2026-08-12 02:29:08', '2026-08-12 02:29:08'),
(4, 'Rohan Patil', 'Updated', 'Admin User', '2026-08-12 02:31:02', '2026-08-12 02:31:02', '2026-08-12 02:31:02'),
(5, 'Umesh Udhane', 'Updated', 'Admin User', '2026-08-12 05:55:52', '2026-08-12 05:55:52', '2026-08-12 05:55:52'),
(6, 'Umesh Udhane', 'Applied for leave (Emergency Leave)', 'Admin User', '2026-08-12 05:56:51', '2026-08-12 05:56:51', '2026-08-12 05:56:51'),
(7, 'Umesh Udhane', 'HR approved leave (Emergency Leave)', 'HR User', '2026-08-12 06:07:32', '2026-08-12 06:07:32', '2026-08-12 06:07:32'),
(8, 'Umesh Udhane', 'Applied for leave (Earned Leave)', 'Admin User', '2026-08-12 06:21:59', '2026-08-12 06:21:59', '2026-08-12 06:21:59'),
(9, 'Rahul Sharma', 'Updated', 'Manager User', '2026-08-12 06:25:24', '2026-08-12 06:25:24', '2026-08-12 06:25:24'),
(10, 'Rahul Sharma', 'Updated', 'Manager User', '2026-08-12 06:28:49', '2026-08-12 06:28:49', '2026-08-12 06:28:49'),
(11, 'Rahul Sharma', 'Applied for leave (Emergency Leave)', 'Manager User', '2026-08-12 06:29:26', '2026-08-12 06:29:26', '2026-08-12 06:29:26'),
(12, 'Rahul Sharma', 'HR rejected leave', 'HR User', '2026-08-12 06:31:21', '2026-08-12 06:31:21', '2026-08-12 06:31:21'),
(13, 'Kaustubh Patil', 'Applied for leave (Sick Leave (SL))', 'Kaustubh Patil', '2026-08-13 06:47:12', '2026-08-13 06:47:12', '2026-08-13 06:47:12'),
(14, 'Kaustubh Patil', 'Applied for leave (Emergency Leave)', 'Kaustubh Patil', '2026-08-14 02:59:57', '2026-08-14 02:59:57', '2026-08-14 02:59:57'),
(15, 'Tushar Jamdhade', 'Applied for leave (Earned Leave)', 'Tushar Jamdhade', '2026-08-14 03:28:02', '2026-08-14 03:28:02', '2026-08-14 03:28:02'),
(16, 'Kaustubh Patil', 'HR rejected leave', 'HR User', '2026-08-14 03:32:39', '2026-08-14 03:32:39', '2026-08-14 03:32:39'),
(17, 'Kaustubh Patil', 'HR rejected leave', 'HR User', '2026-08-14 03:33:10', '2026-08-14 03:33:10', '2026-08-14 03:33:10'),
(18, 'Shreyash Pawar', 'Applied for leave (Emergency Leave)', 'Shreyash Pawar', '2026-08-14 03:39:32', '2026-08-14 03:39:32', '2026-08-14 03:39:32'),
(19, 'Shreyash Pawar', 'Applied for leave (Unpaid Leave)', 'Shreyash Pawar', '2026-08-14 03:55:35', '2026-08-14 03:55:35', '2026-08-14 03:55:35'),
(20, 'Kaustubh Patil', 'Applied for leave (Sick Leave (SL))', 'Kaustubh Patil', '2026-08-14 04:16:48', '2026-08-14 04:16:48', '2026-08-14 04:16:48'),
(21, 'Umesh Udhane', 'Updated', 'Admin User', '2026-08-14 05:01:02', '2026-08-14 05:01:02', '2026-08-14 05:01:02'),
(22, 'Rohan Patil', 'Updated', 'Admin User', '2026-08-14 05:01:26', '2026-08-14 05:01:26', '2026-08-14 05:01:26'),
(23, 'Kaustubh Patil', 'Applied for leave (Earned Leave)', 'Kaustubh Patil', '2026-08-14 05:03:20', '2026-08-14 05:03:20', '2026-08-14 05:03:20'),
(24, 'Kaustubh Patil', 'Manager approved leave', 'Rohan Patil', '2026-08-14 05:04:12', '2026-08-14 05:04:12', '2026-08-14 05:04:12'),
(25, 'guddu pawar', 'Updated', 'Admin User', '2026-08-18 02:01:28', '2026-08-18 02:01:28', '2026-08-18 02:01:28'),
(26, 'Rahul Sharma', 'Updated', 'Admin User', '2026-08-18 02:02:01', '2026-08-18 02:02:01', '2026-08-18 02:02:01'),
(27, 'Umesh Udhane', 'Updated', 'Admin User', '2026-08-18 02:02:20', '2026-08-18 02:02:20', '2026-08-18 02:02:20'),
(28, 'Rohan Patil', 'Updated', 'Admin User', '2026-08-18 02:02:37', '2026-08-18 02:02:37', '2026-08-18 02:02:37'),
(29, 'Suraj Jadhav', 'Updated', 'Admin User', '2026-08-18 02:03:00', '2026-08-18 02:03:00', '2026-08-18 02:03:00'),
(30, 'Shreyash Pawar', 'Updated', 'Admin User', '2026-08-18 02:03:31', '2026-08-18 02:03:31', '2026-08-18 02:03:31'),
(31, 'Kunal More', 'Updated', 'Admin User', '2026-08-18 02:03:45', '2026-08-18 02:03:45', '2026-08-18 02:03:45'),
(32, 'Mangesh Shinde', 'Updated', 'Admin User', '2026-08-18 02:04:01', '2026-08-18 02:04:01', '2026-08-18 02:04:01'),
(33, 'Mangesh Shinde', 'Updated', 'Admin User', '2026-08-18 02:04:16', '2026-08-18 02:04:16', '2026-08-18 02:04:16'),
(34, 'Amol Kale', 'Updated', 'Admin User', '2026-08-18 02:04:29', '2026-08-18 02:04:29', '2026-08-18 02:04:29'),
(35, 'Harshal Deshmukh', 'Updated', 'Admin User', '2026-08-18 02:04:41', '2026-08-18 02:04:41', '2026-08-18 02:04:41'),
(36, 'HR User', 'Created', 'Admin User', '2026-08-19 01:13:47', '2026-08-19 01:13:47', '2026-08-19 01:13:47'),
(37, ' ', 'Deleted', 'HR User', '2026-08-19 01:28:59', '2026-08-19 01:28:59', '2026-08-19 01:28:59'),
(38, 'Kaustubh Patil', 'Applied for leave (Unpaid Leave)', 'Kaustubh Patil', '2026-08-19 02:33:05', '2026-08-19 02:33:05', '2026-08-19 02:33:05'),
(39, 'Kaustubh Patil', 'Manager approved leave and forwarded to HR', 'Rohan Patil', '2026-08-19 02:33:57', '2026-08-19 02:33:57', '2026-08-19 02:33:57'),
(40, 'Kaustubh Patil', 'HR final approved leave', 'HR User', '2026-08-19 02:38:21', '2026-08-19 02:38:21', '2026-08-19 02:38:21'),
(41, 'Rohan Patil', 'Applied for leave (Emergency Leave)', 'Rohan Patil', '2026-08-19 03:28:54', '2026-08-19 03:28:54', '2026-08-19 03:28:54'),
(42, 'Kaustubh Patil', 'Manager approved leave and forwarded to HR', 'Rohan Patil', '2026-08-19 03:29:43', '2026-08-19 03:29:43', '2026-08-19 03:29:43'),
(43, 'Rohan Patil', 'HR final approved leave', 'HR User', '2026-08-19 03:31:16', '2026-08-19 03:31:16', '2026-08-19 03:31:16'),
(44, 'Rohan Patil', 'Applied for leave (Unpaid Leave)', 'Rohan Patil', '2026-08-19 03:35:44', '2026-08-19 03:35:44', '2026-08-19 03:35:44'),
(45, 'Rohan Patil', 'HR final approved leave', 'HR User', '2026-08-19 03:36:52', '2026-08-19 03:36:52', '2026-08-19 03:36:52'),
(46, 'Rahul Sharma', 'Updated', 'Admin User', '2026-08-19 04:39:37', '2026-08-19 04:39:37', '2026-08-19 04:39:37'),
(47, 'HR User', 'Updated', 'Admin User', '2026-08-19 04:47:47', '2026-08-19 04:47:47', '2026-08-19 04:47:47'),
(48, 'Shreyash Pawar', 'Updated', 'Admin User', '2026-08-19 04:49:35', '2026-08-19 04:49:35', '2026-08-19 04:49:35'),
(49, 'Ram Jagadale', 'Created', 'Admin User', '2026-08-19 04:53:50', '2026-08-19 04:53:50', '2026-08-19 04:53:50'),
(50, 'Ram Jagadale', 'Updated', 'Admin User', '2026-08-19 05:22:04', '2026-08-19 05:22:04', '2026-08-19 05:22:04');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `asset_type` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('Issued','Returned') NOT NULL DEFAULT 'Issued',
  `condition` enum('Good','Damaged') NOT NULL DEFAULT 'Good',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `employee_code`, `employee_name`, `department`, `asset_type`, `issue_date`, `return_date`, `status`, `condition`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'E2', 'Umesh Udhane', 'Testing Department', 'Laptop', '2026-06-24', NULL, 'Issued', 'Good', NULL, '2026-07-24 05:00:23', '2026-07-24 05:42:33'),
(2, 'E2', 'Umesh Udhane', 'Testing Department', 'Mouse', '2026-07-24', NULL, 'Issued', 'Good', NULL, '2026-07-24 05:00:23', '2026-07-24 05:00:23'),
(3, 'E2', 'Umesh Udhane', 'Testing Department', 'Keyboard', '2026-07-24', NULL, 'Issued', 'Damaged', NULL, '2026-07-24 05:00:23', '2026-07-24 05:01:48'),
(4, 'E2', 'Umesh Udhane', 'Testing Department', 'Charger', '2026-07-24', NULL, 'Issued', 'Good', NULL, '2026-07-24 05:00:23', '2026-07-24 05:00:23'),
(5, 'E 28', 'guddu pawar', 'N/A', 'Laptop', '2026-01-05', '2026-07-17', 'Returned', 'Good', NULL, '2026-07-24 05:29:35', '2026-07-24 05:41:02'),
(6, 'E 28', 'guddu pawar', 'N/A', 'Headphones', '2026-07-24', NULL, 'Issued', 'Good', NULL, '2026-07-24 05:29:35', '2026-07-24 05:29:35'),
(7, 'E10', 'Truptesh Joshi', 'N/A', 'Bag', '2026-07-24', NULL, 'Issued', 'Damaged', NULL, '2026-07-24 05:39:09', '2026-07-24 05:39:09'),
(8, 'E1', 'Rahul Sharma', 'N/A', 'Laptop', '2026-02-02', '2026-06-01', 'Issued', 'Good', NULL, '2026-07-24 05:47:01', '2026-07-24 05:55:54'),
(9, 'E13', 'Ram More', 'N/A', 'Mouse', '2026-07-20', '2026-07-22', 'Issued', 'Good', 'Pending Return', '2026-07-24 05:53:31', '2026-07-24 05:53:31'),
(10, 'E13', 'Ram More', 'N/A', 'Keyboard', '2026-07-20', '2026-07-22', 'Issued', 'Good', 'Pending Return', '2026-07-24 05:53:31', '2026-07-24 05:53:31'),
(11, 'E21', 'Jaydip Patil', 'N/A', 'Laptop', '2026-07-28', NULL, 'Issued', 'Good', NULL, '2026-07-29 00:00:04', '2026-07-29 00:00:04'),
(12, 'E21', 'Jaydip Patil', 'N/A', 'Mouse', '2026-07-28', NULL, 'Issued', 'Good', NULL, '2026-07-29 00:00:04', '2026-07-29 00:00:04'),
(13, 'E21', 'Jaydip Patil', 'N/A', 'Charger', '2026-07-28', NULL, 'Issued', 'Good', NULL, '2026-07-29 00:00:04', '2026-07-29 00:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `assets_master`
--

CREATE TABLE `assets_master` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_code` varchar(255) NOT NULL,
  `asset_type` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `condition` enum('Good','Damaged') NOT NULL DEFAULT 'Good',
  `status` enum('Available','Issued') NOT NULL DEFAULT 'Available',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets_master`
--

INSERT INTO `assets_master` (`id`, `asset_code`, `asset_type`, `company_name`, `model`, `condition`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'AST01', 'Mouse', 'HP', 'M 2009', 'Good', 'Issued', NULL, '2026-08-11 01:49:34', '2026-08-11 02:02:23'),
(2, 'AST02', 'Laptop', 'Dell', 'M 2008', 'Good', 'Issued', NULL, '2026-08-11 02:03:38', '2026-08-11 03:40:44'),
(3, 'AST03', 'Charger', 'Dell', 'M 2008', 'Good', 'Available', NULL, '2026-08-11 02:04:34', '2026-08-11 03:41:40'),
(4, 'AST04', 'Laptop', 'HP', 'M 2009', 'Good', 'Available', NULL, '2026-08-11 03:40:04', '2026-08-11 03:40:04'),
(5, 'AST 06', 'Laptop', 'HP', 'M 2019', 'Good', 'Available', 'Laptop condition is good', '2026-08-19 06:04:30', '2026-08-19 06:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `asset_issues`
--

CREATE TABLE `asset_issues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_issues`
--

INSERT INTO `asset_issues` (`id`, `employee_code`, `employee_name`, `department`, `asset_id`, `issue_date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'E 1', 'Tushar Jamdhade', 'N/A', 2, '2026-08-11', NULL, '2026-08-11 03:40:44', '2026-08-11 03:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `asset_returns`
--

CREATE TABLE `asset_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `return_date` date NOT NULL,
  `return_reason` enum('Exchange','Employee Resigned','Hardware Problem','Repair Required','Other') NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL DEFAULT 'Present',
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-20', 'Present', '07:41:00', NULL, NULL, '2026-07-20 02:12:18', '2026-07-20 02:12:18'),
(2, 4, '2026-07-20', 'Absent', '07:43:00', NULL, NULL, '2026-07-20 02:13:39', '2026-07-20 02:13:39'),
(3, 4, '2026-08-12', 'Leave', NULL, NULL, NULL, '2026-08-12 06:07:32', '2026-08-12 06:07:32'),
(4, 4, '2026-08-13', 'Leave', NULL, NULL, NULL, '2026-08-12 06:07:32', '2026-08-12 06:07:32'),
(5, 4, '2026-08-14', 'Leave', NULL, NULL, NULL, '2026-08-12 06:07:32', '2026-08-12 06:07:32'),
(6, 7, '2026-08-14', 'Present', '07:40:00', NULL, NULL, '2026-08-14 02:11:06', '2026-08-14 02:11:06'),
(7, 1, '2026-08-27', 'Leave', NULL, NULL, NULL, '2026-08-18 04:43:51', '2026-08-18 04:43:51'),
(8, 1, '2026-08-28', 'Leave', NULL, NULL, NULL, '2026-08-18 04:43:51', '2026-08-18 04:43:51'),
(9, 14, '2026-08-19', 'Leave', NULL, NULL, NULL, '2026-08-18 04:43:59', '2026-08-18 04:43:59'),
(10, 5, '2026-08-19', 'Leave', NULL, NULL, NULL, '2026-08-18 04:59:43', '2026-08-18 04:59:43'),
(11, 5, '2026-08-20', 'Leave', NULL, NULL, NULL, '2026-08-18 04:59:43', '2026-08-18 04:59:43'),
(12, 14, '2026-08-21', 'Leave', NULL, NULL, NULL, '2026-08-19 02:38:21', '2026-08-19 02:38:21'),
(13, 14, '2026-08-22', 'Leave', NULL, NULL, NULL, '2026-08-19 02:38:21', '2026-08-19 02:38:21'),
(14, 5, '2026-08-31', 'Leave', NULL, NULL, NULL, '2026-08-19 03:31:15', '2026-08-19 03:31:15'),
(15, 5, '2026-08-24', 'Leave', NULL, NULL, NULL, '2026-08-19 03:36:52', '2026-08-19 03:36:52'),
(16, 5, '2026-08-25', 'Leave', NULL, NULL, NULL, '2026-08-19 03:36:52', '2026-08-19 03:36:52');

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
-- Table structure for table `company_profiles`
--

CREATE TABLE `company_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_code` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_code`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Web Development', 'D 1', 'Active', '2026-07-02 01:46:40', '2026-07-09 04:12:21', NULL),
(2, 'Testing Department', 'D 2', 'Active', '2026-07-02 01:47:13', '2026-07-02 01:47:13', NULL),
(3, 'Application Development Dep', 'D 3', 'Active', '2026-07-02 01:47:53', '2026-07-09 04:08:59', NULL),
(4, 'HR Department', 'D 4', 'Active', '2026-07-09 04:13:25', '2026-07-09 04:13:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `joining_date` date NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_code`, `first_name`, `last_name`, `email`, `mobile_number`, `designation`, `salary`, `joining_date`, `status`, `department_id`, `manager_id`, `profile_image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'E 1', 'Tushar', 'Jamdhade', 'tusharjamdhe@gmail.com', '2134234521', 'Web Dev', 30000.00, '2026-01-01', 'Active', NULL, 5, NULL, '2026-06-30 00:37:52', '2026-06-30 00:37:52', NULL),
(3, 'E1', 'Rahul', 'Sharma', 'manager@example.com', '9876543201', 'Laravel Developer', 35000.00, '2025-01-01', 'Active', NULL, NULL, 'employees/1xjppUDVowjGhTw5b4V95dDZ7hp2Efz4FaHEDQfX.jpg', '2026-06-30 06:32:53', '2026-08-12 06:28:49', NULL),
(4, 'E2', 'Umesh', 'Udhane', 'admin@example.com', '9876543202', 'PHP Develope', 36000.00, '2025-01-03', 'Active', 2, NULL, 'employees/9xGrxAyhie9ny7yoofDT03Nem7aXjY7diV6WL4RB.jpg', '2026-06-30 06:32:53', '2026-08-14 05:01:02', NULL),
(5, 'E3', 'Rohan', 'Patil', 'rohan.patil@example.com', '9876543203', 'Project Manger', 35000.00, '2025-01-05', 'Active', NULL, NULL, 'employees/Em3sw27mSrJIrE0lSyO5jY43VoYBXCIsLRxh7EXD.jpg', '2026-06-30 06:32:53', '2026-08-14 05:01:26', NULL),
(6, 'E4', 'Suraj', 'Jadhav', 'suraj.jadhav@example.com', '9876543204', 'Backend Developer', 38000.00, '2025-01-07', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-07-06 00:47:50', NULL),
(7, 'E5', 'Shreyash', 'Pawar', 'shreyash.pawar@example.com', '9876543205', 'UI/UX Designer', 32000.00, '2025-01-09', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(8, 'E6', 'Kunal', 'More', 'kunal.more@example.com', '9876543206', 'HR Executive', 30000.00, '2025-01-11', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(9, 'E7', 'Mangesh', 'Shinde', 'mangesh.shinde@example.com', '9876543207', 'Software Engineer', 42000.00, '2025-01-13', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(10, 'E8', 'Amol', 'Kale', 'amol.kale@example.com', '9876543208', 'QA Tester', 31000.00, '2025-01-15', 'Inactive', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(11, 'E9', 'Harshal', 'Deshmukh', 'harshal.deshmukh@example.com', '9876543209', 'Laravel Developer', 39000.00, '2025-01-17', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(12, 'E10', 'Truptesh', 'Joshi', 'truptesh.joshi@example.com', '9876543210', 'Project Coordinator', 40000.00, '2025-01-19', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(13, 'E11', 'Swapanil', 'Kulkarni', 'swapanil.kulkarni@example.com', '9876543211', 'Backend Developer', 37000.00, '2025-01-21', 'Inactive', 1, NULL, NULL, '2026-06-30 06:32:53', '2026-07-02 02:28:50', NULL),
(14, 'E12', 'Kaustubh', 'Patil', 'kaustubh.patil@example.com', '9876543212', 'PHP Developer', 36000.00, '2025-01-23', 'Active', NULL, 5, NULL, '2026-06-30 06:32:53', '2026-08-14 05:03:20', NULL),
(15, 'E13', 'Ram', 'More', 'ram.more@example.com', '9876543213', 'Software Engineer', 41000.00, '2025-01-25', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(16, 'E14', 'Yash', 'Shinde', 'yash.shinde@example.com', '9876543214', 'UI/UX Designer', 33000.00, '2025-01-27', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(17, 'E15', 'Yogesh', 'Jagtap', 'yogesh.jagtap@example.com', '9876543215', 'DevOps Engineer', 45000.00, '2025-01-29', 'Inactive', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(18, 'E16', 'Nikhil', 'Pawar', 'nikhil.pawar@example.com', '9876543216', 'Frontend Developer', 34000.00, '2025-02-01', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(19, 'E17', 'Pradip', 'Mane', 'pradip.mane@example.com', '9876543217', 'HR Executive', 30000.00, '2025-02-03', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(20, 'E18', 'Sahil', 'Gaikwad', 'sahil.gaikwad@example.com', '9876543218', 'Laravel Developer', 39000.00, '2025-02-05', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(21, 'E19', 'Sumit', 'Chavan', 'sumit.chavan@example.com', '9876543219', 'QA Tester', 32000.00, '2025-02-07', 'Inactive', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(22, 'E20', 'Tushar', 'Bhosale', 'tushar.bhosale@example.com', '9876543220', 'Backend Developer', 38000.00, '2025-02-09', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(23, 'E21', 'Jaydip', 'Patil', 'jaydip.patil@example.com', '9876543221', 'Software Engineer', 42000.00, '2025-02-11', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(24, 'E22', 'Aditya', 'Sharma', 'aditya.sharma@example.com', '9876543222', 'Project Manager', 50000.00, '2025-02-13', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(25, 'E23', 'Sarthak', 'Jadhav', 'sarthak.jadhav@example.com', '9876543223', 'PHP Developer', 36000.00, '2025-02-15', 'Inactive', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(26, 'E24', 'Pratham', 'More', 'pratham.more@example.com', '9876543224', 'Frontend Developer', 34000.00, '2025-02-17', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(27, 'E25', 'Arbaj', 'Shaikh', 'arbaj.shaikh@example.com', '9876543225', 'Laravel Developer', 40000.00, '2025-02-19', 'Active', NULL, NULL, NULL, '2026-06-30 06:32:53', '2026-06-30 06:32:53', NULL),
(32, 'E 27', 'Nilesh', 'Shinde', 'nileshshinde@gmail.com', '9089877890', 'HR Manager', 500000.00, '2026-02-03', 'Active', 4, NULL, NULL, '2026-07-09 04:15:51', '2026-07-14 04:17:08', '2026-07-14 04:17:08'),
(34, 'E 28', 'guddu', 'pawar', 'guddupawar@gmail.com', '9356281911', 'Web Developer', 40000.00, '2026-01-01', 'Active', NULL, NULL, NULL, '2026-07-24 05:20:55', '2026-07-24 05:20:55', NULL),
(35, 'E 29', 'HR', 'User', 'hr@example.com', '1223432354', 'HR', 100000.00, '2026-01-01', 'Active', 4, NULL, NULL, '2026-08-19 01:13:47', '2026-08-19 01:13:47', NULL),
(36, 'E 31', 'Ram', 'Jagadale', 'ramjagdale@gmail.com', '08261918528', 'web developer', 30000.00, '2026-08-01', 'Active', 1, 5, NULL, '2026-08-19 04:53:50', '2026-08-19 05:22:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_approvals`
--

CREATE TABLE `leave_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_request_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED NOT NULL,
  `approver_role` enum('Manager','HR','Admin') NOT NULL,
  `action` enum('Approve','Reject') NOT NULL,
  `comment` text DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_approvals`
--

INSERT INTO `leave_approvals` (`id`, `leave_request_id`, `approver_id`, `approver_role`, `action`, `comment`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 'HR', 'Approve', NULL, '2026-08-12 06:07:32', '2026-08-12 06:07:32', '2026-08-12 06:07:32'),
(2, 3, 9, 'HR', 'Reject', 'because not possbile today is standup meeting', '2026-08-12 06:31:21', '2026-08-12 06:31:21', '2026-08-12 06:31:21'),
(3, 5, 9, 'HR', 'Reject', 'ffsdvs sdvsd vsfv svds', '2026-08-14 03:32:39', '2026-08-14 03:32:39', '2026-08-14 03:32:39'),
(4, 4, 9, 'HR', 'Reject', 'xcsvcd svdv vdvd', '2026-08-14 03:33:10', '2026-08-14 03:33:10', '2026-08-14 03:33:10'),
(5, 11, 16, 'Manager', 'Approve', NULL, '2026-08-14 05:04:12', '2026-08-14 05:04:12', '2026-08-14 05:04:12'),
(6, 15, 16, 'Manager', 'Approve', NULL, '2026-08-19 02:33:57', '2026-08-19 02:33:57', '2026-08-19 02:33:57'),
(7, 15, 9, 'HR', 'Approve', NULL, '2026-08-19 02:38:20', '2026-08-19 02:38:20', '2026-08-19 02:38:20'),
(8, 12, 16, 'Manager', 'Approve', NULL, '2026-08-19 03:29:43', '2026-08-19 03:29:43', '2026-08-19 03:29:43'),
(9, 16, 9, 'HR', 'Approve', NULL, '2026-08-19 03:31:15', '2026-08-19 03:31:15', '2026-08-19 03:31:15'),
(10, 17, 9, 'HR', 'Approve', NULL, '2026-08-19 03:36:52', '2026-08-19 03:36:52', '2026-08-19 03:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `allocated_days` int(11) NOT NULL DEFAULT 0,
  `used_days` int(11) NOT NULL DEFAULT 0,
  `remaining_days` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `employee_id`, `leave_type_id`, `year`, `allocated_days`, `used_days`, `remaining_days`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(2, 1, 2, '2026', 2, 2, 0, '2026-08-14 02:54:26', '2026-08-18 04:43:51'),
(3, 1, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(4, 1, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(5, 1, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(6, 3, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(7, 3, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(8, 3, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(9, 3, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(10, 3, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(11, 4, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(12, 4, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(13, 4, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(14, 4, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(15, 4, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(16, 5, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(17, 5, 2, '2026', 2, 2, 0, '2026-08-14 02:54:26', '2026-08-18 04:59:43'),
(18, 5, 3, '2026', 1, 1, 0, '2026-08-14 02:54:26', '2026-08-19 03:31:15'),
(19, 5, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(20, 5, 5, '2026', 5, 2, 3, '2026-08-14 02:54:26', '2026-08-19 03:36:52'),
(21, 6, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(22, 6, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(23, 6, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(24, 6, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(25, 6, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(26, 7, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(27, 7, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(28, 7, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(29, 7, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(30, 7, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(31, 8, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(32, 8, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(33, 8, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(34, 8, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(35, 8, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(36, 9, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(37, 9, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(38, 9, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(39, 9, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(40, 9, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(41, 10, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(42, 10, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(43, 10, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(44, 10, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(45, 10, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(46, 11, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(47, 11, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(48, 11, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(49, 11, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(50, 11, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(51, 12, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(52, 12, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(53, 12, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(54, 12, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(55, 12, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(56, 13, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(57, 13, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(58, 13, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(59, 13, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(60, 13, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(61, 14, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(62, 14, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(63, 14, 3, '2026', 1, 1, 0, '2026-08-14 02:54:26', '2026-08-18 04:43:59'),
(64, 14, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(65, 14, 5, '2026', 5, 2, 3, '2026-08-14 02:54:26', '2026-08-19 02:38:20'),
(66, 15, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(67, 15, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(68, 15, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(69, 15, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(70, 15, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(71, 16, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(72, 16, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(73, 16, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(74, 16, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(75, 16, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(76, 17, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(77, 17, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(78, 17, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(79, 17, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(80, 17, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(81, 18, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(82, 18, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(83, 18, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(84, 18, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(85, 18, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(86, 19, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(87, 19, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(88, 19, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(89, 19, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(90, 19, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(91, 20, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(92, 20, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(93, 20, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(94, 20, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(95, 20, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(96, 21, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(97, 21, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(98, 21, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(99, 21, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(100, 21, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(101, 22, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(102, 22, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(103, 22, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(104, 22, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(105, 22, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(106, 23, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(107, 23, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(108, 23, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(109, 23, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(110, 23, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(111, 24, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(112, 24, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(113, 24, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(114, 24, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(115, 24, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(116, 25, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(117, 25, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(118, 25, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(119, 25, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(120, 25, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(121, 26, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(122, 26, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(123, 26, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(124, 26, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(125, 26, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(126, 27, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(127, 27, 2, '2026', 2, 0, 2, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(128, 27, 3, '2026', 1, 0, 1, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(129, 27, 4, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(130, 27, 5, '2026', 5, 0, 5, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(131, 34, 1, '2026', 10, 0, 10, '2026-08-14 02:54:26', '2026-08-14 02:54:26'),
(132, 34, 2, '2026', 2, 0, 2, '2026-08-14 02:54:27', '2026-08-14 02:54:27'),
(133, 34, 3, '2026', 1, 0, 1, '2026-08-14 02:54:27', '2026-08-14 02:54:27'),
(134, 34, 4, '2026', 5, 0, 5, '2026-08-14 02:54:27', '2026-08-14 02:54:27'),
(135, 34, 5, '2026', 5, 0, 5, '2026-08-14 02:54:27', '2026-08-14 02:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `leave_policies`
--

CREATE TABLE `leave_policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_consecutive_days` int(11) DEFAULT NULL,
  `allow_carry_forward` tinyint(1) NOT NULL DEFAULT 0,
  `carry_forward_limit` int(11) DEFAULT NULL,
  `requires_document` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `reason` text NOT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` enum('pending_manager','manager_approved','manager_rejected','pending_hr','hr_rejected','approved','cancelled') NOT NULL DEFAULT 'pending_manager',
  `manager_comment` text DEFAULT NULL,
  `hr_comment` text DEFAULT NULL,
  `manager_approved_at` timestamp NULL DEFAULT NULL,
  `manager_rejected_at` timestamp NULL DEFAULT NULL,
  `manager_remarks` text DEFAULT NULL,
  `hr_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `hr_approved_at` timestamp NULL DEFAULT NULL,
  `hr_rejected_at` timestamp NULL DEFAULT NULL,
  `hr_remarks` text DEFAULT NULL,
  `admin_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_approved_at` timestamp NULL DEFAULT NULL,
  `admin_rejected_at` timestamp NULL DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `final_status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `manager_id`, `manager_status`, `leave_type_id`, `from_date`, `to_date`, `total_days`, `reason`, `document`, `status`, `manager_comment`, `hr_comment`, `manager_approved_at`, `manager_rejected_at`, `manager_remarks`, `hr_status`, `hr_approved_at`, `hr_rejected_at`, `hr_remarks`, `admin_status`, `admin_approved_at`, `admin_rejected_at`, `admin_remarks`, `final_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, NULL, 'pending', 3, '2026-08-12', '2026-08-14', 3, 'mla gavi jayche aahe mla ghari emergency kam aahe tymule mi leave takat aahe', NULL, 'approved', NULL, NULL, NULL, NULL, NULL, 'pending', '2026-08-12 06:07:32', NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-12 05:56:51', '2026-08-12 06:07:32', NULL),
(2, 4, NULL, 'pending', 2, '2026-08-12', '2026-08-12', 1, 'mla personly kam aahe tyamule mi out of city jat aahe', NULL, 'approved', NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-12 06:21:59', '2026-08-12 06:21:59', NULL),
(3, 3, NULL, 'pending', 3, '2026-08-12', '2026-08-12', 1, 'sdfds sdvvdsv sdcx dscd scdsc dcdsc scdcdv', NULL, 'hr_rejected', NULL, 'because not possbile today is standup meeting', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-12 06:29:26', '2026-08-12 06:31:21', NULL),
(4, 14, NULL, 'pending', 1, '2026-08-13', '2026-08-13', 1, 'Colleage exam not possible come for office', NULL, 'hr_rejected', NULL, 'xcsvcd svdv vdvd', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-13 06:47:12', '2026-08-14 03:33:10', NULL),
(5, 14, NULL, 'approved', 3, '2026-08-14', '2026-08-14', 1, 'fdjfidv  dcddv dscdsvds dvdsvdsv vsdvsdvsd vsdvsdv vdsvcdvd', NULL, 'hr_rejected', NULL, 'ffsdvs sdvsd vsfv svds', '2026-08-18 04:35:28', NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-14 02:59:57', '2026-08-18 04:35:28', NULL),
(6, 14, NULL, 'approved', 2, '2026-08-26', '2026-08-27', 2, 'dasd csac cascsa cascas ddvd csdv vsdvd vdssvd ddsv', NULL, 'pending_manager', NULL, NULL, '2026-08-18 04:35:24', NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-14 03:15:19', '2026-08-18 04:35:24', NULL),
(7, 1, NULL, 'approved', 2, '2026-08-27', '2026-08-28', 2, 'sndsf fsdsfds vssdfs vsdvds vdsv vsdvd vsdvsd', NULL, 'pending_manager', NULL, NULL, '2026-08-18 04:35:36', NULL, NULL, 'approved', '2026-08-18 04:43:51', NULL, NULL, 'pending', NULL, NULL, NULL, 'approved', '2026-08-14 03:28:02', '2026-08-18 04:43:51', NULL),
(8, 7, NULL, 'pending', 3, '2026-08-28', '2026-08-28', 1, 'dsv vdsvs vsdvd vdvdv vdvsd vdvds sdcdsc csdc vdsv vvdsv dvds sddvv vsdvds vsvd', NULL, 'pending_manager', NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-14 03:39:32', '2026-08-14 03:39:32', NULL),
(9, 7, NULL, 'pending', 4, '2026-08-26', '2026-08-27', 2, 'dsfdss dsvds csd sdvds vsdvds sdvsd', NULL, 'pending_manager', NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-14 03:55:35', '2026-08-14 03:55:35', NULL),
(10, 14, NULL, 'rejected', 1, '2026-08-30', '2026-08-31', 2, 'dsfd sddfvds vsdvd svdsv svdvds vsdv sdvsd vdv vsdv', NULL, 'pending_manager', NULL, NULL, NULL, '2026-08-18 04:35:08', 'vdsvsdvd vsddv vsdvds', 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'rejected', '2026-08-14 04:16:48', '2026-08-18 04:35:08', NULL),
(11, 14, NULL, 'approved', 2, '2026-09-01', '2026-09-01', 1, 'dfsd sdvs vsvds vsdv vsdvsd vsddvsd vsdvs vdssdvsd', NULL, 'pending_hr', NULL, NULL, '2026-08-18 04:35:31', NULL, NULL, 'rejected', NULL, '2026-08-18 04:44:11', 'fdsvd vsdvd vdvds vdsvvdvv', 'pending', NULL, NULL, NULL, 'rejected', '2026-08-14 05:03:20', '2026-08-18 04:44:11', NULL),
(12, 14, 5, 'approved', 3, '2026-08-19', '2026-08-19', 1, 'fsdf sddsf fsdfvds vddsv dsvdsvd svdsv dsvds', NULL, 'pending_hr', NULL, NULL, '2026-08-19 03:29:43', NULL, NULL, 'approved', '2026-08-18 04:43:59', NULL, NULL, 'pending', NULL, NULL, NULL, 'approved', '2026-08-18 04:23:26', '2026-08-19 03:29:43', NULL),
(13, 5, NULL, 'pending', 2, '2026-08-19', '2026-08-20', 2, 'dads  dsvd cccd  sdvsdvds sdcvds dsdcds vdsvdv dvds vvd vsdv d', NULL, 'pending_manager', NULL, NULL, NULL, NULL, NULL, 'approved', '2026-08-18 04:59:43', NULL, NULL, 'pending', NULL, NULL, NULL, 'approved', '2026-08-18 04:52:18', '2026-08-18 04:59:43', NULL),
(14, 5, NULL, 'pending', 3, '2026-08-18', '2026-08-18', 1, 'dsdv vv vdcds cdvcdsv dsvdsv dsvsd vdsv sv', NULL, 'pending_manager', NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-18 05:08:51', '2026-08-18 05:08:51', NULL),
(15, 14, 5, 'pending', 5, '2026-08-21', '2026-08-22', 2, 'svsdv vsdvds vdsvds vdsv dsvdsv dvdsv vdsvdsv', NULL, 'approved', NULL, NULL, '2026-08-19 02:33:57', NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-19 02:33:05', '2026-08-19 02:38:20', NULL),
(16, 5, NULL, 'pending', 3, '2026-08-31', '2026-08-31', 1, 'vdsv vsvd ddsv dvdv dsvds vdsv dvds vdsv dsv ds', NULL, 'approved', NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-19 03:28:54', '2026-08-19 03:31:15', NULL),
(17, 5, NULL, 'pending', 5, '2026-08-24', '2026-08-25', 2, 'svdv vsv dvdsv dssvd vdsv vds vsdv vsv vdsv dvdsvv dvdsvsvsdv', NULL, 'approved', NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'pending', '2026-08-19 03:35:44', '2026-08-19 03:36:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `annual_limit` int(11) NOT NULL DEFAULT 0,
  `max_consecutive_days` int(11) DEFAULT NULL,
  `carry_forward` tinyint(1) NOT NULL DEFAULT 0,
  `is_paid` tinyint(1) NOT NULL DEFAULT 1,
  `requires_document` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `code`, `annual_limit`, `max_consecutive_days`, `carry_forward`, `is_paid`, `requires_document`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Sick Leave (SL)', 'LC 01', 10, 3, 0, 1, 0, 1, NULL, '2026-08-12 05:32:13', '2026-08-12 05:32:13'),
(2, 'Earned Leave', 'LC 02', 2, 1, 0, 1, 0, 1, NULL, '2026-08-12 05:33:43', '2026-08-12 05:33:43'),
(3, 'Emergency Leave', 'LC 03', 1, 1, 0, 1, 0, 1, NULL, '2026-08-12 05:34:27', '2026-08-12 05:34:27'),
(4, 'Unpaid Leave', 'LC 04', 5, 6, 1, 1, 0, 1, NULL, '2026-08-12 05:35:22', '2026-08-12 05:35:22'),
(5, 'Unpaid Leave', 'LC 05', 5, 6, 0, 1, 0, 1, NULL, '2026-08-12 05:35:32', '2026-08-12 05:35:32');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_30_052126_create_employees_table', 1),
(5, '2026_07_01_052835_add_profile_image_to_employees_table', 2),
(6, '2026_07_02_062335_create_departments_table', 3),
(7, '2026_07_02_062511_add_department_id_to_employees_table', 3),
(9, '2026_07_09_090547_add_soft_deletes_to_employee_table', 5),
(10, '2026_07_09_093302_add_soft_deletes_to_departments_table', 6),
(13, '2026_07_20_062907_create_attendances_table', 8),
(14, '2026_07_10_095330_create_activity_logs_table', 9),
(16, '2026_07_24_072237_create_assets_table', 10),
(26, '2026_08_05_060023_create_assets_master_table', 11),
(29, '2026_08_05_060025_create_asset_issues_table', 12),
(31, '2026_08_05_060026_create_asset_returns_table', 13),
(32, '2026_07_08_065422_add_role_to_users_table', 14),
(33, '2026_08_12_101216_create_leave_types_table', 15),
(34, '2026_08_12_101324_create_leave_requests_table', 15),
(35, '2026_08_12_101351_create_leave_balances_table', 15),
(36, '2026_08_12_101419_create_leave_approvals_table', 15),
(37, '2026_08_12_101455_create_holidays_table', 15),
(38, '2026_08_12_101518_create_leave_policies_table', 15),
(39, '2026_08_12_101953_add_manager_id_to_employees_table', 16),
(40, '2026_08_13_095052_add_role_and_status_to_users_table', 17),
(41, '2026_08_13_120627_update_role_enum_add_employee', 18),
(42, '2026_08_14_065318_create_settings_tables', 19),
(43, '2026_08_18_065435_update_leave_requests_table_new_structure', 20);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('kXD1Kix6xSauur2zLxpvDxtiIMT2QDl09rZd0k5v', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNlZpRDFweHVHTmoybkpraXJuUGhpRHRFUFZhb3RUQlprSUxNbzBJcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hc3NldHMvcmVwb3J0cyI7czo1OiJyb3V0ZSI7czoxNDoiYXNzZXRzLnJlcG9ydHMiO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1784894228);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) NOT NULL DEFAULT 'Asia/Kolkata',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 'Encure IT', 'Asia/Kolkata', NULL, '2026-08-20 05:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('Admin','HR','Manager','Employee') NOT NULL DEFAULT 'Employee',
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `employee_id`, `status`) VALUES
(8, 'Admin User', 'admin@example.com', NULL, '$2y$12$Ap2WNdbGVVJ3IubHwWA70OYQr88S/0M/aEnmiq2Gth2y5OCSdp5/K', 'nbRzHRAOMUa8DzdXXrAds7LfofHLSR16crKAmVWLlOo7xdtiHowTbbK9uIA9', '2026-08-11 04:27:02', '2026-08-11 04:27:02', 'Admin', NULL, 'Active'),
(9, 'HR User', 'hr@example.com', NULL, '$2y$12$msShkmTXQ0TRgpv/rFQSU.d0HtkSpVKWJ8TFTWyegDWkBtl8035zO', NULL, '2026-08-11 04:27:02', '2026-08-11 04:27:02', 'HR', NULL, 'Active'),
(12, 'Tushar Jamdhade', 'tusharjamdhe@gmail.com', NULL, '$2y$12$NgIvj5LC82TuRIh9aWEg1eoSAeLLJwX9QpGjIpJD/yoQWEB.L0YaW', NULL, '2026-08-13 06:40:15', '2026-08-13 06:40:15', 'Employee', 1, 'Active'),
(13, 'Kaustubh Patil', 'kaustubh.patil@example.com', NULL, '$2y$12$8CQ1lfxLMd4SK8dF7H.Jz.F.u54fa4KJvQOO3LqF2B6TYEd0kYvJq', NULL, '2026-08-13 06:41:06', '2026-08-13 06:41:06', 'Employee', 14, 'Active'),
(14, 'Suraj Jadhav', 'suraj.jadhav@example.com', NULL, '$2y$12$nFMQDC2.J1VLwuMOuKMUP.bJBMWRrS6fjmtPzZ3YpokVbGgrsB2He', NULL, '2026-08-13 06:44:28', '2026-08-13 06:44:28', 'Employee', 6, 'Active'),
(15, 'Shreyash Pawar', 'shreyash.pawar@example.com', NULL, '$2y$12$DY4I85Fh4K83QA1AIqlW3.FI6HnkMXRNRG1MTKFqjIX1d/N0oOIF2', NULL, '2026-08-14 03:37:44', '2026-08-14 03:37:44', 'Employee', 7, 'Active'),
(16, 'Rohan Patil', 'rohan.patil@example.com', NULL, '$2y$12$JU2DIODyOCKo7wz/DUmgiu1zYuUtT2YDns/ws38FM48deoi72KKYC', NULL, '2026-08-14 04:12:27', '2026-08-14 04:12:27', 'Manager', 5, 'Active'),
(17, 'Nilesh Shinde', 'nileshshinde@gmail.com', NULL, '$2y$12$l1zFyljdYBuxZXY2FOY2lOAU3plkRM/D6heFVNUSyGh.e3PoAOrAK', NULL, '2026-08-18 02:00:26', '2026-08-18 02:00:26', 'Manager', 32, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets_master`
--
ALTER TABLE `assets_master`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assets_master_asset_code_unique` (`asset_code`),
  ADD KEY `assets_master_status_index` (`status`),
  ADD KEY `assets_master_asset_code_index` (`asset_code`),
  ADD KEY `assets_master_asset_type_index` (`asset_type`);

--
-- Indexes for table `asset_issues`
--
ALTER TABLE `asset_issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_issues_employee_code_index` (`employee_code`),
  ADD KEY `asset_issues_asset_id_index` (`asset_id`),
  ADD KEY `asset_issues_issue_date_index` (`issue_date`);

--
-- Indexes for table `asset_returns`
--
ALTER TABLE `asset_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_returns_employee_code_index` (`employee_code`),
  ADD KEY `asset_returns_asset_id_index` (`asset_id`),
  ADD KEY `asset_returns_return_date_index` (`return_date`),
  ADD KEY `asset_returns_return_reason_index` (`return_reason`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_employee_id_date_unique` (`employee_id`,`date`);

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
-- Indexes for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_department_code_unique` (`department_code`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_department_id_foreign` (`department_id`),
  ADD KEY `employees_manager_id_index` (`manager_id`);

--
-- Indexes for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_approvals_leave_request_id_index` (`leave_request_id`),
  ADD KEY `leave_approvals_approver_id_index` (`approver_id`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_balances_employee_id_leave_type_id_year_unique` (`employee_id`,`leave_type_id`,`year`),
  ADD KEY `leave_balances_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leave_balances_employee_id_index` (`employee_id`),
  ADD KEY `leave_balances_year_index` (`year`);

--
-- Indexes for table `leave_policies`
--
ALTER TABLE `leave_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leave_requests_employee_id_index` (`employee_id`),
  ADD KEY `leave_requests_status_index` (`status`),
  ADD KEY `leave_requests_from_date_index` (`from_date`),
  ADD KEY `leave_requests_to_date_index` (`to_date`),
  ADD KEY `leave_requests_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `leave_requests_manager_id_foreign` (`manager_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_types_code_unique` (`code`),
  ADD KEY `leave_types_code_index` (`code`),
  ADD KEY `leave_types_is_active_index` (`is_active`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_employee_id_foreign` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `assets_master`
--
ALTER TABLE `assets_master`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `asset_issues`
--
ALTER TABLE `asset_issues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `asset_returns`
--
ALTER TABLE `asset_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `company_profiles`
--
ALTER TABLE `company_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `leave_policies`
--
ALTER TABLE `leave_policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asset_issues`
--
ALTER TABLE `asset_issues`
  ADD CONSTRAINT `asset_issues_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets_master` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `asset_returns`
--
ALTER TABLE `asset_returns`
  ADD CONSTRAINT `asset_returns_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets_master` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD CONSTRAINT `leave_approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `leave_approvals_leave_request_id_foreign` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_balances_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`),
  ADD CONSTRAINT `leave_requests_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
