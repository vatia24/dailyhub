-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2025 at 03:04 PM
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
-- Database: `sumo`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_tokens`
--

CREATE TABLE `access_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_tokens`
--

INSERT INTO `access_tokens` (`id`, `user_id`, `token`, `created_at`, `expires_at`) VALUES
(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTc5NTA3OCwiZXhwIjoxNzU1Nzk4Njc4LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.FEutbDVeIAVbZHFZXcsc5UNz7h0MyknC7lnS8KKooks', '2025-08-21 16:51:18', '2025-08-21 19:51:18'),
(2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMTU5NCwiZXhwIjoxNzU1ODA1MTk0LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.-Umj7uHgtbbriTcCxE2c2d_qjwl8qXX2R5NA1kwEAUw', '2025-08-21 18:39:54', '2025-08-21 21:39:54'),
(3, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMTU5NywiZXhwIjoxNzU1ODA1MTk3LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.zLkgMVaWi93AjlOrlLm4GKts1yRuYk888XfVVvVn5eA', '2025-08-21 18:39:57', '2025-08-21 21:39:57'),
(4, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMTYwMCwiZXhwIjoxNzU1ODA1MjAwLCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.2CvVIXssrkpUYUNfgfNizHMjuaRqLGs_vFVyNJDlVNA', '2025-08-21 18:40:00', '2025-08-21 21:40:00'),
(5, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMTYwMiwiZXhwIjoxNzU1ODA1MjAyLCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.sz6mgCm-ytFfIH1zw15lWA5RHL-Aa1c5hmk0ksZaePY', '2025-08-21 18:40:03', '2025-08-21 21:40:02'),
(6, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMTc4NSwiZXhwIjoxNzU1ODA1Mzg1LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.mRlKP3ENfJ2egQorsVL63eOn6G2Y1RNqhrVZ3oewq20', '2025-08-21 18:43:06', '2025-08-21 21:43:05'),
(7, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMTc4OCwiZXhwIjoxNzU1ODA1Mzg4LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.ulVamjakEOtp9xJhgJI8oI38bTHlMB3Aaad_DoQZjsU', '2025-08-21 18:43:08', '2025-08-21 21:43:08'),
(8, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMjI3OSwiZXhwIjoxNzU1ODA1ODc5LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.t4eenU5Gr1oFPi6nMTDYtDSByFwU0aE3mDgour8LkX8', '2025-08-21 18:51:19', '2025-08-21 21:51:19'),
(9, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMjQ4MiwiZXhwIjoxNzU1ODA2MDgyLCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.6leB7FR-DK2OTmXOSOn5grgDDHAGlpHdDI5B-YQmbCI', '2025-08-21 18:54:43', '2025-08-21 21:54:42'),
(10, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMjYyMSwiZXhwIjoxNzU1ODA2MjIxLCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.qRHCrf7R_7HnxOR4DiIP7MH0EuHHgbI6MM1v8cmcdhs', '2025-08-21 18:57:01', '2025-08-21 19:57:01'),
(11, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NTgwMjYyOCwiZXhwIjoxNzU1ODA2MjI4LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoiNTkxOTQxNjU1Iiwicm9sZSI6ImN1c3RvbWVyIn19.d1WLBF4bVJ8ke1ByRuq0hIYpOKFfSFCp-hf-udT9Q_8', '2025-08-21 18:57:08', '2025-08-21 21:57:08'),
(12, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NjI5NTQ5NSwiZXhwIjoxNzU2Mjk5MDk1LCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoidmF0aWExOTk4QGdtYWlsLmNvbSIsInJvbGUiOiJjdXN0b21lciJ9fQ.SrZNXIc4rM7PDTQ51oNNawJKl_qQqqxO8psVquFaIkI', '2025-08-27 15:51:35', '2025-08-27 14:51:35'),
(13, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJkaXNjb3VudCIsImlhdCI6MTc1NjI5NzIyMSwiZXhwIjoxNzU2MzAwODIxLCJkYXRhIjp7ImlkIjoxLCJpZGVudGlmaWVyIjoidmF0aWExOTk4QGdtYWlsLmNvbSIsInJvbGUiOiJjdXN0b21lciJ9fQ.7YbBcN5osFHkth6tpXMbrmD2OX5UZpd_y71B8CMSjh8', '2025-08-27 16:20:21', '2025-08-27 15:20:21');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','suspended') NOT NULL DEFAULT 'pending',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_branches`
--

CREATE TABLE `company_branches` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` text DEFAULT NULL,
  `branch_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `company_contacts`
