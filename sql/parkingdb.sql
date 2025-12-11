-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 05:55 PM
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
-- Database: `parkingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `slot_code` varchar(10) NOT NULL,
  `reserved_by` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `booking_time` datetime DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_slots`
--

CREATE TABLE `parking_slots` (
  `id` int(11) NOT NULL,
  `slot_code` varchar(10) NOT NULL,
  `status` enum('available','occupied') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_slots`
--

INSERT INTO `parking_slots` (`id`, `slot_code`, `status`) VALUES
(1, 'A1', 'available'),
(2, 'A2', 'available'),
(3, 'A3', 'available'),
(4, 'A4', 'available'),
(5, 'A5', 'available'),
(6, 'A6', 'available'),
(7, 'A7', 'available'),
(8, 'A8', 'available'),
(9, 'A9', 'available'),
(10, 'A10', 'available'),
(11, 'A11', 'available'),
(12, 'A12', 'available'),
(13, 'A13', 'available'),
(14, 'A14', 'available'),
(15, 'A15', 'available'),
(16, 'A16', 'available'),
(17, 'B1', 'available'),
(18, 'B2', 'available'),
(19, 'B3', 'available'),
(20, 'B4', 'available'),
(21, 'B5', 'available'),
(22, 'B6', 'available'),
(23, 'B7', 'available'),
(24, 'B8', 'available'),
(25, 'B9', 'available'),
(26, 'B10', 'available'),
(27, 'B11', 'available'),
(28, 'B12', 'available'),
(29, 'B13', 'available'),
(30, 'B14', 'available'),
(31, 'B15', 'available'),
(32, 'B16', 'available'),
(33, 'C1', 'available'),
(34, 'C2', 'available'),
(35, 'C3', 'available'),
(36, 'C4', 'available'),
(37, 'C5', 'available'),
(38, 'C6', 'available'),
(39, 'C7', 'available'),
(40, 'C8', 'available'),
(41, 'C9', 'available'),
(42, 'C10', 'available'),
(43, 'C11', 'available'),
(44, 'C12', 'available'),
(45, 'C13', 'available'),
(46, 'C14', 'available'),
(47, 'C15', 'available'),
(48, 'C16', 'available'),
(49, 'D1', 'available'),
(50, 'D2', 'available'),
(51, 'D3', 'available'),
(52, 'D4', 'available'),
(53, 'D5', 'available'),
(54, 'D6', 'available'),
(55, 'D7', 'available'),
(56, 'D8', 'available'),
(57, 'D9', 'available'),
(58, 'D10', 'available'),
(59, 'D11', 'available'),
(60, 'D12', 'available'),
(61, 'D13', 'available'),
(62, 'D14', 'available'),
(63, 'D15', 'available'),
(64, 'D16', 'available'),
(65, 'E1', 'available'),
(66, 'E2', 'available'),
(67, 'E3', 'available'),
(68, 'E4', 'available'),
(69, 'E5', 'available'),
(70, 'E6', 'available'),
(71, 'E7', 'available'),
(72, 'E8', 'available'),
(73, 'E9', 'available'),
(74, 'E10', 'available'),
(75, 'E11', 'available'),
(76, 'E12', 'available'),
(77, 'E13', 'available'),
(78, 'E14', 'available'),
(79, 'E15', 'available'),
(80, 'E16', 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slot_code` (`slot_code`);

--
-- Indexes for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slot_code` (`slot_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_slots`
--
ALTER TABLE `parking_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`slot_code`) REFERENCES `parking_slots` (`slot_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
