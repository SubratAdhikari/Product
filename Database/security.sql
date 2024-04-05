-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2024 at 06:05 PM
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
-- Database: `security`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `postid` int(10) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `time` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `userid`, `postid`, `comment`, `time`) VALUES
(6, 7, 6, 'Nice India', '2024-04-05 14:41:51.785507');

-- --------------------------------------------------------

--
-- Table structure for table `live_comment`
--

CREATE TABLE `live_comment` (
  `id` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `liveid` int(10) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_comment`
--

INSERT INTO `live_comment` (`id`, `userid`, `liveid`, `comment`, `time`) VALUES
(1, 7, 11, 'hi', '2024-03-23 12:52:14'),
(2, 7, 11, 'hello', '2024-03-23 12:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `live_video`
--

CREATE TABLE `live_video` (
  `id` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `link` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `startson` datetime NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_video`
--

INSERT INTO `live_video` (`id`, `userid`, `link`, `title`, `startson`, `time`) VALUES
(17, 7, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/N5m5LedCRKI?si=JaT4f4ejYNveT4pm\"', 'Nepal Vs India Football', '2024-04-08 15:35:00', '2024-04-05 13:35:59'),
(22, 7, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/_6jye7FNr1s?si=rdbbHcO4LOI_y574\"', 'IPL 2024 Live', '2024-04-05 16:21:00', '2024-04-05 14:22:10'),
(23, 7, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/k8cZVA2xB8c?si=oG0Ej7asPhFuqRQh\"', 'ONE Friday Fights 58', '2024-04-05 16:48:00', '2024-04-05 14:48:41'),
(24, 7, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/k8cZVA2xB8c?si=oG0Ej7asPhFuqRQh\"', 'India vs Sri lanka', '2024-04-07 16:48:00', '2024-04-05 14:49:16');

-- --------------------------------------------------------

--
-- Table structure for table `postdetails`
--

CREATE TABLE `postdetails` (
  `id` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `fileaddress` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` varchar(800) NOT NULL,
  `time` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postdetails`
--

INSERT INTO `postdetails` (`id`, `userid`, `fileaddress`, `title`, `description`, `time`) VALUES
(5, 8, 'Media-660118977c9463.10705378.jpg', 'subrat haha', 'haha haha', '2024-03-25 06:24:23.511302'),
(6, 7, 'Media-660ffe0071b4b0.23272244.jpg', 'India Vs Sri Lanka ', 'India Won the game wow.', '2024-04-05 13:34:56.466789');

-- --------------------------------------------------------

--
-- Table structure for table `userdetails`
--

CREATE TABLE `userdetails` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` bigint(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `varification_code` varchar(60) NOT NULL,
  `is_varified` int(10) NOT NULL DEFAULT 0,
  `login_otp` int(11) NOT NULL,
  `login_otp_attemp` int(11) NOT NULL DEFAULT 0,
  `role` int(10) NOT NULL DEFAULT 0,
  `blocked` int(10) NOT NULL DEFAULT 0,
  `reset_code` varchar(60) DEFAULT NULL,
  `sport` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdetails`
--

INSERT INTO `userdetails` (`id`, `name`, `country`, `email`, `contact`, `password`, `varification_code`, `is_varified`, `login_otp`, `login_otp_attemp`, `role`, `blocked`, `reset_code`, `sport`) VALUES
(7, 'Subrat Adhikari', 'Nepal', 'subrat@ismt.edu.np', 9876543210, '$2y$10$goQ3/3Appm55Fms2OBfHvOKIcyG0lUEgjZFfuy0d2SumoGL74Ve26', '87517910966147e053ae30c93e645dfe', 1, 120792, 1, 1, 0, '9a09c88d3fab2c780f855c20f407c842', 'Football'),
(12, 'Abinash Adhikari', 'Nepal', 'abinashadhikari8@gmail.com', 9876543212, '$2y$10$EtWWt9phaAPDVKkoWoZp6.9Mmlk0YvUW8V72v5pvwAQTZsz.S/ocS', '26819723bff12628bbd95dcf5bd033da', 1, 639726, 0, 1, 0, NULL, 'Football');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_comment`
--
ALTER TABLE `live_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_video`
--
ALTER TABLE `live_video`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postdetails`
--
ALTER TABLE `postdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userdetails`
--
ALTER TABLE `userdetails`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `live_comment`
--
ALTER TABLE `live_comment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `live_video`
--
ALTER TABLE `live_video`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `postdetails`
--
ALTER TABLE `postdetails`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `userdetails`
--
ALTER TABLE `userdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
