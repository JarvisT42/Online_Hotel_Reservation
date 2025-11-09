-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2025 at 10:58 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u756490121_db_hor`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_charge`
--

CREATE TABLE `additional_charge` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `description` varchar(250) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `additional_charge`
--

INSERT INTO `additional_charge` (`id`, `guest_id`, `transaction_id`, `description`, `date`, `amount`, `paid`) VALUES
(28, 81, 610, 'kutsara na piko', '2025-10-25', 2000.00, 1),
(29, 82, 611, 'nasunog ang habol', '2025-10-25', 1000.00, 1),
(30, 83, 612, 'sudaan', '2025-10-25', 50.00, 1),
(31, 84, NULL, 'baso', '2025-02-10', 150.00, 0),
(32, 87, 624, 'a', '2025-11-02', 655.00, 1),
(33, 88, 626, 'nakabasag vase', '2025-11-06', 2500.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(24) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `username`, `password`) VALUES
(1, 'sdf', 'admin@gmail.com', 'asd', '$2y$10$2S6T3oW20NUVHsoQpjTQseqfz7XBBcG18OMCisLR0jDl9N9Evw5oq'),
(3, 'asd', 'asd@gmail.com', 'asd', '$2y$10$M6uQql7o5/uTEHFXfQrl.u8GLzfgCZfzvgBsaxGG.qslvT3cNi826');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` varchar(20) NOT NULL,
  `no_of_guest` tinyint(1) NOT NULL,
  `room_type_id` int(10) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `first_name`, `last_name`, `email`, `phone`, `booking_date`, `booking_time`, `no_of_guest`, `room_type_id`, `status`) VALUES
(37, 'ya', 'ho', 'yaho.@gmail.com', '09675365435', '2025-10-25', 'morning', 1, 0, 'completed'),
(38, 'buti', 'te', 'butite@gmail.com', '09776186294', '2025-10-25', 'morning', 1, 0, 'completed'),
(39, 'test', 'ting', 'test@gmail.com', '09776186294', '2025-10-25', 'morning', 1, 0, 'completed'),
(40, 'sample', '1', 'sample@gmail.com', '09776186294', '2025-11-01', 'morning', 1, 0, 'completed'),
(41, 'sample', '2', 'sample2@gmail.com', '09916732525', '2025-11-28', 'morning', 1, 0, 'completed'),
(42, 'jgfgf@gmail.com', 'gffd', 'jgfgf@gmail.com', '987987', '2025-09-02', 'morning', 1, 0, 'completed'),
(43, 'sample ', '5', 'sample5@gmail.com', '09776186294', '2025-11-01', 'morning', 1, 0, 'pending'),
(44, 'sample', '6', 'sample6@gmail.com', '09916732525', '2025-10-28', 'morning', 1, 0, 'completed'),
(45, 'ngg', 'ngglast', 'ngg@gmail.com', '09987989898', '2025-11-10', 'morning', 2, 0, 'completed'),
(46, 'pataka', 'second', 'pataka@gmail.com', '09654897998', '2025-11-08', 'morning', 1, 0, 'completed'),
(47, 'dsd', 'dsd', 'asdasdasd@gmail.com', '09103250815', '2025-11-07', 'morning', 2, 0, 'cancelled'),
(48, 'dsd', 'dsd', 'asdasdasd@gmail.com', '09103250815', '2025-11-07', 'morning', 2, 0, 'cancelled'),
(49, 'sample', '8', 'sample8@gmail.com', '12345678901', '2025-11-09', 'afternoon', 1, 0, 'completed'),
(50, 'sample ', '9', 'sample9@gmail.com', '12345678901', '2025-11-10', 'morning', 1, 0, 'cancelled'),
(51, 'sample ', '9', 'sample9@gmail.com', '12345678901', '2025-11-10', 'morning', 1, 0, 'completed'),
(52, 'sample', '00', 'sample00@gmail.com', '12345678901', '2025-11-09', 'afternoon', 1, 0, 'completed'),
(53, 'exam', 'ple', 'example@gmail.com', '09888506671', '2025-11-09', 'morning', 2, 0, 'pending'),
(54, 'one', 'two', 'onetwo@gmail.com', '09888506671', '2025-11-10', 'morning', 1, 0, 'cancelled'),
(55, 'one', 'two', 'onetwo@gmail.com', '09888506671', '2025-11-10', 'morning', 1, 0, 'completed'),
(56, 'sample', '50', 'sample50@gmail.com', '12345678901', '2025-11-09', 'afternoon', 1, 0, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `no_of_guest` tinyint(1) NOT NULL,
  `status` varchar(200) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `room_id`, `first_name`, `last_name`, `email`, `phone`, `checkin_date`, `checkout_date`, `no_of_guest`, `status`, `password`) VALUES
(81, 200, 'ya', 'ho', 'yaho.@gmail.com', '09675365435', '2025-10-25', '2025-10-25', 1, 'checked_out', NULL),
(82, 3, 'buti', 'te', 'butite@gmail.com', '09776186294', '2025-10-25', '2025-10-25', 1, 'checked_out', '$2y$10$2S6T3oW20NUVHsoQpjTQseqfz7XBBcG18OMCisLR0jDl9N9Evw5oq'),
(83, 3, 'test', 'ting', 'test@gmail.com', '09776186294', '2025-10-25', '2025-10-25', 1, 'checked_out', '$2y$10$RipXRBpg/3QOOkiySVuGs.VthIEy0p0Y8f3lkDCPJWTMPDW0wlzmu'),
(84, 3, 'sample', '1', 'sample@gmail.com', '09776186294', '2025-02-04', NULL, 1, 'checked_in', NULL),
(85, 4, 'sample', '2', 'sample2@gmail.com', '09916732525', '2025-09-01', NULL, 1, 'checked_in', NULL),
(86, 2, 'jgfgf@gmail.com', 'gffd', 'jgfgf@gmail.com', '987987', '2025-10-28', NULL, 1, 'checked_in', NULL),
(87, 2001, 'ngg', 'ngglast', 'ngg@gmail.com', '09987989898', '2025-11-02', '2025-11-02', 2, 'checked_out', '$2y$10$om05qm0y9r6aHl8hpM.g2e1opjwCEDJmdt0s5MX4T1xIdktKMUvs.'),
(88, 54, 'pataka', 'second', 'pataka@gmail.com', '09654897998', '2025-11-06', '2025-11-06', 1, 'checked_out', NULL),
(89, 54, 'sample', '6', 'sample6@gmail.com', '09916732525', '2025-11-07', NULL, 1, 'checked_in', NULL),
(90, 54, 'sample', '6', 'sample6@gmail.com', '09916732525', '2025-11-07', '2025-11-08', 1, 'checked_out', NULL),
(91, 2001, 'sample', '8', 'sample8@gmail.com', '12345678901', '2025-11-08', NULL, 1, 'checked_in', NULL),
(92, 200, 'sample ', '9', 'sample9@gmail.com', '12345678901', '2025-11-08', NULL, 1, 'checked_in', NULL),
(93, 200, 'sample ', '9', 'sample9@gmail.com', '12345678901', '2025-11-08', NULL, 1, 'checked_in', NULL),
(94, 54, 'sample', '00', 'sample00@gmail.com', '12345678901', '2025-11-08', NULL, 1, 'checked_in', NULL),
(95, 35, 'one', 'two', 'onetwo@gmail.com', '09888506671', '2025-11-09', '2025-11-09', 1, 'checked_out', '$2y$10$FbLVT8XIrFnvPt/0RsdFeu2h7bs0joLoRnioqRnFqyaRUFKGVkshe'),
(96, 35, 'sample', '50', 'sample50@gmail.com', '12345678901', '2025-11-09', '2025-11-09', 1, 'checked_out', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `room_type_id` int(10) NOT NULL,
  `status` enum('available','occupied') NOT NULL,
  `archive` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `guest_id`, `room_type_id`, `status`, `archive`) VALUES
(2, 86, 23, 'occupied', 'no'),
(3, 84, 22, 'occupied', 'no'),
(4, 85, 22, 'occupied', 'no'),
(35, NULL, 22, 'available', 'no'),
(54, 94, 24, 'occupied', 'no'),
(200, 93, 22, 'occupied', 'no'),
(2001, 91, 22, 'occupied', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `rooms_archive`
--

CREATE TABLE `rooms_archive` (
  `archive_id` int(11) NOT NULL,
  `room` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_price`
