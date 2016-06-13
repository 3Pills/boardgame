-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2016 at 04:33 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(10) unsigned NOT NULL DEFAULT '0',
  `url` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `about` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fave_char` int(10) unsigned DEFAULT NULL,
  `level` int(10) unsigned NOT NULL DEFAULT '1',
  `exp` int(10) unsigned NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `url`, `about`, `fave_char`, `level`, `exp`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '3Pills', 'threedawg55@gmail.com', '$2y$10$LM.uFrz1Lammlap/9XcfcOo6oEGaLC14kEpOLrSKnjVmABXN04Ka6', 1, 'memersclub', '', NULL, 1, 0, 'RL0fZdoYIiPnNqM6oiZ6iby5L5JLBnp8IHNJyPHEtwas4yrQYZaIxNV27x9q', '2016-04-22 19:59:14', '2016-04-23 04:25:19'),
(2, 'memersclub', 'memers.club@gmail.com', '$2y$10$7ov4P6vMpTvUFvjw84uV/OMNoZS6/zW7rNKDaiJFzSaSbOo0BTdsm', 0, 'nicememer', 'yert', NULL, 1, 0, NULL, '2016-04-22 21:57:09', '2016-04-22 21:57:21'),
(3, 'zigbo', 'Eric_ricketts@yahoo.com', '$2y$10$GkjZUXu.VIl2Gj.9ejCKs.va64u5jGBTFFtVyMdgZ/rLnWWGwK9T2', 0, 'zzkdz8jo', NULL, NULL, 1, 0, NULL, '2016-04-23 04:18:24', '2016-04-23 04:18:24'),
(4, 'WooferZ', 'stephenkoren7@gmail.com', '$2y$10$XfoHRAGI9FSRpedz7g.0s.SEWNzuTpNkDm2ilkVZtOQyFuO1zcxim', 0, 'juuhzjvd', NULL, NULL, 1, 0, 'SblObzflewqgyaMXitlWBIFoKFsJ2rOCv0evTYkW6AzNupWUTwFRTpz2ZCYa', '2016-06-07 18:46:26', '2016-06-08 00:50:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_email_unique` (`email`), ADD UNIQUE KEY `users_url_unique` (`url`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
