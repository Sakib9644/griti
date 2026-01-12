-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2025 at 10:42 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ias24x7db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `gateway` enum('stripe','paypal','manual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Web Design', 'web-design', NULL, 'active', NULL, NULL),
(2, 'Web Development', 'web-development', NULL, 'active', NULL, NULL),
(3, 'Web Deployment', 'web-deployment', NULL, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('sent','read','unread') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_m_s`
--

CREATE TABLE `c_m_s` (
  `id` bigint UNSIGNED NOT NULL,
  `page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `sub_description` longtext COLLATE utf8mb4_unicode_ci,
  `bg` longtext COLLATE utf8mb4_unicode_ci,
  `image` longtext COLLATE utf8mb4_unicode_ci,
  `btn_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_display` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `lang` enum('en','es','fr','de','it') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `slug`, `subject`, `body`, `type`, `lang`, `status`, `created_at`, `updated_at`) VALUES
(4, 'otp', 'otp', 'otp email', '<p>hi {{name}},</p><p>how are you.</p>', 'default', 'en', 'active', '2025-08-07 03:35:24', '2025-08-07 03:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('image','video','document','audio','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `size` bigint UNSIGNED DEFAULT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firebase_tokens`
--

CREATE TABLE `firebase_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f_a_q_s`
--

CREATE TABLE `f_a_q_s` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `type` enum('main','sidebar','footer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'main',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int UNSIGNED NOT NULL DEFAULT '0',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `type`, `slug`, `url`, `name`, `description`, `icon`, `order`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'main', 'main-menu', '#', 'Main Menu', NULL, NULL, 0, NULL, 1, '2025-08-07 02:31:46', '2025-08-07 02:31:46'),
(2, 'main', 'sidebar-menu', '#', 'Sidebar Menu', NULL, NULL, 0, NULL, 1, '2025-08-07 02:31:46', '2025-08-07 02:31:46'),
(3, 'main', 'footer-menu', '#', 'Footer Menu', NULL, NULL, 0, NULL, 1, '2025-08-07 02:31:46', '2025-08-07 02:31:46'),
(4, 'main', 'header-menu', '#', 'Header Menu', NULL, NULL, 0, NULL, 1, '2025-08-07 02:31:46', '2025-08-07 02:31:46'),
(5, 'main', 'custom-menu', '#', 'Custom Menu', NULL, NULL, 0, NULL, 1, '2025-08-07 02:31:46', '2025-08-07 02:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_01_000003_create_profiles_table', 1),
(5, '2024_09_16_105018_create_notifications_table', 1),
(6, '2024_11_04_082000_create_categories_table', 1),
(7, '2024_11_04_083000_create_subcategories_table', 1),
(8, '2024_11_04_083500_create_posts_table', 1),
(9, '2024_11_04_083700_create_images_table', 1),
(10, '2024_11_05_081700_create_products_table', 1),
(11, '2024_11_05_082700_create_orders_table', 1),
(12, '2024_11_05_083600_create_order_items_table', 1),
(13, '2024_11_05_083800_create_bookings_table', 1),
(14, '2024_11_05_085700_create_transactions_table', 1),
(15, '2024_11_06_041513_create_settings_table', 1),
(16, '2024_11_06_055204_create_c_m_s_table', 1),
(17, '2024_11_06_056204_create_f_a_q_s_table', 1),
(18, '2024_11_16_061214_create_firebase_tokens_table', 1),
(19, '2025_01_09_110024_create_pages_table', 1),
(20, '2025_01_10_133349_create_personal_access_tokens_table', 1),
(21, '2025_01_10_140158_create_permission_tables', 1),
(22, '2025_01_21_150830_create_subscribers_table', 1),
(23, '2025_01_21_153621_create_contacts_table', 1),
(24, '2025_01_23_063621_create_social_links_table', 1),
(25, '2025_04_15_064505_create_rooms_table', 1),
(26, '2025_04_15_064905_create_chats_table', 1),
(27, '2025_05_07_083102_create_plans_table', 1),
(28, '2025_05_07_094428_add_foreign_key_to_users_table', 1),
(29, '2025_06_05_180130_create_types_table', 1),
(30, '2025_06_06_152623_create_projects_table', 1),
(31, '2025_07_13_094453_create_templates_table', 1),
(32, '2025_07_15_101134_create_menus_table', 1),
(33, '2025_08_03_101440_create_files_table', 1),
(34, '2025_08_07_082657_create_email_templates_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 3),
(6, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 3),
(8, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 4),
(7, 'App\\Models\\User', 4),
(8, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 12),
(4, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 14),
(4, 'App\\Models\\User', 15),
(4, 'App\\Models\\User', 16),
(4, 'App\\Models\\User', 17),
(4, 'App\\Models\\User', 18),
(4, 'App\\Models\\User', 19),
(4, 'App\\Models\\User', 20),
(4, 'App\\Models\\User', 21),
(4, 'App\\Models\\User', 22),
(4, 'App\\Models\\User', 23),
(4, 'App\\Models\\User', 24),
(4, 'App\\Models\\User', 25),
(4, 'App\\Models\\User', 26),
(4, 'App\\Models\\User', 27),
(4, 'App\\Models\\User', 28),
(4, 'App\\Models\\User', 29),
(4, 'App\\Models\\User', 30),
(4, 'App\\Models\\User', 31),
(4, 'App\\Models\\User', 32),
(4, 'App\\Models\\User', 33),
(4, 'App\\Models\\User', 34),
(4, 'App\\Models\\User', 35),
(4, 'App\\Models\\User', 36),
(4, 'App\\Models\\User', 37),
(4, 'App\\Models\\User', 38),
(4, 'App\\Models\\User', 39),
(4, 'App\\Models\\User', 40),
(4, 'App\\Models\\User', 41),
(4, 'App\\Models\\User', 42),
(4, 'App\\Models\\User', 43),
(4, 'App\\Models\\User', 44),
(4, 'App\\Models\\User', 45),
(4, 'App\\Models\\User', 46),
(4, 'App\\Models\\User', 47),
(4, 'App\\Models\\User', 48),
(4, 'App\\Models\\User', 49),
(4, 'App\\Models\\User', 50),
(4, 'App\\Models\\User', 51),
(4, 'App\\Models\\User', 52),
(4, 'App\\Models\\User', 53),
(4, 'App\\Models\\User', 54),
(4, 'App\\Models\\User', 55),
(4, 'App\\Models\\User', 56),
(4, 'App\\Models\\User', 57),
(4, 'App\\Models\\User', 58),
(4, 'App\\Models\\User', 59),
(4, 'App\\Models\\User', 60),
(4, 'App\\Models\\User', 61),
(4, 'App\\Models\\User', 62),
(4, 'App\\Models\\User', 63),
(4, 'App\\Models\\User', 64),
(4, 'App\\Models\\User', 65),
(4, 'App\\Models\\User', 66),
(4, 'App\\Models\\User', 67),
(4, 'App\\Models\\User', 68),
(4, 'App\\Models\\User', 69),
(4, 'App\\Models\\User', 70),
(4, 'App\\Models\\User', 71),
(4, 'App\\Models\\User', 72),
(4, 'App\\Models\\User', 73),
(4, 'App\\Models\\User', 74),
(4, 'App\\Models\\User', 75),
(4, 'App\\Models\\User', 76),
(4, 'App\\Models\\User', 77),
(4, 'App\\Models\\User', 78),
(4, 'App\\Models\\User', 79),
(4, 'App\\Models\\User', 80),
(4, 'App\\Models\\User', 81),
(4, 'App\\Models\\User', 82),
(4, 'App\\Models\\User', 83),
(4, 'App\\Models\\User', 84),
(4, 'App\\Models\\User', 85),
(4, 'App\\Models\\User', 86),
(4, 'App\\Models\\User', 87),
(4, 'App\\Models\\User', 88),
(4, 'App\\Models\\User', 89),
(4, 'App\\Models\\User', 90),
(4, 'App\\Models\\User', 91),
(4, 'App\\Models\\User', 92),
(4, 'App\\Models\\User', 93),
(4, 'App\\Models\\User', 94),
(4, 'App\\Models\\User', 95),
(4, 'App\\Models\\User', 96),
(4, 'App\\Models\\User', 97),
(4, 'App\\Models\\User', 98),
(4, 'App\\Models\\User', 99),
(4, 'App\\Models\\User', 100);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('047b7f3a-be04-4830-95c5-e50d6aeee96a', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 40\",\"body\":\"Sample Notification Body 40\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('08ffa971-6f18-44cb-a7c4-de595b2ed9c9', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 36\",\"body\":\"Sample Notification Body 36\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('1194d4e7-ac12-4072-ae3e-4ddc7fff6f9a', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 16\",\"body\":\"Sample Notification Body 16\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('14785259-09bd-40df-8bab-bb78c35d10d1', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 15\",\"body\":\"Sample Notification Body 15\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('1866c1b7-4dcd-4de7-ba53-eab2f631e395', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 46\",\"body\":\"Sample Notification Body 46\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('1c480789-a320-4952-85e2-4316d831f5b8', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 9\",\"body\":\"Sample Notification Body 9\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('2f3f287d-42e1-40df-80bc-503212580d55', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 45\",\"body\":\"Sample Notification Body 45\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('3100fcaa-a21a-4b4e-8cfa-ef4bcd189ca3', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 29\",\"body\":\"Sample Notification Body 29\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('3282eb33-8e7d-4be0-b63d-8839db0cbf5d', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 19\",\"body\":\"Sample Notification Body 19\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('373ce28d-06f7-4d72-8534-e6e0566e0894', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 11\",\"body\":\"Sample Notification Body 11\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('4e6e34c0-c484-491f-80b9-4d53c4936e23', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 23\",\"body\":\"Sample Notification Body 23\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('51df6c55-a52e-40ca-9a4b-9abced342c07', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 1\",\"body\":\"Sample Notification Body 1\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('5e3d46ba-97d9-40ef-ae46-3442cae7a950', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 37\",\"body\":\"Sample Notification Body 37\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('62186c06-a02b-43f1-a391-d7d2d0f3f460', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 47\",\"body\":\"Sample Notification Body 47\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('67628131-5d84-489d-b18c-8fe1400a4a56', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 3\",\"body\":\"Sample Notification Body 3\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('676d47f2-9b8e-4c1f-b226-dcfa8748591a', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 13\",\"body\":\"Sample Notification Body 13\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('7234c611-a51a-4fdd-84ba-f68a8477978e', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 8\",\"body\":\"Sample Notification Body 8\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('794cfeb9-9136-436f-86bb-fb291f93e5a5', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 33\",\"body\":\"Sample Notification Body 33\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('79709dad-a738-462a-addf-4c30597d73d1', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 50\",\"body\":\"Sample Notification Body 50\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('7d4110a1-1e3d-4dce-8ef9-30db95588349', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 2\",\"body\":\"Sample Notification Body 2\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('825b9514-588a-440e-ab4e-bc3272724d0a', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 24\",\"body\":\"Sample Notification Body 24\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('8648ddc5-c19a-4cc2-a460-10525e6cb1ae', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 38\",\"body\":\"Sample Notification Body 38\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('9300fef6-35c7-4002-a09f-3d50a1dff472', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 48\",\"body\":\"Sample Notification Body 48\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('935834f4-444e-4174-9b63-2abd3fe5bc4e', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 17\",\"body\":\"Sample Notification Body 17\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('99dc5550-1bdd-44fb-87b6-82663fa2cc56', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 7\",\"body\":\"Sample Notification Body 7\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('9f28e82c-f015-4315-9850-32595cc5300c', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 18\",\"body\":\"Sample Notification Body 18\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('a546a5e2-97de-4d37-b645-b58736b35e7c', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 4\",\"body\":\"Sample Notification Body 4\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('a9144ff2-ca77-4958-99fe-c5e13c0085e1', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 22\",\"body\":\"Sample Notification Body 22\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('b06f3358-0388-4694-99c9-f7c0a263973d', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 31\",\"body\":\"Sample Notification Body 31\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('b5208b66-cac4-4aaf-b16c-d24191848476', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 34\",\"body\":\"Sample Notification Body 34\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('be3b77cb-0f52-4c1b-8322-ee69af4beb0f', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 21\",\"body\":\"Sample Notification Body 21\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('d2fa9c5d-b777-4b98-b453-5564d82caf93', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 32\",\"body\":\"Sample Notification Body 32\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('d51085df-689c-4323-940f-86688fc12650', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 39\",\"body\":\"Sample Notification Body 39\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('d51b2391-4834-4925-a294-94840556cbc3', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 5\",\"body\":\"Sample Notification Body 5\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('d54631ed-86d5-4b58-9079-05474fc7c7ac', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 28\",\"body\":\"Sample Notification Body 28\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('d6f21190-a917-419b-b325-aef852156d1d', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 35\",\"body\":\"Sample Notification Body 35\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('d9314771-42f6-486d-ba9e-642e949a99ee', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 14\",\"body\":\"Sample Notification Body 14\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('dca052b5-22f2-427b-b3b5-fc71d3bc6df0', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 30\",\"body\":\"Sample Notification Body 30\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('dd9e6333-5795-4254-9366-3a8333f78fe9', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 6\",\"body\":\"Sample Notification Body 6\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('de9c38f5-1915-4967-bade-b07f665c81a0', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 42\",\"body\":\"Sample Notification Body 42\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('e91195b1-e0e6-4cad-b416-e16471ed5367', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 44\",\"body\":\"Sample Notification Body 44\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('e94a2b3c-059e-475c-a921-32be34a5a3fd', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 12\",\"body\":\"Sample Notification Body 12\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('ec6aa748-6548-4ce2-bac4-01bd6e96c3fe', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 10\",\"body\":\"Sample Notification Body 10\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('ec7f21c9-9bb5-4b05-a124-3bc77955de27', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 26\",\"body\":\"Sample Notification Body 26\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('edb58f12-c9f6-4f3c-ab79-d1ab1995bfc6', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 41\",\"body\":\"Sample Notification Body 41\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('f09dd9f8-2407-44f3-b716-222308a046b1', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 49\",\"body\":\"Sample Notification Body 49\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('f58a2824-085e-4012-b7dc-8bac87df176e', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 2, '{\"title\":\"Sample Notification Title 27\",\"body\":\"Sample Notification Body 27\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('fa903188-1c82-462b-a65e-7ac3978346e5', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 43\",\"body\":\"Sample Notification Body 43\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('fce9fc16-b2ac-419a-b1df-2cf26828c0ae', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 3, '{\"title\":\"Sample Notification Title 25\",\"body\":\"Sample Notification Body 25\",\"id\":0}', '2025-08-07 02:31:45', '2025-08-07 02:31:45', '2025-08-07 02:31:45'),
('fef7e8c0-02b4-4c2b-bc80-69be0ac76843', 'App\\Notifications\\SampleNotification', 'App\\Models\\User', 1, '{\"title\":\"Sample Notification Title 20\",\"body\":\"Sample Notification Body 20\",\"id\":0}', NULL, '2025-08-07 02:31:45', '2025-08-07 02:31:45');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `status` enum('pending','delivered','accepted','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `meta_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `icon`, `title`, `content`, `meta_title`, `meta_description`, `meta_keywords`, `meta_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'faq', 'faq', NULL, 'this is faq page.', 'content', NULL, NULL, NULL, NULL, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'web_insert', 'web', NULL, NULL),
(2, 'web_update', 'web', NULL, NULL),
(3, 'web_delete', 'web', NULL, NULL),
(4, 'web_view', 'web', NULL, NULL),
(5, 'api_insert', 'api', NULL, NULL),
(6, 'api_update', 'api', NULL, NULL),
(7, 'api_delete', 'api', NULL, NULL),
(8, 'api_view', 'api', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `stripe_price_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `subcategory_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `dob`, `gender`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-01-01', 'male', NULL, NULL),
(2, 2, '2019-01-01', 'male', NULL, NULL),
(3, 3, '2019-01-01', 'male', NULL, NULL),
(4, 4, '2019-01-01', 'male', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `credintials` longtext COLLATE utf8mb4_unicode_ci,
  `technologies` longtext COLLATE utf8mb4_unicode_ci,
  `features` longtext COLLATE utf8mb4_unicode_ci,
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `frontend` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `backend` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'developer', 'web', NULL, NULL),
(2, 'admin', 'web', NULL, NULL),
(3, 'retailer', 'api', NULL, NULL),
(4, 'customer', 'api', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(5, 4),
(6, 4),
(7, 4),
(8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `user_one_id` bigint UNSIGNED NOT NULL,
  `user_two_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('UGeZEKZ20lGHEwiQobuWPFmF7WdgB2Uas93764QS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiVmMxMWNHUHVlSFNqU0lTU1RNYkw1aVF0SlRDMHhIR1VnMEpQR1JLVSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNzoiaHR0cHM6Ly9zdGFja21hc3Rlci50ZXN0L2FqYXgvZ2FsbGVyeSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', 1754563261);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `keywords` longtext COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copyright` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_height` int DEFAULT NULL,
  `logo_width` int DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `title`, `description`, `keywords`, `author`, `phone`, `email`, `address`, `copyright`, `logo`, `logo_height`, `logo_width`, `favicon`, `thumbnail`, `signature`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Laravel', 'Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience', 'Laravel, Framework, PHP', 'Laravel', '123456789', 'admin@admin.com', 'Cairo, Egypt', 'Copyright © 2022 Laravel. All rights reserved.', NULL, 63, 240, NULL, NULL, NULL, '2025-08-07 08:31:45', '2025-08-07 03:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sn` int NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'React', 'react', NULL, 'active', NULL, NULL),
(2, 1, 'Vue', 'vue', NULL, 'active', NULL, NULL),
(3, 2, 'Laravel', 'laravel', NULL, 'active', NULL, NULL),
(4, 3, 'AWS', 'aws', NULL, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('email','sms','notification') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `type` enum('increment','decrement') COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway` enum('stripe','paypal','manual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL,
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `title`, `trx_id`, `user_id`, `amount`, `currency`, `type`, `gateway`, `metadata`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Deposit', 'trx-123', 2, 1000, 'USD', 'increment', 'manual', NULL, 'success', '2025-08-06 02:31:45', NULL),
(2, 'Withdraw', 'trx-456', 2, 1000, 'USD', 'decrement', 'manual', NULL, 'success', '2025-08-06 02:31:46', NULL),
(3, 'Deposit', 'trx-789', 2, 2000, 'USD', 'increment', 'manual', NULL, 'success', '2025-08-06 02:31:46', NULL),
(4, 'Withdraw', 'trx-1011', 2, 5000, 'USD', 'increment', 'manual', NULL, 'success', '2025-08-06 02:31:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `otp_verified_at` timestamp NULL DEFAULT NULL,
  `reset_password_token` longtext COLLATE utf8mb4_unicode_ci,
  `reset_password_token_expire_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `slug`, `email`, `otp`, `otp_expires_at`, `otp_verified_at`, `reset_password_token`, `reset_password_token_expire_at`, `avatar`, `password`, `last_activity_at`, `stripe_customer_id`, `stripe_account_id`, `stripe_subscription_id`, `plan_id`, `status`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'developer', 'developer', 'developer@developer.com', NULL, NULL, '2025-08-07 02:31:20', NULL, NULL, NULL, '$2y$12$qh7nVoe1aijHC0LMkkUFx.Z2RVjvANuDYL73klWC3QEvwI4IezvS6', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(2, 'Admin', 'admin', 'admin@admin.com', NULL, NULL, '2025-08-07 02:31:21', NULL, NULL, NULL, '$2y$12$PWNPPaSip6zrcGJ5R./Yb.XFrcjavSbLiuN95PTYuLTRqvgRSpOh.', '2025-08-07 04:41:01', NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, '2025-08-07 04:41:01'),
(3, 'Retailer', 'retailer', 'retailer@retailer.com', NULL, NULL, '2025-08-07 02:31:21', NULL, NULL, NULL, '$2y$12$09Kvr7zDyrGL4/5tfkMaleMXfrNIWPb6za32ZIgay//Mcz/4rFxKq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(4, 'Customer', 'customer', 'customer@customer.com', NULL, NULL, '2025-08-07 02:31:21', NULL, NULL, NULL, '$2y$12$VkQhTX10qNq/j879io3INOMGReMnLBLYOFUFnAxSvn/ipB/bbmioa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(5, 'User 5', 'user-5', 'user5@example.com', NULL, NULL, '2025-08-07 02:31:21', NULL, NULL, NULL, '$2y$12$ljdmaBBTD8Oxw1DMDwKrhO3oAKRDq1354wD55vvobVZq63HatSDr.', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(6, 'User 6', 'user-6', 'user6@example.com', NULL, NULL, '2025-08-07 02:31:22', NULL, NULL, NULL, '$2y$12$v3mKDvob1BFL6JsFyDZ1hua1dsa4cEFOBsVljg6DwWsV4FvDGOzGW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(7, 'User 7', 'user-7', 'user7@example.com', NULL, NULL, '2025-08-07 02:31:22', NULL, NULL, NULL, '$2y$12$1u5K9uiDeVM5mawBlA75x.8TKj1tgFOJEGxsfFnTq/tv.B9J0W8Mq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(8, 'User 8', 'user-8', 'user8@example.com', NULL, NULL, '2025-08-07 02:31:22', NULL, NULL, NULL, '$2y$12$olgjqnJ6AqondQaXegSfou1e/aNutU1TtUNrs243h4Okq1T7MrIPC', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(9, 'User 9', 'user-9', 'user9@example.com', NULL, NULL, '2025-08-07 02:31:22', NULL, NULL, NULL, '$2y$12$eSOEYXZn8pMDaEhbLCgOiuJATFtvWHraQ4H.LP.hVXZgYwkor/5/a', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(10, 'User 10', 'user-10', 'user10@example.com', NULL, NULL, '2025-08-07 02:31:23', NULL, NULL, NULL, '$2y$12$yhlFDVnR0siJaN71Y5bB0.YVZrVMQWe5a/JB4eRte2oqmC1bEDam.', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(11, 'User 11', 'user-11', 'user11@example.com', NULL, NULL, '2025-08-07 02:31:23', NULL, NULL, NULL, '$2y$12$sqHZQ0nP/yV6YJqCWGnFT.Q4oBemikSylZVoP1UyuBzJl7zqiNZ8m', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(12, 'User 12', 'user-12', 'user12@example.com', NULL, NULL, '2025-08-07 02:31:23', NULL, NULL, NULL, '$2y$12$GA.69M5koSNGQr0iWQrmlOOGYQaT1AMEtcDmkhuSuiavnN2Fy.9g.', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(13, 'User 13', 'user-13', 'user13@example.com', NULL, NULL, '2025-08-07 02:31:23', NULL, NULL, NULL, '$2y$12$px/uWz26P83GXOydYDRXl.bXFMNt8jzc4OgMtjK0ArTLzv2pPbXEa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(14, 'User 14', 'user-14', 'user14@example.com', NULL, NULL, '2025-08-07 02:31:24', NULL, NULL, NULL, '$2y$12$nhpnf87NK4elm3gbbPv0d.B0CqBa1Yy.usAqZXo.iSlX1yNkIWMXa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(15, 'User 15', 'user-15', 'user15@example.com', NULL, NULL, '2025-08-07 02:31:24', NULL, NULL, NULL, '$2y$12$AYjVDZSzjzNcz1.CUHQE2eHQLngy00k7vyVYk/W5GcRjH.RzQo4SW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(16, 'User 16', 'user-16', 'user16@example.com', NULL, NULL, '2025-08-07 02:31:24', NULL, NULL, NULL, '$2y$12$V9PiUIyCGg0aKgrE4HIci.nB6qLi5MzOaTjk57p7SCWVDjrIiptsa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(17, 'User 17', 'user-17', 'user17@example.com', NULL, NULL, '2025-08-07 02:31:24', NULL, NULL, NULL, '$2y$12$Traw8xo5XkTLQWNC/jvqKeYgN7TQfDOq1JYP.n3PEq2fOE4zJhU.S', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(18, 'User 18', 'user-18', 'user18@example.com', NULL, NULL, '2025-08-07 02:31:25', NULL, NULL, NULL, '$2y$12$Hibmskj.wCqUZ9OywHA4e.WifuYh3CUewniEBtMa1JySuXYs1ytU.', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(19, 'User 19', 'user-19', 'user19@example.com', NULL, NULL, '2025-08-07 02:31:25', NULL, NULL, NULL, '$2y$12$iGT5xzbnbl6eGJCoFshdWuzim2J6GIWX6UTnj3WsTl8owEeQ8M0pa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(20, 'User 20', 'user-20', 'user20@example.com', NULL, NULL, '2025-08-07 02:31:25', NULL, NULL, NULL, '$2y$12$zXIIq3MyEdt.VW3fW8yJguSukNYHkpLV1gLK4p/FPUATrrZbdbDDe', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(21, 'User 21', 'user-21', 'user21@example.com', NULL, NULL, '2025-08-07 02:31:25', NULL, NULL, NULL, '$2y$12$RvyIBSLt4LrmQCje67OfW.wZLyh/7A.QPLhR1rtVBVPtUcF8Oe8Eq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(22, 'User 22', 'user-22', 'user22@example.com', NULL, NULL, '2025-08-07 02:31:26', NULL, NULL, NULL, '$2y$12$NFgRYAh8XwXBpfteX7wS5OhhfYrZScja9U98zUGUyjyRx2iBHqcpa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(23, 'User 23', 'user-23', 'user23@example.com', NULL, NULL, '2025-08-07 02:31:26', NULL, NULL, NULL, '$2y$12$aBlXS.TfbZsayG5nQkYgtuZbiRmQXsuzI1bv8aNc0K2Tr5smU2J7O', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(24, 'User 24', 'user-24', 'user24@example.com', NULL, NULL, '2025-08-07 02:31:26', NULL, NULL, NULL, '$2y$12$u3BXczMuZwFTdbeVHp75IeXhfsS8AD/pKg6Z7gpZBCk9LTuJlNdTm', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(25, 'User 25', 'user-25', 'user25@example.com', NULL, NULL, '2025-08-07 02:31:26', NULL, NULL, NULL, '$2y$12$ipuuhKOt9DejGVery6Z92OT7LkTO0JVZffYb4S41viTw/h.IWSI8W', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(26, 'User 26', 'user-26', 'user26@example.com', NULL, NULL, '2025-08-07 02:31:27', NULL, NULL, NULL, '$2y$12$Y96g0y0DDBDRxpJs2I1hment8h2txFENMORhRjy8bk0B8bh8GHAY2', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(27, 'User 27', 'user-27', 'user27@example.com', NULL, NULL, '2025-08-07 02:31:27', NULL, NULL, NULL, '$2y$12$JTIb2Mea/gUJa8hvpRBG.eu0rI.uogH0ouQ0vetTbE6ioS3FGjCh2', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(28, 'User 28', 'user-28', 'user28@example.com', NULL, NULL, '2025-08-07 02:31:27', NULL, NULL, NULL, '$2y$12$iwnS.JePYRO1OdAjGs7aUeRvk2qP.Mm0ah5goJMRB225LHwXlQpOK', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(29, 'User 29', 'user-29', 'user29@example.com', NULL, NULL, '2025-08-07 02:31:27', NULL, NULL, NULL, '$2y$12$CUwwLTfWY4HBvq2VyuPPdOozF9x97ZqBc7sS0zNpIBOGIjYVqV/RK', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(30, 'User 30', 'user-30', 'user30@example.com', NULL, NULL, '2025-08-07 02:31:27', NULL, NULL, NULL, '$2y$12$CXrLG8EkNqUpt2oFV56WZO9EsZQ44w7dN9T7wSfFh6dtiFrY6Xkti', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(31, 'User 31', 'user-31', 'user31@example.com', NULL, NULL, '2025-08-07 02:31:28', NULL, NULL, NULL, '$2y$12$qQyzO56EKUNKehu7ZEqa8.6Id5RXnQHTJe7EEH.AiSLT3vR6nZlWm', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(32, 'User 32', 'user-32', 'user32@example.com', NULL, NULL, '2025-08-07 02:31:28', NULL, NULL, NULL, '$2y$12$NCpoOFCsKOJzC5dwBFH9Xua3LdoOysEY6tT3Ff/t9n5n7gRk1GAa6', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(33, 'User 33', 'user-33', 'user33@example.com', NULL, NULL, '2025-08-07 02:31:28', NULL, NULL, NULL, '$2y$12$ClxlDu..bZYKPF.nttex1.JKygkep1/F8jo.XBMU8LV78rwEUdq/O', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(34, 'User 34', 'user-34', 'user34@example.com', NULL, NULL, '2025-08-07 02:31:28', NULL, NULL, NULL, '$2y$12$8ZnCJDclIzQgIMPwr.omZuAcBf.JUsaYt1TtvGJDmZT8aumAAHBiq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(35, 'User 35', 'user-35', 'user35@example.com', NULL, NULL, '2025-08-07 02:31:29', NULL, NULL, NULL, '$2y$12$7pIJj.HqsZD2PedybI7kIuJyG8yuhZnkaedk4YRvj/RdnVerDDgsK', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(36, 'User 36', 'user-36', 'user36@example.com', NULL, NULL, '2025-08-07 02:31:29', NULL, NULL, NULL, '$2y$12$2KNP0e4Pv0e5OvMM8fTALuJpt/eaoRNUqw3/DBPIzqKJB9.IjzgGq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(37, 'User 37', 'user-37', 'user37@example.com', NULL, NULL, '2025-08-07 02:31:29', NULL, NULL, NULL, '$2y$12$ofWZaBDYKluDnp3CHFSsxO3dzebM7R84xYGreDGRyaNXxebd0341e', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(38, 'User 38', 'user-38', 'user38@example.com', NULL, NULL, '2025-08-07 02:31:29', NULL, NULL, NULL, '$2y$12$Q6f73xC3zETReBOMpw3T2Of0Ta0YNJQmdBxfcEFy1/30Xgb0zRN6i', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(39, 'User 39', 'user-39', 'user39@example.com', NULL, NULL, '2025-08-07 02:31:30', NULL, NULL, NULL, '$2y$12$k.5Ac1n46bhklKKzrSJsjOovA2gIaksamRR.xOdJJra2zx9QgIHmS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(40, 'User 40', 'user-40', 'user40@example.com', NULL, NULL, '2025-08-07 02:31:30', NULL, NULL, NULL, '$2y$12$AWdRF6W1a4cqKKu2iKdTzuqaB9suSG08.TecoTKGNi1QfW7FR4D0W', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(41, 'User 41', 'user-41', 'user41@example.com', NULL, NULL, '2025-08-07 02:31:30', NULL, NULL, NULL, '$2y$12$oFyLNB3Fh9kX4KUFjlZvoucUoRll2RtEAa5pHF0CEAGixwd0t7mNa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(42, 'User 42', 'user-42', 'user42@example.com', NULL, NULL, '2025-08-07 02:31:30', NULL, NULL, NULL, '$2y$12$ZuVf8Ce/yEL1tNbAstiywe1T41dmtNy2nF.ZRSaRIJCNLnr9zYpM.', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(43, 'User 43', 'user-43', 'user43@example.com', NULL, NULL, '2025-08-07 02:31:31', NULL, NULL, NULL, '$2y$12$.8FNL6zegjEZ9MHSJYS88OAgHwf2Y8aWFpc2deGrp4x5JNtoQS1wG', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(44, 'User 44', 'user-44', 'user44@example.com', NULL, NULL, '2025-08-07 02:31:31', NULL, NULL, NULL, '$2y$12$n3WD7Go3n8LOi0PaJpnwOuOihnJIABSEsaqDpGAjnrh1onBX5Dsku', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(45, 'User 45', 'user-45', 'user45@example.com', NULL, NULL, '2025-08-07 02:31:31', NULL, NULL, NULL, '$2y$12$P1TXH6fjTUpVqGF01ocUIOhiWHrxS58FNQVtOIlFCtkK4IVQvevW.', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(46, 'User 46', 'user-46', 'user46@example.com', NULL, NULL, '2025-08-07 02:31:31', NULL, NULL, NULL, '$2y$12$.nzL7TdHiSg/X2L1F9hIleV9v9uYpqXRuA82Q4/NwH2YsR8XMVr/W', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(47, 'User 47', 'user-47', 'user47@example.com', NULL, NULL, '2025-08-07 02:31:32', NULL, NULL, NULL, '$2y$12$SX1VJtxvDkM.ajgG54NNjulc4KN2.lZdbqoL4cvcsANnxKjQNM6JW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(48, 'User 48', 'user-48', 'user48@example.com', NULL, NULL, '2025-08-07 02:31:32', NULL, NULL, NULL, '$2y$12$YAlQ0UsZqFiQJ.1Eqd3/pezXtzkQNxEo3CvI4mfhLSb.i1TUVkGkK', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(49, 'User 49', 'user-49', 'user49@example.com', NULL, NULL, '2025-08-07 02:31:32', NULL, NULL, NULL, '$2y$12$MsW9ntQ2ytKYs1wZ/ebEku4qvJ.ODJRIIpwG96SyI6J.PW8DPX7EG', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(50, 'User 50', 'user-50', 'user50@example.com', NULL, NULL, '2025-08-07 02:31:32', NULL, NULL, NULL, '$2y$12$J2/dQdwQZJnTE0ohqdEfA.8WE4WsSjG/Vxiy9KEaS8iUxyVMDTTbO', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(51, 'User 51', 'user-51', 'user51@example.com', NULL, NULL, '2025-08-07 02:31:33', NULL, NULL, NULL, '$2y$12$fHZxWrShBz/bsOZiF6NaQ..dHooScGR4nGD6W5jRRa9T.T6kNA0zy', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(52, 'User 52', 'user-52', 'user52@example.com', NULL, NULL, '2025-08-07 02:31:33', NULL, NULL, NULL, '$2y$12$iJjJuqcvYDuV4vmA3Q.Pxecs.lKrIHEVHuQoicxdKFQo9yuY9YJ7e', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(53, 'User 53', 'user-53', 'user53@example.com', NULL, NULL, '2025-08-07 02:31:33', NULL, NULL, NULL, '$2y$12$doZ3gOs2vOvwAAfBhd3Y/.HwSG1wN1YKPRbBRdSafM7XgKGHaNOeq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(54, 'User 54', 'user-54', 'user54@example.com', NULL, NULL, '2025-08-07 02:31:33', NULL, NULL, NULL, '$2y$12$.VVkmdzUZ16idJniSY33hup6c2vqrh9XkydYWwgsFGktwtry/Y8pi', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(55, 'User 55', 'user-55', 'user55@example.com', NULL, NULL, '2025-08-07 02:31:34', NULL, NULL, NULL, '$2y$12$Rqc/agl9q8bW1mvX4.ZE5evQWQSsuLfMBBmG0RKT9O0BNmhUOFOhW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(56, 'User 56', 'user-56', 'user56@example.com', NULL, NULL, '2025-08-07 02:31:34', NULL, NULL, NULL, '$2y$12$Od9rjD.w5E9uyHQFOX0Hbei.1sGM0dj2BjELXfHYJrPxoLcWBYgMy', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(57, 'User 57', 'user-57', 'user57@example.com', NULL, NULL, '2025-08-07 02:31:34', NULL, NULL, NULL, '$2y$12$3gdQZwiROChAPM/G.qVk9eZDg2CcXShPY3q7Kdx6WF7KOnz7HntXG', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(58, 'User 58', 'user-58', 'user58@example.com', NULL, NULL, '2025-08-07 02:31:34', NULL, NULL, NULL, '$2y$12$ic6zGxnkgbf.eEA7MRwYZeVGuo68mbZCDapI7pJN83CS81VTGr5LG', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(59, 'User 59', 'user-59', 'user59@example.com', NULL, NULL, '2025-08-07 02:31:35', NULL, NULL, NULL, '$2y$12$suw6DODmRhJARThk9BEzoe0LKdraNjkuOcXoLKEBXE0y.J4WSEaNS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(60, 'User 60', 'user-60', 'user60@example.com', NULL, NULL, '2025-08-07 02:31:35', NULL, NULL, NULL, '$2y$12$/5TSYdL1XTgQym3O070pU.enQw1E6on6JCkS4zSk2WM8g1vq1kvbW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(61, 'User 61', 'user-61', 'user61@example.com', NULL, NULL, '2025-08-07 02:31:35', NULL, NULL, NULL, '$2y$12$jFCBKtxES6dteGBGPNMzS.906eHtvkvbz2chNIB4DVitOxuLeY7Fa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(62, 'User 62', 'user-62', 'user62@example.com', NULL, NULL, '2025-08-07 02:31:35', NULL, NULL, NULL, '$2y$12$arP7J8j2dN.XevDaBJm1Bea/DFDvq3MNStCJqOxE636DDkDI7PZdW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(63, 'User 63', 'user-63', 'user63@example.com', NULL, NULL, '2025-08-07 02:31:36', NULL, NULL, NULL, '$2y$12$BbHLCnfb/MadciFlQLhvmOF9BZz8T/5clDp9niIPUCpzLl9blf7mu', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(64, 'User 64', 'user-64', 'user64@example.com', NULL, NULL, '2025-08-07 02:31:36', NULL, NULL, NULL, '$2y$12$JR8h/BjGT7svG94TFixzde3L/0PkLEKc4r5NTg3PvGSUCGI9dyXMS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(65, 'User 65', 'user-65', 'user65@example.com', NULL, NULL, '2025-08-07 02:31:36', NULL, NULL, NULL, '$2y$12$ulvl5IwZi9N3Rfwq3YTiO.x5Rn2IBOjA9m2qxC9cgUn6X7WL4/jIa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(66, 'User 66', 'user-66', 'user66@example.com', NULL, NULL, '2025-08-07 02:31:36', NULL, NULL, NULL, '$2y$12$W2QBTSm8UwJS1r7VCQGGsu6/rVITfWv9pA/tutX/kyoiOUscEo.Ja', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(67, 'User 67', 'user-67', 'user67@example.com', NULL, NULL, '2025-08-07 02:31:37', NULL, NULL, NULL, '$2y$12$GpLZXadb0Vp8egMwUQ44auDjOgGukYV1YzY5bka5gmasbKakPedvi', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(68, 'User 68', 'user-68', 'user68@example.com', NULL, NULL, '2025-08-07 02:31:37', NULL, NULL, NULL, '$2y$12$PkY6s9pLqLPnc/wRO4sCZ.RuCSHjad9MWbxver9KAKOKuFzsp5yBa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(69, 'User 69', 'user-69', 'user69@example.com', NULL, NULL, '2025-08-07 02:31:37', NULL, NULL, NULL, '$2y$12$ic.OQw0XL9sT/jwQjjTCmejqDeLs1fXqiYwIG81Tcqk6AbbPU9Te6', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(70, 'User 70', 'user-70', 'user70@example.com', NULL, NULL, '2025-08-07 02:31:37', NULL, NULL, NULL, '$2y$12$vtbf2TQ0hnsvpKewL0ls9Oc5bBGhHj5Nwnhcm/QTe7leISNonNCaS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(71, 'User 71', 'user-71', 'user71@example.com', NULL, NULL, '2025-08-07 02:31:38', NULL, NULL, NULL, '$2y$12$x3oZE8YDvhvC9vmNwrmat.oDPlHiu22f2vNtXV.o96J30DebD.IGW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(72, 'User 72', 'user-72', 'user72@example.com', NULL, NULL, '2025-08-07 02:31:38', NULL, NULL, NULL, '$2y$12$NgyiujSxnRR74BDlvDfAMeddNJkBYJmqsFf7CGFgmthRedBRrQGWu', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(73, 'User 73', 'user-73', 'user73@example.com', NULL, NULL, '2025-08-07 02:31:38', NULL, NULL, NULL, '$2y$12$xRntgrWhfTGVaaLL3j/x7.iS2kNW5AC99NS6aGs7Jcf3Zjj8RPnU6', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(74, 'User 74', 'user-74', 'user74@example.com', NULL, NULL, '2025-08-07 02:31:38', NULL, NULL, NULL, '$2y$12$Ra7DdsIK.RLryE9IfQ74LOuAM923VNCoDGOjz2lPJ8e1zr.SOUob2', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(75, 'User 75', 'user-75', 'user75@example.com', NULL, NULL, '2025-08-07 02:31:38', NULL, NULL, NULL, '$2y$12$KWsjs9ZbmOquxteVjGkXbuPWtWZqWc2QAFXP.Nt4025lb2Hrncq7W', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(76, 'User 76', 'user-76', 'user76@example.com', NULL, NULL, '2025-08-07 02:31:39', NULL, NULL, NULL, '$2y$12$RrnMZNcQ9.3ju3FTiUwSaeGB0yHT9hgF7Px5E66Vc9K5Gv.VfdvpO', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(77, 'User 77', 'user-77', 'user77@example.com', NULL, NULL, '2025-08-07 02:31:39', NULL, NULL, NULL, '$2y$12$jKBKax6oJI5a3L5tj25gT.YorZzTgJAWlPJOPKR5G5DioVx.2Piyq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(78, 'User 78', 'user-78', 'user78@example.com', NULL, NULL, '2025-08-07 02:31:39', NULL, NULL, NULL, '$2y$12$u7IyAnwzAUcslpLTP6BkP.bLHpC2w4d5wzuw52kcc58RgycbR6jPS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(79, 'User 79', 'user-79', 'user79@example.com', NULL, NULL, '2025-08-07 02:31:39', NULL, NULL, NULL, '$2y$12$IGWLC36IHF3hkrFvud2XGeZFuwTV6obLuiKLqQNBgjfkloaXDkxR6', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(80, 'User 80', 'user-80', 'user80@example.com', NULL, NULL, '2025-08-07 02:31:40', NULL, NULL, NULL, '$2y$12$izZQ.ETpfQDir0mfwZ9kdOoLlV.mDGD3zV/4046VAvO579zYajoVS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(81, 'User 81', 'user-81', 'user81@example.com', NULL, NULL, '2025-08-07 02:31:40', NULL, NULL, NULL, '$2y$12$Wge97VCpxJ9N91Roge8HKuyCdDdjAkHxoNK.d5ISJT.R6ZPbfK8S2', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(82, 'User 82', 'user-82', 'user82@example.com', NULL, NULL, '2025-08-07 02:31:40', NULL, NULL, NULL, '$2y$12$i1N1J7iL3A1C8GcoL9VuH.gcSQs2Y7NqFw7rQH/bdtX3g6TX8nEde', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(83, 'User 83', 'user-83', 'user83@example.com', NULL, NULL, '2025-08-07 02:31:40', NULL, NULL, NULL, '$2y$12$DZ/tLgE86t5aUQbRoGwHE.p4IkC/OrCc/KV2ImQHIwd9M8CgDL8iS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(84, 'User 84', 'user-84', 'user84@example.com', NULL, NULL, '2025-08-07 02:31:41', NULL, NULL, NULL, '$2y$12$Te.BTmZrE1vZL4ADARI3QungFz99MOdC2Az5EVDRPBIwE7QghgZJq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(85, 'User 85', 'user-85', 'user85@example.com', NULL, NULL, '2025-08-07 02:31:41', NULL, NULL, NULL, '$2y$12$k7inpqmgQmLTNJTDt9bJIuLmJlRZ5yH3XCC1putjCYvTnz1UzF3Qi', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(86, 'User 86', 'user-86', 'user86@example.com', NULL, NULL, '2025-08-07 02:31:41', NULL, NULL, NULL, '$2y$12$PM8aAYBY8UplreYVMlVu..I.HYF2gFVzzksW4iPTgNRxRe7f1rTIC', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(87, 'User 87', 'user-87', 'user87@example.com', NULL, NULL, '2025-08-07 02:31:41', NULL, NULL, NULL, '$2y$12$BGH13TQlWvAie.LTLLt5D.ZLwT11Q9B2Vt8K0Pimq60P.REcWQIFq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(88, 'User 88', 'user-88', 'user88@example.com', NULL, NULL, '2025-08-07 02:31:42', NULL, NULL, NULL, '$2y$12$rnwPp4G5KcPqyzCTx3hVsOl1Pws3HMmT1nWebxzDQ0qBidemv/2Le', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(89, 'User 89', 'user-89', 'user89@example.com', NULL, NULL, '2025-08-07 02:31:42', NULL, NULL, NULL, '$2y$12$Vaio/qnt4v7SjAxixtDSV.j73ploo3S0x2L6KIxwCcL/h0xxMchVi', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(90, 'User 90', 'user-90', 'user90@example.com', NULL, NULL, '2025-08-07 02:31:42', NULL, NULL, NULL, '$2y$12$C.bOZUp/yLEhEbDplW4XNu1oCFDoCOWQLNg5.qw6KtW2ugibyjxrq', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(91, 'User 91', 'user-91', 'user91@example.com', NULL, NULL, '2025-08-07 02:31:42', NULL, NULL, NULL, '$2y$12$JOC4tgPhPr/JtS/L8WNgS.Qe8vvfIU4EGghrgcP2VjaqhTva62cVu', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(92, 'User 92', 'user-92', 'user92@example.com', NULL, NULL, '2025-08-07 02:31:43', NULL, NULL, NULL, '$2y$12$8urSwDhKERcinIGIxp/Tkea.W9uDzI6Z2IPcqcMWgIZumEsX3XMRW', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(93, 'User 93', 'user-93', 'user93@example.com', NULL, NULL, '2025-08-07 02:31:43', NULL, NULL, NULL, '$2y$12$P7qgCKX0XVAD5.hILbOHL.1KfiYBdpRS3MvF9s497IGlqPeMsubt6', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(94, 'User 94', 'user-94', 'user94@example.com', NULL, NULL, '2025-08-07 02:31:43', NULL, NULL, NULL, '$2y$12$RY8Ur/yQr2iHwoju8M026OvsLFHt0p.MFon23h9CGHETNlIOsVUtm', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(95, 'User 95', 'user-95', 'user95@example.com', NULL, NULL, '2025-08-07 02:31:43', NULL, NULL, NULL, '$2y$12$vAiyIgkAg5c3Jc4ld6C8MOm4jFFQI7hnxBiy4xtv3P3dWteEKHwpe', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(96, 'User 96', 'user-96', 'user96@example.com', NULL, NULL, '2025-08-07 02:31:44', NULL, NULL, NULL, '$2y$12$vz9bHTvDycwl3/CEBDVTQ.IT7Rudl/iWVOBQGdw6ntdn1T729QFQa', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(97, 'User 97', 'user-97', 'user97@example.com', NULL, NULL, '2025-08-07 02:31:44', NULL, NULL, NULL, '$2y$12$01D22or5dpmMs6UNcUGuU.YkskaqpkQpLzhVJpbaJspipe6Q8jOVK', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(98, 'User 98', 'user-98', 'user98@example.com', NULL, NULL, '2025-08-07 02:31:44', NULL, NULL, NULL, '$2y$12$6gDXmErEomEhGU9vHiYy2urYKS1OT1J2jQBERdjN1fjNKBfBg0tFi', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(99, 'User 99', 'user-99', 'user99@example.com', NULL, NULL, '2025-08-07 02:31:44', NULL, NULL, NULL, '$2y$12$Pu.NC2Sn8VDCj.L73uhwNeyCgw1EXHTu0q7sq15jFFZ8e1xRdcDhy', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL),
(100, 'User 100', 'user-100', 'user100@example.com', NULL, NULL, '2025-08-07 02:31:45', NULL, NULL, NULL, '$2y$12$eh2oH.aG/NujNW7m8lWaN.i.cAHp5lO8fS23jmu6HE7MI.05KWZwS', NULL, NULL, 'acct_1RHGjbQPESrwz7hv', NULL, NULL, 'active', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_order_id_foreign` (`order_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_sender_id_foreign` (`sender_id`),
  ADD KEY `chats_receiver_id_foreign` (`receiver_id`),
  ADD KEY `chats_room_id_foreign` (`room_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `c_m_s`
--
ALTER TABLE `c_m_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_templates_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `firebase_tokens`
--
ALTER TABLE `firebase_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firebase_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `f_a_q_s`
--
ALTER TABLE `f_a_q_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_post_id_foreign` (`post_id`);

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
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_slug_unique` (`slug`),
  ADD KEY `menus_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_uid_unique` (`uid`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_category_id_foreign` (`category_id`),
  ADD KEY `posts_subcategory_id_foreign` (`subcategory_id`),
  ADD KEY `posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_user_id_foreign` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_user_id_foreign` (`user_id`),
  ADD KEY `projects_type_id_foreign` (`type_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rooms_user_one_id_user_two_id_unique` (`user_one_id`,`user_two_id`),
  ADD KEY `rooms_user_two_id_foreign` (`user_two_id`);

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
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_links_sn_unique` (`sn`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_category_id_foreign` (`category_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_unique` (`email`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `templates_slug_unique` (`slug`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_slug_unique` (`slug`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_plan_id_foreign` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `c_m_s`
--
ALTER TABLE `c_m_s`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `firebase_tokens`
--
ALTER TABLE `firebase_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `f_a_q_s`
--
ALTER TABLE `f_a_q_s`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chats_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `firebase_tokens`
--
ALTER TABLE `firebase_tokens`
  ADD CONSTRAINT `firebase_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_user_one_id_foreign` FOREIGN KEY (`user_one_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_user_two_id_foreign` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
