-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2026 at 09:43 AM
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_code`, `first_name`, `last_name`, `email`, `mobile_number`, `designation`, `salary`, `joining_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'E 1', 'Tushar', 'Jamdhade', 'tusharjamdhe@gmail.com', '2134234521', 'Web Dev', 30000.00, '2026-01-01', 'Active', '2026-06-30 00:37:52', '2026-06-30 00:37:52'),
(3, 'E1', 'Rahul', 'Sharma', 'rahul.sharma@example.com', '9876543201', 'Laravel Developer', 35000.00, '2025-01-01', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(4, 'E2', 'Umesh', 'Udhane', 'umesh.udhane@example.com', '9876543202', 'PHP Developer', 36000.00, '2025-01-03', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(5, 'E3', 'Rohan', 'Patil', 'rohan.patil@example.com', '9876543203', 'Frontend Developer', 34000.00, '2025-01-05', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(6, 'E4', 'Suraj', 'Jadhav', 'suraj.jadhav@example.com', '9876543204', 'Backend Developer', 38000.00, '2025-01-07', 'Inactive', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(7, 'E5', 'Shreyash', 'Pawar', 'shreyash.pawar@example.com', '9876543205', 'UI/UX Designer', 32000.00, '2025-01-09', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(8, 'E6', 'Kunal', 'More', 'kunal.more@example.com', '9876543206', 'HR Executive', 30000.00, '2025-01-11', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(9, 'E7', 'Mangesh', 'Shinde', 'mangesh.shinde@example.com', '9876543207', 'Software Engineer', 42000.00, '2025-01-13', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(10, 'E8', 'Amol', 'Kale', 'amol.kale@example.com', '9876543208', 'QA Tester', 31000.00, '2025-01-15', 'Inactive', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(11, 'E9', 'Harshal', 'Deshmukh', 'harshal.deshmukh@example.com', '9876543209', 'Laravel Developer', 39000.00, '2025-01-17', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(12, 'E10', 'Truptesh', 'Joshi', 'truptesh.joshi@example.com', '9876543210', 'Project Coordinator', 40000.00, '2025-01-19', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(13, 'E11', 'Swapanil', 'Kulkarni', 'swapanil.kulkarni@example.com', '9876543211', 'Backend Developer', 37000.00, '2025-01-21', 'Inactive', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(14, 'E12', 'Kaustubh', 'Patil', 'kaustubh.patil@example.com', '9876543212', 'PHP Developer', 36000.00, '2025-01-23', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(15, 'E13', 'Ram', 'More', 'ram.more@example.com', '9876543213', 'Software Engineer', 41000.00, '2025-01-25', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(16, 'E14', 'Yash', 'Shinde', 'yash.shinde@example.com', '9876543214', 'UI/UX Designer', 33000.00, '2025-01-27', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(17, 'E15', 'Yogesh', 'Jagtap', 'yogesh.jagtap@example.com', '9876543215', 'DevOps Engineer', 45000.00, '2025-01-29', 'Inactive', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(18, 'E16', 'Nikhil', 'Pawar', 'nikhil.pawar@example.com', '9876543216', 'Frontend Developer', 34000.00, '2025-02-01', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(19, 'E17', 'Pradip', 'Mane', 'pradip.mane@example.com', '9876543217', 'HR Executive', 30000.00, '2025-02-03', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(20, 'E18', 'Sahil', 'Gaikwad', 'sahil.gaikwad@example.com', '9876543218', 'Laravel Developer', 39000.00, '2025-02-05', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(21, 'E19', 'Sumit', 'Chavan', 'sumit.chavan@example.com', '9876543219', 'QA Tester', 32000.00, '2025-02-07', 'Inactive', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(22, 'E20', 'Tushar', 'Bhosale', 'tushar.bhosale@example.com', '9876543220', 'Backend Developer', 38000.00, '2025-02-09', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(23, 'E21', 'Jaydip', 'Patil', 'jaydip.patil@example.com', '9876543221', 'Software Engineer', 42000.00, '2025-02-11', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(24, 'E22', 'Aditya', 'Sharma', 'aditya.sharma@example.com', '9876543222', 'Project Manager', 50000.00, '2025-02-13', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(25, 'E23', 'Sarthak', 'Jadhav', 'sarthak.jadhav@example.com', '9876543223', 'PHP Developer', 36000.00, '2025-02-15', 'Inactive', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(26, 'E24', 'Pratham', 'More', 'pratham.more@example.com', '9876543224', 'Frontend Developer', 34000.00, '2025-02-17', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53'),
(27, 'E25', 'Arbaj', 'Shaikh', 'arbaj.shaikh@example.com', '9876543225', 'Laravel Developer', 40000.00, '2025-02-19', 'Active', '2026-06-30 06:32:53', '2026-06-30 06:32:53');

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

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_30_052126_create_employees_table', 1);

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
('OjHr748gSonvsR7BmxzC9hfLAcdqijK9anROizl5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUY5RGVxandXcEZXT0pGenRwV1U5RFhoVmNWc0MzZ2QxTkhWOHdTOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbXBsb3llZXM/c2VhcmNoPXBocCUyMCI7czo1OiJyb3V0ZSI7czoxNToiZW1wbG95ZWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782803748);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
