-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2025 at 05:13 AM
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
-- Database: `db_hor`
--

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
(1, '', '', 'asd', '$2y$10$deY3Vagd85poMdQHaZdJXeSAkCeIQXrNQtoPB0gBhtgFcBaSvbrTe');

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
(11, 'kenasd', 'daborbor', 'ksdfamoradaborbor@gmail.com', '09798765465', '2025-08-05', 'morning', 1, 0, 'completed');

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
  `checkin_date` date NOT NULL,
  `checkout_date` date DEFAULT NULL,
  `no_of_guest` tinyint(1) NOT NULL,
  `status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `room_id`, `first_name`, `last_name`, `email`, `phone`, `checkin_date`, `checkout_date`, `no_of_guest`, `status`) VALUES
(33, 203, 'kenasd', 'daborbor', 'ksdfamoradaborbor@gmail.com', '09798765465', '2025-02-04', '2025-08-04', 1, 'checked_out');

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
(203, 33, 16, 'available', 'no'),
(204, NULL, 17, 'available', 'no');

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
(16, 'single', 5000.00, '2025-08-04 06:13:01', 'no'),
(17, 'double', 6000.00, '2025-08-04 06:13:14', 'no');

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
  `description` varchar(200) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `checkin_time` time NOT NULL,
  `checkout` date NOT NULL,
  `checkout_time` time NOT NULL,
  `bill` varchar(10) NOT NULL,
  `is_paid` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `guest_id`, `room_id`, `room_type_id`, `bill_month`, `description`, `amount`, `total_amount`, `checkin_time`, `checkout`, `checkout_time`, `bill`, `is_paid`, `created_at`) VALUES
(47, 33, 203, 16, '2025-02', 'wer', 3000.00, 8000.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-08-15 11:13:20');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  MODIFY `room_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