-- (See below for the actual view)
--
CREATE TABLE `company_contacts` (
`id` int(11)
,`user_id` int(11)
,`company_id` int(11)
,`phone` varchar(255)
,`email` varchar(255)
,`address` text
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `company_documents`
--

CREATE TABLE `company_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `doc_type` varchar(64) NOT NULL,
  `path` varchar(512) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_gallery`
--

CREATE TABLE `company_gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `path` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_hours`
--

CREATE TABLE `company_hours` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `day_of_week` tinyint(3) UNSIGNED NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_socials`
--

CREATE TABLE `company_socials` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `platform` varchar(32) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `company_users`
-- (See below for the actual view)
--
CREATE TABLE `company_users` (
`id` int(11)
,`company_id` int(11)
,`user_id` int(11)
,`name` varchar(255)
,`email` varchar(255)
,`mobile` varchar(255)
,`role` varchar(255)
,`active` tinyint(1)
,`registration_date` date
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zones`
--

CREATE TABLE `delivery_zones` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `zone_type` enum('radius','polygon') NOT NULL,
  `center_lat` decimal(10,7) DEFAULT NULL,
  `center_lng` decimal(10,7) DEFAULT NULL,
  `radius_m` int(11) DEFAULT NULL,
  `polygon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`polygon`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('draft','active','inactive','archived','scheduled') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `discounts`
-- (See below for the actual view)
--
CREATE TABLE `discounts` (
`id` int(11)
,`user_id` int(11)
,`company_id` int(11)
,`product_id` int(11)
,`discount_price` decimal(10,2)
,`discount_percent` decimal(5,2)
,`start_date` date
,`end_date` date
,`status` enum('draft','active','inactive','archived','scheduled')
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `discount_actions`
--

CREATE TABLE `discount_actions` (
  `id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `view_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `action` enum('view','clicked','redirect','map_open','share','favorite') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `device_type` enum('mobile','desktop','tablet') DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `age_group` enum('under_18','18_24','25_34','35_44','45_54','55_64','65_plus') DEFAULT NULL,
  `gender` enum('male','female','other','unknown') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `discount_events`
-- (See below for the actual view)
--
CREATE TABLE `discount_events` (
`id` int(11)
,`discount_id` int(11)
,`view_date` timestamp
,`action` enum('view','clicked','redirect','map_open','share','favorite')
,`created_at` timestamp
,`user_id` int(11)
,`device_type` enum('mobile','desktop','tablet')
,`city` varchar(100)
,`region` varchar(100)
,`age_group` enum('under_18','18_24','25_34','35_44','45_54','55_64','65_plus')
,`gender` enum('male','female','other','unknown')
);

-- --------------------------------------------------------

--
-- Table structure for table `individual_entrepreneurs`
--

CREATE TABLE `individual_entrepreneurs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `personal_identification_number` varchar(50) NOT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `business_activity` text DEFAULT NULL,
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `legal_persons`
--

CREATE TABLE `legal_persons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_type` varchar(50) NOT NULL,
  `identification_number` varchar(50) NOT NULL,
  `director_name` varchar(255) DEFAULT NULL,
  `business_activity` text DEFAULT NULL,
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `type` enum('problem','error','info') DEFAULT NULL,
  `status` enum('new','in_progress','resolved') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `mobile` varchar(25) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `is_used` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `mobile`, `otp`, `is_used`, `created_at`) VALUES
