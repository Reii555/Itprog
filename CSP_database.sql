-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 03, 2026 at 10:56 AM
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
-- Database: `CSP_database`
--

CREATE DATABASE IF NOT EXISTS `CSP_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `CSP_database`;

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

--
-- Dumping data for table `ACCOUNTS`
--

INSERT INTO `ACCOUNTS` (`account_id`, `email`, `password`, `role`, `is_active`, `account_created`, `password_upd`, `last_login`) VALUES
(1, 'admin@csp.edu', 'admin123', 'Admin', 1, '2026-04-01 21:07:30', '2026-04-01 21:07:30', '2026-04-01 21:07:30'),
(2, 'it.admin@csp.edu', 'it123', 'IT', 1, '2026-04-01 21:07:30', '2026-04-01 21:07:30', '2026-04-01 21:07:30'),
(3, 'john.doe@email.com', 'password123', 'Student', 1, '2026-04-01 21:07:30', '2026-04-01 21:07:30', '2026-04-01 21:07:30'),
(4, 'jane.smith@email.com', 'password123', 'Student', 1, '2026-04-01 21:07:30', '2026-04-01 21:07:30', '2026-04-01 21:07:30'),
(5, 'bob.wilson@email.com', 'password123', 'Student', 1, '2026-04-01 21:07:30', '2026-04-01 21:07:30', '2026-04-01 21:07:30'),
(6, 'alice.brown@email.com', 'password123', 'Student', 1, '2026-04-01 21:07:30', '2026-04-01 21:07:30', '2026-04-01 21:07:30'),
(7, 'admin2@csp.edu', 'admin123', 'Admin', 1, '2026-04-03 09:48:14', '2026-04-03 09:48:14', '2026-04-03 09:48:14'),
(8, 'admin3@csp.edu', 'admin123', 'Admin', 1, '2026-04-03 09:48:14', '2026-04-03 09:48:14', '2026-04-03 09:48:14'),
(9, 'admin4@csp.edu', 'admin123', 'Admin', 1, '2026-04-03 09:48:14', '2026-04-03 09:48:14', '2026-04-03 09:48:14');

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

--
-- Dumping data for table `ADMINISTRATORS`
--

INSERT INTO `ADMINISTRATORS` (`admin_id`, `account_id`, `first_name`, `last_name`, `contact_num`) VALUES
(1, 1, 'Super Admin', 'User', 912345678),
(2, 2, 'IT', 'Administrator', 923456789),
(3, 7, 'AdminTwo', 'Second', 934567891),
(4, 8, 'AdminThree', 'Third', 945678912),
(5, 9, 'AdminFour', 'Fourth', 956789123);

-- --------------------------------------------------------

--
-- Table structure for table `APPLICATIONS`
--

CREATE TABLE `APPLICATIONS` (
  `application_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `scholarship_id` int(11) NOT NULL,
  `status` enum('Draft','Submitted','Under Review','Approved','Rejected','Waitlisted') NOT NULL,
  `submission_date` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `review_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `APPLICATIONS`
--

INSERT INTO `APPLICATIONS` (`application_id`, `student_id`, `scholarship_id`, `status`, `submission_date`, `reviewed_by`, `review_date`) VALUES
(1, 1, 3, 'Submitted', '2026-03-11 10:30:00', NULL, NULL),
(2, 1, 4, 'Under Review', '2026-04-08 14:45:00', 3, '2026-04-13 15:57:41'),
(3, 2, 3, 'Approved', '2026-03-01 09:15:00', 5, '2026-04-01 16:08:09'),
(4, 2, 4, 'Draft', NULL, NULL, NULL),
(5, 3, 4, 'Rejected', '2026-04-01 16:20:00', 4, '2026-04-15 16:08:36'),
(6, 4, 3, 'Waitlisted', '2026-03-10 14:45:00', 4, '2026-04-02 16:10:00');

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

--
-- Dumping data for table `DOCUMENTS`
--

INSERT INTO `DOCUMENTS` (`document_id`, `application_id`, `file_name`, `file_type`, `docu_type`) VALUES
(1, 1, 'transcript_records.pdf', 'application/pdf', 'Transcript of Records'),
(2, 2, 'recommendation.pdf', 'application/pdf', 'Recommendation Letter'),
(3, 3, 'portfolio.pdf', 'application/pdf', 'Project Portfolio'),
(4, 5, 'proposal.pdf', 'application/pdf', 'Project proposal'),
(5, 5, 'certificate.jpg', 'image/jpeg', 'Certificates of volunteering'),
(6, 1, 'portfolio.pdf', 'application/pdf', 'Project Portfolio'),
(7, 1, 'statement.pdf', 'application/pdf', 'Personal Statement');

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

--
-- Dumping data for table `SCHOLARSHIPS`
--

INSERT INTO `SCHOLARSHIPS` (`scholarship_id`, `title`, `description`, `deadline`, `eligibility`, `requirements`, `release_status`, `status`, `created_by`, `created_at`) VALUES
(1, 'STEM Achievers Grant', 'Supports students in STEM courses.', '2026-02-11 23:59:59', 'Enrolled in STEM program\nGPA of at least 1.75', 'Transcript of Records\nRecommendation Letter\nPersonal Statement', 'Published', 'Completed', 1, '2026-01-11 09:00:00'),
(2, 'Health Heroes Scholarship', 'For aspiring healthcare professionals.', '2026-03-21 23:59:59', 'Enrolled in health-related course\nGood academic standing', 'Transcript of Records\nPersonal Statement\nRecommendation Letter', 'Published', 'Completed', 1, '2026-01-11 09:00:00'),
(3, 'Tech Pioneers Scholarship', 'Supports IT and engineering students.', '2026-04-15 23:59:59', 'Enrolled in IT/Engineering\nGPA of at least 1.75', 'Transcript of Records\nProject portfolio\nPersonal Statement', 'Published', 'Ongoing', 1, '2026-01-11 09:00:00'),
(4, 'Community Builders Grant', 'For projects benefiting local communities.', '2026-04-28 23:59:59', 'Active in community service\nHas ongoing or proposed project', 'Project proposal\nCertificates of volunteering\nRecommendation Letter', 'Published', 'Ongoing', 1, '2026-01-11 09:00:00'),
(5, 'Women in Science Award', 'Encourages women pursuing science degrees.', '2026-04-28 23:59:59', 'Female student in science field\nGPA of at least 1.75', 'Transcript of Records\nPersonal Statement\nRecommendation Letter', 'Published', 'Ongoing', 1, '2026-01-11 09:00:00'),
(6, 'Digital Creators Scholarship', 'For content creators and digital innovators.', '2026-05-01 23:59:59', 'Active digital content creator\nStudent in any program', 'Portfolio of content\nTranscript of Records\nStatement of purpose', 'Published', 'Ongoing', 1, '2026-01-11 09:00:00'),
(7, 'Global Scholars Program', 'For students planning international studies.', '2026-05-03 23:59:59', 'Planning or accepted for international study\nGood academic standing', 'Acceptance letter\nTranscript of Records\nPersonal Statement', 'Published', 'Ongoing', 1, '2026-01-11 09:00:00'),
(8, 'Future Innovators Grant', 'For inventive and tech-forward projects.', '2026-06-01 23:59:59', 'With innovative tech project\nOpen to all students', 'Project proposal\nCV\nTranscript of Records', 'Draft', 'Upcoming', 1, '2026-01-11 09:00:00'),
(9, 'Leadership Excellence Award', 'Recognizes future leaders in any field.', '2026-06-15 23:59:59', 'Demonstrated leadership skills\nActive in organizations', 'Leadership resume\nRecommendation Letters\nPersonal Statement', 'Draft', 'Upcoming', 1, '2026-01-11 09:00:00'),
(10, 'Cultural Ambassadors Scholarship', 'Promotes cultural exchange and arts.', '2026-07-01 23:59:59', 'Involved in cultural/art programs\nOpen to all students', 'Portfolio or proof of participation\nCertificates\nPersonal Statement', 'Draft', 'Upcoming', 1, '2026-01-11 09:00:00'),
(11, 'Green Tech Innovators Grant', 'Supports sustainability tech ideas.', '2026-08-10 23:59:59', 'With green technology idea\nOpen to STEM students', 'Project proposal\nCV\nTranscript of Records', 'Draft', 'Upcoming', 1, '2026-01-11 09:00:00'),
(12, 'Next Gen Scientists Award', 'For promising young science students.', '2026-08-30 23:59:59', 'Enrolled in STEM program\nStrong academic performance', 'Transcript of Records\nResearch proposal\nRecommendation Letter', 'Draft', 'Upcoming', 1, '2026-01-11 09:00:00');

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
-- Dumping data for table `STUDENTS`
--

INSERT INTO `STUDENTS` (`student_id`, `account_id`, `first_name`, `last_name`, `department`, `year_level`, `contact_num`) VALUES
(1, 3, 'John', 'Doe', 'Computer Science', 'Freshman', 912345678),
(2, 4, 'Jane', 'Smith', 'Business Administration', 'Sophomore', 923456789),
(3, 5, 'Bob', 'Wilson', 'Engineering', 'Junior', 934567890),
(4, 6, 'Alice', 'Brown', 'Education', 'Senior', 945678901);

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
  ADD KEY `fk_applications_scholarshio` (`scholarship_id`),
  ADD KEY `fk_applications_admins` (`reviewed_by`) USING BTREE;

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
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ADMINISTRATORS`
--
ALTER TABLE `ADMINISTRATORS`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `APPLICATIONS`
--
ALTER TABLE `APPLICATIONS`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `DOCUMENTS`
--
ALTER TABLE `DOCUMENTS`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `SCHOLARSHIPS`
--
ALTER TABLE `SCHOLARSHIPS`
  MODIFY `scholarship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `STUDENTS`
--
ALTER TABLE `STUDENTS`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `fk_applications_admins` FOREIGN KEY (`reviewed_by`) REFERENCES `ADMINISTRATORS` (`admin_id`),
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
