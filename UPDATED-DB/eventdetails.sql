-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 08:38 AM
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
-- Table structure for table `eventdetails`
--

CREATE TABLE `eventdetails` (
  `eID` int(11) NOT NULL,
  `eName` varchar(100) NOT NULL,
  `eDescription` varchar(500) NOT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  `eImage` varchar(500) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1 = active 0 = inactive 2 = delete',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventdetails`
--

INSERT INTO `eventdetails` (`eID`, `eName`, `eDescription`, `dateFrom`, `dateTo`, `eImage`, `status`, `updated_at`, `created_at`) VALUES
(1, 'Celebrate Christmas with massive savings 🎄✨', 'Up to 80% OFF on your favourite brands !! Starting from Rs. 100/-.\r\n📍At CHROMA, Negombo.\r\n🗓️From December 18th - 20th.', '2025-12-18', '2025-12-20', '1773813396_69ba3e9456e77.jpg', 1, '2026-03-18 05:56:36', '2026-03-13 06:05:55'),
(2, 'Chroma with Abans', 'For the first time in Negombo, over six top global brands are bringing their biggest discounts to Chroma with Abans!                                                                                                                                                                                \r\n🔥 BOSS • Under Armour • Skechers • Abans Times Square • Titan • Miniso\r\nGet up to 80% OFF — only at Chroma from Dec 18–20', '2025-12-18', '2025-12-20', '1773813481_69ba3ee9612c7.jpg', 1, '2026-03-18 05:58:01', '2026-03-13 05:16:04'),
(3, 'Track dancing class.', 'Running on beats🎶, dancing on vibes ✨!\r\nJoin with us !\r\n\r\n❕Track dancing class.\r\n❕Starting from 28th Sept.\r\n❕From 10:30 am - 11:30 am.\r\n\r\n❗Join our first session for FREE.\r\n\r\nContact, 076 661 3376 😊', '2025-09-18', NULL, '1773813579_69ba3f4b63d4b.jpg', 1, '2026-03-18 05:59:39', '2026-03-18 05:59:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventdetails`
--
ALTER TABLE `eventdetails`
  ADD PRIMARY KEY (`eID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eventdetails`
--
ALTER TABLE `eventdetails`
  MODIFY `eID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
