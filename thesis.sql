-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2019 at 01:48 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thesis`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_materials`
--

CREATE TABLE `bill_of_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bill_of_materials`
--

INSERT INTO `bill_of_materials` (`id`, `project_id`, `item`, `quantity`, `amount`, `total`, `paid`) VALUES
(1, 1, 'dfgfd', 3, '6', '18', NULL),
(3, 2, 'Wordpress', 12, '22', '264', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cashflow`
--

CREATE TABLE `cashflow` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cashflow`
--

INSERT INTO `cashflow` (`id`, `description`, `date_added`, `amount`, `type`, `transaction_date`, `project_id`) VALUES
(1, 'dfgdfg', '0000-00-00', '-34', 'out', '2019-03-13', 1),
(2, 'dfgdfgfgfg', '2019-03-15', '4545', 'OUT', '2019-03-22', 1),
(3, '54545', '2019-03-15', '5', 'IN', '2019-03-23', 1),
(4, 'Wordpress developer', '2019-03-15', '6', 'in', '2019-03-19', 2),
(5, 'dfgdfg', '2019-03-15', '50', 'in', '2019-03-22', 2);

-- --------------------------------------------------------

--
-- Table structure for table `gantt_chart`
--

CREATE TABLE `gantt_chart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(100) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `project_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gantt_chart`
--

INSERT INTO `gantt_chart` (`id`, `activity`, `date_added`, `date_start`, `date_end`, `project_id`) VALUES
(2, 'ddfjgjg', '2019-03-15', '2019-03-16', '2019-03-22', 1),
(3, 'fdgdf', '2019-03-15', '2019-03-16', '2019-03-21', 1),
(4, 'php ', '2019-03-15', '2019-03-15', '2019-03-17', 2),
(5, 'Html', '2019-03-15', '2019-03-18', '2019-03-21', 2),
(6, 'Wordpress, React JS', '2019-03-15', '2019-03-17', '2019-03-22', 2);

-- --------------------------------------------------------

--
-- Table structure for table `gantt_chart_resource`
--

CREATE TABLE `gantt_chart_resource` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `rate` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `gantt_chart_id` bigint(20) UNSIGNED DEFAULT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `date_added`, `date_start`, `date_end`, `duration`) VALUES
(1, 'fgf', '2019-03-15', '2019-03-20', '2019-03-21', 1),
(2, 'My freelancing project', '2019-03-15', '2019-03-15', '2019-03-22', 7);

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `category`) VALUES
(1, 'Sample1dsds', 'E'),
(2, 'ddsd', 'E'),
(3, 'ddsd', 'E'),
(25, 'Han Xu Project Wordpress PHP', 'E');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `cashflow`
--
ALTER TABLE `cashflow`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `gantt_chart`
--
ALTER TABLE `gantt_chart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `gantt_chart_resource`
--
ALTER TABLE `gantt_chart_resource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `gantt_chart_id` (`gantt_chart_id`),
  ADD KEY `resource_id` (`resource_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cashflow`
--
ALTER TABLE `cashflow`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gantt_chart`
--
ALTER TABLE `gantt_chart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gantt_chart_resource`
--
ALTER TABLE `gantt_chart_resource`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gantt_chart_resource`
--
ALTER TABLE `gantt_chart_resource`
  ADD CONSTRAINT `gantt_chart_resource_ibfk_1` FOREIGN KEY (`gantt_chart_id`) REFERENCES `gantt_chart` (`id`),
  ADD CONSTRAINT `gantt_chart_resource_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
  ADD CONSTRAINT `gantt_chart_resource_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `resources` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
