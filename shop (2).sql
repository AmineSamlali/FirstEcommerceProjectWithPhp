-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2021 at 12:54 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorys`
--

CREATE TABLE `categorys` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Visibility` tinyint(1) NOT NULL DEFAULT 1,
  `Comments` int(11) NOT NULL DEFAULT 1,
  `Ads` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categorys`
--

INSERT INTO `categorys` (`id`, `Name`, `Description`, `Visibility`, `Comments`, `Ads`) VALUES
(78, 'Casework', 'Casework', 1, 1, 1),
(79, 'Asphalt Paving', 'Asphalt Paving', 1, 1, 0),
(80, 'Termite Control', 'Termite Control', 1, 0, 1),
(81, 'Drywall & Acoustical (FED)', 'Planar Nucl Med Imag Hepatobil Sys, All w Oth Radionuclide', 1, 1, 1),
(82, 'Roofing (Asphalt)', 'Resection of Nasal Bone, Open Approach', 1, 1, 1),
(83, 'Roofing (Asphalt V1)', 'Insertion of Infusion Dev into R Int Mamm Art, Perc Approach', 1, 1, 1),
(84, 'Electrical and Fire Alarm', 'Traction of Right Upper Arm', 1, 1, 1),
(85, 'Sitework & Site Utilities', 'Reposition Right Toe Phalanx with Ext Fix, Perc Approach', 1, 1, 1),
(86, 'Glass & Glazing V1', 'Excision of Acoustic Nerve, Perc Endo Approach, Diagn', 1, 1, 1),
(87, 'Rebar & Wire Mesh Install', 'Bypass R Innom Vein to Up Vein w Autol Vn, Perc Endo', 1, 1, 1),
(88, 'Glass & Glazing', 'Dilation of Bladder with Intraluminal Device, Open Approach', 1, 1, 1),
(89, 'RF Shielding', 'Removal of Autol Sub from R Ulna, Perc Approach', 1, 1, 1),
(90, 'HVAC', 'Drainage of L Sacroiliac Jt with Drain Dev, Open Approach', 1, 1, 1),
(91, 'Drilled Shafts', 'Bypass R Com Iliac Art to Low Ex Art w Autol Vn, Perc Endo', 1, 1, 1),
(92, 'Plumbing & Medical Gas', 'Extirpation of Matter from 2 Cor Art, Perc Approach', 1, 1, 1),
(93, 'Drywall & Acoustical (MOB)', 'Fragmentation in Left Large Intestine, Percutaneous Approach', 1, 1, 1),
(94, 'Granite Surfaces', 'Transfuse Nonaut Fibrinogen in Central Vein, Open', 1, 1, 1),
(95, 'Structural and Misc Steel (Fabrication)', 'Gastrointestinal System, Reposition', 1, 1, 1),
(96, 'Fire Sprinkler System', 'Destruction of Left Innominate Vein, Percutaneous Approach', 1, 1, 1),
(99, 'Structural and Misc Steel (Fabrication) V1', 'Gastrointestinal System, Reposition', 1, 1, 1),
(102, 'Structural and Misc Steel (Fabrication) V2', 'Gastrointestinal System, Reposition', 1, 1, 1),
(103, 'Fire Sprinkler System V3', 'Destruction of Left Innominate Vein, Percutaneous Approach', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `comment_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_text`, `added_by`, `item_id`, `added_at`, `comment_status`) VALUES
(196, 'Hey Babe', 275, 694, '2021-09-01 14:12:34', 1),
(199, 'hello', 279, 697, '2021-09-02 19:56:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `product_main` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `new_price` int(11) NOT NULL,
  `expire` datetime NOT NULL,
  `used_times` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`coupon_id`, `coupon_code`, `product_main`, `added_by`, `new_price`, `expire`, `used_times`) VALUES
(8, 'KWILI21', 695, 278, 87, '2021-09-05 00:25:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `log_id` int(11) NOT NULL,
  `log_text` text NOT NULL,
  `log_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `log_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`log_id`, `log_text`, `log_datetime`, `log_by`) VALUES
(849, 'Activating Product: \"nonos zwin\" By \"admin\"', '2021-09-03 17:49:09', 'admin'),
(850, 'Activating Product: \"Amine Samlali\" By \"admin\"', '2021-09-03 22:28:32', 'admin'),
(851, 'showing User Information\'s: \"anas123anas123\" By \"admin\"', '2021-09-04 00:54:18', 'admin'),
(852, 'Activating Product: \"Amine Samlaliwedwqelkjqloke\" By \"admin\"', '2021-09-04 00:54:21', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_to` int(11) NOT NULL,
  `message_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `added_by`, `added_to`, `message_date`) VALUES
(66, 220, 695, '2021-09-03 15:35:54'),
(67, 275, 695, '2021-09-03 15:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `messages_sms`
--

CREATE TABLE `messages_sms` (
  `sms_id` int(11) NOT NULL,
  `message_main` int(11) NOT NULL,
  `sms_from` int(11) NOT NULL,
  `sms_date` datetime NOT NULL DEFAULT current_timestamp(),
  `sms_text` text NOT NULL,
  `sms_status` int(11) NOT NULL DEFAULT 1,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages_sms`
--