--

CREATE TABLE `room_price` (
  `room_price_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `room_type_id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `price` double(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archive` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `type`, `price`, `created_at`, `archive`) VALUES
(22, 'single', 150.00, '2025-10-16 12:34:00', 'no'),
(23, 'double', 120.00, '2025-10-17 09:06:27', 'no'),
(24, 'deluxe', 300.00, '2025-11-06 00:18:07', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_type_id` int(5) NOT NULL,
  `bill_month` varchar(7) NOT NULL,
  `days_rendered` int(11) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `room_charge` double(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `checkin_time` time NOT NULL,
  `checkout` date NOT NULL,
  `checkout_time` time NOT NULL,
  `bill` varchar(10) NOT NULL,
  `is_paid` varchar(200) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `guest_id`, `room_id`, `room_type_id`, `bill_month`, `days_rendered`, `description`, `room_charge`, `total_amount`, `checkin_time`, `checkout`, `checkout_time`, `bill`, `is_paid`, `transaction_date`) VALUES
(610, 81, 200, 22, '2025-10', NULL, '', 150.00, 2150.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-10-25 04:10:47'),
(611, 82, 3, 22, '2025-10', NULL, '', 150.00, 1150.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-10-25 04:21:02'),
(612, 83, 3, 22, '2025-10', NULL, '', 150.00, 200.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-10-25 05:42:05'),
(613, 85, 4, 22, '2025-08', NULL, '', 4650.00, 4650.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-10-28 05:22:47'),
(620, 87, 2001, 22, '2025-11', 1, '', 300.00, 300.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-01 11:02:40'),
(621, 87, 2001, 22, '2025-10', 31, '', 4650.00, 4650.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-02 11:03:01'),
(622, 87, 2001, 22, '2025-09', 22, '', 3300.00, 3300.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-02 11:03:05'),
(624, 87, 2001, 22, '2025-11', 1, '', 150.00, 805.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-02 12:16:05'),
(625, 84, 3, 22, '2025-02', 25, '', 3750.00, 3750.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-03 03:39:35'),
(626, 88, 54, 24, '2025-11', 1, '', 300.00, 2800.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-06 00:22:57'),
(627, 90, 54, 24, '2025-11', 3, '', 900.00, 900.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-08 16:18:29'),
(628, 95, 35, 22, '2025-11', 1, '', 150.00, 150.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-09 02:00:41'),
(629, 96, 35, 22, '2025-11', 1, '', 150.00, 150.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-11-09 02:20:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_charge`
--
ALTER TABLE `additional_charge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`) USING BTREE,
  ADD KEY `guest_id` (`guest_id`);

--
-- Indexes for table `rooms_archive`
--
ALTER TABLE `rooms_archive`
  ADD PRIMARY KEY (`archive_id`);

--
-- Indexes for table `room_price`
--
ALTER TABLE `room_price`
  ADD PRIMARY KEY (`room_price_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`room_type_id`),
  ADD KEY `price_id` (`price`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_charge`
--
ALTER TABLE `additional_charge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rooms_archive`
--
ALTER TABLE `rooms_archive`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_price`
--
ALTER TABLE `room_price`
  MODIFY `room_price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=630;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`guest_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
