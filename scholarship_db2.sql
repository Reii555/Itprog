-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 01, 2026 at 03:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scholarship_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ACCOUNTS`
--

CREATE TABLE `ACCOUNTS` (
  `account_id` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `role` enum('Student','Admin','IT') NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `account_created` datetime NOT NULL,
  `password_upd` datetime NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ADMINISTRATORS`
--

CREATE TABLE `ADMINISTRATORS` (
  `admin_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `contact_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `APPLICATIONS`
--

CREATE TABLE `APPLICATIONS` (
  `application_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `scholarship_id` int(11) NOT NULL,
  `status` enum('Draft','Submitted','Under Review','Approved','Rejected','Waitlisted') NOT NULL,
  `submission_date` datetime NOT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `review_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DOCUMENTS`
--

CREATE TABLE `DOCUMENTS` (
  `document_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `file_name` varchar(45) NOT NULL,
  `file_type` varchar(45) NOT NULL,
  `docu_type` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `SCHOLARSHIPS`
--

CREATE TABLE `SCHOLARSHIPS` (
  `scholarship_id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `deadline` datetime NOT NULL,
  `eligibility` text NOT NULL,
  `requirements` text NOT NULL,
  `release_status` enum('Draft','Published') NOT NULL,
  `status` enum('Completed','Ongoing','Upcoming') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `STUDENTS`
--

CREATE TABLE `STUDENTS` (
  `student_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `department` varchar(45) NOT NULL,
  `year_level` enum('Freshman','Sophomore','Junior','Senior') NOT NULL,
  `contact_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ACCOUNTS`
--
ALTER TABLE `ACCOUNTS`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `ADMINISTRATORS`
--
ALTER TABLE `ADMINISTRATORS`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_admin_accounts` (`account_id`);

--
-- Indexes for table `APPLICATIONS`
--
ALTER TABLE `APPLICATIONS`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `fk_applications_students` (`student_id`),
  ADD KEY `fk_applications_scholarshio` (`scholarship_id`);

--
-- Indexes for table `DOCUMENTS`
--
ALTER TABLE `DOCUMENTS`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `fk_documents_applications` (`application_id`);

--
-- Indexes for table `SCHOLARSHIPS`
--
ALTER TABLE `SCHOLARSHIPS`
  ADD PRIMARY KEY (`scholarship_id`),
  ADD KEY `fk_scholarships_admin` (`created_by`);

--
-- Indexes for table `STUDENTS`
--
ALTER TABLE `STUDENTS`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_students_accounts` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ACCOUNTS`
--
ALTER TABLE `ACCOUNTS`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ADMINISTRATORS`
--
ALTER TABLE `ADMINISTRATORS`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `APPLICATIONS`
--
ALTER TABLE `APPLICATIONS`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `DOCUMENTS`
--
ALTER TABLE `DOCUMENTS`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SCHOLARSHIPS`
--
ALTER TABLE `SCHOLARSHIPS`
  MODIFY `scholarship_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `STUDENTS`
--
ALTER TABLE `STUDENTS`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ADMINISTRATORS`
--
ALTER TABLE `ADMINISTRATORS`
  ADD CONSTRAINT `fk_admin_accounts` FOREIGN KEY (`account_id`) REFERENCES `ACCOUNTS` (`account_id`);

--
-- Constraints for table `APPLICATIONS`
--
ALTER TABLE `APPLICATIONS`
  ADD CONSTRAINT `fk_applications_scholarshio` FOREIGN KEY (`scholarship_id`) REFERENCES `SCHOLARSHIPS` (`scholarship_id`),
  ADD CONSTRAINT `fk_applications_students` FOREIGN KEY (`student_id`) REFERENCES `STUDENTS` (`student_id`);

--
-- Constraints for table `DOCUMENTS`
--
ALTER TABLE `DOCUMENTS`
  ADD CONSTRAINT `fk_documents_applications` FOREIGN KEY (`application_id`) REFERENCES `APPLICATIONS` (`application_id`);

--
-- Constraints for table `SCHOLARSHIPS`
--
ALTER TABLE `SCHOLARSHIPS`
  ADD CONSTRAINT `fk_scholarships_admin` FOREIGN KEY (`created_by`) REFERENCES `ADMINISTRATORS` (`admin_id`);

--
-- Constraints for table `STUDENTS`
--
ALTER TABLE `STUDENTS`
  ADD CONSTRAINT `fk_students_accounts` FOREIGN KEY (`account_id`) REFERENCES `ACCOUNTS` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
