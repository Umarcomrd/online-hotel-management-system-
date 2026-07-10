-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 08, 2026 at 09:32 AM
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
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `booking_status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookingss`
--

CREATE TABLE `bookingss` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `guest_email` varchar(100) NOT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) DEFAULT 1,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookingss`
--

INSERT INTO `bookingss` (`id`, `room_id`, `guest_name`, `guest_email`, `guest_phone`, `check_in`, `check_out`, `guests`, `status`, `created_at`) VALUES
(1, 5, 'Umar Babawuro Abubakar', 'fashiondesign1176@gmail.com', '09086353663', '2026-09-12', '2026-10-12', 19, 'pending', '2026-05-16 11:50:19'),
(2, 11, 'Aminu Gambo Abubakar', 'Aminullah@gmail.com', '09065432789', '2026-12-23', '2026-12-31', 2, 'pending', '2026-05-17 15:11:47'),
(3, 8, 'Mustapha Abdullahi Musa', 'Mustapah@gmail.com', '09086353663', '2026-09-09', '2026-10-01', 12, 'pending', '2026-05-17 15:22:05'),
(4, 8, 'Salma Mahmud Saleh', 'Salmah1908@gmail.com', '09065432789', '2026-09-14', '2026-09-30', 19, 'pending', '2026-05-17 15:54:01'),
(5, 13, 'zainab shaza', 'zeeshaza@gmail.com', '0908635366', '2026-05-21', '2026-05-22', 2, 'pending', '2026-05-21 11:58:45'),
(6, 8, 'aljan bukar abba', 'aljan11763@gmail.com', '09083756533', '2026-06-10', '2221-01-22', 1, 'pending', '2026-05-31 18:05:54'),
(7, 13, 'Umar Babawuro Abubakar', 'fashiondesign1176@gmail.com', '09065432789', '2026-09-10', '2026-12-01', 1, 'pending', '2026-05-31 18:06:54'),
(8, 16, 'samir auwal', 'auwal11@gmail.com', '09065432789', '2026-06-12', '2026-06-13', 12, 'pending', '2026-06-12 13:59:38'),
(9, 12, 'jeremier johnah', 'jeremieir@gmail.com', '09086353667', '2026-12-09', '2026-12-12', 6, 'pending', '2026-07-01 12:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `category` enum('technical','billing','service','other') NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','in_review','resolved') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `fullname`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'hajara musa Abubakar', 'fashiondesign1176@gmail.com', 'room', 'hi kiddo', '2026-05-17 16:34:17'),
(9, 'Affan Baba Dawaki', 'affan@gmail.com', 'water issue', 'hi my lady', '2026-05-17 17:01:16'),
(10, 'Affan Baba Dawaki', 'affan@gmail.com', 'water issue', 'hi my lady', '2026-05-17 17:01:49'),
(11, 'john doe', 'john11761@gmail.com', 'about my booking', 'i want to leave my room by tommorrow morning can i get my money refund', '2026-06-27 13:13:32'),
(12, 'john doe', 'john11761@gmail.com', 'about my booking', 'i want to leave my room by tommorrow morning can i get my money refund', '2026-06-27 13:13:52');

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`id`, `full_name`, `email`, `phone_number`, `password`, `created_at`) VALUES
(1, 'Umar Babawuro Abubakar', 'fashiondesign1176@gmail.com', '09078291176', '$2y$10$8uv9qlvB2Mei08z0Q7f.WuJPavkYfdkf2dRCDliNCoXtYezIe5JS6', '2026-05-14 12:51:38'),
(2, 'Maimuna salisau', 'maimunasalisu@gmail.com', '0707 210 5145', '$2y$10$Q9qtRyVPRbc.e9MUlkWah.QseIl8WqMgAlX8dta8U8OGxJgb7f4Xu', '2026-05-14 13:54:51'),
(3, 'emanual john', 'john1176@gmail.com', '0707 210 5145', '$2y$10$JktaS1x6CspATldAD4shSur.H22ys/mRaDnwwtPBL43ZUeQviwbxm', '2026-05-14 14:52:39'),
(4, 'abraham musa', 'abraham@gmail.com', '0707 210 5145', '$2y$10$PODObaZEvjckJqfXmOZGS.YLBJMcd7y7.UaaiQbTHVWkf.R6iMpXq', '2026-05-14 16:58:14'),
(5, 'abraham musa aliyu', 'abrahamALIYU@gmail.com', '0707 210 5145', '$2y$10$hZpqVz7thY4hmBBR.FvNOu8EmqA3ktnAmUi2iIt.XWUDxKIZkJBvi', '2026-05-14 17:00:10'),
(6, 'kadijah musa aliyu', 'kadijah@gmail.com', '09078291176', '$2y$10$BCM3Yg3SSvfPlYzBWXE9yOag0BH6jb1BTwGP6fvtT0F.dc.u7Wz0S', '2026-05-14 17:04:36'),
(7, 'Ismail Gabukka', 'ismail1234@gmail.com', '09176356733', '$2y$10$RONBLddiKXvJfXEg.LZe3egadwq30YMWyDHmx/4doEJpU0yhe2vSi', '2026-05-15 10:42:34'),
(8, 'peter showmachiecl', 'peter@gmail.com', '07078291176', '$2y$10$Wgh7kGrYg9e6xDG.kvg7zueTe6/lwKwnMj9pVZ77Nr6757It3ZATC', '2026-05-16 11:38:13'),
(9, 'Maryam babangida', 'maryam90@gmail.com', '09065244622', '$2y$10$m24x4cEpJd65/8bhXNnQeuEnawrQc8nMX1W0twP8HXzlpxiBcxWH.', '2026-05-16 11:40:33'),
(10, 'Zainab Muhammad bajoga', 'zeeshaza@gmail.com', '09078291176', '$2y$10$ZuzxXe7ftG4Y0k21XdVAGet2aZ3eYeMPCZUr9e4qlQFIuLGUfn5EG', '2026-05-21 12:41:11'),
(11, 'Usman malala', 'usman@gmail.com', '09078291179', '$2y$10$Km/NM8aZfkZwXxSdSbHajeF8ORr2n7OcQxpaHD3BAzSphgc3kDLvi', '2026-06-13 22:44:09'),
(12, 'john Doe james', 'jamesdoe@gmail.com', '09078291176', '$2y$10$UGhu4j6LfJurCCPlHJqJJ.y0RitT9YMRMJuja2XCmA9/c7leO9L0O', '2026-06-27 14:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `full_name`, `email`, `phone_number`, `password`, `created_at`) VALUES
(3, 'Khadijah Buhari', 'kadijahbuhari@gmail.com', '07078291176', '$2y$10$HcMDNJoq3QvQmUS71lL7au35Jf1xQGINk1flj0d1JNpDcfHsZRtnK', '2026-05-12 14:31:23'),
(4, 'Maimuna salisau', 'maimunasalisu@gmail.com', '07078291176', '$2y$10$LVhUGk/SyB5OoLRQq0iBVe.J/N/toIRyA5WuVYI/ND09iQcyMh3/e', '2026-05-12 14:33:42'),
(5, 'Abdullahi Baffa Garkuwa', 'Abdullahi1176@gmail.com', '09078291176', '$2y$10$wSUzS0yOi0tw.2woINq.kOBAr9tZw6laOOf4SOJ8.eCh68nrnELcS', '2026-05-13 13:49:12'),
(6, 'salisu buhari', 'salisu@gmail.com', '0917635663', '$2y$10$LrKTft6BC.MP1z4FTGkIeOkCl5JgaKfD2rPR04NrXOx/Evc9zbd5m', '2026-05-14 12:57:04'),
(9, 'kadijah manman', 'mana@gmail.com', '09078291176', '$2y$10$XwsdpzDlSLqahgAvzT83Z.dN4A34OJ80ow8BFD0T3qEXk2PqaS98G', '2026-05-14 16:10:31'),
(10, 'Ismail Ibraheem', 'ismail1234@gmail.com', '0707 210 514', '$2y$10$waSslO5g9FnEF/iLtc3B4eSmwcYHDwnuhNmDi/vcfDM3z/rPZstfy', '2026-05-15 09:53:14'),
(11, 'peter showmachiecl', 'peter@gmail.com', '0973656356', '$2y$10$z2Azgx0vrHpe/SxLAdBnGOi4S3GkPyjclGcwDa8.R5y0o2tl./.T.', '2026-05-16 10:32:34'),
(12, 'Mustapha Musa', 'Muhammadasahabbai@gmail.com', '09078291176', '$2y$10$B7GnYRqyK/VLUKrUADOzbeBVyekAXq68eTErL7/FU4NuLZNqtrLdu', '2026-05-17 15:51:54'),
(13, 'Zainab Muhammad bajoga', 'zeeshaza1124@gmail.com', '0707 210 5144', '$2y$10$tcXSz5WomPcVmV.SFjK3HODPs3Pe6ySVY7LqCCg7Ol5JxccgU3Stq', '2026-05-21 11:49:20'),
(14, 'aljan bukar abba', 'aljan1143@gmail.com', '09087766532', '$2y$10$G3rv6G.Z1nPFzw0vg3Bgd.SvbNTvPndwxCU8gjC96rstSY0maUlUe', '2026-05-31 18:02:05'),
(15, 'john Doe', 'john1176@gmail.com', '0707 210 514', '$2y$10$N/6mXjyqR5vphpWsFsJcLeiBTlCpmLXTg1jw6Eocj8Zt2wb7I8yTO', '2026-06-27 13:05:42'),
(16, 'Ramond Redinton', 'Red1176@gmail.com', '070276781929', '$2y$10$KH/tU0BCcx3j.3tIm784sOJeJ4yuhcOjFxYLMURREE9F2F20FUHRu', '2026-06-30 13:37:51'),
(17, 'Uncle johnah jeremier', 'johnajeremier@gmail.com', '09078291176', '$2y$10$.kktpDV6xO8dgvG4QwOAvu74xAwm23nxBVL8hMSUuOR5UlhAUeI26', '2026-07-01 12:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `price`, `image`, `description`, `status`, `created_at`) VALUES
(5, 'Luxery Room', 190.00, '1782921074_Prince Gallery Tokyo Kioicho Room.jpeg', 'A step above standard, this room features upgraded furnishings, a work desk and premium bedding for added comfort.', 'available', '2026-05-14 16:01:09'),
(8, 'Family VIP', 136.99, '../uploads/rooms/room_6a087e877f0ad.jpeg', 'Three bed rooms, one toilet, one swimming pool', 'available', '2026-05-16 14:26:15'),
(9, 'Meeting room', 350.00, '../uploads/rooms/room_6a09d87043a1c.jpeg', 'Long Hall for meeting', 'maintenance', '2026-05-17 15:02:08'),
(10, 'VIP Room', 399.99, '../uploads/rooms/room_6a09d93b6f529.jpeg', 'Special for Special Guest', 'available', '2026-05-17 15:05:31'),
(11, 'Governors site', 300.00, '../uploads/rooms/room_6a09d9da8d710.jpeg', 'Very Expensive site made for Governors', 'available', '2026-05-17 15:08:10'),
(12, 'Presidential Residence', 1000.00, '../uploads/rooms/room_6a09e1e559333.jpeg', 'Luxery Site for Presidents', 'available', '2026-05-17 15:42:29'),
(13, 'Celebrities suite', 200.00, '../uploads/rooms/room_6a0ef02b1b84d.jpeg', 'celebrities are hightly welcome here', 'available', '2026-05-21 11:44:43'),
(14, 'Spectacular Rooms', 170.00, '../uploads/rooms/room_6a1c7a4bc1536.jpeg', 'Iceland Home For Special Celebrities', 'available', '2026-05-31 18:13:31'),
(15, 'Attheltic Suite', 249.99, '../uploads/rooms/room_6a1c7aa64b378.jpeg', 'Footballers VIP Suite Quite place', 'available', '2026-05-31 18:15:02'),
(16, 'Marvel Room Iron man suites', 119.99, '../uploads/rooms/room_6a2c0fbb289cf.jpeg', 'Marvel suites for their heros alien wears', 'available', '2026-06-12 13:55:07'),
(17, 'Classic standard Room', 80.00, '../uploads/rooms/room_6a4532ae86eb8.jpeg', 'A cozy and well-furnished room perfect for solo travelers or short business trips with all essential amenities.', 'available', '2026-07-01 15:30:54'),
(19, 'Penthouse Suite', 550.00, '../uploads/rooms/room_6a45339849711.jpeg', 'Perched at the top floor with floor-to-ceiling windows, a private terrace and an exclusive lounge area.', 'available', '2026-07-01 15:34:48'),
(21, 'Junior Suite', 160.00, '../uploads/rooms/room_6a453544cdcf4.jpeg', 'A spacious suite with a separate sitting area, perfect for guests who want extra space without full suite pricing.', 'available', '2026-07-01 15:41:56'),
(22, 'Deluxe Room', 150.00, '../uploads/rooms/room_6a4538cad1caf.jpeg', 'Elegant deluxe room with modern interior, king-size bed and a beautiful view of the hotel surroundings.', 'available', '2026-07-01 15:56:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookingss`
--
ALTER TABLE `bookingss`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookingss`
--
ALTER TABLE `bookingss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookingss`
--
ALTER TABLE `bookingss`
  ADD CONSTRAINT `bookingss_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
