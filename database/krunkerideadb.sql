-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2023 at 05:41 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `krunkerideadb`
--
CREATE DATABASE `krunkerideadb` CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `krunkerideadb`;
-- --------------------------------------------------------

--
-- Table structure for table `category_tbl`
--

CREATE TABLE `category_tbl` (
  `CategoryId` int(11) NOT NULL,
  `CategoryTitle` varchar(50) DEFAULT NULL,
  `DateCreated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_tbl`
--

INSERT INTO `category_tbl` (`CategoryId`, `CategoryTitle`, `DateCreated`) VALUES
(1, 'Technology', '2023-04-18 11:06:45'),
(2, 'Education', '2023-04-18 11:06:45'),
(3, 'Health', '2023-04-18 11:06:46'),
(4, 'Business', '2023-04-18 11:06:46');

-- --------------------------------------------------------

--
-- Table structure for table `comment_tbl`
--

CREATE TABLE `comment_tbl` (
  `CommentId` int(11) NOT NULL,
  `IdeaId` int(11) DEFAULT NULL,
  `UserId` int(11) NOT NULL,
  `CommentDetails` varchar(500) DEFAULT NULL,
  `CommentAnonymous` tinyint(1) DEFAULT 0,
  `comment_hidden` int(11) DEFAULT 0,
  `DateComment` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_tbl`
--

INSERT INTO `comment_tbl` (`CommentId`, `IdeaId`, `UserId`, `CommentDetails`, `CommentAnonymous`, `comment_hidden`, `DateComment`) VALUES
(1, 1, 7, 'You&#039;re right and the LAN cables are not working as well.', 0, 0, '2023-04-18 11:40:25');

-- --------------------------------------------------------

--
-- Table structure for table `department_tbl`
--

CREATE TABLE `department_tbl` (
  `DepartmentId` int(11) NOT NULL,
  `DepartmentName` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_tbl`
--

INSERT INTO `department_tbl` (`DepartmentId`, `DepartmentName`) VALUES
(1, 'Information Technology'),
(2, 'Human Resource'),
(3, 'Business & Marketing'),
(4, 'Accounting'),
(5, 'Management');

-- --------------------------------------------------------

--
-- Table structure for table `ideamedia_tbl`
--

CREATE TABLE `ideamedia_tbl` (
  `IdeaMediaId` int(11) NOT NULL,
  `IdeaId` int(11) DEFAULT NULL,
  `IdeaImage` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_tbl`
--

CREATE TABLE `idea_tbl` (
  `IdeaId` int(11) NOT NULL,
  `CategoryId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `IdeaTitle` varchar(50) DEFAULT NULL,
  `IdeaDescription` varchar(500) DEFAULT NULL,
  `ViewCount` int(255) DEFAULT NULL,
  `IdeaAnonymous` tinyint(1) DEFAULT NULL,
  `is_hidden` tinyint(1) DEFAULT 0,
  `DatePost` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idea_tbl`
--

INSERT INTO `idea_tbl` (`IdeaId`, `CategoryId`, `UserId`, `IdeaTitle`, `IdeaDescription`, `ViewCount`, `IdeaAnonymous`, `is_hidden`, `DatePost`) VALUES
(1, 1, 7, 'Internet connectivity', 'The internet is very slow at lab 6C. Please fix it soon.', 22, 0, 0, '2023-04-18 11:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `like_dislikepost_tbl`
--

CREATE TABLE `like_dislikepost_tbl` (
  `LikeDislikePostId` int(11) NOT NULL,
  `IdeaId` int(11) DEFAULT NULL,
  `UserId` int(11) NOT NULL,
  `LikeStatus` tinyint(1) DEFAULT 0,
  `DislikeStatus` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `like_dislikepost_tbl`
--

INSERT INTO `like_dislikepost_tbl` (`LikeDislikePostId`, `IdeaId`, `UserId`, `LikeStatus`, `DislikeStatus`) VALUES
(1, 1, 9, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `UserId` int(11) NOT NULL,
  `DepartmentId` int(11) NOT NULL,
  `UserRoleName` enum('QA Manager','QA Coordinator','Admin','Staff') DEFAULT NULL,
  `Username` varchar(50) NOT NULL,
  `UserPassword` varchar(500) NOT NULL,
  `UserEmail` varchar(50) DEFAULT NULL,
  `UserContactNo` varchar(15) DEFAULT NULL,
  `UserAddress` varchar(500) DEFAULT NULL,
  `DateClosure` date DEFAULT '2023-06-29',
  `DateFinal` date DEFAULT '2023-07-29'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`UserId`, `DepartmentId`, `UserRoleName`, `Username`, `UserPassword`, `UserEmail`, `UserContactNo`, `UserAddress`, `DateClosure`, `DateFinal`) VALUES
(1, 5, 'QA Manager', 'Will', 'a377c599dcffa6ea118927d8a91d7887', 'will@gmail.com', '0192468452', '10, Jalan Ampang Besar', '2023-06-29', '2023-07-29'),
(2, 1, 'QA Coordinator', 'Sharmila', 'bfbec7af06ff0ed0997d67131091f9b8', 'sharmila@gmail.com', '0123678349', '20, Jalan Batu Cheras', '2023-06-29', '2023-07-29'),
(3, 2, 'QA Coordinator', 'Kenny', '2abdf7621b10620279082bc6c44e2c8a', 'kenny@gmail.com', '0193234789', '10, Jalan Cheras Besar', '2023-06-29', '2023-07-29'),
(4, 3, 'QA Coordinator', 'Mani', '1ed30f635d959bb572c96839a28eef34', 'mani@gmail.com', '0183523578', '15, Jalan Hang Lekiu', '2023-06-29', '2023-07-29'),
(5, 4, 'QA Coordinator', 'Malar', 'eef15bf2d35de38998e95121c57c8471', 'malar@gmail.com', '0172133478', '11, Jalan Cheras Lima', '2023-06-29', '2023-07-29'),
(6, 1, 'Admin', 'Admin@01', '7f16bd4e937475148773a8d0485194ca', 'admin01@gmail.com', '0123456789', '21, seer road london', '2023-06-29', '2023-07-29'),
(7, 1, 'Staff', 'Hari', '202cb962ac59075b964b07152d234b70', 'hari@gmail.com', '0123456789', '21, seer road london', '2023-06-29', '2023-07-29'),
(8, 2, 'Staff', 'Hong', 'debf06ac07d50d42c065fcee64654418', 'hong@gmail.com', '0183456789', '23, Jalan Jinjang Lima', '2023-06-29', '2023-07-29'),
(9, 3, 'Staff', 'Faiz', '67f27ddf40a0dd24fe50fbe33c4460bb', 'faiz@gmail.com', '0183634189', '21, Jalan Sungai Buloh', '2023-06-29', '2023-07-29'),
(10, 4, 'Staff', 'Caleb', 'd41a410724b06fbe0f684feb6fbda2a4', 'caleb@gmail.com', '0123456789', '21, seer road london', '2023-06-29', '2023-07-29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_tbl`
--
ALTER TABLE `category_tbl`
  ADD PRIMARY KEY (`CategoryId`);

--
-- Indexes for table `comment_tbl`
--
ALTER TABLE `comment_tbl`
  ADD PRIMARY KEY (`CommentId`),
  ADD KEY `fk_comment_ideaid` (`IdeaId`),
  ADD KEY `fk_comment_userid` (`UserId`);

--
-- Indexes for table `department_tbl`
--
ALTER TABLE `department_tbl`
  ADD PRIMARY KEY (`DepartmentId`);

--
-- Indexes for table `ideamedia_tbl`
--
ALTER TABLE `ideamedia_tbl`
  ADD PRIMARY KEY (`IdeaMediaId`),
  ADD KEY `fk_ideamedia_ideaid` (`IdeaId`);

--
-- Indexes for table `idea_tbl`
--
ALTER TABLE `idea_tbl`
  ADD PRIMARY KEY (`IdeaId`),
  ADD KEY `fk_idea_categoryid` (`CategoryId`),
  ADD KEY `fk_idea_userid` (`UserId`);

--
-- Indexes for table `like_dislikepost_tbl`
--
ALTER TABLE `like_dislikepost_tbl`
  ADD PRIMARY KEY (`LikeDislikePostId`),
  ADD KEY `fk_likedislikepost_ideaid` (`IdeaId`),
  ADD KEY `fk_likedislikepost_userid` (`UserId`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`UserId`),
  ADD KEY `fk_user_departmentid` (`DepartmentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_tbl`
--
ALTER TABLE `category_tbl`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment_tbl`
--
ALTER TABLE `comment_tbl`
  MODIFY `CommentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department_tbl`
--
ALTER TABLE `department_tbl`
  MODIFY `DepartmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ideamedia_tbl`
--
ALTER TABLE `ideamedia_tbl`
  MODIFY `IdeaMediaId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `idea_tbl`
--
ALTER TABLE `idea_tbl`
  MODIFY `IdeaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `like_dislikepost_tbl`
--
ALTER TABLE `like_dislikepost_tbl`
  MODIFY `LikeDislikePostId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment_tbl`
--
ALTER TABLE `comment_tbl`
  ADD CONSTRAINT `fk_comment_ideaid` FOREIGN KEY (`IdeaId`) REFERENCES `idea_tbl` (`IdeaId`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_userid` FOREIGN KEY (`UserId`) REFERENCES `user_tbl` (`UserId`) ON DELETE CASCADE;

--
-- Constraints for table `ideamedia_tbl`
--
ALTER TABLE `ideamedia_tbl`
  ADD CONSTRAINT `fk_ideamedia_ideaid` FOREIGN KEY (`IdeaId`) REFERENCES `idea_tbl` (`IdeaId`) ON DELETE CASCADE;

--
-- Constraints for table `idea_tbl`
--
ALTER TABLE `idea_tbl`
  ADD CONSTRAINT `fk_idea_categoryid` FOREIGN KEY (`CategoryId`) REFERENCES `category_tbl` (`CategoryId`),
  ADD CONSTRAINT `fk_idea_userid` FOREIGN KEY (`UserId`) REFERENCES `user_tbl` (`UserId`) ON DELETE CASCADE;

--
-- Constraints for table `like_dislikepost_tbl`
--
ALTER TABLE `like_dislikepost_tbl`
  ADD CONSTRAINT `fk_likedislikepost_ideaid` FOREIGN KEY (`IdeaId`) REFERENCES `idea_tbl` (`IdeaId`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_likedislikepost_userid` FOREIGN KEY (`UserId`) REFERENCES `user_tbl` (`UserId`) ON DELETE CASCADE;

--
-- Constraints for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD CONSTRAINT `fk_user_departmentid` FOREIGN KEY (`DepartmentId`) REFERENCES `department_tbl` (`DepartmentId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
