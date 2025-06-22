-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2025 at 03:39 PM
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
-- Database: `traffic_fine_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `fine_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `complaint_text` text DEFAULT NULL,
  `evidence_file` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `date_submitted` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `fine_id`, `user_id`, `complaint_text`, `evidence_file`, `status`, `date_submitted`) VALUES
(1, 14, 5, 'cdsvbgg', '1749579409_6f1e17a68b9e5f86d78d3bb4437b7ec2.jpg', 'resolved', '2025-06-10 23:46:49'),
(2, NULL, 6, 'fake complaint', '1749652313_8698-213454544_tiny.mp4', 'resolved', '2025-06-11 20:01:53'),
(3, 18, 7, 'complaint given mistakenly or fake complaint', '1749790133_8698-213454544_tiny.mp4', 'Pending', '2025-06-13 10:18:53'),
(4, 19, 7, 'hjgcdsjnkfs', '1749790711_8698-213454544_tiny.mp4', 'resolved', '2025-06-13 10:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `officer_id` int(11) DEFAULT NULL,
  `violation_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `date_issued` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`id`, `user_id`, `vehicle_id`, `officer_id`, `violation_id`, `reason`, `amount`, `status`, `date_issued`) VALUES
(1, 2, NULL, NULL, NULL, 'Drunk And Drive', 20000.00, 'paid', '2025-05-29'),
(2, NULL, NULL, NULL, NULL, 'Over Speed', 5000.00, 'paid', '2025-05-30'),
(3, NULL, NULL, NULL, NULL, 'Over Speed', 5000.00, 'paid', '2025-05-30'),
(4, NULL, NULL, NULL, NULL, 'Over Speed', 8000.00, 'paid', '2025-05-31'),
(5, NULL, NULL, NULL, NULL, 'Over Speed', 8000.00, 'paid', '2025-05-31'),
(6, NULL, NULL, NULL, NULL, 'Drunk and Drive', 10000.00, 'paid', '2025-05-31'),
(9, 5, 10, 5, 7, 'Turning without Signals', 2500.00, 'paid', '2025-06-08'),
(12, 5, 10, 1, 11, 'Honking in silent zones', 5000.00, 'paid', '2025-06-09'),
(13, 5, 10, 5, 4, 'Speeding', 5000.00, 'paid', '2025-06-10'),
(14, 5, 10, 5, 5, 'Overtaking from the left side', 2500.00, 'pending', '2025-06-10'),
(16, 6, 11, 5, 12, 'Failure to obey traffic signs or signals', 10000.00, 'pending', '2025-06-12'),
(17, 7, 12, 6, 5, 'Overtaking from the left side', 2500.00, 'paid', '2025-06-13'),
(18, 7, 12, 6, 1, 'Drunk and Drive', 15000.00, 'paid', '2025-06-13'),
(19, 7, 12, 6, 12, 'Failure to obey traffic signs or signals', 10000.00, 'pending', '2025-06-13'),
(20, 6, 11, 6, 1, 'Drunk and Drive', 15000.00, 'pending', '2025-06-19');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `fine_id` int(11) DEFAULT NULL,
  `date_paid` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `fine_id`, `date_paid`) VALUES
(1, 1, '2025-05-29'),
(2, 2, '2025-05-30'),
(3, 3, '2025-05-30'),
(4, 4, '2025-05-31'),
(5, 5, '2025-05-31'),
(6, 6, '2025-05-31'),
(7, 9, '2025-06-08'),
(8, 12, '2025-06-09'),
(9, 13, '2025-06-10'),
(10, 17, '2025-06-13'),
(11, 18, '2025-06-13');

-- --------------------------------------------------------

--
-- Table structure for table `police_officers`
--

CREATE TABLE `police_officers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `job_id` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `security_q1` varchar(255) DEFAULT NULL,
  `security_q2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `police_officers`
--

INSERT INTO `police_officers` (`id`, `first_name`, `last_name`, `nic`, `job_id`, `email`, `mobile`, `password`, `security_q1`, `security_q2`) VALUES
(1, 'John', 'S', '198012345678', 'PL001', 'johns123@gmail.com', '0751234567', '$2y$10$7IW1HD63cRmSqhmnD42Jhe0iffWSnv9gdL0UhU7aiQYhrVrWgD3.m', 'B001', 'Jaffna'),
(4, 'Nithu', 'Ruva', '200412345678', 'PL003', 'nithu123@gmail.com', '0217654321', 'Nithu123', 'B003', 'malasiya'),
(5, 'Police', '01', '965165165154', 'PL004', 'police01@gmail.com', '0770750215', '$2y$10$AcWK.D0lYootiKb4hBQejuIR7lcQbdfExpmzeKpcfha8RHoaoLQT2', 'B004', 'Colombo'),
(6, 'Police', '02', '546515615315', 'PL005', 'police02@gmail.com', '0766465165', '$2y$10$T9fowhLRDC1Wg4eRhdyuGeoNdGm6YZ71EU5HgXQzRn1L8AI4712ky', 'B005', 'Jaffna');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `security_q1` varchar(255) NOT NULL,
  `security_a1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `nic`, `email`, `phone`, `password`, `security_q1`, `security_a1`) VALUES
(2, 'Bam', 'Bam', '200112345678', 'bambam123@gmail.com', '0771234567', 'Bambam123', 'Black', ''),
(5, 'Test', '02', '207412345678', 'test02@gmail.com', '075698754', '$2y$10$8FFYNdA3mPJbYqtTmYANpOWD3iWgEiv67l1csBsxMyd2sSVq6moB2', 'city', 'Colombo'),
(6, 'Test', '03', '272386327277', 'test03@gmail.com', '0212828832', '$2y$10$FZtEfC1nvqJ7LdtyOSpohOCAOXD5bRBRrAMhNjODbnqMuK6DeH95S', 'school', 'ATI'),
(7, 'Abi', 'Abi', '646515313131', 'abi123@gmail.com', '0765645353', '$2y$10$Lc9j3YGYULPgfkVSeS0ceeHudhAu2wVGYiVwsTNsT8.NSvdUBwkVi', 'city', 'Jaffna');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vehicle_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `vehicle_no`) VALUES
(4, 2, 'NP BDG8257'),
(10, 5, 'WP BFG5732'),
(11, 6, 'NW VT2456'),
(12, 7, 'EP VZ6598'),
(13, 2, 'NP VT6548');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `violation_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `violation_name`, `amount`) VALUES
(1, 'Drunk and Drive', 15000.00),
(2, 'Driving without a license', 25000.00),
(3, 'Driving without a valid insurance', 30000.00),
(4, 'Speeding', 5000.00),
(5, 'Overtaking from the left side', 2500.00),
(6, 'Overtaking in the yellow line', 15000.00),
(7, 'Turning without Signals', 2500.00),
(8, 'Driving without a valid Road Tax', 5000.00),
(9, 'Driving without seat belts', 2000.00),
(10, 'Using a mobile phone or electronic device while driving', 10000.00),
(11, 'Honking in silent zones', 5000.00),
(12, 'Failure to obey traffic signs or signals', 10000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fine_id` (`fine_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `officer_id` (`officer_id`),
  ADD KEY `fines_ibfk_violation` (`violation_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fine_id` (`fine_id`);

--
-- Indexes for table `police_officers`
--
ALTER TABLE `police_officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `police_officers`
--
ALTER TABLE `police_officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`fine_id`) REFERENCES `fines` (`id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fines_ibfk_3` FOREIGN KEY (`officer_id`) REFERENCES `police_officers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fines_ibfk_violation` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`fine_id`) REFERENCES `fines` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
