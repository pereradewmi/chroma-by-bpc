-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 10:30 AM
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
-- Table structure for table `classdetails`
--

CREATE TABLE `classdetails` (
  `cID` int(11) NOT NULL,
  `cName` varchar(50) NOT NULL,
  `classfee` int(11) NOT NULL,
  `cDescription` varchar(1000) NOT NULL,
  `cImage` varchar(500) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classdetails`
--

INSERT INTO `classdetails` (`cID`, `cName`, `classfee`, `cDescription`, `cImage`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Elocution', 3000, '<div><span style=\"font-size: 1rem; font-weight: bolder;\">(Monthly fee – 3000/-)&nbsp;</span></div><div><span style=\"font-weight: bolder;\"><br></span><div>Monday (age 3 - 5) (Time 4:00 pm – 5:00 pm)&nbsp;</div><div>Wednesday (age 5 - 12) (Time 3:30 pm – 5:00 pm)</div></div><!--EndFragment-->', '1773825310_69ba6d1edaf6b.jpg', 1, '2026-03-13 06:05:41', '2026-03-18 09:29:16'),
(2, 'Art', 1000, '<b style=\"font-size: 1rem;\">KIDS (Monthly fee 3000/-)&nbsp;</b><br><div><span style=\"font-size: 1rem;\">Tuesday (Time 4:30 pm – 6:00 pm)\r\nFriday (Time 4:00 pm – 5:30 pm)&nbsp;</span></div><div><br><div><b style=\"font-size: 1rem;\">ADULTS (Below 18 – 3000/-) (Above 18 – 4000/-)</b><span style=\"font-size: 1rem;\">&nbsp;</span></div><div>Saturday (Time 3:30 pm – 5:00 pm)</div></div>', '1773824976_69ba6bd0944e8.jpg', 1, '2026-03-13 05:15:30', '2026-03-18 09:18:47'),
(3, 'KARATE', 3000, '<b>(Monthly fee – 3000/-)&nbsp;</b><div><br></div><div>Saturday (above 5) (Time 10:30 am – 12:30 pm) (old batch)&nbsp;</div><div>Saturday (above 5) (Time 1:00 pm – 3:00 pm) (new batch)\r\n                                            </div>', '1773825486_69ba6dceaa18b.jpg', 1, '2026-03-18 08:26:16', '2026-03-18 09:19:12'),
(4, 'VOCAL TRAINING', 4000, '<b>(Voice print academy) (Monthly fee – 4000/-)&nbsp;</b><div><br></div><div>KIDS\r\nSaturday (Time 9:30 am – 10:30 am)&nbsp;</div><div><span style=\"font-size: 1rem;\">TEENS\r\nSaturday (Time 8:30 am – 9:30 am)</span><br></div>', '1773825880_69ba6f58c2e20.jpg', 1, '2026-03-18 09:21:51', '2026-03-18 09:24:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classdetails`
--
ALTER TABLE `classdetails`
  ADD PRIMARY KEY (`cID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classdetails`
--
ALTER TABLE `classdetails`
  MODIFY `cID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
