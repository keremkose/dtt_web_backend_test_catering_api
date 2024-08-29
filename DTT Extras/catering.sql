-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2024 at 03:26 PM
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
-- Database: `catering`
--

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE `facility` (
  `Id` int(10) NOT NULL,
  `CreationDate` date NOT NULL DEFAULT current_timestamp(),
  `Name` varchar(50) NOT NULL,
  `LocationId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`Id`, `CreationDate`, `Name`, `LocationId`) VALUES
(98651634, '2024-08-26', 'facility2', 487255455),
(146739598, '2024-08-26', 'facility3', 709555002),
(861645019, '2024-08-26', 'facility1', 342344747);

-- --------------------------------------------------------

--
-- Table structure for table `facilitytags`
--

CREATE TABLE `facilitytags` (
  `FacilityId` int(10) NOT NULL,
  `TagId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilitytags`
--

INSERT INTO `facilitytags` (`FacilityId`, `TagId`) VALUES
(861645019, 708126125),
(861645019, 472541126),
(98651634, 156395555),
(98651634, 153000969),
(146739598, 602979422);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `Id` int(10) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Adress` varchar(50) NOT NULL,
  `ZipCode` text NOT NULL,
  `CountryCode` varchar(20) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`Id`, `City`, `Adress`, `ZipCode`, `CountryCode`, `PhoneNumber`) VALUES
(201278109, 'Eindhoven', '123 Straat', '00aa00', '+31', '123456789'),
(299063749, 'Haarlem', '123 Straat', '00aa00', '+31', '123456789'),
(342344747, 'Rotterdam', '123 Straat', '00aa00', '+31', '123456789'),
(429761297, 'Den Haag', '123 Straat', '00aa00', '+31', '123456789'),
(487255455, 'Amsterdam', '123 Straat', '00aa00', '+31', '123456789'),
(709555002, 'Utrecht', '123 Straat', '00aa00', '+31', '123456789'),
(904770370, 'Breda', '123 Straat', '00aa00', '+31', '123456789');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `Id` int(10) NOT NULL,
  `TagName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`Id`, `TagName`) VALUES
(770000941, 'tag1'),
(285285522, 'tag2'),
(352843845, 'tag3'),
(708126125, 'tag5'),
(472541126, 'tag6'),
(156395555, 'tag7'),
(153000969, 'tag8'),
(602979422, 'tag9');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `facility_location_foreignkey` (`LocationId`) USING BTREE;

--
-- Indexes for table `facilitytags`
--
ALTER TABLE `facilitytags`
  ADD KEY `facility_id` (`FacilityId`),
  ADD KEY `tag_id` (`TagId`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`Id`) USING BTREE,
  ADD UNIQUE KEY `TagName` (`TagName`) USING BTREE;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facility`
--
ALTER TABLE `facility`
  ADD CONSTRAINT `facility_location_foreignkey` FOREIGN KEY (`LocationId`) REFERENCES `location` (`Id`);

--
-- Constraints for table `facilitytags`
--
ALTER TABLE `facilitytags`
  ADD CONSTRAINT `facility_id` FOREIGN KEY (`FacilityId`) REFERENCES `facility` (`Id`),
  ADD CONSTRAINT `tag_id` FOREIGN KEY (`TagId`) REFERENCES `tag` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
