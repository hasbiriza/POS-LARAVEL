-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2024 at 03:51 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `branch` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `account_number` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `account_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `swift_code` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `credit` double(20,2) NOT NULL DEFAULT '0.00',
  `debit` double(20,2) NOT NULL DEFAULT '0.00',
  `saldo` double(20,2) DEFAULT NULL,
  `logo` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `name`, `branch`, `account_number`, `account_name`, `swift_code`, `credit`, `debit`, `saldo`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'BCA', 'UTAN KAYU', '123456789', 'MUKHLIS HDAYAT', '-', 0.00, 0.00, 0.00, NULL, '2024-10-24 13:39:44', '2024-10-24 13:39:44'),
(2, 'MANDIRI', 'MATRAMAN', '00001234567', 'BUDIANA', '-', 0.00, 0.00, 0.00, NULL, '2024-10-24 13:40:59', '2024-10-24 13:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Nike', 'Brand fashion olahraga terkenal', 'logo_nike.png', '2024-10-19 13:57:53', '2024-10-19 13:57:53'),
(2, 'Adidas', 'Brand fashion olahraga internasional', 'logo_adidas.png', '2024-10-19 13:57:53', '2024-10-19 13:57:53'),
(3, 'Samsung', 'Brand elektronik dengan produk smartphone dan elektronik', 'logo_samsung.png', '2024-10-19 13:57:53', '2024-10-19 13:57:53'),
(4, 'Nestle', 'Brand makanan dengan berbagai produk makanan dan minuman', 'logo_nestle.png', '2024-10-19 13:57:53', '2024-10-19 13:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('articflap@gmail.com|127.0.0.1', 'i:1;', 1731944601),
('articflap@gmail.com|127.0.0.1:timer', 'i:1731944601;', 1731944601);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Fashion', NULL, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(2, 'Elektronik', NULL, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(3, 'Makanan & Minuman', NULL, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(4, 'Pakaian Pria', 1, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(5, 'Pakaian Wanita', 1, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(6, 'Smartphone', 2, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(7, 'Peralatan Dapur', 2, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(8, 'Makanan Instan', 3, '2024-10-19 13:58:49', '2024-10-19 13:58:49'),
(9, 'Minuman', 3, '2024-10-19 13:58:49', '2024-10-19 13:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'guest', 'guest@example.com', '1234567890', '123 Tamu St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(2, 'Joko Widodo', 'joko.w@example.com', '08123456789', '456 Merdeka St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(3, 'Siti Aisyah', 'siti.a@example.com', '08234567890', '789 Kebangsaan St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(4, 'Budi Santoso', 'budi.s@example.com', '08345678901', '101 Cinta St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(5, 'Dewi Lestari', 'dewi.l@example.com', '08456789012', '202 Harapan St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(6, 'Agus Salim', 'agus.s@example.com', '08567890123', '303 Persatuan St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(7, 'Rina Fatimah', 'rina.f@example.com', '08678901234', '404 Kesatuan St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(8, 'Fajar Nugraha', 'fajar.n@example.com', '08789012345', '505 Kemandirian St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(9, 'Putri Ayu', 'putri.a@example.com', '08890123456', '606 Karya St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32'),
(10, 'Rudi Hartono', 'rudi.h@example.com', '08901234567', '707 Semangat St, Jakarta', '2024-10-19 15:17:32', '2024-10-19 15:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cash','bank_transfer','tempo') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `total_amount` double NOT NULL,
  `remaining_amount` double NOT NULL,
  `status` enum('lunas','belum lunas') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `expense_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` bigint UNSIGNED NOT NULL,
  `expense_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `expense_category_id` int NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `customer_id` int DEFAULT '0',
  `amount` double NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_payment`
--

CREATE TABLE `expense_payment` (
  `id` bigint UNSIGNED NOT NULL,
  `expense_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cash','bank_transfer') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `amount_paid` double NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layouts`
--

CREATE TABLE `layouts` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `layouts`
--

INSERT INTO `layouts` (`id`, `name`, `description`) VALUES
(1, 'Layout 1', 'ini layout 1'),
(2, 'Layout 2', 'ini layout 2'),
(3, 'Layout 3', 'ini layout 3 dengan dominan warna biru');

-- --------------------------------------------------------

--
-- Table structure for table `layout_settings`
--