INSERT INTO `messages_sms` (`sms_id`, `message_main`, `sms_from`, `sms_date`, `sms_text`, `sms_status`, `deleted`) VALUES
(167, 66, 220, '2021-09-03 15:35:54', 'Hey babe!', 1, 0),
(168, 66, 220, '2021-09-03 15:43:33', 'waili !', 1, 0),
(169, 66, 278, '2021-09-03 15:43:47', 'yes!', 1, 0),
(170, 66, 220, '2021-09-03 15:45:29', 'Waili!!', 1, 0),
(171, 66, 278, '2021-09-03 15:46:13', 'YESSBEBE', 1, 0),
(172, 67, 275, '2021-09-03 15:50:10', 'hey1', 1, 0),
(173, 67, 278, '2021-09-03 15:50:40', 'afeen azeen', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Added_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Country_Made` varchar(255) NOT NULL,
  `Image` text DEFAULT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 0,
  `Rating` varchar(255) NOT NULL,
  `Category` int(11) NOT NULL,
  `Added_by` int(11) NOT NULL,
  `tags` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `Name`, `Description`, `Price`, `Added_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Category`, `Added_by`, `tags`) VALUES
(694, 'sumsung 8+', 'tele ba9i n9i yallah khdemt beh chi 2 moins', '1800', '2021-08-29 15:04:42', 'Morocco', '6.webp', 1, '2', 79, 220, 'tele,mobile,hatif'),
(695, 'Bmw serie 4 gran coupe 2L 190ch', 'Bmw 420 d / modèle 2016 / kilométrage 190k Pack sport Volant M Toit ouvrant Climatisation Bi-zone …', '295000', '2021-08-30 21:02:24', 'USA', '7.jpg', 1, '2', 78, 278, 'BMW,car,tonobile'),
(697, 'Amine Samlali', 'this is test', '121', '2021-09-02 19:19:00', 'Morocco', '9.gif', 1, '1', 78, 279, 'amine,ali,maohammed'),
(698, 'nonos zwin', 'nonos ba9i n9i , yallah l3ebt beh chi 4 mois db.', '81', '2021-09-03 16:49:01', 'Morocco', '10.jpg', 1, '2', 79, 220, 'nonos,bimo'),
(699, 'Amine Samlali', 'this is test', '12', '2021-09-03 21:28:26', 'maroc', '10.gif', 1, '1', 78, 220, 'amine,ali,maohammed'),
(700, 'Amine Samlaliwedwqelkjqloke', 'wwwwwwwwwwwwwwwwwWrew', '121', '2021-09-03 23:53:55', 'Morocco', '10.png', 1, '2', 83, 220, 'amine,ali,maohammed');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `maintenance_mode` int(11) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `maintenance_mode`, `email`) VALUES
(1, 0, 'samlaliamine2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT 0,
  `trust_status` tinyint(1) NOT NULL DEFAULT 1,
  `reg_status` int(11) NOT NULL DEFAULT 0,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_ip` varchar(255) DEFAULT NULL,
  `user_log` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `image`, `group_id`, `trust_status`, `reg_status`, `registration_date`, `user_ip`, `user_log`) VALUES
(220, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Amine Samlali', 'admin@gmail.org', '3.jpeg', 1, 1, 1, '2021-08-18 23:11:54', '41.141.189.0', NULL),
(275, 'samlaliamine2', 'cb1bfdadd34f5b8e6ed456abe40a2346f5206eac', 'samlaliamine', 'samlaliamine2@gmail.com', '2.jpeg', 0, 1, 1, '2021-08-27 14:21:37', NULL, NULL),
(277, 'samlaliamine1', 'a15dc1d7744281ebf4a2d854686065f5c12a7d12', 'Amine Samlali', 'samlaliamine2@gmail.com', '5.png', 0, 1, 1, '2021-08-29 14:11:50', NULL, NULL),
(278, 'samlaliamine0', 'c1df7d6fe4c0a311d8f608e0e2e2bb68f072f6af', 'Amine Samlali', 'samlaliamine2@gmail.com', '6.gif', 0, 1, 1, '2021-08-29 14:49:12', NULL, NULL),
(279, 'anas123anas123', '612c6b905ce7cb7769ba91c7f14aa4d2f7e34f8f', 'anas test1', 'samlaliamine2@gmail.com', '7.png', 0, 1, 1, '2021-09-02 19:03:53', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorys`
--
ALTER TABLE `categorys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dame` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `commentParrent` (`item_id`),
  ADD KEY `comment_who` (`added_by`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`),
  ADD KEY `addedByUser` (`added_by`),
  ADD KEY `mainProduct` (`product_main`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `addedBy` (`added_by`),
  ADD KEY `addedTo` (`added_to`);

--
-- Indexes for table `messages_sms`
--
ALTER TABLE `messages_sms`
  ADD PRIMARY KEY (`sms_id`),
  ADD KEY `messageMain` (`message_main`),
  ADD KEY `messageFrom` (`sms_from`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `userid` (`Added_by`),
  ADD KEY `category_id` (`Category`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorys`
--
ALTER TABLE `categorys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=853;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `messages_sms`
--
ALTER TABLE `messages_sms`
  MODIFY `sms_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=701;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `commentParrent` FOREIGN KEY (`item_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_who` FOREIGN KEY (`added_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `addedByUser` FOREIGN KEY (`added_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mainProduct` FOREIGN KEY (`product_main`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `addedBy` FOREIGN KEY (`added_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `addedTo` FOREIGN KEY (`added_to`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages_sms`
--
ALTER TABLE `messages_sms`
  ADD CONSTRAINT `messageFrom` FOREIGN KEY (`sms_from`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messageMain` FOREIGN KEY (`message_main`) REFERENCES `messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `category_id` FOREIGN KEY (`Category`) REFERENCES `categorys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userid` FOREIGN KEY (`Added_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
