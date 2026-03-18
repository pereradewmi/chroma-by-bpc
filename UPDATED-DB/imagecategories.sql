-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 04:08 PM
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
-- Database: `chroma_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `imagecategories`
--

CREATE TABLE `imagecategories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `imagecategories`
--

INSERT INTO `imagecategories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Art', 1, '2026-03-18 14:16:44', '2026-03-18 14:16:44'),
(2, 'Pottery', 1, '2026-03-18 14:16:57', '2026-03-18 14:16:57'),
(3, 'Karate', 1, '2026-03-18 14:17:38', '2026-03-18 14:17:38'),
(4, 'Events', 1, '2026-03-18 14:17:47', '2026-03-18 14:17:47'),
(5, 'Others', 1, '2026-03-18 14:18:06', '2026-03-18 14:18:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `imagecategories`
--
ALTER TABLE `imagecategories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `imagecategories`
--
ALTER TABLE `imagecategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
