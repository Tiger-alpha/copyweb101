-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 04:06 AM
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
(1, 'asd', 'asd', 'asd', 'admin', '1234567', 'afdsgh2@gmail.com', 111, '6756a55246391.jpg'),
(3, 'qwe', 'qwe', 'qwe', 'client', '$2y$10$ipbAJVRynaJe.dh94E2ZYOFC0iicTwn2rlQgV3LckmDO6e.l1xijq', 'afdsgh2@gmail.com', 0, 'WIN_20240930_12_44_33_Pro.jpg'),
(5, 'jhk', 'jk', 'jk', 'client', '$2y$10$bcCY3GtIPbBgmiNRYapmCukq6Z6VeXYbGoUf9rIb8StN8mOfkGTnu', 'afdsgh2@gmail.com', 221, 'WIN_20240930_12_44_45_Pro.jpg'),
(6, 'cv', 'cv', 'cv', 'client', '$2y$10$m1fP38k0e1qYLeekhsZOo.qIL85/5qC7cFbYqioZbujAbwKNt0Yu6', 'afdsgh2@gmail.com', 0, 'WIN_20240930_12_44_43_Pro.jpg'),
(7, 'cvc', 'cvc', 'vcv', 'client', '$2y$10$3Z6IaHvLPVF/VrgU1qdpNeudWK0r5bpMVpEaqvA9UnKKia/GHG/am', 'afdsgh2@gmail.com', 22, ''),
(8, 'hg', 'jh', 'jh', 'client', '$2y$10$JF2fLE9bTEaORSP2THbcJOBQ2pCMNgMABUKjfGLClxqufMKCSHy3i', 'jaredbatad@gmail.com', 111, 'sd.jpg'),
(9, 'DIWA', 'TA', 'kyle', 'admin', '$2y$10$7UoJAtkDbdJ3wh33wfwQfeBjvmqVWtVMptztKjESzmfD.o2gKH8.G', 'diwata@gmail.com', 2147483647, '6757ac544e5e5.jpg'),
(10, 'rich', 'neil', 'neil', 'client', '$2y$10$3nJnGUk170YZ/4Xp58ocLevfYAKozCgT1EdQuQvTE4Px2XfBpdGsm', 'neil@gmail.com', 2147483647, 'mokey.jpg'),
(11, 'MARK', 'cocon', 'cocon', 'client', '$2y$10$gztXSVPMTUxiM92nWbwal.wnNWbiZRDsJTNpEBAC4Jro0SpFAXMKK', 'cocon@gmail.com', 2147483647, 'sir cocon.jpg'),
(12, 'ryza', 'ryza', 'ryza', 'client', '$2y$10$yB4ZQ3LA29fB7xwb0/BFgus8iuzDQUfmLLwAoiRPq/5bIAtpCQPYW', 'ryza@gmail.com', 11111111, 'ryza.jpg');

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