(1, '+995591941655', '580241', 0, '2025-08-21 16:35:36'),
(2, '+995555123456', '883346', 0, '2025-08-26 19:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('draft','active','inactive','archived','scheduled','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `products`
-- (See below for the actual view)
--
CREATE TABLE `products` (
`id` int(11)
,`user_id` int(11)
,`company_id` int(11)
,`branch_id` int(11)
,`name` varchar(255)
,`description` text
,`price` decimal(10,2)
,`image_url` varchar(255)
,`address` text
,`link` varchar(255)
,`status` enum('draft','active','inactive','archived','scheduled','published')
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `path` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_user`
--

CREATE TABLE `sub_user` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `registration_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(55) NOT NULL,
  `user_type` enum('customer','legal_person','individual_entrepreneur') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive','unverified') NOT NULL DEFAULT 'unverified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `mobile`, `user_type`, `created_at`, `updated_at`, `status`) VALUES
(1, 'whatia', '$argon2id$v=19$m=65536,t=4,p=2$UEZSMEliMkFOcGlPYzNXZA$u2qjTba9gUKztzIrwlZM8LQa9JSb5S8ZviyiqTFL9WE', 'Giorgi', 'vatia1998@gmail.com', '591941655', 'customer', '2025-08-21 12:35:36', '2025-08-27 11:46:00', 'active'),
(2, 'john', '$argon2id$v=19$m=65536,t=4,p=2$SUF3QmpoOC9pUWRTbGNISA$rKaK2fnmmweMsOPRil7jdW30NBoKsjGS0nCj9PLiMKs', 'John', 'john@example.com', '555123456', 'customer', '2025-08-26 15:15:21', '2025-08-26 15:15:21', 'unverified');

-- --------------------------------------------------------

--
-- Table structure for table `user_limits`
--

CREATE TABLE `user_limits` (
  `username` varchar(255) NOT NULL,
  `limit_check` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `company_contacts`
--
DROP TABLE IF EXISTS `company_contacts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `company_contacts`  AS SELECT `contact`.`id` AS `id`, `contact`.`user_id` AS `user_id`, `contact`.`company_id` AS `company_id`, `contact`.`phone` AS `phone`, `contact`.`email` AS `email`, `contact`.`address` AS `address`, `contact`.`created_at` AS `created_at` FROM `contact` ;

-- --------------------------------------------------------

--
-- Structure for view `company_users`
--
DROP TABLE IF EXISTS `company_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `company_users`  AS SELECT `sub_user`.`id` AS `id`, `sub_user`.`company_id` AS `company_id`, `sub_user`.`user_id` AS `user_id`, `sub_user`.`name` AS `name`, `sub_user`.`email` AS `email`, `sub_user`.`mobile` AS `mobile`, `sub_user`.`role` AS `role`, `sub_user`.`active` AS `active`, `sub_user`.`registration_date` AS `registration_date`, `sub_user`.`created_at` AS `created_at`, `sub_user`.`updated_at` AS `updated_at` FROM `sub_user` ;

-- --------------------------------------------------------

--
-- Structure for view `discounts`
--
DROP TABLE IF EXISTS `discounts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `discounts`  AS SELECT `discount`.`id` AS `id`, `discount`.`user_id` AS `user_id`, `discount`.`company_id` AS `company_id`, `discount`.`product_id` AS `product_id`, `discount`.`discount_price` AS `discount_price`, `discount`.`discount_percent` AS `discount_percent`, `discount`.`start_date` AS `start_date`, `discount`.`end_date` AS `end_date`, `discount`.`status` AS `status`, `discount`.`created_at` AS `created_at`, `discount`.`updated_at` AS `updated_at` FROM `discount` ;

-- --------------------------------------------------------

--
-- Structure for view `discount_events`
--
DROP TABLE IF EXISTS `discount_events`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `discount_events`  AS SELECT `discount_actions`.`id` AS `id`, `discount_actions`.`discount_id` AS `discount_id`, `discount_actions`.`view_date` AS `view_date`, `discount_actions`.`action` AS `action`, `discount_actions`.`created_at` AS `created_at`, `discount_actions`.`user_id` AS `user_id`, `discount_actions`.`device_type` AS `device_type`, `discount_actions`.`city` AS `city`, `discount_actions`.`region` AS `region`, `discount_actions`.`age_group` AS `age_group`, `discount_actions`.`gender` AS `gender` FROM `discount_actions` ;

-- --------------------------------------------------------

--
-- Structure for view `products`
--
DROP TABLE IF EXISTS `products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `products`  AS SELECT `product`.`id` AS `id`, `product`.`user_id` AS `user_id`, `product`.`company_id` AS `company_id`, `product`.`branch_id` AS `branch_id`, `product`.`name` AS `name`, `product`.`description` AS `description`, `product`.`price` AS `price`, `product`.`image_url` AS `image_url`, `product`.`address` AS `address`, `product`.`link` AS `link`, `product`.`status` AS `status`, `product`.`created_at` AS `created_at`, `product`.`updated_at` AS `updated_at` FROM `product` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_tokens`
--
ALTER TABLE `access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`) USING HASH,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_access_tokens_user_id` (`user_id`),
  ADD KEY `idx_access_tokens_expires_at` (`expires_at`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `idx_companies_status` (`status`);

--
-- Indexes for table `company_branches`
--
ALTER TABLE `company_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_branches_ibfk_1` (`company_id`);

--
-- Indexes for table `company_documents`
--
ALTER TABLE `company_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cd_company` (`company_id`),
  ADD KEY `idx_cd_status` (`status`);

--
-- Indexes for table `company_gallery`
--
ALTER TABLE `company_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cg_company` (`company_id`);

--
-- Indexes for table `company_hours`
--
ALTER TABLE `company_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ch_company_day` (`company_id`,`day_of_week`);

--
-- Indexes for table `company_socials`
--
ALTER TABLE `company_socials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cs_company` (`company_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_ibfk_1` (`user_id`),
  ADD KEY `contact_ibfk_2` (`company_id`);

--
-- Indexes for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dz_company` (`company_id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_ibfk_1` (`user_id`),
  ADD KEY `discount_ibfk_2` (`company_id`),
  ADD KEY `discount_ibfk_3` (`product_id`),
  ADD KEY `idx_discount_company_id` (`company_id`),
  ADD KEY `idx_discount_product_id` (`product_id`),
  ADD KEY `idx_discount_status` (`status`),
  ADD KEY `idx_discount_window` (`start_date`,`end_date`);

--
-- Indexes for table `discount_actions`
--
ALTER TABLE `discount_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_actions_ibfk_1` (`discount_id`),
  ADD KEY `idx_actions_discount_created` (`discount_id`,`created_at`),
  ADD KEY `idx_actions_discount_action` (`discount_id`,`action`);

--
-- Indexes for table `individual_entrepreneurs`
--
ALTER TABLE `individual_entrepreneurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_identification_number` (`personal_identification_number`),
  ADD KEY `fk_ie_company_id` (`company_id`),
  ADD KEY `fk_ie_user_id` (`user_id`);

--
-- Indexes for table `legal_persons`
--
ALTER TABLE `legal_persons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identification_number` (`identification_number`),
  ADD KEY `fk_lp_company_id` (`company_id`),
  ADD KEY `fk_lp_user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_ibfk_1` (`user_id`),
  ADD KEY `notifications_ibfk_2` (`company_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mobile_created_at` (`mobile`,`created_at`),
  ADD KEY `idx_otp_mobile_created` (`mobile`,`created_at`),
  ADD KEY `idx_otp_is_used` (`is_used`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ibfk_1` (`user_id`),
  ADD KEY `product_ibfk_2` (`company_id`),
  ADD KEY `product_ibfk_3` (`branch_id`),
  ADD KEY `idx_product_company_id` (`company_id`),
  ADD KEY `idx_product_branch_id` (`branch_id`),
  ADD KEY `idx_product_status` (`status`),
  ADD KEY `idx_product_name` (`name`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pi_product` (`product_id`);

--
-- Indexes for table `sub_user`
--
ALTER TABLE `sub_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_user_ibfk_1` (`company_id`),
  ADD KEY `sub_user_ibfk_2` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_users_username` (`username`),
  ADD UNIQUE KEY `idx_users_email` (`email`),
  ADD UNIQUE KEY `idx_users_mobile` (`mobile`);

--
-- Indexes for table `user_limits`
--
ALTER TABLE `user_limits`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_tokens`
--
ALTER TABLE `access_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_branches`
--
ALTER TABLE `company_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_documents`
--
ALTER TABLE `company_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_gallery`
--
ALTER TABLE `company_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_hours`
--
ALTER TABLE `company_hours`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_socials`
--
ALTER TABLE `company_socials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_actions`
--
ALTER TABLE `discount_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `individual_entrepreneurs`
--
ALTER TABLE `individual_entrepreneurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `legal_persons`
--
ALTER TABLE `legal_persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_user`
--
ALTER TABLE `sub_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_tokens`
--
ALTER TABLE `access_tokens`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `company_branches`
--
ALTER TABLE `company_branches`
  ADD CONSTRAINT `company_branches_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `company_documents`
--
ALTER TABLE `company_documents`
  ADD CONSTRAINT `fk_cd_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_gallery`
--
ALTER TABLE `company_gallery`
  ADD CONSTRAINT `fk_cg_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_hours`
--
ALTER TABLE `company_hours`
  ADD CONSTRAINT `fk_ch_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_socials`
--
ALTER TABLE `company_socials`
  ADD CONSTRAINT `fk_cs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  ADD CONSTRAINT `fk_dz_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `discount_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discount_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `discount_actions`
--
ALTER TABLE `discount_actions`
  ADD CONSTRAINT `discount_actions_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `individual_entrepreneurs`
--
ALTER TABLE `individual_entrepreneurs`
  ADD CONSTRAINT `fk_ie_company_id` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `fk_ie_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `legal_persons`
--
ALTER TABLE `legal_persons`
  ADD CONSTRAINT `fk_lp_company_id` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `fk_lp_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `company_branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_pi_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_user`
--
ALTER TABLE `sub_user`
  ADD CONSTRAINT `sub_user_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
