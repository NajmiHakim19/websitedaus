-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 19, 2025 at 01:25 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `daus`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_bookings`
--

CREATE TABLE `appointment_bookings` (
  `id` int NOT NULL,
  `location` varchar(100) NOT NULL,
  `doctor` varchar(100) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `concern` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `salutation` varchar(10) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `ic` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointment_bookings`
--

INSERT INTO `appointment_bookings` (`id`, `location`, `doctor`, `specialty`, `concern`, `date`, `time`, `salutation`, `fullname`, `ic`, `phone`, `created_at`) VALUES
(1, 'Gleneagles Hospital Johor', '1', '', 'hello', '2025-07-01', '20:08:00', 'Ms', 'Najmi Hakimah', '123456123245', '6017127727272', '2025-06-19 12:04:18');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `message`, `timestamp`) VALUES
(1, 'muhammad firdaus bin md shahrunnahar ', 'ciku137@gmail.com', 'hahah', '2025-05-30 19:21:14'),
(2, 'CAPIK', 'htsmtiofficial@gmail.com', 'kakakak', '2025-06-02 15:59:34'),
(3, 'Muhammad Firdaus Bin Md Shahrunnahar', 'ngadamdeanna@gmail.com', 'affafaf', '2025-06-02 02:50:44'),
(4, 'dada', 'stellazahnis@gmail.com', 'hai', '2025-06-02 03:15:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `userType` varchar(50) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `icnumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `userType`, `firstname`, `lastname`, `icnumber`) VALUES
('admin', 'admin', 'admin', NULL, NULL, NULL),
('daus', 'daus', 'doctor', 'Muhammad', 'Firdaus', NULL),
('hakim', 'hakim', 'patient', 'Najmi', 'Hakimah', '123456123245');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_bookings`
--
ALTER TABLE `appointment_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_bookings`
--
ALTER TABLE `appointment_bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
