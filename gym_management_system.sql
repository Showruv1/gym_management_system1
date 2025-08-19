-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2025 at 08:55 PM
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
-- Database: `gym_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_card`
--

CREATE TABLE `access_card` (
  `Access_card_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `Attendance_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Access_card_id` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Check_in_time` time DEFAULT NULL,
  `Check_out_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `Member_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Membership_id` int(11) DEFAULT NULL,
  `Access_card_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `Membership_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `Payment_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `Trainer_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Specialization` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_assignment`
--

CREATE TABLE `trainer_assignment` (
  `Assignment_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Trainer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_card`
--
ALTER TABLE `access_card`
  ADD PRIMARY KEY (`Access_card_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`Attendance_id`),
  ADD KEY `Member_id` (`Member_id`),
  ADD KEY `Access_card_id` (`Access_card_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`Member_id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Membership_id` (`Membership_id`),
  ADD KEY `Access_card_id` (`Access_card_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`Membership_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`Payment_id`),
  ADD KEY `Member_id` (`Member_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`Trainer_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `trainer_assignment`
--
ALTER TABLE `trainer_assignment`
  ADD PRIMARY KEY (`Assignment_id`),
  ADD KEY `Member_id` (`Member_id`),
  ADD KEY `Trainer_id` (`Trainer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_card`
--
ALTER TABLE `access_card`
  MODIFY `Access_card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `Attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `Member_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `Membership_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `Payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `Trainer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_assignment`
--
ALTER TABLE `trainer_assignment`
  MODIFY `Assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`Member_id`) REFERENCES `members` (`Member_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`Access_card_id`) REFERENCES `access_card` (`Access_card_id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`Membership_id`) REFERENCES `membership` (`Membership_id`),
  ADD CONSTRAINT `members_ibfk_2` FOREIGN KEY (`Access_card_id`) REFERENCES `access_card` (`Access_card_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`Member_id`) REFERENCES `members` (`Member_id`);

--
-- Constraints for table `trainer_assignment`
--
ALTER TABLE `trainer_assignment`
  ADD CONSTRAINT `trainer_assignment_ibfk_1` FOREIGN KEY (`Member_id`) REFERENCES `members` (`Member_id`),
  ADD CONSTRAINT `trainer_assignment_ibfk_2` FOREIGN KEY (`Trainer_id`) REFERENCES `trainers` (`Trainer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
