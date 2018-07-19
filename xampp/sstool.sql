-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2018 at 04:15 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sstool`
--

-- --------------------------------------------------------

--
-- Table structure for table `ips`
--

CREATE TABLE `ips` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `low_ip` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `high_ip` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2018_04_19_062637_create_posts_table', 1),
(3, '2018_04_26_004836_add_user_id_to_posts', 1),
(4, '2018_04_27_045823_add_cover_image_to_posts', 1),
(5, '2018_05_28_112659_create_ips_table', 1),
(6, '2018_05_28_113933_add_post_id_to_ips', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `body`, `created_at`, `updated_at`, `user_id`, `cover_image`) VALUES
(1, 'AdHoc Scan', '123.123.123.123, 123.123.123.123/24', '2018-06-13 16:23:15', '2018-06-13 16:23:15', 2, 'noimage.jpg'),
(2, 'AdHoc Scan', '123.123.123.123, 123.123.123.123/24', '2018-06-20 15:02:07', '2018-06-20 15:02:07', 3, 'noimage.jpg'),
(3, 'AdHoc Scan', '123.123.123.123, 123.123.123.123/24', '2018-06-24 15:32:38', '2018-06-24 15:32:38', 3, 'noimage.jpg'),
(4, 'AdHoc Scan', '123.123.123.123, 123.123.123.123/24', '2018-06-24 17:43:16', '2018-06-24 17:43:16', 2, 'noimage.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'user', 'nate', 'nate@gmail.com', '$2y$10$vnQqIC9BkpIF82IH8NeNtueCwKATa1Sc3HRRpSBRZKxb/34aQwKxm', 'hoSQJm1iRvNNpct7bhXhOpZ8e1djHNVG0VfBAnDoGFeR5W86ceMKUiE8fqs4', '2018-06-13 16:23:06', '2018-06-13 16:23:06'),
(3, 'admin', 'bertram', 'bertram@gmail.com', '$2y$10$1xEaff0U9bZG0tSlOjGoueDmvEJfT8fMqxxznnz9jhFExeDj/vK1m', 'Fu6wrVsPNJQcgLyQb52Qv7xFEVjhmy9SVnbC2mE0tRsgCAklXvFnDMt1uZgi', '2018-06-20 15:01:49', '2018-06-20 15:01:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ips`
--
ALTER TABLE `ips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- AUTO_INCREMENT for table `ips`
--
ALTER TABLE `ips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
