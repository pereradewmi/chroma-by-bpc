-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 04:07 PM
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
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `image_path` varchar(1000) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=active| 0=inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `c_id`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'images/XHcsCLSJUkPR8hu9vTOsCtOCRvo1yTMCfzrZJWZh.jpg', 1, '2026-03-01 11:53:26', '2026-03-01 11:53:26'),
(2, 2, 'images/HPredTv9reIdzC1i6fRgKBayyc36KSPeuFLc6TNc.jpg', 1, '2026-03-01 12:02:28', '2026-03-01 12:02:28'),
(3, 1, 'images/fSXgYssSDz4G24DOqWqrppgbDwlU7vFPJYxXuIVI.jpg', 1, '2026-03-01 12:06:25', '2026-03-01 12:06:25'),
(4, 3, 'images/1I8q6XcttUlM78z9otUd22H8bYl4aCuQJZi2WtDw.jpg', 1, '2026-03-01 12:06:46', '2026-03-01 12:06:46'),
(5, 3, 'images/ugMpyMapsZZqcy5H5K5TYuVvuL8g1y2d8Qf829Qv.jpg', 1, '2026-03-01 12:07:04', '2026-03-01 12:07:04'),
(6, 1, 'images/7wutQOO3OmkGQPlfjspCPl5gAFqjhtK4dlwQEQXe.jpg', 1, '2026-03-01 12:07:25', '2026-03-01 12:07:25'),
(7, 2, 'images/J8RI1gbDsLoi28D36nSEcLQEI1ImqImfLMzZxAfq.jpg', 1, '2026-03-18 09:36:54', '2026-03-18 09:36:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
