-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 11:57 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jhnvlldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `profileimg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `firstname`, `lastname`, `username`, `role`, `password`, `email`, `phone`, `profileimg`) VALUES
(1, 'asd', 'asd', 'asd', 'admin', '$2y$10$kSMMC/r.xeF9r/6WBbHPbeJIbcislwPN5xgXZBfFzU07pT3n3RGg.', 'afdsgh2@gmail.com', 111, '6730698f09f64.jpg'),
(2, 'qwe', 'qwe', 'qwe', 'client', '$2y$10$PiTzNm6spi9p.KBLi2MCZuppFfEaaGTaAjUGpy1HsZAd.kHwLbeba', 'qwe@gmail.com', 54545, 'as.jpg'),
(3, 'qwe', 'qwe', 'qwe', 'client', '$2y$10$ipbAJVRynaJe.dh94E2ZYOFC0iicTwn2rlQgV3LckmDO6e.l1xijq', 'afdsgh2@gmail.com', 0, 'WIN_20240930_12_44_33_Pro.jpg'),
(4, 'rty', 'rty', 'try', 'client', '$2y$10$N7y98ko4S0zU7JngPinx6eV5W3PG3w5iCb1MrVddcdzgqJNoNGAUq', 'afdsgh2@gmail.com', 0, 'WIN_20240930_12_44_48_Pro.jpg'),
(5, 'jhk', 'jk', 'jk', 'client', '$2y$10$bcCY3GtIPbBgmiNRYapmCukq6Z6VeXYbGoUf9rIb8StN8mOfkGTnu', 'afdsgh2@gmail.com', 221, 'WIN_20240930_12_44_45_Pro.jpg'),
(6, 'cv', 'cv', 'cv', 'client', '$2y$10$m1fP38k0e1qYLeekhsZOo.qIL85/5qC7cFbYqioZbujAbwKNt0Yu6', 'afdsgh2@gmail.com', 0, 'WIN_20240930_12_44_43_Pro.jpg'),
(7, 'cvc', 'cvc', 'vcv', 'client', '$2y$10$3Z6IaHvLPVF/VrgU1qdpNeudWK0r5bpMVpEaqvA9UnKKia/GHG/am', 'afdsgh2@gmail.com', 22, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
