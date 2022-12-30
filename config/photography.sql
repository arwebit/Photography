-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2022 at 02:51 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photography`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_access`
--

CREATE TABLE `admin_login_access` (
  `admin_user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `contact_no` bigint(13) NOT NULL,
  `contact_email` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_login_access`
--

INSERT INTO `admin_login_access` (`admin_user_id`, `username`, `password`, `full_name`, `contact_no`, `contact_email`, `status`) VALUES
(1, 'admin', 'NExmK2JuNUw1NXptWVk2bVQycFh4Zz09', 'Administrator', 1234567890, 'admin@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blog_post`
--

CREATE TABLE `blog_post` (
  `blog_id` bigint(14) NOT NULL,
  `username` varchar(20) NOT NULL,
  `blog_category_id` bigint(14) NOT NULL,
  `photo_width` int(5) NOT NULL,
  `photo_height` int(5) NOT NULL,
  `blog_title` varchar(255) NOT NULL,
  `blog_descr` varchar(200) NOT NULL,
  `photo_extension` varchar(10) NOT NULL,
  `photo_mime_type` varchar(20) NOT NULL,
  `photo_encrypted_str` longtext NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `blog_url_slug` text NOT NULL,
  `blog_status` tinyint(1) NOT NULL,
  `blog_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mas_category`
--

CREATE TABLE `mas_category` (
  `category_id` bigint(14) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `category_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mas_category`
--

INSERT INTO `mas_category` (`category_id`, `category_name`, `category_status`) VALUES
(20221216000104, 'Foods', 1),
(20221216000412, 'Places', 1),
(20221216003650, 'Travel', 1),
(20221216231132, 'Food Items', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` bigint(14) NOT NULL,
  `username` varchar(20) NOT NULL,
  `user_first_name` varchar(150) NOT NULL,
  `user_middle_name` varchar(100) DEFAULT NULL,
  `user_last_name` varchar(100) NOT NULL,
  `user_mobile` bigint(10) DEFAULT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_address` longtext DEFAULT NULL,
  `user_dob` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `username`, `user_first_name`, `user_middle_name`, `user_last_name`, `user_mobile`, `user_email`, `user_address`, `user_dob`) VALUES
(20221124004110, 'arghya992', 'Soumyanjan', '', 'Dey', 9612603587, 'arghya992@gmail.com', 'Agartala', '1992-10-13'),
(20221124200543, 'somapika13', 'Somapika', '', 'Dutta', 9874599327, 'somapika13@gmail.com', 'Kolkata', '1995-02-13');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_access`
--

CREATE TABLE `user_login_access` (
  `user_id` bigint(14) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_verified` tinyint(1) NOT NULL,
  `user_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login_access`
--

INSERT INTO `user_login_access` (`user_id`, `username`, `password`, `user_verified`, `user_status`) VALUES
(20221124004110, 'arghya992', 'ais5R2lBQXVuL0NwZXQxcG5oVHpDUT09', 0, 1),
(20221124200543, 'somapika13', 'QUcrTWZCazZySFVzVng1Ui9Fb3Q0QT09', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login_access`
--
ALTER TABLE `admin_login_access`
  ADD PRIMARY KEY (`admin_user_id`);

--
-- Indexes for table `blog_post`
--
ALTER TABLE `blog_post`
  ADD PRIMARY KEY (`blog_id`),
  ADD UNIQUE KEY `photo_permalink` (`blog_url_slug`) USING HASH;

--
-- Indexes for table `mas_category`
--
ALTER TABLE `mas_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_login_access`
--
ALTER TABLE `user_login_access`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login_access`
--
ALTER TABLE `admin_login_access`
  MODIFY `admin_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
