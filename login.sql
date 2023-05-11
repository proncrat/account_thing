-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2023 at 02:00 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `sync_time` datetime DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `email` text NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT '../img/guest.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `sync_time`, `data`, `email`, `image`) VALUES
(2, 'test', '$2y$10$FWBZrArmkAAgBbapMx..Ie0w35bkbKwT3zFUvgOMbyOvSlfRNT8ke', '2023-05-06 18:55:16', '2023-05-11 11:57:50', '{\"settings\":{\"auto_next\":true,\"auto_play\":true,\"auto_skip_intro\":false,\"auto_skip_outro\":false,\"auto_sync\":true,\"share_data\":false,\"custom_recom\":false},\"watch_data\":[]}', '', '../img/users/2.png'),
(3, 'ballz', '$2y$10$CqcovYDojoLESaCt4bzdEOu/Y1hF5onRkdSJ7udAUdcRYJT2iy1QC', '2023-05-10 23:52:55', '2023-05-11 00:00:04', '{\"settings\":{\"auto_next\":false,\"auto_play\":true,\"auto_skip_intro\":false,\"auto_skip_outro\":false,\"auto_sync\":true,\"share_data\":false,\"custom_recom\":false},\"watch_data\":[]}', '', '../img/guest.jpg'),
(4, 'test1', '$2y$10$wXE2P/aVaS1Bo4HZD/kgo.efDdUgwqUYWSLMqB.ELLJR9q8.nzG76', '2023-05-11 00:41:57', '2023-05-11 00:42:17', '{\"settings\":{\"auto_next\":true,\"auto_play\":true,\"auto_skip_intro\":false,\"auto_skip_outro\":false,\"auto_sync\":true,\"share_data\":false,\"custom_recom\":false},\"watch_data\":[]}', '', '../img/guest.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
