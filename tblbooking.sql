-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 06, 2024 at 06:53 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carrental`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `id` int(11) NOT NULL,
  `BookingNumber` bigint(12) DEFAULT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `VehicleId` int(11) DEFAULT NULL,
  `FromDate` varchar(20) DEFAULT NULL,
  `ToDate` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_card` varchar(255) DEFAULT NULL,
  `driving_license` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`id`, `BookingNumber`, `userEmail`, `VehicleId`, `FromDate`, `ToDate`, `message`, `Status`, `PostingDate`, `LastUpdationDate`, `id_card`, `driving_license`) VALUES
(13, 397186812, 'test@gmail.com', 2, '2024-11-03', '2024-11-05', 'test', 1, '2024-11-01 17:41:27', '2024-11-06 05:42:07', 'uploads/idcard/bracelet2.jpg', 'uploads/license/daisy bracelet.jpg'),
(14, 511573465, 'test@gmail.com', 2, '2024-11-09', '2024-11-10', 'test', 1, '2024-11-01 17:44:55', '2024-11-06 05:42:20', 'uploads/idcard/bracelet2.jpg', 'uploads/license/daisy bracelet.jpg'),
(15, 789416472, 'test@gmail.com', 2, '2024-11-15', '2024-11-16', 'hi', 0, '2024-11-01 17:56:17', NULL, 'uploads/idcard/bracelet.jpg', 'uploads/license/charm2.png'),
(16, 436235601, 'test@gmail.com', 3, '2024-11-03', '2024-11-04', 'hi', 0, '2024-11-01 17:57:50', NULL, 'uploads/idcard/bracelet.jpg', 'uploads/license/charm2.png'),
(17, 776176195, 'test@gmail.com', 5, '2024-11-10', '2024-11-17', 'lol', 0, '2024-11-01 18:13:16', NULL, 'uploads/idcard/charmlogo.jpeg', 'uploads/license/couple set butterfly.jpg'),
(18, 318774348, 'test@gmail.com', 4, '2024-11-03', '2024-11-04', 'hh', 1, '2024-11-01 18:24:55', NULL, 'uploads/idcard/Cresent moon.jpg', 'uploads/license/daisy bracelet.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
