-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 10, 2025 at 10:04 AM
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
(20, 'sa', 'sa', 'sample@gmail.com', '09854987', '2025-09-03', 'morning', 1, 0, 'completed'),
(21, 'gwapo', 'kaayo', 'asd@gmail.com', '09916732525', '2025-09-18', 'morning', 2, 0, 'completed'),
(22, 'jonathan', 'Nathanqyt', 'akosyempre3@gmail.com', '09916732525', '2025-09-28', 'morning', 1, 0, 'completed'),
(23, 'juan', 'delacruz', 'juandelacruz@gmail.com', '09123456789', '2025-10-03', 'morning', 1, 0, 'pending'),
(24, 'ella', 'natale', 'ella@gmail.com', '096544876154', '2025-10-11', 'morning', 1, 0, 'pending');

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
(50, 200, 'sa', 'sa', 'kentjoshuazamoradaborbor@gmail.com', '09854987', '2025-03-13', '2025-09-28', 1, 'checked_out', '$2y$10$deY3Vagd85poMdQHaZdJXeSAkCeIQXrNQtoPB0gBhtgFcBaSvbrTe'),
(51, 1, 'gwapo', 'kaayo', 'asd@gmail.com', '09916732525', '2025-09-18', '2025-09-18', 2, 'checked_out', NULL),
(52, 1, 'jonathan', 'Nathanqyt', 'akosyempre3@gmail.com', '09916732525', '2025-09-28', '2025-09-28', 1, 'checked_out', NULL),
(53, NULL, 'JOHN MICHAEL', 'TAN', 'johnmichael.tan@deped.gov.ph', '09652850165', NULL, NULL, 0, 'checked_out', '$2y$10$vImgoeI7ve/gxvQ.Sv.6uOEHXcbyMOVUplKqYGMD8IOb20FNCPciS'),
(54, NULL, 'jonathan', 'gregorio', 'jonathan@gmail.com', '09916732525', NULL, NULL, 0, 'checked_out', '$2y$10$12QrLNM11qYhtKq9kzSWVuqmInRUq1EziiNkDsgHwzGoseqUcF4Xe'),
(55, NULL, 'ella', 'natale', 'ella@gmail.com', '096544876154', NULL, NULL, 0, 'checked_out', '$2y$10$511RK09kdg1hBh4B8WiuY.kgvi1pDeUF5iCdOyy2JZPzGaqQKf3TC'),
(56, 1, 'xamp', 'ple', 'xamp@gmail.com', '096549879', '2025-10-10', '2025-10-10', 0, 'checked_out', NULL);

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

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'kentjoshuazamoradaborbor@gmail.com', '58255a172abd506a607fa21d994431b9f218da37eeadceea05a24b6849cd8f29', '2025-08-31 01:57:38'),
(2, 'kentjoshuazamoradaborbor@gmail.com', '1a694ae0af328c673aea7dea854d5d2b28f9b15b90a9f6684d609d39c051bebe', '2025-08-31 08:13:27'),
(3, 'kentjoshuazamoradaborbor@gmail.com', '2af1e3f702e287cc2a8454bd4fdc87bc06a2a56568430fba937242e9c0b08b09', '2025-10-04 11:28:33'),
(4, 'kentjoshuazamoradaborbor@gmail.com', 'e93f14ab34e444ddcac163e6742e29beecefc6e29fff86ae387b24e2b42b78ba', '2025-10-04 11:36:00'),
(5, 'kentjoshuazamoradaborbor@gmail.com', '2b024b14fff4fc173f33aa27a3f47948e81e04a09432d891a9035ea346201081', '2025-10-04 11:37:49'),
(6, 'asd@gmail.com', '2f8bcdaeed94790ee710a1972cc04de6526abbdee19319227b5421b655e98349', '2025-10-09 03:11:10');

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
(1, NULL, 16, 'available', 'no'),
(103, NULL, 21, 'available', 'no'),
(200, NULL, 16, 'available', 'no');

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
(16, 'single', 150.00, '2025-08-04 06:13:01', 'no'),
(17, 'double', 6000.00, '2025-08-04 06:13:14', 'no'),
(19, 'deluxe', 10000.00, '2025-08-16 01:52:40', 'no'),
(21, 'Matrimonial', 1500.00, '2025-10-02 08:50:48', 'no');

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
(50, 50, 200, 16, '2025-03', 'break coffee glass', 50.00, 4700.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-08-31 08:15:26'),
(51, 50, 200, 16, '2025-04', '', 0.00, 4500.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-08-31 08:16:06'),
(52, 51, 1, 16, '2025-09', '', 0.00, 450.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-09-18 02:31:28'),
(53, 52, 1, 16, '2025-09', '', 0.00, 450.00, '00:00:00', '0000-00-00', '00:00:00', '', '1', '2025-09-28 07:54:18');

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `room_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

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
