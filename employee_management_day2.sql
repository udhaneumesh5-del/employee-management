-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: employee_management
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `joining_date` date NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  UNIQUE KEY `employees_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'E 1','Tushar','Jamdhade','tusharjamdhe@gmail.com','2134234521','Web Dev',30000.00,'2026-01-01','Active',NULL,'2026-06-30 00:37:52','2026-06-30 00:37:52'),(3,'E1','Rahul','Sharma','rahul.sharma@example.com','9876543201','Laravel Developer',35000.00,'2025-01-01','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(4,'E2','Umesh','Udhane','umesh.udhane@example.com','9876543202','PHP Developer',36000.00,'2025-01-03','Active','employees/9xGrxAyhie9ny7yoofDT03Nem7aXjY7diV6WL4RB.jpg','2026-06-30 06:32:53','2026-07-01 06:04:47'),(5,'E3','Rohan','Patil','rohan.patil@example.com','9876543203','Frontend Developer',34000.00,'2025-01-05','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(6,'E4','Suraj','Jadhav','suraj.jadhav@example.com','9876543204','Backend Developer',38000.00,'2025-01-07','Inactive',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(7,'E5','Shreyash','Pawar','shreyash.pawar@example.com','9876543205','UI/UX Designer',32000.00,'2025-01-09','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(8,'E6','Kunal','More','kunal.more@example.com','9876543206','HR Executive',30000.00,'2025-01-11','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(9,'E7','Mangesh','Shinde','mangesh.shinde@example.com','9876543207','Software Engineer',42000.00,'2025-01-13','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(10,'E8','Amol','Kale','amol.kale@example.com','9876543208','QA Tester',31000.00,'2025-01-15','Inactive',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(11,'E9','Harshal','Deshmukh','harshal.deshmukh@example.com','9876543209','Laravel Developer',39000.00,'2025-01-17','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(12,'E10','Truptesh','Joshi','truptesh.joshi@example.com','9876543210','Project Coordinator',40000.00,'2025-01-19','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(13,'E11','Swapanil','Kulkarni','swapanil.kulkarni@example.com','9876543211','Backend Developer',37000.00,'2025-01-21','Inactive',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(14,'E12','Kaustubh','Patil','kaustubh.patil@example.com','9876543212','PHP Developer',36000.00,'2025-01-23','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(15,'E13','Ram','More','ram.more@example.com','9876543213','Software Engineer',41000.00,'2025-01-25','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(16,'E14','Yash','Shinde','yash.shinde@example.com','9876543214','UI/UX Designer',33000.00,'2025-01-27','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(17,'E15','Yogesh','Jagtap','yogesh.jagtap@example.com','9876543215','DevOps Engineer',45000.00,'2025-01-29','Inactive',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(18,'E16','Nikhil','Pawar','nikhil.pawar@example.com','9876543216','Frontend Developer',34000.00,'2025-02-01','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(19,'E17','Pradip','Mane','pradip.mane@example.com','9876543217','HR Executive',30000.00,'2025-02-03','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(20,'E18','Sahil','Gaikwad','sahil.gaikwad@example.com','9876543218','Laravel Developer',39000.00,'2025-02-05','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(21,'E19','Sumit','Chavan','sumit.chavan@example.com','9876543219','QA Tester',32000.00,'2025-02-07','Inactive',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(22,'E20','Tushar','Bhosale','tushar.bhosale@example.com','9876543220','Backend Developer',38000.00,'2025-02-09','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(23,'E21','Jaydip','Patil','jaydip.patil@example.com','9876543221','Software Engineer',42000.00,'2025-02-11','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(24,'E22','Aditya','Sharma','aditya.sharma@example.com','9876543222','Project Manager',50000.00,'2025-02-13','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(25,'E23','Sarthak','Jadhav','sarthak.jadhav@example.com','9876543223','PHP Developer',36000.00,'2025-02-15','Inactive',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(26,'E24','Pratham','More','pratham.more@example.com','9876543224','Frontend Developer',34000.00,'2025-02-17','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53'),(27,'E25','Arbaj','Shaikh','arbaj.shaikh@example.com','9876543225','Laravel Developer',40000.00,'2025-02-19','Active',NULL,'2026-06-30 06:32:53','2026-06-30 06:32:53');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_30_052126_create_employees_table',1),(5,'2026_07_01_052835_add_profile_image_to_employees_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('GoSpVfQun3DLMopmu5Y4xnkQyQaFu76mMfim0wdq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibjJHUXJkRnIxU21STFU5ZjRoUUtsdmtoRHFzZk9WZWhRaWZsVVlCMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbXBsb3llZXMiO3M6NToicm91dGUiO3M6MTU6ImVtcGxveWVlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1782890073),('jYinuX54VEoVNfrHZmMKN4sq2Qk2SoZX3BnayZ6I',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWQzRkJtMHN5MW50M0s1S05ibmFxd21ZSXpvRnllVTJpN3prclNlRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbXBsb3llZXMvMTIvZWRpdCI7czo1OiJyb3V0ZSI7czoxNDoiZW1wbG95ZWVzLmVkaXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1782905762),('PcTgUBmZ1wGqmi6Zz6EBYVaeqCBxoOW09E0AhwB0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjBkWDZwaUlxRUV5QkVXU0NiNmI5MUtscXJyUElHaVJWcDFOUmw5dyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbXBsb3llZXMiO3M6NToicm91dGUiO3M6MTU6ImVtcGxveWVlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1782885866);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-01 17:15:45