CREATE TABLE `layout_settings` (
  `id` int NOT NULL,
  `layout_id` int DEFAULT NULL,
  `sidebar_color` varchar(7) DEFAULT NULL,
  `menu_link_color` varchar(7) DEFAULT NULL,
  `menu_link_hover_color` varchar(7) DEFAULT NULL,
  `app_brand_color` varchar(7) DEFAULT NULL,
  `navbar_color` varchar(7) DEFAULT NULL,
  `button_color` varchar(7) DEFAULT NULL,
  `button_hover_color` varchar(7) DEFAULT NULL,
  `fonts` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `layout_settings`
--

INSERT INTO `layout_settings` (`id`, `layout_id`, `sidebar_color`, `menu_link_color`, `menu_link_hover_color`, `app_brand_color`, `navbar_color`, `button_color`, `button_hover_color`, `fonts`) VALUES
(15, 1, '#0F172A', '#E0E7FF', '#93C5FD', '#FF6F61', '#1E3A8A', '#3B82F6', '#60A5FA', 'Poppins, sans-serif'),
(16, 2, '#2F855A', '#F0FFF4', '#68D391', '#48BB78', '#276749', '#38A169', '#81E6D9', 'Lora, serif'),
(17, 3, '#1A1F24', '#ECF0F1', '#3498DB', '#E67E22', '#34495E', '#E74C3C', '#C0392B', 'Roboto, sans-serif');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `url`, `parent_id`, `icon`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', NULL, 'bx-home', 1, '2024-08-04 12:56:05', '2024-08-06 12:27:25'),
(2, 'Users Management', '', NULL, 'bx-user', 12, '2024-08-04 12:56:05', '2024-08-15 10:55:29'),
(3, 'Menu Management', '', NULL, 'bx-data', 13, '2024-08-04 12:56:05', '2024-08-15 10:55:32'),
(4, 'Master Menu', 'menus', 3, NULL, 96, '2024-08-06 05:20:26', '2024-08-10 02:00:22'),
(10, 'Menu Permission', 'menupermission', 3, NULL, 98, '2024-08-06 19:28:10', '2024-08-07 02:35:16'),
(11, 'User Roles', 'userroles', 2, NULL, 99, '2024-08-07 21:39:12', '2024-08-07 23:32:39'),
(12, 'Master User', 'users', 2, NULL, 98, '2024-08-07 21:39:49', '2024-08-10 01:59:07'),
(16, 'Settings', '', NULL, 'bx-cog', 22, '2024-08-11 22:49:55', '2024-10-19 08:09:12'),
(17, 'Daftar Produk', 'products', 24, NULL, 99, '2024-08-14 06:43:43', '2024-08-15 02:19:50'),
(18, 'Daftar Merek', 'brands', 24, 'bx-heart', 99, '2024-08-14 07:46:39', '2024-08-18 13:00:28'),
(19, 'Toko', 'stores', NULL, 'bx-store', 4, '2024-08-14 07:57:29', '2024-08-15 10:55:54'),
(20, 'Daftar Unit', 'units', 24, 'bx-list-ul', 99, '2024-08-14 20:18:09', '2024-08-15 10:50:04'),
(21, 'Kontak', '', NULL, 'bx-phone', 3, '2024-08-14 23:13:54', '2024-08-15 10:55:42'),
(22, 'Daftar Customer', 'customers', 21, NULL, 99, '2024-08-14 23:14:14', '2024-08-15 10:53:18'),
(23, 'Daftar Supplier', 'suppliers', 21, NULL, 99, '2024-08-14 23:14:34', '2024-08-15 10:53:23'),
(24, 'Produk', '', NULL, 'bx-box', 2, '2024-08-14 23:24:21', '2024-08-15 10:54:45'),
(25, 'Daftar Kategori', 'categories', 24, NULL, 99, '2024-08-14 23:32:17', '2024-08-15 10:50:22'),
(26, 'Print Barcode', 'product-barcode', 24, NULL, 99, '2024-08-15 03:51:48', '2024-08-28 13:21:20'),
(27, 'Bank', 'bank', NULL, 'bxs-bank', 6, '2024-08-19 02:15:30', '2024-08-19 11:46:01'),
(28, 'Pajak', 'pajak', NULL, 'bxs-briefcase-alt', 5, '2024-08-19 02:18:55', '2024-08-19 11:45:58'),
(29, 'Penjualan', '', NULL, 'bx-cart-add', 15, '2024-08-22 19:13:38', '2024-08-23 02:24:34'),
(30, 'Tambah Penjualan', 'sales-transaction', 29, NULL, 97, '2024-08-22 19:15:03', '2024-08-30 05:36:09'),
(31, 'Retur Penjualan', 'sales-return', 29, NULL, 99, '2024-08-22 19:15:24', '2024-09-09 07:47:51'),
(32, 'Pembelian', '', NULL, 'bxs-cart', 16, '2024-08-22 19:15:40', '2024-08-23 02:25:23'),
(33, 'Daftar Pembelian', 'purchase-list', 32, NULL, 98, '2024-08-22 19:16:03', '2024-09-07 10:21:42'),
(34, 'Retur Pembelian', 'purchase-return', 32, NULL, 99, '2024-08-22 19:16:23', '2024-09-12 04:38:51'),
(35, 'Role & Permission Kasir', 'role-permission-kasir', NULL, 'bx-user', 17, '2024-08-27 19:13:11', '2024-08-28 02:17:34'),
(36, 'Daftar Penjualan', 'sales-list', 29, NULL, 98, '2024-08-29 22:33:31', '2024-08-30 08:02:12'),
(37, 'Tambah Pembelian', 'purchase-transaction', 32, NULL, 97, '2024-09-03 18:25:36', '2024-09-04 03:11:02'),
(38, 'Transfer Stok', '', NULL, 'bx-box', 18, '2024-09-10 05:13:24', '2024-09-10 05:13:24'),
(39, 'Biaya Pengeluaran', '', NULL, 'bx-money', 20, '2024-09-10 05:14:22', '2024-09-20 10:29:50'),
(40, 'Laporan penjualan', 'sales-report', 47, NULL, 99, '2024-09-11 18:18:43', '2024-09-17 08:13:22'),
(41, 'Laporan Pembelian', 'purchase-report', 47, NULL, 99, '2024-09-11 20:47:08', '2024-09-17 08:13:33'),
(42, 'Daftar Biaya', 'biaya-pengeluaran-list', 39, NULL, 99, '2024-09-11 20:47:42', '2024-09-19 14:49:27'),
(43, 'Tambah Biaya', 'biaya-pengeluaran', 39, NULL, 98, '2024-09-11 20:48:04', '2024-09-20 03:58:48'),
(44, 'Kategori Biaya', 'biaya-kategori', 39, NULL, 99, '2024-09-11 20:48:26', '2024-09-11 20:48:26'),
(45, 'Daftar Transfer Stock', 'stocktransfer-list', 38, NULL, 99, '2024-09-11 20:48:52', '2024-09-11 20:48:52'),
(46, 'Tambah Transfer Stock', 'stock-transfer', 38, NULL, 98, '2024-09-11 20:49:23', '2024-09-20 10:30:40'),
(47, 'Laporan', '', NULL, 'bx-note', 21, '2024-09-17 08:12:08', '2024-09-20 10:29:52'),
(48, 'Stock Opname', '', NULL, 'bx-pen', 19, '2024-09-20 10:26:20', '2024-09-20 10:29:55'),
(49, 'Tambah StockOpname', 'stockopname-add', 48, NULL, 98, '2024-09-20 10:26:59', '2024-09-20 10:30:26'),
(50, 'Daftar StockOpname', 'stockopname-list', 48, NULL, 99, '2024-09-20 10:27:26', '2024-09-20 10:27:26'),
(51, 'Laporan Stok', 'stock-report', 47, NULL, 99, '2024-09-24 09:47:32', '2024-09-24 09:47:32'),
(52, 'Laporan Laba Rugi', 'labarugi-report', 47, NULL, 99, '2024-09-26 11:58:26', '2024-09-26 11:58:26'),
(53, 'Laporan Akun Bank', 'bank-report', 47, NULL, 99, '2024-09-26 11:58:56', '2024-09-26 11:58:56'),
(54, 'Import Product', 'import-product', 24, NULL, 99, '2024-10-19 02:19:54', '2024-10-19 02:19:54'),
(55, 'Layout', 'settings', 16, NULL, 99, '2024-10-19 08:08:58', '2024-10-19 08:08:58'),
(56, 'Backup Database', 'backup-database', 16, NULL, 99, '2024-10-19 08:10:38', '2024-10-19 08:10:51'),
(57, 'Reset Database', 'reset-database', 16, NULL, 99, '2024-10-19 09:10:28', '2024-10-19 09:10:28'),
(58, 'Update App', 'update', NULL, 'bx-sync', 23, '2024-11-18 15:17:28', '2024-11-18 15:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `menu_permissions`
--

CREATE TABLE `menu_permissions` (
  `id` int UNSIGNED NOT NULL,
  `menu_id` int UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_permissions`
--

INSERT INTO `menu_permissions` (`id`, `menu_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-08-06 12:33:50', '2024-08-06 12:33:50'),
(2, 2, 2, '2024-08-06 12:34:26', '2024-08-06 12:34:26'),
(3, 3, 3, '2024-08-06 12:34:40', '2024-08-06 12:34:40'),
(4, 4, 3, '2024-08-06 12:34:54', '2024-08-06 12:34:54'),
(62, 10, 3, '2024-08-14 11:47:33', '2024-08-14 11:47:33'),
(63, 11, 2, '2024-08-14 04:49:51', '2024-08-14 04:49:51'),
(64, 12, 2, '2024-08-14 04:49:58', '2024-08-14 04:49:58'),
(65, 16, 36, '2024-08-14 04:50:03', '2024-08-14 04:50:03'),
(66, 17, 37, '2024-08-14 06:44:09', '2024-08-14 06:44:09'),
(67, 18, 38, '2024-08-14 07:48:12', '2024-08-14 07:48:12'),
(68, 19, 39, '2024-08-14 07:57:46', '2024-08-14 07:57:46'),
(69, 20, 40, '2024-08-14 20:18:41', '2024-08-14 20:18:41'),
(70, 23, 41, '2024-08-14 23:15:03', '2024-08-14 23:15:03'),
(71, 22, 41, '2024-08-14 23:15:16', '2024-08-14 23:15:16'),
(72, 21, 41, '2024-08-14 23:16:08', '2024-08-14 23:16:08'),
(73, 24, 42, '2024-08-14 23:25:45', '2024-08-14 23:25:45'),
(74, 25, 43, '2024-08-14 23:32:52', '2024-08-14 23:32:52'),
(75, 26, 44, '2024-08-15 03:52:16', '2024-08-15 03:52:16'),
(76, 27, 45, '2024-08-19 02:15:51', '2024-08-19 02:15:51'),
(77, 28, 46, '2024-08-19 02:19:12', '2024-08-19 02:19:12'),
(78, 29, 47, '2024-08-22 19:17:47', '2024-08-22 19:17:47'),
(79, 30, 47, '2024-08-22 19:18:00', '2024-08-22 19:18:00'),
(80, 31, 47, '2024-08-22 19:18:12', '2024-08-22 19:18:12'),
(81, 32, 48, '2024-08-22 19:18:23', '2024-08-22 19:18:23'),
(82, 33, 48, '2024-08-22 19:18:32', '2024-08-22 19:18:32'),
(83, 34, 48, '2024-08-22 19:18:39', '2024-08-22 19:18:39'),
(84, 35, 49, '2024-08-27 19:15:30', '2024-08-27 19:15:30'),
(85, 36, 47, '2024-08-29 22:35:07', '2024-08-29 22:35:07'),
(86, 37, 48, '2024-09-03 18:26:09', '2024-09-03 18:26:09'),
(87, 38, 50, '2024-09-10 05:13:54', '2024-09-10 05:13:54'),
(88, 39, 51, '2024-09-10 05:14:54', '2024-09-10 05:14:54'),
(89, 40, 47, '2024-09-11 18:19:07', '2024-09-11 18:19:07'),
(90, 41, 48, '2024-09-11 20:49:38', '2024-09-11 20:49:38'),
(91, 42, 51, '2024-09-11 20:50:04', '2024-09-11 20:50:04'),
(92, 43, 51, '2024-09-11 20:50:13', '2024-09-11 20:50:13'),
(93, 44, 51, '2024-09-11 20:50:20', '2024-09-11 20:50:20'),
(94, 45, 50, '2024-09-11 20:50:29', '2024-09-11 20:50:29'),
(95, 46, 50, '2024-09-11 20:50:34', '2024-09-11 20:50:34'),
(96, 47, 52, '2024-09-17 08:12:56', '2024-09-17 08:12:56'),
(97, 48, 53, '2024-09-20 10:27:58', '2024-09-20 10:27:58'),
(98, 49, 53, '2024-09-20 10:28:07', '2024-09-20 10:28:07'),
(99, 50, 53, '2024-09-20 10:28:18', '2024-09-20 10:28:18'),
(100, 51, 52, '2024-09-24 09:48:01', '2024-09-24 09:48:01'),
(101, 52, 52, '2024-09-26 11:59:13', '2024-09-26 11:59:13'),
(102, 53, 52, '2024-09-26 11:59:29', '2024-09-26 11:59:29'),
(103, 54, 37, '2024-10-19 02:23:38', '2024-10-19 02:23:38'),
(104, 55, 36, '2024-10-19 08:09:47', '2024-10-19 08:09:47'),
(105, 56, 36, '2024-10-19 08:11:09', '2024-10-19 08:11:09'),
(106, 57, 36, '2024-10-19 09:10:49', '2024-10-19 09:10:49'),
(107, 58, 54, '2024-11-18 15:18:08', '2024-11-18 15:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_08_05_001045_create_permission_tables', 1),
(5, '2024_08_12_053304_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pajak`
--

CREATE TABLE `pajak` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `pajak`
--

INSERT INTO `pajak` (`id`, `name`, `description`, `discount_value`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PPN 10%', 'Pertambahan Nilai', 10.00, 1, '2024-08-19 10:31:02', '2024-08-27 18:25:00'),
(2, 'PPH 2%', 'Pajak Penghasilan', 2.00, 0, '2024-08-19 03:39:44', '2024-08-27 18:24:49'),
(4, 'PPN 11%', 'New', 10.00, 0, '2024-08-26 03:20:15', '2024-08-27 18:25:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cash','bank_transfer') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `transaction_id`, `payment_method`, `bank_id`, `amount`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'cash', NULL, 555000.00, '2024-10-19 15:19:48', '2024-10-19 15:19:48', '2024-10-19 15:19:48'),
(2, 2, 'bank_transfer', 2, 3685000.00, '2024-10-24 13:41:57', '2024-10-24 13:41:57', '2024-10-24 13:41:57');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'web', '2024-08-05 06:38:42', '2024-08-05 06:38:44'),
(2, 'users.view', 'web', '2024-08-05 06:00:05', '2024-08-05 06:00:05'),
(3, 'menus.view', 'web', '2024-08-05 06:00:05', '2024-08-05 06:00:05'),
(13, 'users.edit', 'web', '2024-08-06 22:41:39', '2024-08-06 22:41:39'),
(14, 'users.delete', 'web', '2024-08-06 22:41:48', '2024-08-06 22:41:48'),
(19, 'users.add', 'web', '2024-08-07 02:07:01', '2024-08-07 02:07:01'),
(36, 'settings.view', 'web', '2024-08-11 22:50:18', '2024-08-11 22:50:18'),
(37, 'products.view', 'web', '2024-08-14 06:44:02', '2024-08-14 06:44:02'),
(38, 'brand.view', 'web', '2024-08-14 07:48:03', '2024-08-14 07:48:03'),
(39, 'stores.view', 'web', '2024-08-14 07:57:37', '2024-08-14 07:57:37'),
(40, 'units.view', 'web', '2024-08-14 20:18:31', '2024-08-14 20:18:31'),
(41, 'contacts.view', 'web', '2024-08-14 23:14:49', '2024-08-14 23:14:49'),
(42, 'items.view', 'web', '2024-08-14 23:25:33', '2024-08-14 23:25:33'),
(43, 'category.view', 'web', '2024-08-14 23:32:42', '2024-08-14 23:32:42'),
(44, 'barcode.view', 'web', '2024-08-15 03:52:02', '2024-08-15 03:52:02'),
(45, 'bank.view', 'web', '2024-08-19 02:15:41', '2024-08-19 02:15:41'),
(46, 'pajak.view', 'web', '2024-08-19 02:19:02', '2024-08-19 02:19:02'),
(47, 'penjualan.view', 'web', '2024-08-22 19:17:21', '2024-08-22 19:17:21'),
(48, 'pembelian.view', 'web', '2024-08-22 19:17:29', '2024-08-22 19:17:29'),
(49, 'rolepermissionkasir.view', 'web', '2024-08-27 19:13:30', '2024-08-27 19:13:30'),
(50, 'transfer_stock.view', 'web', '2024-09-10 05:13:45', '2024-09-10 05:13:45'),
(51, 'pengeluaran.view', 'web', '2024-09-10 05:14:34', '2024-09-10 05:14:34'),
(52, 'laporan.view', 'web', '2024-09-17 08:12:44', '2024-09-17 08:12:44'),
(53, 'stockopname.view', 'web', '2024-09-20 10:27:46', '2024-09-20 10:27:46'),
(54, 'update.view', 'web', '2024-11-18 15:17:54', '2024-11-18 15:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `has_varian` enum('Y','N') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `brand_id`, `unit_id`, `has_varian`, `created_at`, `updated_at`) VALUES
(21, 'Sepatu Olahraga Nike', 'Sepatu Olahraga Nike tanpa variasi.', 1, 2, 'N', '2024-10-19 14:11:08', '2024-10-19 14:11:08'),
(22, 'Kopi Nescafe', 'Kopi Nescafe tanpa variasi.', 4, 3, 'N', '2024-10-19 14:11:08', '2024-10-19 14:41:13'),
(26, 'Tas Adidas Originals', 'Tas Adidas Originals ukuran 39 berwarna Merah.', 2, 1, 'Y', '2024-10-19 14:11:08', '2024-10-19 14:45:56'),
(27, 'Sepatu Adidas Superstar', 'Sepatu Adidas Superstar tanpa variasi.', 2, 3, 'N', '2024-10-19 14:11:08', '2024-10-19 14:42:25'),
(29, 'Kaos Samsung Galaxy', 'Kaos Samsung Galaxy tanpa variasi.', 3, 3, 'N', '2024-10-19 14:11:08', '2024-10-19 14:40:52'),
(30, 'Sneaker Adidas UltraBoost', 'Sneaker Adidas UltraBoost ukuran 39 berwarna Merah.', 2, 4, 'Y', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(33, 'Smartphone Samsung Galaxy S21', 'Smartphone Samsung Galaxy S21 ukuran 39 berwarna Merah.', 3, 2, 'Y', '2024-10-19 14:11:08', '2024-10-19 14:44:15'),
(34, 'Coklat Nestle', 'Coklat Nestle ukuran 39 berwarna Merah.', 4, 4, 'Y', '2024-10-19 14:11:09', '2024-10-19 14:40:36'),
(37, 'Sepatu Formal Adidas', 'Sepatu Formal Adidas tanpa variasi.', 2, 1, 'N', '2024-10-19 14:11:09', '2024-10-19 14:11:09'),
(40, 'Makanan Ringan Nestle KitKat', 'Makanan Ringan Nestle KitKat ukuran 39 berwarna Merah.', 4, 2, 'Y', '2024-10-19 14:11:09', '2024-10-19 14:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `product_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(27, 4),
(30, 4),
(21, 5),
(26, 5),
(29, 5),
(37, 5),
(33, 6),
(34, 8),
(40, 8),
(22, 9);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_pricing`
--

CREATE TABLE `product_pricing` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `variasi_1` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `variasi_2` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `variasi_3` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `purchase_price` int DEFAULT NULL,
  `sale_price` int DEFAULT NULL,
  `stock` int NOT NULL,
  `weight` double NOT NULL DEFAULT '0',
  `store_id` bigint UNSIGNED DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_pricing`
--

INSERT INTO `product_pricing` (`id`, `product_id`, `variasi_1`, `variasi_2`, `variasi_3`, `purchase_price`, `sale_price`, `stock`, `weight`, `store_id`, `sku`, `barcode`, `created_at`, `updated_at`) VALUES
(261, 21, NULL, NULL, NULL, 341000, 554000, 60, 1.94, 1, 'SKU0001', '1234567891001', '2024-10-19 14:11:08', '2024-10-29 16:22:22'),
(262, 22, NULL, NULL, NULL, 294000, 191000, 74, 1.67, 1, 'SKU0002', '8996566678680', '2024-10-19 14:11:08', '2024-11-08 02:13:24'),
(314, 26, 'Merah', '39', NULL, 416000, 482000, 7, 1.3, 1, 'SKU0054', '8992870491816', '2024-10-19 14:11:08', '2024-11-08 02:13:24'),
(315, 26, 'Merah', '40', NULL, 225000, 571000, 94, 1.58, 1, 'SKU0055', '8999663785640', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(316, 26, 'Merah', '41', NULL, 115000, 332000, 78, 0.51, 1, 'SKU0056', '8990742643561', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(317, 26, 'Merah', '42', NULL, 344000, 317000, 81, 2, 1, 'SKU0057', '8994141477215', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(318, 26, 'Merah', '43', NULL, 477000, 586000, 32, 1.33, 1, 'SKU0058', '8998003431261', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(319, 26, 'Hijau', '39', NULL, 306000, 529000, 15, 0.87, 1, 'SKU0059', '8991468896453', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(320, 26, 'Hijau', '40', NULL, 304000, 503000, 41, 1.29, 1, 'SKU0060', '8992924375963', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(321, 26, 'Hijau', '41', NULL, 206000, 271000, 22, 0.96, 1, 'SKU0061', '8994904565425', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(322, 26, 'Hijau', '42', NULL, 495000, 418000, 56, 1.97, 1, 'SKU0062', '8997371235877', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(323, 26, 'Hijau', '43', NULL, 342000, 539000, 17, 0.86, 1, 'SKU0063', '8998376470546', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(324, 26, 'Biru', '39', NULL, 362000, 460000, 90, 0.68, 1, 'SKU0064', '8990064927028', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(325, 26, 'Biru', '40', NULL, 203000, 464000, 48, 1.39, 1, 'SKU0065', '8996297189929', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(326, 26, 'Biru', '41', NULL, 403000, 231000, 54, 0.66, 1, 'SKU0066', '8997180228961', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(327, 26, 'Biru', '42', NULL, 349000, 311000, 25, 1.4, 1, 'SKU0067', '8999043850449', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(328, 26, 'Biru', '43', NULL, 186000, 428000, 4, 1.87, 1, 'SKU0068', '8993108225319', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(329, 26, 'Hitam', '39', NULL, 375000, 451000, 88, 0.66, 1, 'SKU0069', '8994936185936', '2024-10-19 14:11:08', '2024-10-24 14:34:56'),
(330, 26, 'Hitam', '40', NULL, 424000, 511000, 84, 0.94, 1, 'SKU0070', '8998175319800', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(331, 26, 'Hitam', '41', NULL, 447000, 258000, 97, 1.35, 1, 'SKU0071', '8996440713865', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(332, 26, 'Hitam', '42', NULL, 236000, 188000, 9, 0.65, 1, 'SKU0072', '8995693393664', '2024-10-19 14:11:08', '2024-10-19 14:45:57'),
(333, 26, 'Hitam', '43', NULL, 342000, 533000, 96, 1.46, 1, 'SKU0073', '8994636056017', '2024-10-19 14:11:08', '2024-10-19 14:45:58'),
(334, 26, 'Putih', '39', NULL, 371000, 480000, 76, 0.82, 1, 'SKU0074', '8991898967082', '2024-10-19 14:11:08', '2024-10-19 14:45:58'),
(335, 26, 'Putih', '40', NULL, 114000, 383000, 85, 1.86, 1, 'SKU0075', '8992067342334', '2024-10-19 14:11:08', '2024-10-19 14:45:58'),
(336, 26, 'Putih', '41', NULL, 282000, 592000, 27, 0.82, 1, 'SKU0076', '8995367781582', '2024-10-19 14:11:08', '2024-10-19 14:45:58'),
(337, 26, 'Putih', '42', NULL, 228000, 378000, 33, 1.31, 1, 'SKU0077', '8996951341090', '2024-10-19 14:11:08', '2024-10-19 14:45:58'),
(338, 26, 'Putih', '43', NULL, 462000, 522000, 36, 1.09, 1, 'SKU0078', '8992048883191', '2024-10-19 14:11:08', '2024-10-19 14:45:58'),
(339, 27, NULL, NULL, NULL, 237000, 223000, 44, 1.49, 1, 'SKU0079', '8993558750096', '2024-10-19 14:11:08', '2024-10-19 14:42:25'),
(341, 29, NULL, NULL, NULL, 287000, 598000, 74, 1.08, 1, 'SKU0081', '8991534027781', '2024-10-19 14:11:08', '2024-10-19 14:18:01'),
(342, 30, 'Merah', '39', NULL, 233000, 463000, 58, 0.95, 1, 'SKU0082', '8994122235445', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(343, 30, 'Merah', '40', NULL, 312000, 241000, 98, 1.29, 1, 'SKU0083', '8995147016484', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(344, 30, 'Merah', '41', NULL, 261000, 413000, 4, 0.67, 1, 'SKU0084', '8995181561421', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(345, 30, 'Merah', '42', NULL, 343000, 255000, 34, 0.82, 1, 'SKU0085', '8995200566321', '2024-10-19 14:11:08', '2024-10-24 13:41:57'),
(346, 30, 'Merah', '43', NULL, 117000, 427000, 59, 1.92, 1, 'SKU0086', '8993192283868', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(347, 30, 'Hijau', '39', NULL, 338000, 258000, 55, 0.98, 1, 'SKU0087', '8991585388107', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(348, 30, 'Hijau', '40', NULL, 301000, 384000, 62, 1.11, 1, 'SKU0088', '8992126735497', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(349, 30, 'Hijau', '41', NULL, 275000, 381000, 37, 1.63, 1, 'SKU0089', '8997632183022', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(350, 30, 'Hijau', '42', NULL, 457000, 226000, 44, 1.43, 1, 'SKU0090', '8999799230427', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(351, 30, 'Hijau', '43', NULL, 330000, 376000, 37, 1.17, 1, 'SKU0091', '8990401372801', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(352, 30, 'Biru', '39', NULL, 381000, 333000, 36, 1.03, 1, 'SKU0092', '8993491197866', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(353, 30, 'Biru', '40', NULL, 345000, 240000, 71, 0.64, 1, 'SKU0093', '8991617716861', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(354, 30, 'Biru', '41', NULL, 330000, 205000, 33, 0.82, 1, 'SKU0094', '8997507891489', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(355, 30, 'Biru', '42', NULL, 124000, 545000, 51, 0.91, 1, 'SKU0095', '8997355008763', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(356, 30, 'Biru', '43', NULL, 199000, 421000, 16, 0.9, 1, 'SKU0096', '8996451658490', '2024-10-19 14:11:08', '2024-10-19 14:45:11'),
(357, 30, 'Hitam', '39', NULL, 100000, 467000, 63, 1.22, 1, 'SKU0097', '8999896534473', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(358, 30, 'Hitam', '40', NULL, 270000, 256000, 79, 0.62, 1, 'SKU0098', '8993851745294', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(359, 30, 'Hitam', '41', NULL, 327000, 297000, 27, 0.89, 1, 'SKU0099', '8996680730646', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(360, 30, 'Hitam', '42', NULL, 123000, 210000, 15, 1.04, 1, 'SKU0100', '8990099643511', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(361, 30, 'Hitam', '43', NULL, 355000, 450000, 24, 1.82, 1, 'SKU0101', '8999426969799', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(362, 30, 'Putih', '39', NULL, 487000, 257000, 29, 1.17, 1, 'SKU0102', '8998546372892', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(363, 30, 'Putih', '40', NULL, 494000, 328000, 65, 1.75, 1, 'SKU0103', '8991842506152', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(364, 30, 'Putih', '41', NULL, 466000, 347000, 70, 1.06, 1, 'SKU0104', '8990499480433', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(365, 30, 'Putih', '42', NULL, 277000, 471000, 97, 1.91, 1, 'SKU0105', '8997234072038', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(366, 30, 'Putih', '43', NULL, 264000, 183000, 97, 0.93, 1, 'SKU0106', '8999984662736', '2024-10-19 14:11:08', '2024-10-19 14:45:12'),
(393, 33, 'Merah', '39', NULL, 130000, 469000, 77, 0.58, 1, 'SKU0133', '8998273401094', '2024-10-19 14:11:08', '2024-10-24 14:34:57'),
(394, 33, 'Merah', '40', NULL, 186000, 259000, 43, 0.92, 1, 'SKU0134', '8993750204045', '2024-10-19 14:11:08', '2024-10-24 14:34:57'),
(395, 33, 'Merah', '41', NULL, 457000, 382000, 74, 0.88, 1, 'SKU0135', '8999357827373', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(396, 33, 'Merah', '42', NULL, 459000, 521000, 88, 0.85, 1, 'SKU0136', '8998104740125', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(397, 33, 'Merah', '43', NULL, 116000, 471000, 74, 1.45, 1, 'SKU0137', '8993197154644', '2024-10-19 14:11:08', '2024-11-08 02:13:24'),
(398, 33, 'Hijau', '39', NULL, 114000, 223000, 40, 1.14, 1, 'SKU0138', '8991782176309', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(399, 33, 'Hijau', '40', NULL, 482000, 221000, 25, 0.51, 1, 'SKU0139', '8994836367708', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(400, 33, 'Hijau', '41', NULL, 265000, 245000, 4, 1.56, 1, 'SKU0140', '8994413227982', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(401, 33, 'Hijau', '42', NULL, 349000, 553000, 15, 1.7, 1, 'SKU0141', '8995238875433', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(402, 33, 'Hijau', '43', NULL, 341000, 245000, 88, 1.96, 1, 'SKU0142', '8993680163290', '2024-10-19 14:11:08', '2024-10-24 14:34:57'),
(403, 33, 'Biru', '39', NULL, 416000, 581000, 75, 1.09, 1, 'SKU0143', '8993926372158', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(404, 33, 'Biru', '40', NULL, 395000, 571000, 83, 1.29, 1, 'SKU0144', '8991908278924', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(405, 33, 'Biru', '41', NULL, 434000, 172000, 77, 0.69, 1, 'SKU0145', '8997754328981', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(406, 33, 'Biru', '42', NULL, 206000, 199000, 62, 0.75, 1, 'SKU0146', '8992957309829', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(407, 33, 'Biru', '43', NULL, 162000, 185000, 54, 1.44, 1, 'SKU0147', '8998241356142', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(408, 33, 'Hitam', '39', NULL, 495000, 368000, 70, 0.99, 1, 'SKU0148', '8991618254621', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(409, 33, 'Hitam', '40', NULL, 278000, 266000, 68, 1.45, 1, 'SKU0149', '8991000976834', '2024-10-19 14:11:08', '2024-10-19 14:44:16'),
(410, 33, 'Hitam', '41', NULL, 479000, 588000, 70, 1.6, 1, 'SKU0150', '8992603952218', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(411, 33, 'Hitam', '42', NULL, 374000, 473000, 22, 1.99, 1, 'SKU0151', '8991495407462', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(412, 33, 'Hitam', '43', NULL, 141000, 288000, 54, 0.55, 1, 'SKU0152', '8995319209812', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(413, 33, 'Putih', '39', NULL, 304000, 506000, 36, 1.31, 1, 'SKU0153', '8996792905482', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(414, 33, 'Putih', '40', NULL, 196000, 577000, 4, 1.12, 1, 'SKU0154', '8998083424948', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(415, 33, 'Putih', '41', NULL, 476000, 453000, 46, 0.9, 1, 'SKU0155', '8994811014788', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(416, 33, 'Putih', '42', NULL, 470000, 597000, 18, 1.81, 1, 'SKU0156', '8993330550975', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(417, 33, 'Putih', '43', NULL, 485000, 265000, 40, 1.13, 1, 'SKU0157', '8996042633035', '2024-10-19 14:11:09', '2024-10-19 14:44:16'),
(418, 34, 'Merah', '39', NULL, 398000, 460000, 33, 1.9, 1, 'SKU0158', '8990862196220', '2024-10-19 14:11:09', '2024-10-19 14:12:55'),
(419, 34, 'Merah', '40', NULL, 180000, 384000, 49, 0.8, 1, 'SKU0159', '8998049316652', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(420, 34, 'Merah', '41', NULL, 142000, 302000, 24, 0.57, 1, 'SKU0160', '8990307064152', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(421, 34, 'Merah', '42', NULL, 352000, 181000, 16, 1.29, 1, 'SKU0161', '8993683066475', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(422, 34, 'Merah', '43', NULL, 130000, 533000, 83, 1.78, 1, 'SKU0162', '8990646473776', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(423, 34, 'Hijau', '39', NULL, 364000, 351000, 3, 1.83, 1, 'SKU0163', '8990322864478', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(424, 34, 'Hijau', '40', NULL, 303000, 358000, 16, 1.37, 1, 'SKU0164', '8999807325459', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(425, 34, 'Hijau', '41', NULL, 466000, 285000, 5, 1.64, 1, 'SKU0165', '8998952350569', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(426, 34, 'Hijau', '42', NULL, 448000, 492000, 91, 1.42, 1, 'SKU0166', '8990706548291', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(427, 34, 'Hijau', '43', NULL, 386000, 586000, 41, 1.44, 1, 'SKU0167', '8990993510902', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(428, 34, 'Biru', '39', NULL, 383000, 512000, 39, 0.58, 1, 'SKU0168', '8992388578719', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(429, 34, 'Biru', '40', NULL, 336000, 520000, 100, 1.77, 1, 'SKU0169', '8998706972771', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(430, 34, 'Biru', '41', NULL, 266000, 485000, 15, 1.32, 1, 'SKU0170', '8990865911325', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(431, 34, 'Biru', '42', NULL, 447000, 337000, 96, 1.95, 1, 'SKU0171', '8991846244289', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(432, 34, 'Biru', '43', NULL, 318000, 345000, 90, 1.67, 1, 'SKU0172', '8996600637628', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(433, 34, 'Hitam', '39', NULL, 440000, 177000, 39, 1.92, 1, 'SKU0173', '8994428959366', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(434, 34, 'Hitam', '40', NULL, 340000, 205000, 92, 1.9, 1, 'SKU0174', '8990780512201', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(435, 34, 'Hitam', '41', NULL, 446000, 353000, 23, 0.77, 1, 'SKU0175', '8995997991580', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(436, 34, 'Hitam', '42', NULL, 213000, 280000, 76, 0.62, 1, 'SKU0176', '8990997692635', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(437, 34, 'Hitam', '43', NULL, 254000, 453000, 27, 0.98, 1, 'SKU0177', '8995429168627', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(438, 34, 'Putih', '39', NULL, 253000, 462000, 9, 1.5, 1, 'SKU0178', '8994543680329', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(439, 34, 'Putih', '40', NULL, 456000, 302000, 59, 0.92, 1, 'SKU0179', '8994810471292', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(440, 34, 'Putih', '41', NULL, 171000, 374000, 91, 1.51, 1, 'SKU0180', '8994409324268', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(441, 34, 'Putih', '42', NULL, 159000, 591000, 54, 1.96, 1, 'SKU0181', '8992589114341', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(442, 34, 'Putih', '43', NULL, 108000, 221000, 42, 1.46, 1, 'SKU0182', '8990672363003', '2024-10-19 14:11:09', '2024-10-19 14:16:19'),
(469, 37, NULL, NULL, NULL, 114000, 518000, 100, 1.06, 1, 'SKU0209', '1234567891209', '2024-10-19 14:11:09', '2024-10-19 14:11:09'),
(496, 40, 'Merah', '39', NULL, 395000, 526000, 48, 0.71, 1, 'SKU0236', '8990401164512', '2024-10-19 14:11:09', '2024-11-08 02:13:24'),
(497, 40, 'Merah', '40', NULL, 376000, 267000, 6, 1.04, 1, 'SKU0237', '8992623659074', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(498, 40, 'Merah', '41', NULL, 328000, 315000, 82, 1.36, 1, 'SKU0238', '8995799898209', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(499, 40, 'Merah', '42', NULL, 229000, 395000, 34, 0.61, 1, 'SKU0239', '8998698358478', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(500, 40, 'Merah', '43', NULL, 392000, 450000, 25, 0.98, 1, 'SKU0240', '8996492914357', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(501, 40, 'Hijau', '39', NULL, 372000, 598000, 53, 1.31, 1, 'SKU0241', '8995174601202', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(502, 40, 'Hijau', '40', NULL, 290000, 409000, 32, 1.49, 1, 'SKU0242', '8994383696078', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(503, 40, 'Hijau', '41', NULL, 346000, 596000, 88, 1.04, 1, 'SKU0243', '8996348899685', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(504, 40, 'Hijau', '42', NULL, 145000, 423000, 68, 0.81, 1, 'SKU0244', '8990662094757', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(505, 40, 'Hijau', '43', NULL, 401000, 151000, 12, 1.19, 1, 'SKU0245', '8992109248686', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(506, 40, 'Biru', '39', NULL, 475000, 435000, 85, 1.83, 1, 'SKU0246', '8991591578721', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(507, 40, 'Biru', '40', NULL, 264000, 575000, 51, 0.56, 1, 'SKU0247', '8999871834864', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(508, 40, 'Biru', '41', NULL, 227000, 421000, 53, 1.17, 1, 'SKU0248', '8997504894612', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(509, 40, 'Biru', '42', NULL, 351000, 331000, 21, 0.86, 1, 'SKU0249', '8996255536635', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(510, 40, 'Biru', '43', NULL, 499000, 533000, 5, 1.95, 1, 'SKU0250', '8995071085846', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(511, 40, 'Hitam', '39', NULL, 363000, 527000, 46, 1.49, 1, 'SKU0251', '8997452033637', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(512, 40, 'Hitam', '40', NULL, 328000, 437000, 24, 0.65, 1, 'SKU0252', '8992858287424', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(513, 40, 'Hitam', '41', NULL, 435000, 269000, 35, 0.63, 1, 'SKU0253', '8990013853231', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(514, 40, 'Hitam', '42', NULL, 319000, 406000, 15, 1.39, 1, 'SKU0254', '8990825536193', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(515, 40, 'Hitam', '43', NULL, 145000, 579000, 29, 1.97, 1, 'SKU0255', '8996646519865', '2024-10-19 14:11:09', '2024-10-19 14:41:54'),
(516, 40, 'Putih', '39', NULL, 458000, 562000, 52, 1.95, 1, 'SKU0256', '8992789903301', '2024-10-19 14:11:09', '2024-10-19 14:41:55'),
(517, 40, 'Putih', '40', NULL, 404000, 504000, 42, 1.89, 1, 'SKU0257', '8991968149165', '2024-10-19 14:11:09', '2024-10-19 14:41:55'),
(518, 40, 'Putih', '41', NULL, 166000, 411000, 85, 1.16, 1, 'SKU0258', '8996394495572', '2024-10-19 14:11:09', '2024-10-19 14:41:55'),
(519, 40, 'Putih', '42', NULL, 331000, 512000, 36, 0.64, 1, 'SKU0259', '8993580069173', '2024-10-19 14:11:09', '2024-10-19 14:41:55'),
(520, 40, 'Putih', '43', NULL, 179000, 521000, 21, 0.59, 1, 'SKU0260', '8991992650644', '2024-10-19 14:11:09', '2024-10-19 14:41:55'),
(521, 34, 'Putih', '44', 'garis-garis', 50000, 70000, 50, 1, 1, 'SKU0183', '8994386967496', '2024-10-29 16:09:59', '2024-10-29 16:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_expenses`
--

CREATE TABLE `purchase_expenses` (
  `id` int NOT NULL,
  `purchase_order_id` int NOT NULL,
  `expense_category_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `reference_no` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cash','bank_transfer','tempo') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('dipesan','diterima') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `isreturn` enum('yes','no') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_status` enum('lunas','belum lunas') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL COMMENT 'exclude biaya lain2',
  `discount` decimal(10,2) DEFAULT NULL,
  `remaining_payment` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'include biaya lain2',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `supplier_id`, `reference_no`, `purchase_date`, `store_id`, `payment_method`, `status`, `isreturn`, `payment_status`, `total_amount`, `discount`, `remaining_payment`, `created_at`, `updated_at`) VALUES
(1, 1, 'PR-20241029-001', '2024-10-29', 1, 'tempo', 'dipesan', 'yes', 'belum lunas', 1705000.00, 0.00, 1700000.00, '2024-10-29 13:44:36', '2024-10-29 16:22:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_detail`
--

CREATE TABLE `purchase_order_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` int NOT NULL DEFAULT '0',
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `purchase_price` int NOT NULL,
  `diskon` int NOT NULL DEFAULT '0',
  `quantity` int NOT NULL,
  `remaining_quantity` int NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `purchase_order_detail`
--

INSERT INTO `purchase_order_detail` (`id`, `purchase_order_id`, `product_pricing_id`, `purchase_price`, `diskon`, `quantity`, `remaining_quantity`, `purchase_date`, `created_at`, `updated_at`) VALUES
(1, 1, 261, 341000, 0, 5, 5, '2024-10-28 17:00:00', '2024-10-29 13:44:36', '2024-10-29 13:44:36');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` int NOT NULL,
  `purchase_order_id` int NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `payment_note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `purchase_order_id`, `payment_date`, `payment_method`, `bank_id`, `amount_paid`, `payment_note`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-10-29', 'cash', NULL, 5000.00, 'bayar 5 rebu hela', '2024-10-29 16:19:33', '2024-10-29 16:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `return_purchase`
--

CREATE TABLE `return_purchase` (
  `id` int NOT NULL,
  `return_no` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `purchase_order_id` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `supplier_id` int NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `return_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `return_purchase`
--

INSERT INTO `return_purchase` (`id`, `return_no`, `purchase_order_id`, `user_id`, `supplier_id`, `store_id`, `return_date`, `total_amount`, `discount`, `note`, `created_at`, `updated_at`) VALUES
(1, 'IR-20241029-001', 1, 3, 1, 1, '2024-10-29', 341000.00, 0.00, 'RETUR HIJI HELA', '2024-10-29 16:22:22', '2024-10-29 16:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `return_purchase_detail`
--

CREATE TABLE `return_purchase_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `return_purchase_id` int NOT NULL,
  `purchase_order_detail_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `quantity_purchased` int NOT NULL,
  `quantity_returned` int NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `return_purchase_detail`
--

INSERT INTO `return_purchase_detail` (`id`, `return_purchase_id`, `purchase_order_detail_id`, `product_pricing_id`, `quantity_purchased`, `quantity_returned`, `price`, `discount_item`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 261, 5, 1, 341000.00, 0.00, 341000.00, '2024-10-29 16:22:22', '2024-10-29 16:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `return_sales_transactions`
--

CREATE TABLE `return_sales_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `return_no` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sales_transaction_id` bigint UNSIGNED NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `total_return` decimal(15,2) NOT NULL,
  `tax_return` decimal(15,2) NOT NULL,
  `discount_return` decimal(15,2) NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_sales_transaction_items`
--

CREATE TABLE `return_sales_transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `return_sales_transaction_id` bigint UNSIGNED NOT NULL,
  `sales_transaction_item_id` bigint UNSIGNED NOT NULL,
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `quantity_sold` int NOT NULL,
  `quantity_returned` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `discount_item` decimal(15,2) DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2024-08-08 07:35:57', '2024-08-08 07:35:57'),
(2, 'kasir', 'web', '2024-08-14 04:51:16', '2024-08-14 04:51:16'),
(3, 'admin', 'web', '2024-08-14 16:31:04', '2024-08-14 16:31:04');

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
(13, 1),
(14, 1),
(19, 1),
(49, 1),
(54, 1),
(1, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2),
(1, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales_transactions`
--

CREATE TABLE `sales_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `store_id` bigint UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `payment_method` enum('cash','bank_transfer','tempo') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `change_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remaining_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('lunas','tempo') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `isreturn` enum('yes','no') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sales_transactions`
--

INSERT INTO `sales_transactions` (`id`, `transaction_date`, `store_id`, `customer_id`, `user_id`, `total_amount`, `discount_amount`, `tax_amount`, `payment_method`, `total_payment`, `change_payment`, `remaining_payment`, `status`, `note`, `isreturn`, `created_at`, `updated_at`) VALUES
(1, '2024-10-19 15:19:48', 1, 1, 3, 554000.00, 0.00, 0.00, 'cash', 555000.00, 1000.00, 0.00, 'lunas', '', NULL, '2024-10-19 15:19:48', '2024-10-19 15:19:48'),
(2, '2024-10-24 13:41:57', 1, 1, 3, 3685000.00, 0.00, 0.00, 'bank_transfer', 3685000.00, 0.00, 0.00, 'lunas', '', NULL, '2024-10-24 13:41:57', '2024-10-24 13:41:57'),
(3, '2024-10-24 14:34:56', 1, 1, 3, 3177000.00, 158850.00, 301815.00, 'tempo', 0.00, 0.00, 3319965.00, 'tempo', '', NULL, '2024-10-24 14:34:56', '2024-10-24 14:34:56'),
(4, '2024-11-08 02:13:24', 1, 1, 3, 6710000.00, 0.00, 0.00, 'tempo', 0.00, 0.00, 6710000.00, 'tempo', '', NULL, '2024-11-08 02:13:24', '2024-11-08 02:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `sales_transaction_items`
--

CREATE TABLE `sales_transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `discount_item` decimal(15,2) DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sales_transaction_items`
--

INSERT INTO `sales_transaction_items` (`id`, `transaction_id`, `product_pricing_id`, `quantity`, `price`, `discount_item`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 261, 1, 554000.00, 0.00, 554000.00, '2024-10-19 15:19:48', '2024-10-19 15:19:48'),
(2, 2, 314, 5, 482000.00, 0.00, 2410000.00, '2024-10-24 13:41:57', '2024-10-24 13:41:57'),
(3, 2, 345, 5, 255000.00, 0.00, 1275000.00, '2024-10-24 13:41:57', '2024-10-24 13:41:57'),
(4, 3, 329, 1, 451000.00, 0.00, 451000.00, '2024-10-24 14:34:56', '2024-10-24 14:34:56'),
(5, 3, 261, 1, 554000.00, 0.00, 554000.00, '2024-10-24 14:34:56', '2024-10-24 14:34:56'),
(6, 3, 393, 2, 469000.00, 0.00, 938000.00, '2024-10-24 14:34:56', '2024-10-24 14:34:56'),
(7, 3, 394, 2, 259000.00, 0.00, 518000.00, '2024-10-24 14:34:57', '2024-10-24 14:34:57'),
(8, 3, 397, 1, 471000.00, 0.00, 471000.00, '2024-10-24 14:34:57', '2024-10-24 14:34:57'),
(9, 3, 402, 1, 245000.00, 0.00, 245000.00, '2024-10-24 14:34:57', '2024-10-24 14:34:57'),
(10, 4, 262, 1, 191000.00, 0.00, 191000.00, '2024-11-08 02:13:24', '2024-11-08 02:13:24'),
(11, 4, 314, 1, 482000.00, 0.00, 482000.00, '2024-11-08 02:13:24', '2024-11-08 02:13:24'),
(12, 4, 397, 5, 471000.00, 0.00, 2355000.00, '2024-11-08 02:13:24', '2024-11-08 02:13:24'),
(13, 4, 496, 7, 526000.00, 0.00, 3682000.00, '2024-11-08 02:13:24', '2024-11-08 02:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1NzdWogRqP9ASAMi6yTmsj8Lxh2Bwo0YanZ4UEVR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVJNeEVjQXhJSTVCQWhicG1INmJXUEpJRVdDTXlkanNyek5iRk5BayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731945026),
('1QL7WXNpa2KuN8PpDGyam7pwyDpzkQb74pfwktom', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOElWVHpzUFlucjNGSm5TMGRraWJVUFRacmpqN045Rm90NDRiQnRjbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943433),
('AoKdlSvfrR7dGqcjoXGHf8104Z10hWwzVicpNrlz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGE5d1Z4V2NJYTFVVllvZVJ2d3RrR0J3SU1IeHlnb3FCVHhaYzRXVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944602),
('c2zxuDUrTDTRFPng8QquZl4pSihMb5JSIoc7vGo6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3dadVpHOTdoa3pXRUxtRWoySm5UejkxN0xmd2w3ZHZReWhYdGo4SSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944579),
('eaUFtFr1exq9uUQBilqiELFEt0QdRB3zJNwpvNOe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWJnS3JIQURTVEV6WE9RanVKUDV5Qmc3Q2pGMEd4MjJpZTVqclJoZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731945003),
('Ebj5zsO6I3Gghw1G5s12vF5c0mPpwGY7Mm0ASGlx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaWl2RURBN0tPYmQ4RUpUOGU3bGRqZDdtYm5BR1dUQUpDQ2hUUjFaRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731942610),
('EqhzD4fAuZe4bkGn5toS0XcsTrKyrfTKowmdnNbI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUVud0NpWjRwdk1uaFlJSVdhU0hnQzdCdzlJWDJoYXR3Z25JcGg3VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943064),
('G1JrXqPKRYDtxe3LdtRj0eIgne1jxjwOrNxac4Yo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWTRRZ1owd2FNcEs4c3Y3azZMQjZIMTlURUxUQXBVRlg5N1dnQmUyUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943108),
('gcfc69WmNe4rIWb9FtemOmVzsALufD8b7oEmyDbO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieUpLUXkzeWFwYlliYjhnS0RXQlpUcjg0VVFFbGVGMmZoRGFFVUJrUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943131),
('h4kdVWMBcbQdrYRBZkHCMjz0JMdujogTaw2ClrNL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWt5SUhwcHV0dzVXakhLNm5UMko4bnVWWldrRlM0QVRyQWtwdjRZNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731942621),
('hI5I7RjriiaEk6U7KWRzUPawprkwmCVyFKeg7KgB', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUHpOMGVzTGVaSkQwd0cxTjdOcUU0NWNpMFJYbXp2VVpYeXhCZWg2bCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1731945037),
('JBX2TdgdKWcNyFx0tUL1Uo9GO3um5joEp9mCHqxy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjJjY1RQWXVFSjVaWmpod3NudEk3Wm5ReERKUTBJc0pSWnJQMUxENiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944589),
('kdWypI6j2SIjMOaVpp91FsG73I899CDdxPVixi3X', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieEtYR3ZwZXA2UHBaS29HQ3pyWDlIMWVUZnZQS2FYdkxUbEhJeTZvQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944476),
('KxmSQTQS7cSMedctFvaEJDmwCdcTgfvMeo7lIWxz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlI1ZFpvY2V6MER5R3hPTkh0dDMzcjZCNlBLeFR2OHFUSkVySGpSRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944553),
('mbkORc5aQc5J2EuWm9SEucbiFSQQRt6q0eZHLnCH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFN1Nmp2WFF5QTMyZXpXeTk4Mmx4TWcyN0Zkb2hwN01FZlVIdm95VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943101),
('MMGhwVl99WK2KNSjfuR6DgPLJQDaShWdf1Xkx31J', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlVqVkduc0VrVHNyUEJHYkk0eXBQdVI0TDVTS1B1bnlGcFVaWlo3RiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943097),
('n6XsZyeDUruMAEfqx9XXM2RN1rK9afmAD30eU2Ap', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0lmZ1kxTUg4Wkw3TjViOXRyNTFNQ3M3eDdPRG4xOTJrbHlHRnZYaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944999),
('njsw9sRib83obHUXzsyC2LOjKw8H28cpCrWNc4Cz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY0RwdzRObUx6NVRXaVBSN2lnT1FWbkZMNmEyV2duUExKRkV5SXAwWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944570),
('NKdEKGYcsYlEChak6P86KBuzKXOF49i4hWB8U3cB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVp0VHlEMjFtMnNTVWFORENIbFZIcjl2TWhJRDdLQmtxOFo3aVl2aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944993),
('nKI1qOnw4RJRC6yinRa6WNxr7R9AizdMVPbuTBiO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic3ZQdUY2VTltQkdHWkhPZ1VUU3JRNU9aNWRSSW9ncnBYRTJNR3Y3aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944563),
('nZiudhE35sOXQUlZ9c0KDz5KtexquTiXpeO1rYFx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2JZZ1Q1WmVwbnQ0QkJ2U1J4czBNaEl0YUtkWUVBcXNzVzIxdzZkQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943473),
('o1RpVhkFODogqBtK0Ba65wcZ6CcNNFrNwc4m3G2R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2RkOGZCNTMwR0xCSnl6OFRwbzJkSWx1UllGbHV0dWZRMzdRNWFYMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943549),
('OLobGrv8EU8e6cyiptfUF9QOfYRRjUb3uvSuuw37', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibTJwMFZTSktKNXcyVmdVY3V0V1JpcHNXcDF3bFVLbXJ4YWdsYzRKQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944981),
('oobM6BhlJyh4UyKEMf46W2CpaRxVRBmHRJ9QgQ0i', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFkwYkpZQURMR3ZkNDh4YWNaakp6N3JYNW4zVDRZVFpmWE9kanJMNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943480),
('PAtqn6vVwtZLRyjMDJmeGlt74a2bPGFJeATQ6qKK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGg3T3dIMm1Lc096Zm5lb3laaDlSUE5OYjllemczREdGNVo1WFppVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731945012),
('PwMMsbXl8YkcwBJJj0Wz1XJEkGy6E2ayBKs6OttC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmZYMUFoRUw0U1Y5bGdrelVydVY0Q0h0ZTRtdWNUWlBZbHFGN2RlcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943075),
('RUqbNKyksEHRhFohGKKPDjsi8O2OLiWu6B9qbXgN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXJDdXZqZWloRklBNjF2N1FOR3pEMWNhQUN4TlhMREI4UzVFRENXcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943562),
('sXYiNuySIKt1da2GwzUgRBCkMQnZFUbVcG8pfvJm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibTA4NVBTazJPNTJTUWNyTWhiM2NlR1c5dXRBSmo3Q216ckNYcU5zNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944586),
('TP98epWITLvBOmQTNpAfyqd6DWkgcoMgjHO6Qqph', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieEZUTThReVJGbjM2cGpMZXppdGduTXU1QkpnYkpPVnUxTENSdUUxNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944607),
('tSxHs6lqNlqQ8Iy1rRagyw3a1MrWmNy9emiFrqdw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGxTclp3VmdNc3ZSRnVtMzRDSUJaaXZpWHpMRWNUb210MGg4NU9rcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943031),
('ukdzs3MClpJdqlde73vz0ch4cAvA91mEBgwEcoV5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2ZpSHNUTTJDc1dlRkNBRms5aVhKOU1aMHJFeU9NOFZaSDNYakR0MyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731944256),
('uxeg63vZRimjdUmiRVTeNF9vpNG7SnPHiKLG5uyM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHp2Zk1pUmR6UnZJdnBCeVd2dThwcFBtRENFTmRjSUVZZTVEREx1QSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943028),
('wl2fxpBS48iP0L1BMob75Aed1WKEsTj33UqbIINc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRzZHSmFITWo4WDJ0RWl2b2NCdlU5cjV4eU1ROFI1eHEzQzh6VjhSWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731942571),
('xJ9pMp18zVyIYoFCTOuYt84DlhJ0L68uomdgSGtL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUtZWk10UlROVk8xT3dURENrTXJrNUgwRkFxWENTMW11bnJtVXd6YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943415),
('xTvLmkyjYQHEJpk8jxptRzqzmPnIUfjsG6Wtf49W', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0pIYWRnRWhUVTRrcWhzdURlaEIwZzFZdVRmSE8yT3RSYXRHaG5PcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943090),
('zvsfKj4AM8XIeHfKAM141kS6LSoszSLfG71cLTMy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDdXUDVLd1V0UlhyTFN0Y2lPNU95MmdhZEpuSG56dWJCS0VSbUFxUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731945040),
('ZWupxnqG1ekGF6W0kH9E2ws7V7uxDXwYfSJiEyDK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQk5ISW5RY2ZPNW1UMUJpekluNXZLNWl5MTJ4b3c1cW9paWpBOHhUSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW5pZmVzdC5qc29uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1731943049);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Laravel S-Kit',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sidebarcolor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `logo`, `created_at`, `updated_at`, `sidebarcolor`) VALUES
(1, 'PoS1', '1729263365.png', '2024-08-14 22:03:17', '2024-10-18 14:56:05', '#fff');

-- --------------------------------------------------------

--
-- Table structure for table `stock_opname`
--

CREATE TABLE `stock_opname` (
  `id` bigint UNSIGNED NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_opname_items`
--

CREATE TABLE `stock_opname_items` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_opname_id` bigint UNSIGNED NOT NULL,
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `system_stock` int NOT NULL,
  `physical_stock` int NOT NULL,
  `stock_difference` int NOT NULL,
  `unit_price` int NOT NULL,
  `total_loss` int DEFAULT NULL,
  `id_reason` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_opname_reason`
--

CREATE TABLE `stock_opname_reason` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `stock_opname_reason`
--

INSERT INTO `stock_opname_reason` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Barang Rusak', 'Penyesuaian stok karena barang rusak atau pecah.', '2024-11-10 01:00:00', '2024-11-10 01:00:00'),
(2, 'Kehilangan Barang', 'Penyesuaian karena barang hilang selama pengiriman atau penyimpanan.', '2024-11-10 01:05:00', '2024-11-10 01:05:00'),
(3, 'Produk Kedaluwarsa', 'Penyesuaian untuk barang yang telah melewati tanggal kedaluwarsa.', '2024-11-10 01:10:00', '2024-11-10 01:10:00'),
(4, 'Kesalahan Pengisian Stok', 'Penyesuaian karena kesalahan dalam pengisian ulang atau penempatan stok.', '2024-11-10 01:15:00', '2024-11-10 01:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `from_store_id` bigint UNSIGNED NOT NULL,
  `to_store_id` bigint UNSIGNED NOT NULL,
  `shipping_status` enum('dikirim','diterima') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'dikirim',
  `transfer_date` date DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','tempo') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_status` enum('lunas','belum_lunas') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL COMMENT 'exclude biaya lain-lain',
  `discount` decimal(10,2) DEFAULT NULL,
  `remaining_payment` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'include biaya lain-lain',
  `received_by` bigint UNSIGNED DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_expenses`
--

CREATE TABLE `stock_transfer_expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_transfer_id` bigint UNSIGNED NOT NULL,
  `expense_category_id` int NOT NULL,
  `amount` double NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_items`
--

CREATE TABLE `stock_transfer_items` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_transfer_id` bigint UNSIGNED NOT NULL,
  `product_pricing_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `quantity_received` int NOT NULL DEFAULT '0',
  `price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_payments`
--

CREATE TABLE `stock_transfer_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_transfer_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cash','bank_transfer') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `amount_paid` double NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint UNSIGNED NOT NULL,
  `store_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `store_name`, `email`, `phone`, `address`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Toko Pusat', 'tokopusat@example.com', '081234567890', 'Jalan Utama No. 1, Kota A', 'store_logos/StsZLYZMoMWHQx06bMlwEpeEitJ0SSDnXHeaCVCC.png', '2024-10-19 13:57:36', '2024-11-09 15:32:24'),
(2, 'Cabang 1', 'cabang1@example.com', '082345678901', 'Jalan Raya No. 2, Kota B', 'store_logos/tf3v0KhBqXGKaKAjYzYUbQJXFKdeY1wxmFxH4U7I.png', '2024-10-19 13:57:36', '2024-11-09 15:35:07'),
(3, 'Cabang 2', 'cabang2@example.com', '083456789012', 'Jalan Pasar No. 3, Kota C', 'store_logos/UAOMwLBUkRCG27gx0R5vDIieas9StIZMJXjxUBAt.png', '2024-10-19 13:57:36', '2024-11-09 15:35:23');

-- --------------------------------------------------------

--
-- Table structure for table `store_layout_preferences`
--

CREATE TABLE `store_layout_preferences` (
  `id` int NOT NULL,
  `store_id` bigint UNSIGNED DEFAULT NULL,
  `layout_id` int DEFAULT NULL,
  `sidebar_color` varchar(7) DEFAULT NULL,
  `menu_link_color` varchar(7) DEFAULT NULL,
  `menu_link_hover_color` varchar(7) DEFAULT NULL,
  `app_brand_color` varchar(7) DEFAULT NULL,
  `navbar_color` varchar(7) DEFAULT NULL,
  `button_color` varchar(7) DEFAULT NULL,
  `button_hover_color` varchar(7) DEFAULT NULL,
  `fonts` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `store_layout_preferences`
--

INSERT INTO `store_layout_preferences` (`id`, `store_id`, `layout_id`, `sidebar_color`, `menu_link_color`, `menu_link_hover_color`, `app_brand_color`, `navbar_color`, `button_color`, `button_hover_color`, `fonts`) VALUES
(8, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `store_user`
--

CREATE TABLE `store_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `store_user`
--

INSERT INTO `store_user` (`id`, `user_id`, `store_id`, `created_at`, `updated_at`) VALUES
(19, 3, 1, '2024-08-28 05:49:59', '2024-10-19 13:37:58'),
(21, 5, 1, '2024-09-03 07:19:26', '2024-10-19 13:38:02'),
(22, 6, 1, '2024-09-03 07:24:25', '2024-10-19 13:38:05'),
(25, 4, 1, '2024-10-08 04:18:47', '2024-10-19 13:38:08'),
(26, 4, 1, '2024-10-08 04:18:47', '2024-10-19 13:38:17'),
(27, 3, 2, '2024-10-19 15:27:28', '2024-10-19 15:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `store_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pic_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `store_name`, `pic_name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Bulan Raya', 'Agus', 'bulanraya@gmail.com', '08512335555', 'Jln Maju Mundur', '2024-10-29 13:43:30', '2024-10-29 13:43:30'),
(2, 'restu gorden', 'tofik', 'tofik@gmail.com', '08577888222', 'jl maju mundur', '2024-11-02 13:54:18', '2024-11-02 13:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Piece', 'Satuan barang yang dihitung per unit', '2024-10-19 13:58:14', '2024-10-19 13:58:14'),
(2, 'Box', 'Barang yang dikemas dalam kotak', '2024-10-19 13:58:14', '2024-10-19 13:58:14'),
(3, 'Kilogram', 'Satuan berat untuk barang berbobot', '2024-10-19 13:58:14', '2024-10-19 13:58:14'),
(4, 'Liter', 'Satuan volume untuk cairan', '2024-10-19 13:58:14', '2024-10-19 13:58:14'),
(5, 'Pasang', 'Barang yang dihitung per pasang (psg)', '2024-10-19 13:58:14', '2024-10-19 13:58:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@laravel.kit', NULL, '$2y$12$k5Xu1ia6RWs1uNfxX42z3.gNFQKXFK4x8HqfADLXS.1dc1OhOgB3O', NULL, '2024-08-08 07:35:57', '2024-11-18 15:50:24'),
(3, 'kasir1_arkstore', 'arkstore@mail.com', NULL, '$2y$12$HdhSgI0LhhWqBrTuivUEQOUu8YYwcMXToXvA0vsNctM7nsG9.0bv2', 'EZ9dc592EtIVEE8LeDRjaJbC3vj5qpUMCVBTL5W7QIkOab4Oq4LQuohAykbm', '2024-08-14 04:52:10', '2024-09-03 00:25:17'),
(4, 'admin', 'admin@mail.com', NULL, '$2y$12$OO0LVno5ZcwaEoKzUPuXeu7J1cxgDmYOe9OlS0LOiRUa/c1a.pZ6G', NULL, '2024-08-14 16:31:34', '2024-08-14 16:31:34'),
(5, 'kasir1_mikastore', 'mikastore@mail.com', NULL, '$2y$12$H4VlqD/7Ea4F/.nNafgM1u26xtZ5Bt9nSd.1xtMdf50NJMiLITt5e', NULL, '2024-08-27 20:34:04', '2024-09-03 00:25:35'),
(6, 'kasir2_arkstore', 'kasir2_ark@mail.com', NULL, '$2y$12$Fkm8ZfrHmy4N9s0hgANBf.j5AnSj978mGpPZgx5RJeItYuOjgZWeO', NULL, '2024-09-03 00:23:57', '2024-11-04 03:01:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_categories_categories` (`parent_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_expenses_stores` (`store_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_expense_items_expense_categories` (`expense_category_id`),
  ADD KEY `FK_expense_items_expenses` (`expense_id`),
  ADD KEY `FK_expense_items_users` (`user_id`),
  ADD KEY `FK_expense_items_customers` (`customer_id`);

--
-- Indexes for table `expense_payment`
--
ALTER TABLE `expense_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_expense_payment_expenses` (`expense_id`),
  ADD KEY `FK_expense_payment_bank` (`bank_id`);

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
-- Indexes for table `layouts`
--
ALTER TABLE `layouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layout_settings`
--
ALTER TABLE `layout_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `parent_id` (`parent_id`) USING BTREE;

--
-- Indexes for table `menu_permissions`
--
ALTER TABLE `menu_permissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `menu_id` (`menu_id`) USING BTREE,
  ADD KEY `permission_id` (`permission_id`) USING BTREE;

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
-- Indexes for table `pajak`
--
ALTER TABLE `pajak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `transaction_id` (`transaction_id`) USING BTREE,
  ADD KEY `payment_methods_ibfk_2` (`bank_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_pricing_id` (`product_pricing_id`);

--
-- Indexes for table `product_pricing`
--
ALTER TABLE `product_pricing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_sku_per_store` (`store_id`,`sku`),
  ADD UNIQUE KEY `unique_barcode_per_store` (`store_id`,`barcode`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `purchase_expenses`
--
ALTER TABLE `purchase_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_expenses_ibfk_1` (`purchase_order_id`),
  ADD KEY `purchase_expenses_ibfk_2` (`expense_category_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `purchase_order_detail`
--
ALTER TABLE `purchase_order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_pricing_id` (`product_pricing_id`),
  ADD KEY `FK_purchase_order_detail_purchase_orders` (`purchase_order_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_payments_ibfk_1` (`purchase_order_id`),
  ADD KEY `FK_purchase_payments_bank` (`bank_id`);

--
-- Indexes for table `return_purchase`
--
ALTER TABLE `return_purchase`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `return_purchase_ibfk_1` (`purchase_order_id`) USING BTREE,
  ADD KEY `FK_return_purchase_suppliers` (`supplier_id`),
  ADD KEY `FK_return_purchase_stores` (`store_id`),
  ADD KEY `FK_return_purchase_users` (`user_id`);

--
-- Indexes for table `return_purchase_detail`
--
ALTER TABLE `return_purchase_detail`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `product_pricing_id` (`product_pricing_id`) USING BTREE,
  ADD KEY `return_purchase_detail_ibfk_1` (`return_purchase_id`) USING BTREE,
  ADD KEY `FK_return_purchase_detail_purchase_order_detail` (`purchase_order_detail_id`);

--
-- Indexes for table `return_sales_transactions`
--
ALTER TABLE `return_sales_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `return_no` (`return_no`),
  ADD KEY `sales_transaction_id` (`sales_transaction_id`),
  ADD KEY `return_sales_transactions_ibfk_2` (`store_id`),
  ADD KEY `return_sales_transactions_ibfk_3` (`customer_id`);

--
-- Indexes for table `return_sales_transaction_items`
--
ALTER TABLE `return_sales_transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_sales_transaction_id` (`return_sales_transaction_id`),
  ADD KEY `sales_transaction_item_id` (`sales_transaction_item_id`),
  ADD KEY `product_pricing_id` (`product_pricing_id`);

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
-- Indexes for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `store_id` (`store_id`) USING BTREE,
  ADD KEY `customer_id` (`customer_id`) USING BTREE,
  ADD KEY `FK_sales_transactions_users` (`user_id`);

--
-- Indexes for table `sales_transaction_items`
--
ALTER TABLE `sales_transaction_items`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `transaction_id` (`transaction_id`) USING BTREE,
  ADD KEY `product_pricing_id` (`product_pricing_id`) USING BTREE;

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
-- Indexes for table `stock_opname`
--
ALTER TABLE `stock_opname`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `store_id` (`store_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `stock_opname_items`
--
ALTER TABLE `stock_opname_items`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `stock_opname_id` (`stock_opname_id`) USING BTREE,
  ADD KEY `product_pricing_id` (`product_pricing_id`) USING BTREE,
  ADD KEY `FK_stock_opname_items_stock_opname_reason` (`id_reason`);

--
-- Indexes for table `stock_opname_reason`
--
ALTER TABLE `stock_opname_reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_store_id` (`from_store_id`),
  ADD KEY `to_store_id` (`to_store_id`),
  ADD KEY `FK_stock_transfers_users` (`user_id`),
  ADD KEY `FK_stock_transfers_users_2` (`received_by`) USING BTREE;

--
-- Indexes for table `stock_transfer_expenses`
--
ALTER TABLE `stock_transfer_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_id` (`stock_transfer_id`),
  ADD KEY `expense_category_id` (`expense_category_id`);

--
-- Indexes for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_id` (`stock_transfer_id`),
  ADD KEY `product_pricing_id` (`product_pricing_id`);

--
-- Indexes for table `stock_transfer_payments`
--
ALTER TABLE `stock_transfer_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_id` (`stock_transfer_id`),
  ADD KEY `FK_stock_transfer_payments_bank` (`bank_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_layout_preferences`
--
ALTER TABLE `store_layout_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `store_user`
--
ALTER TABLE `store_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_payment`
--
ALTER TABLE `expense_payment`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layouts`
--
ALTER TABLE `layouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `layout_settings`
--
ALTER TABLE `layout_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `menu_permissions`
--
ALTER TABLE `menu_permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pajak`
--
ALTER TABLE `pajak`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_pricing`
--
ALTER TABLE `product_pricing`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=522;

--
-- AUTO_INCREMENT for table `purchase_expenses`
--
ALTER TABLE `purchase_expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_order_detail`
--
ALTER TABLE `purchase_order_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `return_purchase`
--
ALTER TABLE `return_purchase`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `return_purchase_detail`
--
ALTER TABLE `return_purchase_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `return_sales_transactions`
--
ALTER TABLE `return_sales_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_sales_transaction_items`
--
ALTER TABLE `return_sales_transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_transaction_items`
--
ALTER TABLE `sales_transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_opname`
--
ALTER TABLE `stock_opname`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_opname_items`
--
ALTER TABLE `stock_opname_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_opname_reason`
--
ALTER TABLE `stock_opname_reason`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_expenses`
--
ALTER TABLE `stock_transfer_expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_payments`
--
ALTER TABLE `stock_transfer_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_layout_preferences`
--
ALTER TABLE `store_layout_preferences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `store_user`
--
ALTER TABLE `store_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `FK_categories_categories` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `FK_expenses_stores` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD CONSTRAINT `FK_expense_items_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `FK_expense_items_expense_categories` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`),
  ADD CONSTRAINT `FK_expense_items_expenses` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_expense_items_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `expense_payment`
--
ALTER TABLE `expense_payment`
  ADD CONSTRAINT `FK_expense_payment_bank` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`id`),
  ADD CONSTRAINT `FK_expense_payment_expenses` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `layout_settings`
--
ALTER TABLE `layout_settings`
  ADD CONSTRAINT `layout_settings_ibfk_1` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`id`);

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_permissions`
--
ALTER TABLE `menu_permissions`
  ADD CONSTRAINT `menu_permissions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `sales_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_methods_ibfk_2` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_pricing`
--
ALTER TABLE `product_pricing`
  ADD CONSTRAINT `product_pricing_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_pricing_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_expenses`
--
ALTER TABLE `purchase_expenses`
  ADD CONSTRAINT `purchase_expenses_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_expenses_ibfk_2` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `purchase_order_detail`
--
ALTER TABLE `purchase_order_detail`
  ADD CONSTRAINT `FK_purchase_order_detail_purchase_orders` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_history_fk_product_pricing` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD CONSTRAINT `FK_purchase_payments_bank` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_purchase`
--
ALTER TABLE `return_purchase`
  ADD CONSTRAINT `FK_return_purchase_stores` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
  ADD CONSTRAINT `FK_return_purchase_suppliers` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `FK_return_purchase_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `return_purchase_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_purchase_detail`
--
ALTER TABLE `return_purchase_detail`
  ADD CONSTRAINT `FK_return_purchase_detail_purchase_order_detail` FOREIGN KEY (`purchase_order_detail_id`) REFERENCES `purchase_order_detail` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_purchase_detail_ibfk_1` FOREIGN KEY (`return_purchase_id`) REFERENCES `return_purchase` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_purchase_detail_ibfk_2` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_sales_transactions`
--
ALTER TABLE `return_sales_transactions`
  ADD CONSTRAINT `return_sales_transactions_ibfk_1` FOREIGN KEY (`sales_transaction_id`) REFERENCES `sales_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_sales_transactions_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_sales_transactions_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_sales_transaction_items`
--
ALTER TABLE `return_sales_transaction_items`
  ADD CONSTRAINT `return_sales_transaction_items_ibfk_1` FOREIGN KEY (`return_sales_transaction_id`) REFERENCES `return_sales_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_sales_transaction_items_ibfk_2` FOREIGN KEY (`sales_transaction_item_id`) REFERENCES `sales_transaction_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_sales_transaction_items_ibfk_3` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD CONSTRAINT `FK_sales_transactions_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_transactions_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
  ADD CONSTRAINT `sales_transactions_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `sales_transaction_items`
--
ALTER TABLE `sales_transaction_items`
  ADD CONSTRAINT `sales_transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `sales_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_transaction_items_ibfk_2` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_opname`
--
ALTER TABLE `stock_opname`
  ADD CONSTRAINT `stock_opname_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_opname_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_opname_items`
--
ALTER TABLE `stock_opname_items`
  ADD CONSTRAINT `FK_stock_opname_items_stock_opname_reason` FOREIGN KEY (`id_reason`) REFERENCES `stock_opname_reason` (`id`),
  ADD CONSTRAINT `stock_opname_items_ibfk_1` FOREIGN KEY (`stock_opname_id`) REFERENCES `stock_opname` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_opname_items_ibfk_2` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `FK_stock_transfers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_stock_transfers_users_2` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_1` FOREIGN KEY (`from_store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfers_ibfk_2` FOREIGN KEY (`to_store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfer_expenses`
--
ALTER TABLE `stock_transfer_expenses`
  ADD CONSTRAINT `stock_transfer_expenses_ibfk_1` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfer_expenses_ibfk_2` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`);

--
-- Constraints for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD CONSTRAINT `stock_transfer_items_ibfk_1` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfer_items_ibfk_2` FOREIGN KEY (`product_pricing_id`) REFERENCES `product_pricing` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfer_payments`
--
ALTER TABLE `stock_transfer_payments`
  ADD CONSTRAINT `FK_stock_transfer_payments_bank` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`id`),
  ADD CONSTRAINT `stock_transfer_payments_ibfk_1` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_layout_preferences`
--
ALTER TABLE `store_layout_preferences`
  ADD CONSTRAINT `store_layout_preferences_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
  ADD CONSTRAINT `store_layout_preferences_ibfk_2` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`id`);

--
-- Constraints for table `store_user`
--
ALTER TABLE `store_user`
  ADD CONSTRAINT `store_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_user_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
