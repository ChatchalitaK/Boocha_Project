-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 08:37 AM
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
-- Database: `boocha`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` varchar(20) NOT NULL,
  `CustomerID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `CustomerID`) VALUES
('CRT0000001', 'CUS0000001'),
('CRT0000002', 'CUS0000002'),
('CRT0000003', 'CUS0000003'),
('CRT0000004', 'CUS0000004'),
('CRT0000005', 'CUS0000005'),
('CRT0000006', 'CUS0000006'),
('CRT0000007', 'CUS0000007'),
('CRT0000008', 'CUS0000008'),
('CRT0000009', 'CUS0000009'),
('CRT0000010', 'CUS0000010'),
('CRT0000011', 'CUS0000011'),
('CRT0000012', 'CUS0000012'),
('CRT0000013', 'CUS0000013'),
('CRT0000014', 'CUS0000014'),
('CRT0000015', 'CUS0000015'),
('CRT0000016', 'CUS0000016'),
('CRT0000017', 'CUS0000017'),
('CRT0000018', 'CUS0000018'),
('CRT0000019', 'CUS0000019'),
('CRT0000020', 'CUS0000020'),
('CRT0000021', 'CUS0000021'),
('CRT0000022', 'CUS0000022'),
('CRT0000023', 'CUS0000023'),
('CRT0000024', 'CUS0000024'),
('CRT0000025', 'CUS0000025'),
('CRT0000026', 'CUS0000026'),
('CRT0000027', 'CUS0000027'),
('CRT0000028', 'CUS0000028'),
('CRT0000029', 'CUS0000029'),
('CRT0000030', 'CUS0000030'),
('CRT0000031', 'CUS0000031'),
('CRT0000032', 'CUS0000032'),
('CRT0000033', 'CUS0000033'),
('CRT0000034', 'CUS0000034'),
('CRT0000035', 'CUS0000035'),
('CRT0000036', 'CUS0000036'),
('CRT0000037', 'CUS0000037'),
('CRT0000038', 'CUS0000038'),
('CRT0000039', 'CUS0000039'),
('CRT0000040', 'CUS0000040'),
('CRT0000041', 'CUS0000041'),
('CRT0000042', 'CUS0000042'),
('CRT0000043', 'CUS0000043'),
('CRT0000044', 'CUS0000044'),
('CRT0000045', 'CUS0000045'),
('CRT0000046', 'CUS0000046'),
('CRT0000047', 'CUS0000047'),
('CRT0000048', 'CUS0000048'),
('CRT0000049', 'CUS0000049'),
('CRT0000050', 'CUS0000050'),
('CRT0000051', 'CUS0000051');

--
-- Triggers `cart`
--
DELIMITER $$
CREATE TRIGGER `before_insert_cart` BEFORE INSERT ON `cart` FOR EACH ROW BEGIN
   DECLARE new_id INT;

   -- ดึงเลขสูงสุดจาก CartID ที่มีอยู่ แล้ว +1
   SELECT MAX(CAST(SUBSTRING(CartID, 4) AS UNSIGNED)) INTO new_id FROM Cart;

   -- สร้าง CartID ใหม่โดยคง prefix CRT ไว้ แล้วต่อด้วยเลข 7 หลัก
   SET NEW.CartID = CONCAT('CRT', LPAD(new_id + 1, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `Cart_itemID` varchar(20) NOT NULL,
  `CartID` varchar(20) DEFAULT NULL,
  `ProductID` varchar(20) DEFAULT NULL,
  `Product_quantity` int(11) DEFAULT NULL,
  `Cartadd_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`Cart_itemID`, `CartID`, `ProductID`, `Product_quantity`, `Cartadd_date`) VALUES
('CRI0000001', 'CRT0000043', 'FUKSHI01', 1, '2025-02-16 17:00:08'),
('CRI0000002', 'CRT0000003', 'SENSHI00', 3, '2025-03-24 20:38:08'),
('CRI0000003', 'CRT0000034', 'GENMIE11', 5, '2025-02-10 10:40:08'),
('CRI0000004', 'CRT0000029', 'GENSHI00', 4, '2025-03-07 13:07:08'),
('CRI0000005', 'CRT0000010', 'GENMIE20', 2, '2025-01-25 10:08:08'),
('CRI0000006', 'CRT0000010', 'MATUJI30', 1, '2025-02-18 20:15:08'),
('CRI0000007', 'CRT0000028', 'BANFUK00', 4, '2025-02-08 21:53:08'),
('CRI0000008', 'CRT0000028', 'MATFUK30', 1, '2025-03-19 21:17:08'),
('CRI0000009', 'CRT0000032', 'BANKUM11', 2, '2025-02-19 11:41:08'),
('CRI0000010', 'CRT0000032', 'KABFUK30', 1, '2025-03-01 20:08:08'),
('CRI0000011', 'CRT0000015', 'SENUJI00', 1, '2025-03-23 15:55:08'),
('CRI0000012', 'CRT0000015', 'MATFUK31', 3, '2025-03-19 21:40:08'),
('CRI0000013', 'CRT0000024', 'BANFUK10', 1, '2025-02-26 02:59:08'),
('CRI0000014', 'CRT0000004', 'SENKUM01', 1, '2025-02-01 13:33:08'),
('CRI0000015', 'CRT0000030', 'HOJFUK11', 1, '2025-02-14 18:10:08'),
('CRI0000016', 'CRT0000007', 'MATFUK21', 4, '2025-03-14 03:59:08'),
('CRI0000017', 'CRT0000002', 'MATUJI10', 2, '2025-03-09 08:48:08'),
('CRI0000018', 'CRT0000017', 'BANMIE01', 1, '2025-02-13 02:37:08'),
('CRI0000019', 'CRT0000038', 'GYOUJI11', 4, '2025-02-12 05:29:08'),
('CRI0000020', 'CRT0000039', 'HOJKUM21', 2, '2025-03-16 15:44:08'),
('CRI0000021', 'CRT0000039', 'MATUJI00', 2, '2025-03-16 16:07:08'),
('CRI0000022', 'CRT0000036', 'GENSHI20', 1, '2025-03-16 01:37:08'),
('CRI0000023', 'CRT0000018', 'SENUJI20', 4, '2025-02-08 13:53:08'),
('CRI0000024', 'CRT0000033', 'BANKUM31', 1, '2025-02-16 21:02:08'),
('CRI0000025', 'CRT0000033', 'GYOSHI11', 2, '2025-03-23 07:38:08'),
('CRI0000026', 'CRT0000050', 'KABMIE00', 4, '2025-01-30 10:01:08'),
('CRI0000027', 'CRT0000009', 'GENMIE20', 3, '2025-01-25 05:50:08'),
('CRI0000028', 'CRT0000009', 'BANFUK21', 1, '2025-03-16 11:00:08'),
('CRI0000029', 'CRT0000022', 'BANUJI01', 2, '2025-03-23 20:05:08'),
('CRI0000030', 'CRT0000022', 'HOJUJI30', 4, '2025-03-23 19:30:08'),
('CRI0000031', 'CRT0000016', 'HOJUJI11', 4, '2025-03-05 06:51:08'),
('CRI0000032', 'CRT0000016', 'SENUJI31', 1, '2025-03-23 10:56:08'),
('CRI0000033', 'CRT0000006', 'HOJKUM00', 2, '2025-03-08 02:33:08'),
('CRI0000034', 'CRT0000023', 'KABSHI20', 1, '2025-03-16 16:48:08'),
('CRI0000035', 'CRT0000021', 'KABSHI31', 3, '2025-03-03 16:42:08'),
('CRI0000036', 'CRT0000046', 'GENSHI00', 1, '2025-03-07 10:36:08'),
('CRI0000037', 'CRT0000046', 'KABUJI10', 4, '2025-02-27 17:18:08'),
('CRI0000038', 'CRT0000040', 'GYOFUK21', 2, '2025-03-07 13:54:08'),
('CRI0000039', 'CRT0000040', 'HOJMIE00', 3, '2025-02-15 22:01:08'),
('CRI0000041', 'CRT0000051', 'FUKSHI00', 2, '2025-05-02 15:42:23'),
('CRI0000042', 'CRT0000051', 'GENUJI00', 1, '2025-05-02 15:42:30'),
('CRI0000043', 'CRT0000051', 'KABSHI20', 2, '2025-05-09 05:08:03');

--
-- Triggers `cart_item`
--
DELIMITER $$
CREATE TRIGGER `before_insert_cart_item` BEFORE INSERT ON `cart_item` FOR EACH ROW BEGIN
   DECLARE new_id INT;

   -- ดึงเลขสูงสุดจาก Cart_itemID แล้ว +1
   SELECT MAX(CAST(SUBSTRING(Cart_itemID, 4) AS UNSIGNED)) INTO new_id FROM Cart_item;

   -- สร้าง Cart_itemID ใหม่โดยคง prefix CRI ไว้ แล้วต่อด้วยเลข 7 หลัก
   SET NEW.Cart_itemID = CONCAT('CRI', LPAD(new_id + 1, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `GradeID` varchar(20) NOT NULL,
  `GradeName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`GradeID`, `GradeName`) VALUES
('GR001', 'Culinary'),
('GR002', 'Ceremonial');

--
-- Triggers `grade`
--
DELIMITER $$
CREATE TRIGGER `before_insert_grade` BEFORE INSERT ON `grade` FOR EACH ROW BEGIN
   DECLARE new_id INT;
   
   -- ค้นหาค่าตัวเลขสูงสุดจาก ID ที่มีอยู่
   SELECT MAX(CAST(SUBSTRING(GradeID, 3) AS UNSIGNED)) INTO new_id FROM Grade;
   
   -- กำหนดค่า GradeID ที่มี Prefix GR
   SET NEW.GradeID = CONCAT('GR', LPAD(new_id + 1, 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` varchar(9) NOT NULL,
  `CustomerID` varchar(10) NOT NULL,
  `OrderDate` datetime NOT NULL,
  `Note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderID`, `CustomerID`, `OrderDate`, `Note`) VALUES
('AA0000001', 'CUS0000003', '2024-12-03 09:42:17', ''),
('AA0000002', 'CUS0000047', '2024-12-05 14:23:58', ''),
('AA0000003', 'CUS0000012', '2024-12-07 07:56:31', 'Can you gift wrap the tea powder and add a note: \'Enjoy your tea and have a great day!'),
('AA0000004', 'CUS0000029', '2024-12-09 16:08:45', ''),
('AA0000005', 'CUS0000005', '2024-12-11 11:37:22', ''),
('AA0000006', 'CUS0000038', '2024-12-13 08:59:03', 'Please write a birthday card with the message: \'Happy Birthday! Wishing you a day filled with joy and happiness.'),
('AA0000007', 'CUS0000017', '2024-12-15 13:24:51', ''),
('AA0000008', 'CUS0000042', '2024-12-17 10:45:36', ''),
('AA0000009', 'CUS0000009', '2024-12-19 15:12:44', ''),
('AA0000010', 'CUS0000021', '2024-12-21 06:33:27', ''),
('AA0000011', 'CUS0000035', '2024-12-23 12:47:19', ''),
('AA0000012', 'CUS0000014', '2024-12-25 17:30:08', ''),
('AA0000013', 'CUS0000048', '2024-12-27 09:18:52', 'Can you gift wrap the tea powder in a nice box with a ribbon? Thank you!'),
('AA0000014', 'CUS0000002', '2024-12-29 14:05:41', ''),
('AA0000015', 'CUS0000031', '2024-12-31 10:22:33', ''),
('AA0000016', 'CUS0000025', '2025-01-02 08:47:26', ''),
('AA0000017', 'CUS0000040', '2025-01-04 13:39:14', 'Please include a thank you card with the message: \'Thank you for your support! Enjoy your tea!'),
('AA0000018', 'CUS0000019', '2025-01-06 16:20:57', ''),
('AA0000019', 'CUS0000036', '2025-01-08 07:53:42', ''),
('AA0000020', 'CUS0000007', '2025-01-10 11:28:35', ''),
('AA0000021', 'CUS0000028', '2025-01-12 09:45:21', 'Can you write a congratulatory card with the message: \'Congratulations on your achievement! Keep up the great work!'),
('AA0000022', 'CUS0000045', '2025-01-14 14:17:08', 'Please gift wrap the tea powder and add a small note: \'Enjoy your tea time!\''),
('AA0000023', 'CUS0000011', '2025-01-16 10:39:52', ''),
('AA0000024', 'CUS0000033', '2025-01-18 17:04:43', ''),
('AA0000025', 'CUS0000004', '2025-01-20 08:26:37', ''),
('AA0000026', 'CUS0000022', '2025-01-22 12:53:29', ''),
('AA0000027', 'CUS0000049', '2025-01-24 15:38:14', ''),
('AA0000028', 'CUS0000016', '2025-01-26 07:22:45', 'Can you write a birthday card with the message: \'Wishing you a wonderful birthday and a year full of blessings!'),
('AA0000029', 'CUS0000030', '2025-01-28 13:59:31', ''),
('AA0000030', 'CUS0000008', '2025-01-30 09:41:18', 'Please include a card with the message: \'Thank you for choosing us! We hope you enjoy your tea!'),
('AA0000031', 'CUS0000024', '2025-02-01 16:27:53', ''),
('AA0000032', 'CUS0000041', '2025-02-03 11:14:42', ''),
('AA0000033', 'CUS0000013', '2025-02-05 08:39:27', 'Can you gift wrap the tea powder and add a note: \'Best wishes for a happy and healthy life!'),
('AA0000034', 'CUS0000037', '2025-02-07 14:52:16', ''),
('AA0000035', 'CUS0000006', '2025-02-09 10:23:05', ''),
('AA0000036', 'CUS0000027', '2025-02-11 17:08:49', ''),
('AA0000037', 'CUS0000043', '2025-02-13 09:37:32', ''),
('AA0000038', 'CUS0000018', '2025-02-15 13:45:21', ''),
('AA0000039', 'CUS0000032', '2025-02-17 07:59:14', 'Please write a thank you card with the message: \'Thank you for your order! We appreciate your support!'),
('AA0000040', 'CUS0000001', '2025-02-19 12:36:47', ''),
('AA0000041', 'CUS0000026', '2025-02-21 15:22:38', ''),
('AA0000042', 'CUS0000046', '2025-02-23 08:47:25', ''),
('AA0000043', 'CUS0000015', '2025-02-25 11:53:19', 'Can you include a birthday card with the message: \'Happy Birthday! May your day be as special as you are!'),
('AA0000044', 'CUS0000039', '2025-02-27 14:28:06', ''),
('AA0000045', 'CUS0000020', '2025-03-01 09:15:42', ''),
('AA0000046', 'CUS0000044', '2025-03-03 16:39:27', ''),
('AA0000047', 'CUS0000010', '2025-03-05 10:52:13', ''),
('AA0000048', 'CUS0000034', '2025-03-07 13:27:48', ''),
('AA0000049', 'CUS0000005', '2025-03-09 07:43:35', 'Can you write a thank you card with the message: \'Thank you for your support! Enjoy your tea time!'),
('AA0000050', 'CUS0000023', '2025-03-11 12:18:54', ''),
('AA0000051', 'CUS0000047', '2025-03-13 15:47:29', ''),
('AA0000052', 'CUS0000012', '2025-03-15 08:32:17', ''),
('AA0000053', 'CUS0000029', '2025-03-17 11:59:43', ''),
('AA0000054', 'CUS0000002', '2025-03-19 14:26:38', ''),
('AA0000055', 'CUS0000038', '2025-03-21 09:53:24', ''),
('AA0000056', 'CUS0000017', '2025-03-23 17:12:45', 'Please include a birthday card with the message: \'Happy Birthday! Wishing you all the best on your special day!'),
('AA0000057', 'CUS0000042', '2025-03-25 10:37:52', ''),
('AA0000058', 'CUS0000021', '2025-03-27 13:48:39', ''),
('AA0000059', 'CUS0000035', '2025-03-29 08:15:26', ''),
('AA0000060', 'CUS0000014', '2025-03-31 16:29:14', ''),
('AA0000061', 'CUS0000048', '2025-04-02 11:42:37', ''),
('AA0000062', 'CUS0000002', '2025-04-04 14:53:28', ''),
('AA0000063', 'CUS0000031', '2025-04-06 09:27:45', 'Please gift wrap the tea powder and add a small note: \'Wishing you a delightful tea experience!'),
('AA0000064', 'CUS0000025', '2025-04-08 12:39:16', ''),
('AA0000065', 'CUS0000040', '2025-05-01 15:24:53', ''),
('AA0000066', 'CUS0000019', '2025-05-03 07:58:42', 'Can you write a card with the message: \'Congratulations on your new journey! Best of luck!'),
('AA0000067', 'CUS0000036', '2025-05-09 10:33:27', ''),
('AA0000068', 'CUS0000007', '2025-05-09 13:47:19', ''),
('AA0000069', 'CUS0000028', '2025-05-09 17:05:38', ''),
('AA0000070', 'CUS0000003', '2025-05-12 14:50:31', NULL);

--
-- Triggers `order`
--
DELIMITER $$
CREATE TRIGGER `before_insert_order` BEFORE INSERT ON `order` FOR EACH ROW BEGIN
    DECLARE max_id VARCHAR(9);
    DECLARE prefix1 CHAR(1);
    DECLARE prefix2 CHAR(1);
    DECLARE num_part INT;

    -- ดึง OrderID ล่าสุด
    SELECT OrderID INTO max_id
    FROM `order`
    WHERE OrderID IS NOT NULL
    ORDER BY OrderID DESC
    LIMIT 1;

    IF max_id IS NULL THEN
        SET NEW.OrderID = 'AA0000001';
    ELSE
        SET prefix1 = SUBSTRING(max_id, 1, 1);
        SET prefix2 = SUBSTRING(max_id, 2, 1);
        SET num_part = CAST(SUBSTRING(max_id, 3) AS UNSIGNED);

        IF num_part < 9999999 THEN
            SET num_part = num_part + 1;
        ELSE
            -- reset number & update prefix
            SET num_part = 1;
            IF prefix2 < 'Z' THEN
                SET prefix2 = CHAR(ASCII(prefix2) + 1);
            ELSE
                SET prefix2 = 'A';
                IF prefix1 < 'Z' THEN
                    SET prefix1 = CHAR(ASCII(prefix1) + 1);
                ELSE
                    -- ถึง ZZ9999999 แล้ว
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Maximum OrderID limit reached';
                END IF;
            END IF;
        END IF;

        SET NEW.OrderID = CONCAT(prefix1, prefix2, LPAD(num_part, 7, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `OrderdetailID` varchar(11) NOT NULL,
  `OrderID` varchar(9) NOT NULL,
  `ProductID` varchar(8) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Total_productPrice` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`OrderdetailID`, `OrderID`, `ProductID`, `Quantity`, `Total_productPrice`) VALUES
('AAAA0000001', 'AA0000001', 'SENSHI20', 3, 1650),
('AAAA0000002', 'AA0000002', 'MATSHI31', 1, 1100),
('AAAA0000003', 'AA0000002', 'KABMIE31', 1, 1160),
('AAAA0000004', 'AA0000003', 'BANFUK01', 2, 2060),
('AAAA0000005', 'AA0000004', 'KABUJI21', 4, 4480),
('AAAA0000006', 'AA0000005', 'HOJMIE30', 1, 900),
('AAAA0000007', 'AA0000006', 'BANUJI00', 2, 1460),
('AAAA0000008', 'AA0000007', 'HOJUJI10', 2, 1600),
('AAAA0000009', 'AA0000007', 'HOJMIE21', 2, 2300),
('AAAA0000010', 'AA0000007', 'HOJKUM00', 2, 1500),
('AAAA0000011', 'AA0000008', 'KABMIE20', 1, 810),
('AAAA0000012', 'AA0000009', 'SENUJI10', 3, 1650),
('AAAA0000013', 'AA0000010', 'GYOUJI20', 1, 920),
('AAAA0000014', 'AA0000011', 'MATFUK00', 4, 2440),
('AAAA0000015', 'AA0000012', 'HOJUJI11', 2, 2200),
('AAAA0000016', 'AA0000013', 'GYOSHI30', 1, 950),
('AAAA0000017', 'AA0000014', 'BANUJI00', 3, 2190),
('AAAA0000018', 'AA0000015', 'MATSHI11', 2, 1900),
('AAAA0000019', 'AA0000016', 'KABMIE30', 1, 860),
('AAAA0000020', 'AA0000017', 'GENSHI20', 3, 2580),
('AAAA0000021', 'AA0000018', 'SENUJI30', 2, 1400),
('AAAA0000022', 'AA0000019', 'FUKSHI10', 1, 820),
('AAAA0000023', 'AA0000019', 'GYOFUK10', 2, 1720),
('AAAA0000024', 'AA0000020', 'BANFUK10', 1, 780),
('AAAA0000025', 'AA0000021', 'HOJMIE01', 3, 3150),
('AAAA0000026', 'AA0000022', 'KABSHI01', 2, 2000),
('AAAA0000027', 'AA0000023', 'MATFUK20', 1, 710),
('AAAA0000028', 'AA0000024', 'SENKUM00', 3, 1380),
('AAAA0000029', 'AA0000025', 'GENUJI00', 2, 1520),
('AAAA0000030', 'AA0000026', 'GYOFUK31', 1, 1360),
('AAAA0000031', 'AA0000027', 'BANUJI10', 2, 1560),
('AAAA0000032', 'AA0000027', 'MATSHI31', 1, 1100),
('AAAA0000033', 'AA0000028', 'KABMIE00', 3, 2130),
('AAAA0000034', 'AA0000029', 'HOJUJI21', 2, 2300),
('AAAA0000035', 'AA0000030', 'SENSHI10', 1, 500),
('AAAA0000036', 'AA0000031', 'FUKSHI21', 3, 3510),
('AAAA0000037', 'AA0000031', 'GYOUJI11', 2, 2540),
('AAAA0000038', 'AA0000032', 'BANMIE11', 1, 1080),
('AAAA0000039', 'AA0000033', 'MATFUK10', 2, 1320),
('AAAA0000040', 'AA0000034', 'KABSHI30', 1, 850),
('AAAA0000041', 'AA0000035', 'SENFUK21', 3, 2490),
('AAAA0000042', 'AA0000036', 'GENSHI01', 2, 2120),
('AAAA0000043', 'AA0000037', 'GYOFUK20', 1, 910),
('AAAA0000044', 'AA0000038', 'BANMIE01', 3, 3090),
('AAAA0000045', 'AA0000039', 'MATSHI01', 2, 1800),
('AAAA0000046', 'AA0000040', 'KABFUK00', 1, 730),
('AAAA0000047', 'AA0000041', 'HOJMIE00', 2, 1500),
('AAAA0000048', 'AA0000041', 'SENUJI11', 1, 800),
('AAAA0000049', 'AA0000042', 'FUKSHI11', 3, 3360),
('AAAA0000050', 'AA0000043', 'GYOUJI30', 2, 1940),
('AAAA0000051', 'AA0000044', 'BANMIE30', 1, 880),
('AAAA0000052', 'AA0000045', 'MATUJI21', 2, 2040),
('AAAA0000053', 'AA0000046', 'KABUJI00', 4, 2880),
('AAAA0000054', 'AA0000047', 'SENKUM10', 3, 1560),
('AAAA0000055', 'AA0000048', 'GENSHI30', 2, 1820),
('AAAA0000056', 'AA0000049', 'GYOFUK21', 1, 1310),
('AAAA0000057', 'AA0000049', 'BANUJI21', 2, 2260),
('AAAA0000058', 'AA0000050', 'MATSHI21', 1, 1000),
('AAAA0000059', 'AA0000051', 'KABMIE10', 3, 2280),
('AAAA0000060', 'AA0000052', 'HOJMIE10', 2, 1600),
('AAAA0000061', 'AA0000053', 'SENSHI30', 1, 600),
('AAAA0000062', 'AA0000053', 'FUKSHI30', 3, 2760),
('AAAA0000063', 'AA0000054', 'GYOFUK01', 2, 2420),
('AAAA0000064', 'AA0000055', 'BANFUK01', 1, 1030),
('AAAA0000065', 'AA0000056', 'MATFUK00', 2, 1220),
('AAAA0000066', 'AA0000057', 'KABUJI10', 1, 770),
('AAAA0000067', 'AA0000058', 'SENKUM21', 3, 2460),
('AAAA0000068', 'AA0000059', 'GENSHI11', 2, 2220),
('AAAA0000069', 'AA0000059', 'GYOFUK30', 1, 960),
('AAAA0000070', 'AA0000060', 'BANMIE00', 2, 1460),
('AAAA0000071', 'AA0000061', 'MATUJI00', 5, 3100),
('AAAA0000072', 'AA0000062', 'KABFUK10', 3, 2340),
('AAAA0000073', 'AA0000063', 'HOJMIE11', 2, 2200),
('AAAA0000074', 'AA0000064', 'SENMIE01', 1, 650),
('AAAA0000075', 'AA0000065', 'FUKSHI31', 3, 3660),
('AAAA0000076', 'AA0000066', 'GYOUJI20', 2, 1840),
('AAAA0000077', 'AA0000067', 'BANFUK20', 1, 830),
('AAAA0000080', 'AA0000070', 'BANKUM21', 3, 2260);

--
-- Triggers `order_detail`
--
DELIMITER $$
CREATE TRIGGER `before_insert_orderdetail` BEFORE INSERT ON `order_detail` FOR EACH ROW BEGIN
    DECLARE max_id VARCHAR(11);
    DECLARE prefix CHAR(4);
    DECLARE num_part INT;
    DECLARE i INT;
    DECLARE ascii_val INT;
    DECLARE done_prefix BOOLEAN DEFAULT FALSE;

    -- ดึง OrderdetailID ล่าสุด
    SELECT OrderdetailID INTO max_id
    FROM order_detail
    WHERE OrderdetailID IS NOT NULL
    ORDER BY OrderdetailID DESC
    LIMIT 1;

    IF max_id IS NULL THEN
        SET NEW.OrderdetailID = 'AAAA0000001';
    ELSE
        SET prefix = SUBSTRING(max_id, 1, 4);
        SET num_part = CAST(SUBSTRING(max_id, 5) AS UNSIGNED);

        IF num_part < 9999999 THEN
            SET num_part = num_part + 1;
        ELSE
            SET num_part = 1;
            SET i = 4;

            change_prefix: WHILE i >= 1 DO
                SET ascii_val = ASCII(SUBSTRING(prefix, i, 1));
                IF ascii_val < ASCII('Z') THEN
                    SET prefix = CONCAT(
                        LEFT(prefix, i - 1),
                        CHAR(ascii_val + 1),
                        REPEAT('A', 4 - i)
                    );
                    SET done_prefix = TRUE;
                    LEAVE change_prefix;
                END IF;
                SET i = i - 1;
            END WHILE;

            -- ถ้ายังไม่สามารถเลื่อน prefix ได้ (กรณีถึง ZZZZ แล้ว)
            IF NOT done_prefix THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Maximum OrderdetailID limit reached';
            END IF;
        END IF;

        SET NEW.OrderdetailID = CONCAT(prefix, LPAD(num_part, 7, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` varchar(10) NOT NULL,
  `OrderID` varchar(9) NOT NULL,
  `Total_price` decimal(10,0) NOT NULL,
  `Payment_method` varchar(30) NOT NULL,
  `Payment_status` varchar(30) NOT NULL,
  `Paydate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentID`, `OrderID`, `Total_price`, `Payment_method`, `Payment_status`, `Paydate`) VALUES
('PAY0000001', 'AA0000001', 1730, 'Mobile Banking', 'Paid', '2024-12-04 08:15:23'),
('PAY0000002', 'AA0000002', 2340, 'Credit/Debit Card', 'Paid', '2024-12-05 18:15:43'),
('PAY0000003', 'AA0000003', 2140, 'Mobile Banking', 'Paid', '2024-12-09 08:15:23'),
('PAY0000004', 'AA0000004', 4560, 'Mobile Banking', 'Paid', '2024-12-09 10:35:23'),
('PAY0000005', 'AA0000005', 980, 'Credit/Debit Card', 'Paid', '2024-12-11 08:34:22'),
('PAY0000006', 'AA0000006', 1540, 'Credit/Debit Card', 'Paid', '2024-12-13 12:45:16'),
('PAY0000007', 'AA0000007', 5480, 'Mobile Banking', 'Paid', '2024-12-15 10:32:47'),
('PAY0000008', 'AA0000008', 890, 'Mobile Banking', 'Paid', '2024-12-18 05:48:12'),
('PAY0000009', 'AA0000009', 1730, 'Mobile Banking', 'Paid', '2024-12-19 14:20:05'),
('PAY0000010', 'AA0000010', 1000, 'Credit/Debit Card', 'Paid', '2024-12-22 09:33:56'),
('PAY0000011', 'AA0000011', 2520, 'Mobile Banking', 'Paid', '2024-12-23 11:45:30'),
('PAY0000012', 'AA0000012', 2280, 'Mobile Banking', 'Paid', '2024-12-26 07:22:18'),
('PAY0000013', 'AA0000013', 1030, 'Mobile Banking', 'Paid', '2024-12-27 16:10:41'),
('PAY0000014', 'AA0000014', 2270, 'Credit/Debit Card', 'Paid', '2024-12-29 11:27:45'),
('PAY0000015', 'AA0000015', 1980, 'Credit/Debit Card', 'Paid', '2025-01-01 04:52:18'),
('PAY0000016', 'AA0000016', 940, 'Credit/Debit Card', 'Paid', '2025-01-02 15:08:33'),
('PAY0000017', 'AA0000017', 2660, 'Credit/Debit Card', 'Paid', '2025-01-05 08:41:07'),
('PAY0000018', 'AA0000018', 1480, 'Credit/Debit Card', 'Paid', '2025-01-06 12:19:22'),
('PAY0000019', 'AA0000019', 2620, 'Credit/Debit Card', 'Paid', '2025-01-09 06:35:50'),
('PAY0000020', 'AA0000020', 860, 'Mobile Banking', 'Paid', '2025-01-10 17:24:11'),
('PAY0000021', 'AA0000021', 3230, 'Mobile Banking', 'Paid', '2025-01-13 09:47:39'),
('PAY0000022', 'AA0000022', 2080, 'Credit/Debit Card', 'Paid', '2025-01-14 14:03:56'),
('PAY0000023', 'AA0000023', 790, 'Credit/Debit Card', 'Paid', '2025-01-17 03:28:44'),
('PAY0000024', 'AA0000024', 1460, 'Mobile Banking', 'Paid', '2025-01-18 14:36:52'),
('PAY0000025', 'AA0000025', 1600, 'Credit/Debit Card', 'Paid', '2025-01-21 02:45:18'),
('PAY0000026', 'AA0000026', 1440, 'Credit/Debit Card', 'Paid', '2025-01-22 09:51:07'),
('PAY0000027', 'AA0000027', 2740, 'Credit/Debit Card', 'Paid', '2025-01-25 05:23:40'),
('PAY0000028', 'AA0000028', 2210, 'Credit/Debit Card', 'Paid', '2025-01-26 16:08:29'),
('PAY0000029', 'AA0000029', 2380, 'Credit/Debit Card', 'Paid', '2025-01-29 11:42:15'),
('PAY0000030', 'AA0000030', 580, 'Mobile Banking', 'Paid', '2025-01-31 07:54:33'),
('PAY0000031', 'AA0000031', 6130, 'Credit/Debit Card', 'Paid', '2025-02-02 13:27:01'),
('PAY0000032', 'AA0000032', 1160, 'Mobile Banking', 'Paid', '2025-02-04 08:19:46'),
('PAY0000033', 'AA0000033', 1400, 'Credit/Debit Card', 'Paid', '2025-02-06 19:35:22'),
('PAY0000034', 'AA0000034', 930, 'Credit/Debit Card', 'Paid', '2025-02-08 10:12:57'),
('PAY0000035', 'AA0000035', 2570, 'Credit/Debit Card', 'Paid', '2025-02-10 22:48:05'),
('PAY0000036', 'AA0000036', 2200, 'Credit/Debit Card', 'Paid', '2025-02-12 04:30:11'),
('PAY0000037', 'AA0000037', 990, 'Credit/Debit Card', 'Paid', '2025-02-13 12:42:37'),
('PAY0000038', 'AA0000038', 3170, 'Credit/Debit Card', 'Paid', '2025-02-16 03:18:55'),
('PAY0000039', 'AA0000039', 1880, 'Credit/Debit Card', 'Paid', '2025-02-17 09:27:14'),
('PAY0000040', 'AA0000040', 810, 'Credit/Debit Card', 'Paid', '2025-02-20 06:51:28'),
('PAY0000041', 'AA0000041', 2380, 'Credit/Debit Card', 'Paid', '2025-02-21 14:35:09'),
('PAY0000042', 'AA0000042', 3440, 'Mobile Banking', 'Paid', '2025-02-24 10:08:42'),
('PAY0000043', 'AA0000043', 2020, 'Mobile Banking', 'Paid', '2025-02-26 07:22:19'),
('PAY0000044', 'AA0000044', 960, 'Mobile Banking', 'Paid', '2025-02-28 16:45:33'),
('PAY0000045', 'AA0000045', 2120, 'Credit/Debit Card', 'Paid', '2025-03-02 05:37:51'),
('PAY0000046', 'AA0000046', 2960, 'Credit/Debit Card', 'Paid', '2025-03-04 11:54:27'),
('PAY0000047', 'AA0000047', 1640, 'Credit/Debit Card', 'Paid', '2025-03-06 18:12:45'),
('PAY0000048', 'AA0000048', 1900, 'Credit/Debit Card', 'Paid', '2025-03-08 09:31:08'),
('PAY0000049', 'AA0000049', 3650, 'Credit/Debit Card', 'Paid', '2025-03-10 20:15:52'),
('PAY0000050', 'AA0000050', 1080, 'Credit/Debit Card', 'Paid', '2025-03-12 04:48:16'),
('PAY0000051', 'AA0000051', 2360, 'Credit/Debit Card', 'Paid', '2025-03-13 11:28:47'),
('PAY0000052', 'AA0000052', 1680, 'Credit/Debit Card', 'Paid', '2025-03-16 04:52:19'),
('PAY0000053', 'AA0000053', 3440, 'Credit/Debit Card', 'Paid', '2025-03-17 15:08:34'),
('PAY0000054', 'AA0000054', 2500, 'Credit/Debit Card', 'Paid', '2025-03-20 08:41:08'),
('PAY0000055', 'AA0000055', 1110, 'Credit/Debit Card', 'Paid', '2025-03-21 12:19:23'),
('PAY0000056', 'AA0000056', 1300, 'Mobile Banking', 'Paid', '2025-03-24 06:35:51'),
('PAY0000057', 'AA0000057', 850, 'Credit/Debit Card', 'Paid', '2025-03-26 07:24:12'),
('PAY0000058', 'AA0000058', 2540, 'Mobile Banking', 'Paid', '2025-03-28 13:47:40'),
('PAY0000059', 'AA0000059', 3260, 'Credit/Debit Card', 'Paid', '2025-03-30 14:03:57'),
('PAY0000060', 'AA0000060', 1540, 'Credit/Debit Card', 'Paid', '2025-04-01 03:28:45'),
('PAY0000061', 'AA0000061', 3180, 'Credit/Debit Card', 'Paid', '2025-04-03 09:12:38'),
('PAY0000062', 'AA0000062', 2420, 'Credit/Debit Card', 'Paid', '2025-04-05 17:45:22'),
('PAY0000063', 'AA0000063', 2280, 'Mobile Banking', 'Paid', '2025-04-07 10:30:15'),
('PAY0000064', 'AA0000064', 730, 'Mobile Banking', 'Paid', '2025-04-09 21:18:33'),
('PAY0000065', 'AA0000065', 3740, 'Mobile Banking', 'Paid', '2025-05-02 05:42:09'),
('PAY0000066', 'AA0000066', 1920, 'Mobile Banking', 'Paid', '2025-05-02 10:19:46'),
('PAY0000067', 'AA0000067', 910, 'Mobile Banking', 'Paid', '2025-05-09 08:17:33'),
('PAY0000070', 'AA0000070', 3470, 'Mobile Banking', 'Pending', NULL);

--
-- Triggers `payment`
--
DELIMITER $$
CREATE TRIGGER `before_insert_payment` BEFORE INSERT ON `payment` FOR EACH ROW BEGIN
   DECLARE new_id INT;

   -- ดึงเลขสูงสุดจาก PaymentID แล้ว +1
   SELECT IFNULL(MAX(CAST(SUBSTRING(PaymentID, 4) AS UNSIGNED)), 0)
   INTO new_id
   FROM Payment;

   -- สร้าง PaymentID ใหม่โดยใช้ prefix PAY และเลข 7 หลัก
   SET NEW.PaymentID = CONCAT('PAY', LPAD(new_id + 1, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` varchar(20) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stock` int(11) NOT NULL,
  `Status` enum('Available','Out of Stock') NOT NULL,
  `update_date` date DEFAULT NULL,
  `Image` varchar(500) DEFAULT NULL,
  `TeaTypeID` varchar(20) NOT NULL,
  `ProvinceID` varchar(20) NOT NULL,
  `RoastLevelID` varchar(20) NOT NULL,
  `GradeID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `Description`, `Price`, `Stock`, `Status`, `update_date`, `Image`, `TeaTypeID`, `ProvinceID`, `RoastLevelID`, `GradeID`) VALUES
('BANFUK00', 'Bancha Fukuoka Non-firing Culinary', 'Bancha green tea from Fukuoka, Japan. This non-firing variety offers a rich umami flavor, perfect for culinary applications. Packaged in 150g bags.', 730.00, 139, 'Available', '2025-05-11', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311972/BANFUK00_lb7fjz.png', 'TT001', 'PV001', 'RL001', 'GR001'),
('BANFUK01', 'Bancha Fukuoka Non-firing Ceremonial', 'Premium Bancha green tea from Fukuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a smooth finish. Packaged in 150g bags.', 1030.00, 40, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311976/BANFUK01_h6wksj.png', 'TT001', 'PV001', 'RL001', 'GR002'),
('BANFUK10', 'Bancha Fukuoka Low-firing Culinary', 'Bancha green tea from Fukuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 780.00, 80, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311978/BANFUK10_hzwrsf.png', 'TT001', 'PV001', 'RL002', 'GR001'),
('BANFUK11', 'Bancha Fukuoka Low-firing Ceremonial', 'High-quality Bancha green tea from Fukuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1080.00, 20, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311978/BANFUK11_f9w2vz.png', 'TT001', 'PV001', 'RL002', 'GR002'),
('BANFUK20', 'Bancha Fukuoka Medium-firing Culinary', 'Bancha green tea from Fukuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 830.00, 110, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311976/BANFUK20_lo80kq.png', 'TT001', 'PV001', 'RL003', 'GR001'),
('BANFUK21', 'Bancha Fukuoka Medium-firing Ceremonial', 'Premium Bancha green tea from Fukuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1130.00, 37, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311982/BANFUK21_bxecag.png', 'TT001', 'PV001', 'RL003', 'GR002'),
('BANFUK30', 'Bancha Fukuoka High-firing Culinary', 'Bancha green tea from Fukuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 880.00, 92, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311979/BANFUK30_cng09l.png', 'TT001', 'PV001', 'RL004', 'GR001'),
('BANFUK31', 'Bancha Fukuoka High-firing Ceremonial', 'Exquisite Bancha green tea from Fukuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1180.00, 19, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311979/BANFUK31_s4or0u.png', 'TT001', 'PV001', 'RL004', 'GR002'),
('BANKUM00', 'Bancha Kumamoto Non-firing Culinary', 'Bancha green tea from Kumamoto, Japan. This non-firing variety offers a rich umami flavor, perfect for culinary applications. Packaged in 150g bags.', 730.00, 130, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311984/BANKUM00_lmpct1.png', 'TT001', 'PV002', 'RL001', 'GR001'),
('BANKUM01', 'Bancha Kumamoto Non-firing Ceremonial', 'Premium Bancha green tea from Kumamoto, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a smooth finish. Packaged in 150g bags.', 1030.00, 40, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311982/BANKUM01_l9nwmc.png', 'TT001', 'PV002', 'RL001', 'GR002'),
('BANKUM10', 'Bancha Kumamoto Low-firing Culinary', 'Bancha green tea from Kumamoto, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 780.00, 75, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311985/BANKUM10_rtkyfd.png', 'TT001', 'PV002', 'RL002', 'GR001'),
('BANKUM11', 'Bancha Kumamoto Low-firing Ceremonial', 'High-quality Bancha green tea from Kumamoto, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1080.00, 25, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311982/BANKUM11_zzkofm.png', 'TT001', 'PV002', 'RL002', 'GR002'),
('BANKUM20', 'Bancha Kumamoto Medium-firing Culinary', 'Bancha green tea from Kumamoto, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 830.00, 105, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311984/BANKUM20_up24vj.png', 'TT001', 'PV002', 'RL003', 'GR001'),
('BANKUM21', 'Bancha Kumamoto Medium-firing Ceremonial', 'Premium Bancha green tea from Kumamoto, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1130.00, 35, 'Available', '2024-02-16', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311988/BANKUM21_cf3xxb.png', 'TT001', 'PV002', 'RL003', 'GR002'),
('BANKUM30', 'Bancha Kumamoto High-firing Culinary', 'Bancha green tea from Kumamoto, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 880.00, 90, 'Available', '2024-02-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311985/BANKUM30_d2do27.png', 'TT001', 'PV002', 'RL004', 'GR001'),
('BANKUM31', 'Bancha Kumamoto High-firing Ceremonial', 'Exquisite Bancha green tea from Kumamoto, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1180.00, 18, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311987/BANKUM31_czta5q.png', 'TT001', 'PV002', 'RL004', 'GR002'),
('BANMIE00', 'Bancha Mie Non-firing Culinary', 'Bancha green tea from Mie, Japan. This non-firing variety offers a rich, earthy flavor, perfect for culinary applications. Packaged in 150g bags.', 730.00, 139, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311987/BANMIE00_yb1f4e.png', 'TT001', 'PV003', 'RL001', 'GR001'),
('BANMIE01', 'Bancha Mie Non-firing Ceremonial', 'Premium Bancha green tea from Mie, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, earthy flavor and a smooth finish. Packaged in 150g bags.', 1030.00, 32, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311991/BANMIE01_hfcmea.png', 'TT001', 'PV003', 'RL001', 'GR002'),
('BANMIE10', 'Bancha Mie Low-firing Culinary', 'Bancha green tea from Mie, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 780.00, 88, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311988/BANMIE10_kvzaup.png', 'TT001', 'PV003', 'RL002', 'GR001'),
('BANMIE11', 'Bancha Mie Low-firing Ceremonial', 'High-quality Bancha green tea from Mie, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1080.00, 26, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311989/BANMIE11_uwqvq3.png', 'TT001', 'PV003', 'RL002', 'GR002'),
('BANMIE20', 'Bancha Mie Medium-firing Culinary', 'Bancha green tea from Mie, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 830.00, 112, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311989/BANMIE20_qats7r.png', 'TT001', 'PV003', 'RL003', 'GR001'),
('BANMIE21', 'Bancha Mie Medium-firing Ceremonial', 'Premium Bancha green tea from Mie, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1130.00, 34, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311994/BANMIE21_vcy7bp.png', 'TT001', 'PV003', 'RL003', 'GR002'),
('BANMIE30', 'Bancha Mie High-firing Culinary', 'Bancha green tea from Mie, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 880.00, 94, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311993/BANMIE30_yygdin.png', 'TT001', 'PV003', 'RL004', 'GR001'),
('BANMIE31', 'Bancha Mie High-firing Ceremonial', 'Exquisite Bancha green tea from Mie, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1180.00, 20, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311991/BANMIE31_qqfxmr.png', 'TT001', 'PV003', 'RL004', 'GR002'),
('BANSHI00', 'Bancha Shizuoka Non-firing Culinary', 'Bancha green tea from Shizuoka, Japan. This non-firing variety offers a rich umami flavor, perfect for culinary applications. Packaged in 150g bags.', 730.00, 150, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311992/BANSHI00_qmyukm.png', 'TT001', 'PV004', 'RL001', 'GR001'),
('BANSHI01', 'Bancha Shizuoka Non-firing Ceremonial', 'Premium Bancha green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a smooth finish. Packaged in 150g bags.', 1030.00, 50, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311996/BANSHI01_ropiml.png', 'TT001', 'PV004', 'RL001', 'GR002'),
('BANSHI10', 'Bancha Shizuoka Low-firing Culinary', 'Bancha green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 780.00, 90, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311996/BANSHI10_uis4lp.png', 'TT001', 'PV004', 'RL002', 'GR001'),
('BANSHI11', 'Bancha Shizuoka Low-firing Ceremonial', 'High-quality Bancha green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1080.00, 35, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311998/BANSHI11_uut3uz.png', 'TT001', 'PV004', 'RL002', 'GR002'),
('BANSHI20', 'Bancha Shizuoka Medium-firing Culinary', 'Bancha green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 830.00, 120, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311998/BANSHI20_xouppz.png', 'TT001', 'PV004', 'RL003', 'GR001'),
('BANSHI21', 'Bancha Shizuoka Medium-firing Ceremonial', 'Premium Bancha green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1130.00, 40, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311996/BANSHI21_q93r3w.png', 'TT001', 'PV004', 'RL003', 'GR002'),
('BANSHI30', 'Bancha Shizuoka High-firing Culinary', 'Bancha green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 880.00, 100, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312002/BANSHI30_m2ggo1.png', 'TT001', 'PV004', 'RL004', 'GR001'),
('BANSHI31', 'Bancha Shizuoka High-firing Ceremonial', 'Exquisite Bancha green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1180.00, 25, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312000/BANSHI31_zjtezv.png', 'TT001', 'PV004', 'RL004', 'GR002'),
('BANUJI00', 'Bancha Uji Non-firing Culinary', 'Bancha green tea from Uji, Japan. This non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes.', 730.00, 145, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482323/BANUJI00_n1hioj.png', 'TT001', 'PV005', 'RL001', 'GR001'),
('BANUJI01', 'Bancha Uji Non-firing Ceremonial', 'Premium Bancha green tea from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1030.00, 45, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482323/BANUJI01_j4vorf.png', 'TT001', 'PV005', 'RL001', 'GR002'),
('BANUJI10', 'Bancha Uji Low-firing Culinary', 'Bancha green tea from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, great for culinary applications. Packaged in 150g bags.', 780.00, 85, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482324/BANUJI10_dihnzj.png', 'TT001', 'PV005', 'RL002', 'GR001'),
('BANUJI11', 'Bancha Uji Low-firing Ceremonial', 'High-quality Bancha green tea from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1080.00, 30, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482323/BANUJI11_btzq9z.png', 'TT001', 'PV005', 'RL002', 'GR002'),
('BANUJI20', 'Bancha Uji Medium-firing Culinary', 'Bancha green tea from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, ideal for culinary uses. Packaged in 150g bags.', 830.00, 115, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482323/BANUJI20_qmxouy.png', 'TT001', 'PV005', 'RL003', 'GR001'),
('BANUJI21', 'Bancha Uji Medium-firing Ceremonial', 'Premium Bancha green tea from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1130.00, 38, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482323/BANUJI21_xlraxn.png', 'TT001', 'PV005', 'RL003', 'GR002'),
('BANUJI30', 'Bancha Uji High-firing Culinary', 'Bancha green tea from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 880.00, 95, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482323/BANUJI30_sqlyux.png', 'TT001', 'PV005', 'RL004', 'GR001'),
('BANUJI31', 'Bancha Uji High-firing Ceremonial', 'Exquisite Bancha green tea from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1180.00, 22, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745482324/BANUJI31_rdd422.png', 'TT001', 'PV005', 'RL004', 'GR002'),
('FUKSHI00', 'Fukamushi Sencha Shizuoka Non-firing Culinary', 'Fukamushi Sencha green tea from Shizuoka, Japan. This non-firing variety is deeply steamed, offering a rich, umami flavor perfect for culinary applications. Packaged in 150g bags.', 770.00, 119, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312010/FUKSHI00_pqdimn.png', 'TT002', 'PV004', 'RL001', 'GR001'),
('FUKSHI01', 'Fukamushi Sencha Shizuoka Non-firing Ceremonial', 'Premium Fukamushi Sencha green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a smooth finish. Packaged in 150g bags.', 1070.00, 30, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312009/FUKSHI01_xqzcyi.png', 'TT002', 'PV004', 'RL001', 'GR002'),
('FUKSHI10', 'Fukamushi Sencha Shizuoka Low-firing Culinary', 'Fukamushi Sencha green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 820.00, 85, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312011/FUKSHI10_nxarge.png', 'TT002', 'PV004', 'RL002', 'GR001'),
('FUKSHI11', 'Fukamushi Sencha Shizuoka Low-firing Ceremonial', 'High-quality Fukamushi Sencha green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1120.00, 27, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312012/FUKSHI11_kx1sue.png', 'TT002', 'PV004', 'RL002', 'GR002'),
('FUKSHI20', 'Fukamushi Sencha Shizuoka Medium-firing Culinary', 'Fukamushi Sencha green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 870.00, 108, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312011/FUKSHI20_qmve1o.png', 'TT002', 'PV004', 'RL003', 'GR001'),
('FUKSHI21', 'Fukamushi Sencha Shizuoka Medium-firing Ceremonial', 'Premium Fukamushi Sencha green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1170.00, 35, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312014/FUKSHI21_qiey97.png', 'TT002', 'PV004', 'RL003', 'GR002'),
('FUKSHI30', 'Fukamushi Sencha Shizuoka High-firing Culinary', 'Fukamushi Sencha green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 920.00, 92, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312013/FUKSHI30_l7rtgf.png', 'TT002', 'PV004', 'RL004', 'GR001'),
('FUKSHI31', 'Fukamushi Sencha Shizuoka High-firing Ceremonial', 'Exquisite Fukamushi Sencha green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1220.00, 19, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312018/FUKSHI31_q64pbn.png', 'TT002', 'PV004', 'RL004', 'GR002'),
('GENMIE00', 'Genmaicha Mie Non-firing Culinary', 'Genmaicha green tea from Mie, Japan. This non-firing variety combines green tea with roasted brown rice, offering a rich, nutty flavor perfect for culinary applications. Packaged in 150g bags.', 760.00, 126, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312013/GENMIE00_ek79vo.png', 'TT003', 'PV003', 'RL001', 'GR001'),
('GENMIE01', 'Genmaicha Mie Non-firing Ceremonial', 'Premium Genmaicha green tea from Mie, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, nutty flavor and a smooth finish. Packaged in 150g bags.', 1060.00, 31, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312014/GENMIE01_er1atf.png', 'TT003', 'PV003', 'RL001', 'GR002'),
('GENMIE10', 'Genmaicha Mie Low-firing Culinary', 'Genmaicha green tea from Mie, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 810.00, 86, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312018/GENMIE10_enr17r.png', 'TT003', 'PV003', 'RL002', 'GR001'),
('GENMIE11', 'Genmaicha Mie Low-firing Ceremonial', 'High-quality Genmaicha green tea from Mie, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1110.00, 25, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312019/GENMIE11_fufkjg.png', 'TT003', 'PV003', 'RL002', 'GR002'),
('GENMIE20', 'Genmaicha Mie Medium-firing Culinary', 'Genmaicha green tea from Mie, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 860.00, 112, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312022/GENMIE20_adtvm6.png', 'TT003', 'PV003', 'RL003', 'GR001'),
('GENMIE21', 'Genmaicha Mie Medium-firing Ceremonial', 'Premium Genmaicha green tea from Mie, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1160.00, 34, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312021/GENMIE21_f1nwfy.png', 'TT003', 'PV003', 'RL003', 'GR002'),
('GENMIE30', 'Genmaicha Mie High-firing Culinary', 'Genmaicha green tea from Mie, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 910.00, 93, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312021/GENMIE30_ilqtxk.png', 'TT003', 'PV003', 'RL004', 'GR001'),
('GENMIE31', 'Genmaicha Mie High-firing Ceremonial', 'Exquisite Genmaicha green tea from Mie, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1210.00, 17, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312022/GENMIE31_db9pdt.png', 'TT003', 'PV003', 'RL004', 'GR002'),
('GENSHI00', 'Genmaicha Shizuoka Non-firing Culinary', 'Genmaicha green tea from Shizuoka, Japan. This non-firing variety combines green tea with roasted brown rice, offering a rich, nutty flavor perfect for culinary applications. Packaged in 150g bags.', 760.00, 125, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312022/GENSHI00_yrbyib.png', 'TT003', 'PV004', 'RL001', 'GR001'),
('GENSHI01', 'Genmaicha Shizuoka Non-firing Ceremonial', 'Premium Genmaicha green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, nutty flavor and a smooth finish. Packaged in 150g bags.', 1060.00, 32, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312022/GENSHI01_zofpx7.png', 'TT003', 'PV004', 'RL001', 'GR002'),
('GENSHI10', 'Genmaicha Shizuoka Low-firing Culinary', 'Genmaicha green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 810.00, 88, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312024/GENSHI10_y2sn1w.png', 'TT003', 'PV004', 'RL002', 'GR001'),
('GENSHI11', 'Genmaicha Shizuoka Low-firing Ceremonial', 'High-quality Genmaicha green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1110.00, 28, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312023/GENSHI11_tvkv8o.png', 'TT003', 'PV004', 'RL002', 'GR002'),
('GENSHI20', 'Genmaicha Shizuoka Medium-firing Culinary', 'Genmaicha green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 860.00, 110, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312030/GENSHI20_ckbonh.png', 'TT003', 'PV004', 'RL003', 'GR001'),
('GENSHI21', 'Genmaicha Shizuoka Medium-firing Ceremonial', 'Premium Genmaicha green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1160.00, 37, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312028/GENSHI21_srde0l.png', 'TT003', 'PV004', 'RL003', 'GR002'),
('GENSHI30', 'Genmaicha Shizuoka High-firing Culinary', 'Genmaicha green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 910.00, 94, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312026/GENSHI30_rg3fvd.png', 'TT003', 'PV004', 'RL004', 'GR001'),
('GENSHI31', 'Genmaicha Shizuoka High-firing Ceremonial', 'Exquisite Genmaicha green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1210.00, 20, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312026/GENSHI31_lw6ppa.png', 'TT003', 'PV004', 'RL004', 'GR002'),
('GENUJI00', 'Genmaicha Uji Non-firing Culinary', 'Genmaicha green tea from Uji, Japan. This non-firing variety combines green tea with roasted brown rice, offering a rich, nutty flavor perfect for culinary applications. Packaged in 150g bags.', 760.00, 120, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312025/GENUJI00_vdc0uy.png', 'TT003', 'PV005', 'RL001', 'GR001'),
('GENUJI01', 'Genmaicha Uji Non-firing Ceremonial', 'Premium Genmaicha green tea from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, nutty flavor and a smooth finish. Packaged in 150g bags.', 1060.00, 33, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312027/GENUJI01_mpbovx.png', 'TT003', 'PV005', 'RL001', 'GR002'),
('GENUJI10', 'Genmaicha Uji Low-firing Culinary', 'Genmaicha green tea from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 810.00, 90, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312027/GENUJI10_vnakbs.png', 'TT003', 'PV005', 'RL002', 'GR001'),
('GENUJI11', 'Genmaicha Uji Low-firing Ceremonial', 'High-quality Genmaicha green tea from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1110.00, 26, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312029/GENUJI11_xhdm3j.png', 'TT003', 'PV005', 'RL002', 'GR002'),
('GENUJI20', 'Genmaicha Uji Medium-firing Culinary', 'Genmaicha green tea from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 860.00, 115, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312031/GENUJI20_zl4cbm.png', 'TT003', 'PV005', 'RL003', 'GR001'),
('GENUJI21', 'Genmaicha Uji Medium-firing Ceremonial', 'Premium Genmaicha green tea from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1160.00, 36, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312030/GENUJI21_dwujg0.png', 'TT003', 'PV005', 'RL003', 'GR002'),
('GENUJI30', 'Genmaicha Uji High-firing Culinary', 'Genmaicha green tea from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 910.00, 97, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312029/GENUJI30_jvjzbc.png', 'TT003', 'PV005', 'RL004', 'GR001'),
('GENUJI31', 'Genmaicha Uji High-firing Ceremonial', 'Exquisite Genmaicha green tea from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1210.00, 18, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312034/GENUJI31_jqdf41.png', 'TT003', 'PV005', 'RL004', 'GR002'),
('GYOFUK00', 'Gyokuro Fukuoka Non-firing Culinary', 'Gyokuro green tea from Fukuoka, Japan. This non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes.', 810.00, 134, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312031/GYOFUK00_fytvd3.png', 'TT004', 'PV001', 'RL001', 'GR001'),
('GYOFUK01', 'Gyokuro Fukuoka Non-firing Ceremonial', 'Premium Gyokuro green tea from Fukuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1210.00, 41, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312034/GYOFUK01_ltcpbi.png', 'TT004', 'PV001', 'RL001', 'GR002'),
('GYOFUK10', 'Gyokuro Fukuoka Low-firing Culinary', 'Gyokuro green tea from Fukuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 860.00, 78, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312037/GYOFUK10_uhbnxx.png', 'TT004', 'PV001', 'RL002', 'GR001'),
('GYOFUK11', 'Gyokuro Fukuoka Low-firing Ceremonial', 'High-quality Gyokuro green tea from Fukuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1260.00, 29, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312033/GYOFUK11_c6y8vs.png', 'TT004', 'PV001', 'RL002', 'GR002'),
('GYOFUK20', 'Gyokuro Fukuoka Medium-firing Culinary', 'Gyokuro green tea from Fukuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 910.00, 112, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312033/GYOFUK20_ajdg7v.png', 'TT004', 'PV001', 'RL003', 'GR001'),
('GYOFUK21', 'Gyokuro Fukuoka Medium-firing Ceremonial', 'Premium Gyokuro green tea from Fukuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1310.00, 36, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312038/GYOFUK21_gfsxei.png', 'TT004', 'PV001', 'RL003', 'GR002'),
('GYOFUK30', 'Gyokuro Fukuoka High-firing Culinary', 'Gyokuro green tea from Fukuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 960.00, 95, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312036/GYOFUK30_ci140i.png', 'TT004', 'PV001', 'RL004', 'GR001'),
('GYOFUK31', 'Gyokuro Fukuoka High-firing Ceremonial', 'Exquisite Gyokuro green tea from Fukuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1360.00, 21, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312035/GYOFUK31_olx8lt.png', 'TT004', 'PV001', 'RL004', 'GR002'),
('GYOSHI00', 'Gyokuro Shizuoka Non-firing Culinary', 'Gyokuro green tea from Shizuoka, Japan. This non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 800.00, 132, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312043/GYOSHI00_p8coio.png', 'TT004', 'PV004', 'RL001', 'GR001'),
('GYOSHI01', 'Gyokuro Shizuoka Non-firing Ceremonial', 'Premium Gyokuro green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1200.00, 45, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312041/GYOSHI01_nmvro5.png', 'TT004', 'PV004', 'RL001', 'GR002'),
('GYOSHI10', 'Gyokuro Shizuoka Low-firing Culinary', 'Gyokuro green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 850.00, 78, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312037/GYOSHI10_pqmmd0.png', 'TT004', 'PV004', 'RL002', 'GR001'),
('GYOSHI11', 'Gyokuro Shizuoka Low-firing Ceremonial', 'High-quality Gyokuro green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1250.00, 29, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312039/GYOSHI11_pbpu2d.png', 'TT004', 'PV004', 'RL002', 'GR002'),
('GYOSHI20', 'Gyokuro Shizuoka Medium-firing Culinary', 'Gyokuro green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 900.00, 112, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312039/GYOSHI20_hbpekc.png', 'TT004', 'PV004', 'RL003', 'GR001'),
('GYOSHI21', 'Gyokuro Shizuoka Medium-firing Ceremonial', 'Premium Gyokuro green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1300.00, 36, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312043/GYOSHI21_xq4lon.png', 'TT004', 'PV004', 'RL003', 'GR002'),
('GYOSHI30', 'Gyokuro Shizuoka High-firing Culinary', 'Gyokuro green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 950.00, 95, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312041/GYOSHI30_itsp3h.png', 'TT004', 'PV004', 'RL004', 'GR001'),
('GYOSHI31', 'Gyokuro Shizuoka High-firing Ceremonial', 'Exquisite Gyokuro green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1350.00, 21, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312044/GYOSHI31_uic4oa.png', 'TT004', 'PV004', 'RL004', 'GR002'),
('GYOUJI00', 'Gyokuro Uji Non-firing Culinary', 'Gyokuro green tea from Uji, Japan. Known for its superior quality, this non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes.', 820.00, 143, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312043/GYOUJI00_itk2dn.png', 'TT004', 'PV005', 'RL001', 'GR001'),
('GYOUJI01', 'Gyokuro Uji Non-firing Ceremonial', 'Premium Gyokuro green tea from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1220.00, 47, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312044/GYOUJI01_xa4rua.png', 'TT004', 'PV005', 'RL001', 'GR002'),
('GYOUJI10', 'Gyokuro Uji Low-firing Culinary', 'Gyokuro green tea from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 870.00, 84, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312046/GYOUJI10_jcoxyt.png', 'TT004', 'PV005', 'RL002', 'GR001'),
('GYOUJI11', 'Gyokuro Uji Low-firing Ceremonial', 'High-quality Gyokuro green tea from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1270.00, 31, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312046/GYOUJI11_e1agdg.png', 'TT004', 'PV005', 'RL002', 'GR002'),
('GYOUJI20', 'Gyokuro Uji Medium-firing Culinary', 'Gyokuro green tea from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 920.00, 118, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312048/GYOUJI20_pikhyd.png', 'TT004', 'PV005', 'RL003', 'GR001'),
('GYOUJI21', 'Gyokuro Uji Medium-firing Ceremonial', 'Premium Gyokuro green tea from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1320.00, 39, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312051/GYOUJI21_kpsxjv.png', 'TT004', 'PV005', 'RL003', 'GR002'),
('GYOUJI30', 'Gyokuro Uji High-firing Culinary', 'Gyokuro green tea from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 970.00, 97, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312049/GYOUJI30_twtxdk.png', 'TT004', 'PV005', 'RL004', 'GR001'),
('GYOUJI31', 'Gyokuro Uji High-firing Ceremonial', 'Exquisite Gyokuro green tea from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1370.00, 24, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312047/GYOUJI31_imm5yt.png', 'TT004', 'PV005', 'RL004', 'GR002'),
('HOJFUK00', 'Hojicha Fukuoka Non-firing Culinary', 'Hojicha green tea from Fukuoka, Japan. This non-firing variety offers a rich, toasty flavor, perfect for culinary applications. Packaged in 150g bags.', 750.00, 130, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312049/HOJFUK00_zsksl2.png', 'TT005', 'PV001', 'RL001', 'GR001'),
('HOJFUK01', 'Hojicha Fukuoka Non-firing Ceremonial', 'Premium Hojicha green tea from Fukuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, toasty flavor and a smooth finish. Packaged in 150g bags.', 1050.00, 36, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312049/HOJFUK01_bieh9a.png', 'TT005', 'PV001', 'RL001', 'GR002'),
('HOJFUK10', 'Hojicha Fukuoka Low-firing Culinary', 'Hojicha green tea from Fukuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 800.00, 92, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312051/HOJFUK10_qmpchn.png', 'TT005', 'PV001', 'RL002', 'GR001'),
('HOJFUK11', 'Hojicha Fukuoka Low-firing Ceremonial', 'High-quality Hojicha green tea from Fukuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1100.00, 29, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312052/HOJFUK11_jbhkql.png', 'TT005', 'PV001', 'RL002', 'GR002'),
('HOJFUK20', 'Hojicha Fukuoka Medium-firing Culinary', 'Hojicha green tea from Fukuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 850.00, 108, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312057/HOJFUK20_iuqayb.png', 'TT005', 'PV001', 'RL003', 'GR001'),
('HOJFUK21', 'Hojicha Fukuoka Medium-firing Ceremonial', 'Premium Hojicha green tea from Fukuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1150.00, 39, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312053/HOJFUK21_kgvbge.png', 'TT005', 'PV001', 'RL003', 'GR002'),
('HOJFUK30', 'Hojicha Fukuoka High-firing Culinary', 'Hojicha green tea from Fukuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 900.00, 96, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312058/HOJFUK30_r2cu6l.png', 'TT005', 'PV001', 'RL004', 'GR001'),
('HOJFUK31', 'Hojicha Fukuoka High-firing Ceremonial', 'Exquisite Hojicha green tea from Fukuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1200.00, 21, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312057/HOJFUK31_ezmd1i.png', 'TT005', 'PV001', 'RL004', 'GR002'),
('HOJKUM00', 'Hojicha Kumamoto Non-firing Culinary', 'Hojicha green tea from Kumamoto, Japan. This non-firing variety offers a rich, toasty flavor, perfect for culinary applications. Packaged in 150g bags.', 750.00, 135, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312056/HOJKUM00_wyvcu8.png', 'TT005', 'PV002', 'RL001', 'GR001'),
('HOJKUM01', 'Hojicha Kumamoto Non-firing Ceremonial', 'Premium Hojicha green tea from Kumamoto, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, toasty flavor and a smooth finish. Packaged in 150g bags.', 1050.00, 34, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312055/HOJKUM01_m79w3x.png', 'TT005', 'PV002', 'RL001', 'GR002'),
('HOJKUM10', 'Hojicha Kumamoto Low-firing Culinary', 'Hojicha green tea from Kumamoto, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 800.00, 94, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312057/HOJKUM10_pdrk57.png', 'TT005', 'PV002', 'RL002', 'GR001'),
('HOJKUM11', 'Hojicha Kumamoto Low-firing Ceremonial', 'High-quality Hojicha green tea from Kumamoto, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1100.00, 27, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312060/HOJKUM11_svx5he.png', 'TT005', 'PV002', 'RL002', 'GR002'),
('HOJKUM20', 'Hojicha Kumamoto Medium-firing Culinary', 'Hojicha green tea from Kumamoto, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 850.00, 112, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312061/HOJKUM20_kdqi8a.png', 'TT005', 'PV002', 'RL003', 'GR001'),
('HOJKUM21', 'Hojicha Kumamoto Medium-firing Ceremonial', 'Premium Hojicha green tea from Kumamoto, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1150.00, 38, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312062/HOJKUM21_r1owdg.png', 'TT005', 'PV002', 'RL003', 'GR002'),
('HOJKUM30', 'Hojicha Kumamoto High-firing Culinary', 'Hojicha green tea from Kumamoto, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 900.00, 99, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312063/HOJKUM30_vuhbnc.png', 'TT005', 'PV002', 'RL004', 'GR001'),
('HOJKUM31', 'Hojicha Kumamoto High-firing Ceremonial', 'Exquisite Hojicha green tea from Kumamoto, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1200.00, 19, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312063/HOJKUM31_bh4yij.png', 'TT005', 'PV002', 'RL004', 'GR002'),
('HOJMIE00', 'Hojicha Mie Non-firing Culinary', 'Hojicha green tea from Mie, Japan. This non-firing variety offers a rich, toasty flavor, perfect for culinary applications. Packaged in 150g bags.', 750.00, 125, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312063/HOJMIE00_ch5mra.png', 'TT005', 'PV003', 'RL001', 'GR001'),
('HOJMIE01', 'Hojicha Mie Non-firing Ceremonial', 'Premium Hojicha green tea from Mie, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, toasty flavor and a smooth finish. Packaged in 150g bags.', 1050.00, 33, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312066/HOJSHI01_plsgen.png', 'TT005', 'PV003', 'RL001', 'GR002'),
('HOJMIE10', 'Hojicha Mie Low-firing Culinary', 'Hojicha green tea from Mie, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 800.00, 88, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312067/HOJSHI10_iw22u6.png', 'TT005', 'PV003', 'RL002', 'GR001'),
('HOJMIE11', 'Hojicha Mie Low-firing Ceremonial', 'High-quality Hojicha green tea from Mie, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1100.00, 26, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312066/HOJSHI11_lpgb6m.png', 'TT005', 'PV003', 'RL002', 'GR002'),
('HOJMIE20', 'Hojicha Mie Medium-firing Culinary', 'Hojicha green tea from Mie, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 850.00, 115, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312068/HOJSHI20_kfcoo4.png', 'TT005', 'PV003', 'RL003', 'GR001'),
('HOJMIE21', 'Hojicha Mie Medium-firing Ceremonial', 'Premium Hojicha green tea from Mie, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1150.00, 35, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312068/HOJSHI21_hmv2pm.png', 'TT005', 'PV003', 'RL003', 'GR002'),
('HOJMIE30', 'Hojicha Mie High-firing Culinary', 'Hojicha green tea from Mie, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 900.00, 98, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312068/HOJSHI30_aa51zh.png', 'TT005', 'PV003', 'RL004', 'GR001'),
('HOJMIE31', 'Hojicha Mie High-firing Ceremonial', 'Exquisite Hojicha green tea from Mie, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1200.00, 18, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312071/HOJSHI31_i7eap4.png', 'TT005', 'PV003', 'RL004', 'GR002'),
('HOJSHI00', 'Hojicha Shizuoka Non-firing Culinary', 'Hojicha green tea from Shizuoka, Japan. This non-firing variety offers a rich, toasty flavor, perfect for culinary applications. Packaged in 150g bags.', 750.00, 120, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312071/HOJSHI00_mwbqan.png', 'TT005', 'PV004', 'RL001', 'GR001');
INSERT INTO `product` (`ProductID`, `ProductName`, `Description`, `Price`, `Stock`, `Status`, `update_date`, `Image`, `TeaTypeID`, `ProvinceID`, `RoastLevelID`, `GradeID`) VALUES
('HOJSHI01', 'Hojicha Shizuoka Non-firing Ceremonial', 'Premium Hojicha green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, toasty flavor and a smooth finish. Packaged in 150g bags.', 1050.00, 35, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312074/HOJSHI01_xvcedc.png', 'TT005', 'PV004', 'RL001', 'GR002'),
('HOJSHI10', 'Hojicha Shizuoka Low-firing Culinary', 'Hojicha green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 800.00, 85, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312073/HOJSHI10_bjf55d.png', 'TT005', 'PV004', 'RL002', 'GR001'),
('HOJSHI11', 'Hojicha Shizuoka Low-firing Ceremonial', 'High-quality Hojicha green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1100.00, 30, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312071/HOJSHI11_js5gys.png', 'TT005', 'PV004', 'RL002', 'GR002'),
('HOJSHI20', 'Hojicha Shizuoka Medium-firing Culinary', 'Hojicha green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 850.00, 110, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312071/HOJSHI20_uncfmo.png', 'TT005', 'PV004', 'RL003', 'GR001'),
('HOJSHI21', 'Hojicha Shizuoka Medium-firing Ceremonial', 'Premium Hojicha green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1150.00, 40, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312074/HOJSHI21_shxyj3.png', 'TT005', 'PV004', 'RL003', 'GR002'),
('HOJSHI30', 'Hojicha Shizuoka High-firing Culinary', 'Hojicha green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 900.00, 95, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312074/HOJSHI30_btos0v.png', 'TT005', 'PV004', 'RL004', 'GR001'),
('HOJSHI31', 'Hojicha Shizuoka High-firing Ceremonial', 'Exquisite Hojicha green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1200.00, 22, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312074/HOJSHI31_kxqyak.png', 'TT005', 'PV004', 'RL004', 'GR002'),
('HOJUJI00', 'Hojicha Uji Non-firing Culinary', 'Hojicha green tea from Uji, Japan. This non-firing variety offers a rich, toasty flavor, perfect for culinary applications. Packaged in 150g bags.', 750.00, 130, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312075/HOJUJI00_w6lme0.png', 'TT005', 'PV005', 'RL001', 'GR001'),
('HOJUJI01', 'Hojicha Uji Non-firing Ceremonial', 'Premium Hojicha green tea from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, toasty flavor and a smooth finish. Packaged in 150g bags.', 1050.00, 38, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312078/HOJUJI01_dbv2wi.png', 'TT005', 'PV005', 'RL001', 'GR002'),
('HOJUJI10', 'Hojicha Uji Low-firing Culinary', 'Hojicha green tea from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness, ideal for culinary uses. Packaged in 150g bags.', 800.00, 90, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312080/HOJUJI10_dmxp2s.png', 'TT005', 'PV005', 'RL002', 'GR001'),
('HOJUJI11', 'Hojicha Uji Low-firing Ceremonial', 'High-quality Hojicha green tea from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1100.00, 28, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312077/HOJUJI11_ucjask.png', 'TT005', 'PV005', 'RL002', 'GR002'),
('HOJUJI20', 'Hojicha Uji Medium-firing Culinary', 'Hojicha green tea from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note, perfect for culinary applications. Packaged in 150g bags.', 850.00, 105, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312078/HOJUJI20_uvkclp.png', 'TT005', 'PV005', 'RL003', 'GR001'),
('HOJUJI21', 'Hojicha Uji Medium-firing Ceremonial', 'Premium Hojicha green tea from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, ideal for ceremonial tea drinking. Packaged in 150g bags.', 1150.00, 37, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312078/HOJUJI21_xjugp4.png', 'TT005', 'PV005', 'RL003', 'GR002'),
('HOJUJI30', 'Hojicha Uji High-firing Culinary', 'Hojicha green tea from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 900.00, 100, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312079/HOJUJI30_yc0t5q.png', 'TT005', 'PV005', 'RL004', 'GR001'),
('HOJUJI31', 'Hojicha Uji High-firing Ceremonial', 'Exquisite Hojicha green tea from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1200.00, 20, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312083/HOJUJI31_j9kg1f.png', 'TT005', 'PV005', 'RL004', 'GR002'),
('KABFUK00', 'Kabusecha Fukuoka Non-firing Culinary', 'Kabusecha green tea from Fukuoka, Japan. This non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes.', 730.00, 143, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312081/KABFUK00_lugfxx.png', 'TT006', 'PV001', 'RL001', 'GR001'),
('KABFUK01', 'Kabusecha Fukuoka Non-firing Ceremonial', 'Premium Kabusecha green tea from Fukuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1030.00, 47, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312081/KABFUK01_cs2jvg.png', 'TT006', 'PV001', 'RL001', 'GR002'),
('KABFUK10', 'Kabusecha Fukuoka Low-firing Culinary', 'Kabusecha green tea from Fukuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 780.00, 84, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312084/KABFUK10_vbequd.png', 'TT006', 'PV001', 'RL002', 'GR001'),
('KABFUK11', 'Kabusecha Fukuoka Low-firing Ceremonial', 'High-quality Kabusecha green tea from Fukuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1080.00, 31, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312082/KABFUK11_c56dri.png', 'TT006', 'PV001', 'RL002', 'GR002'),
('KABFUK20', 'Kabusecha Fukuoka Medium-firing Culinary', 'Kabusecha green tea from Fukuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 830.00, 118, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312084/KABFUK20_a45ayz.png', 'TT006', 'PV001', 'RL003', 'GR001'),
('KABFUK21', 'Kabusecha Fukuoka Medium-firing Ceremonial', 'Premium Kabusecha green tea from Fukuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1130.00, 39, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312086/KABFUK21_asgiim.png', 'TT006', 'PV001', 'RL003', 'GR002'),
('KABFUK30', 'Kabusecha Fukuoka High-firing Culinary', 'Kabusecha green tea from Fukuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 880.00, 97, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312085/KABFUK30_bmn8eo.png', 'TT006', 'PV001', 'RL004', 'GR001'),
('KABFUK31', 'Kabusecha Fukuoka High-firing Ceremonial', 'Exquisite Kabusecha green tea from Fukuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1180.00, 24, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312086/KABFUK31_nearwp.png', 'TT006', 'PV001', 'RL004', 'GR002'),
('KABMIE00', 'Kabusecha Mie Non-firing Culinary', 'Kabusecha green tea from Mie, Japan. This non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes.', 710.00, 134, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312089/KABMIE00_bcgls8.png', 'TT006', 'PV003', 'RL001', 'GR001'),
('KABMIE01', 'Kabusecha Mie Non-firing Ceremonial', 'Premium Kabusecha green tea from Mie, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1010.00, 41, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312087/KABMIE01_gmfliq.png', 'TT006', 'PV003', 'RL001', 'GR002'),
('KABMIE10', 'Kabusecha Mie Low-firing Culinary', 'Kabusecha green tea from Mie, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 760.00, 78, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312089/KABMIE10_gdoqlv.png', 'TT006', 'PV003', 'RL002', 'GR001'),
('KABMIE11', 'Kabusecha Mie Low-firing Ceremonial', 'High-quality Kabusecha green tea from Mie, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1060.00, 29, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312089/KABMIE11_woelvb.png', 'TT006', 'PV003', 'RL002', 'GR002'),
('KABMIE20', 'Kabusecha Mie Medium-firing Culinary', 'Kabusecha green tea from Mie, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 810.00, 112, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312091/KABMIE20_oi3y8u.png', 'TT006', 'PV003', 'RL003', 'GR001'),
('KABMIE21', 'Kabusecha Mie Medium-firing Ceremonial', 'Premium Kabusecha green tea from Mie, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1110.00, 36, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312090/KABMIE21_badkkd.png', 'TT006', 'PV003', 'RL003', 'GR002'),
('KABMIE30', 'Kabusecha Mie High-firing Culinary', 'Kabusecha green tea from Mie, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 860.00, 95, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312092/KABMIE30_jheptx.png', 'TT006', 'PV003', 'RL004', 'GR001'),
('KABMIE31', 'Kabusecha Mie High-firing Ceremonial', 'Exquisite Kabusecha green tea from Mie, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1160.00, 21, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312095/KABMIE31_bn5sv8.png', 'TT006', 'PV003', 'RL004', 'GR002'),
('KABSHI00', 'Kabusecha Shizuoka Non-firing Culinary', 'Kabusecha green tea from Shizuoka, Japan. This non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 700.00, 132, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312094/KABSHI00_nzsild.png', 'TT006', 'PV004', 'RL001', 'GR001'),
('KABSHI01', 'Kabusecha Shizuoka Non-firing Ceremonial', 'Premium Kabusecha green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1000.00, 45, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312094/KABSHI01_nvmwwk.png', 'TT006', 'PV004', 'RL001', 'GR002'),
('KABSHI10', 'Kabusecha Shizuoka Low-firing Culinary', 'Kabusecha green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 750.00, 78, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312093/KABSHI10_tjiifr.png', 'TT006', 'PV004', 'RL002', 'GR001'),
('KABSHI11', 'Kabusecha Shizuoka Low-firing Ceremonial', 'High-quality Kabusecha green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1050.00, 29, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312095/KABSHI11_bdcmps.png', 'TT006', 'PV004', 'RL002', 'GR002'),
('KABSHI20', 'Kabusecha Shizuoka Medium-firing Culinary', 'Kabusecha green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 800.00, 112, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312095/KABSHI20_ddvujw.png', 'TT006', 'PV004', 'RL003', 'GR001'),
('KABSHI21', 'Kabusecha Shizuoka Medium-firing Ceremonial', 'Premium Kabusecha green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1100.00, 36, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312096/KABSHI21_gae5jy.png', 'TT006', 'PV004', 'RL003', 'GR002'),
('KABSHI30', 'Kabusecha Shizuoka High-firing Culinary', 'Kabusecha green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 850.00, 95, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312097/KABSHI30_pohcgn.png', 'TT006', 'PV004', 'RL004', 'GR001'),
('KABSHI31', 'Kabusecha Shizuoka High-firing Ceremonial', 'Exquisite Kabusecha green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1150.00, 21, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312100/KABSHI31_btbb2c.png', 'TT006', 'PV004', 'RL004', 'GR002'),
('KABUJI00', 'Kabusecha Uji Non-firing Culinary', 'Kabusecha green tea from Uji, Japan. Known for its superior quality, this non-firing variety is shade-grown, offering a rich, umami flavor with a sweet aftertaste. Packaged in 150g bags, ideal for culinary purposes.', 720.00, 143, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312099/KABUJI00_ccm8u5.png', 'TT006', 'PV005', 'RL001', 'GR001'),
('KABUJI01', 'Kabusecha Uji Non-firing Ceremonial', 'Premium Kabusecha green tea from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a deep, umami-rich flavor and a luxurious aroma. Packaged in 150g bags.', 1020.00, 47, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312100/KABUJI01_lbzaaq.png', 'TT006', 'PV005', 'RL001', 'GR002'),
('KABUJI10', 'Kabusecha Uji Low-firing Culinary', 'Kabusecha green tea from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 770.00, 84, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312101/KABUJI10_sbnbmy.png', 'TT006', 'PV005', 'RL002', 'GR001'),
('KABUJI11', 'Kabusecha Uji Low-firing Ceremonial', 'High-quality Kabusecha green tea from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 1070.00, 31, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312100/KABUJI11_fb0wf1.png', 'TT006', 'PV005', 'RL002', 'GR002'),
('KABUJI20', 'Kabusecha Uji Medium-firing Culinary', 'Kabusecha green tea from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 820.00, 117, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312101/KABUJI20_zpuiil.png', 'TT006', 'PV005', 'RL003', 'GR001'),
('KABUJI21', 'Kabusecha Uji Medium-firing Ceremonial', 'Premium Kabusecha green tea from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1120.00, 39, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312104/KABUJI21_jcifom.png', 'TT006', 'PV005', 'RL003', 'GR002'),
('KABUJI30', 'Kabusecha Uji High-firing Culinary', 'Kabusecha green tea from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 870.00, 97, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312103/KABUJI30_unomse.png', 'TT006', 'PV005', 'RL004', 'GR001'),
('KABUJI31', 'Kabusecha Uji High-firing Ceremonial', 'Exquisite Kabusecha green tea from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1170.00, 24, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312104/KABUJI31_phhv49.png', 'TT006', 'PV005', 'RL004', 'GR002'),
('MATFUK00', 'Matcha Fukuoka Non-firing Culinary', 'Matcha green tea powder from Fukuoka, Japan. This non-firing variety is perfect for culinary use, offering a vibrant green color and a fresh, grassy flavor. Ideal for baking, smoothies, or lattes. Packaged in 150g bags.', 610.00, 143, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312104/MATFUK00_rfcqnd.png', 'TT007', 'PV001', 'RL001', 'GR001'),
('MATFUK01', 'Matcha Fukuoka Non-firing Ceremonial', 'Premium Matcha green tea powder from Fukuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a smooth, umami-rich flavor and a delicate aroma. Packaged in 150g bags.', 910.00, 47, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312104/MATFUK01_fzffmx.png', 'TT007', 'PV001', 'RL001', 'GR002'),
('MATFUK10', 'Matcha Fukuoka Low-firing Culinary', 'Matcha green tea powder from Fukuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 660.00, 84, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312105/MATFUK10_oqudhn.png', 'TT007', 'PV001', 'RL002', 'GR001'),
('MATFUK11', 'Matcha Fukuoka Low-firing Ceremonial', 'High-quality Matcha green tea powder from Fukuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 960.00, 31, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312105/MATFUK11_cia2hj.png', 'TT007', 'PV001', 'RL002', 'GR002'),
('MATFUK20', 'Matcha Fukuoka Medium-firing Culinary', 'Matcha green tea powder from Fukuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 710.00, 118, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312106/MATFUK20_i0kbab.png', 'TT007', 'PV001', 'RL003', 'GR001'),
('MATFUK21', 'Matcha Fukuoka Medium-firing Ceremonial', 'Premium Matcha green tea powder from Fukuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1010.00, 39, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312107/MATFUK21_ar2san.png', 'TT007', 'PV001', 'RL003', 'GR002'),
('MATFUK30', 'Matcha Fukuoka High-firing Culinary', 'Matcha green tea powder from Fukuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 760.00, 97, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312107/MATFUK30_lpg9fh.png', 'TT007', 'PV001', 'RL004', 'GR001'),
('MATFUK31', 'Matcha Fukuoka High-firing Ceremonial', 'Exquisite Matcha green tea powder from Fukuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1110.00, 24, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312110/MATFUK31_bwofj9.png', 'TT007', 'PV001', 'RL004', 'GR002'),
('MATSHI00', 'Matcha Shizuoka Non-firing Culinary', 'Matcha green tea powder from Shizuoka, Japan. This non-firing variety is perfect for culinary use, offering a vibrant green color and a fresh, grassy flavor. Ideal for baking, smoothies, or lattes. Packaged in 150g bags.', 600.00, 145, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312107/MATSHI00_f444nq.png', 'TT007', 'PV004', 'RL001', 'GR001'),
('MATSHI01', 'Matcha Shizuoka Non-firing Ceremonial', 'Premium Matcha green tea powder from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a smooth, umami-rich flavor and a delicate aroma. Packaged in 150g bags.', 900.00, 38, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312110/MATSHI01_tpgpo6.png', 'TT007', 'PV004', 'RL001', 'GR002'),
('MATSHI10', 'Matcha Shizuoka Low-firing Culinary', 'Matcha green tea powder from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 650.00, 92, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312109/MATSHI10_ommior.png', 'TT007', 'PV004', 'RL002', 'GR001'),
('MATSHI11', 'Matcha Shizuoka Low-firing Ceremonial', 'High-quality Matcha green tea powder from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 950.00, 22, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312110/MATSHI11_bstaoi.png', 'TT007', 'PV004', 'RL002', 'GR002'),
('MATSHI20', 'Matcha Shizuoka Medium-firing Culinary', 'Matcha green tea powder from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 700.00, 123, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312111/MATSHI20_mclw6k.png', 'TT007', 'PV004', 'RL003', 'GR001'),
('MATSHI21', 'Matcha Shizuoka Medium-firing Ceremonial', 'Premium Matcha green tea powder from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1000.00, 45, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312110/MATSHI21_sqsc1l.png', 'TT007', 'PV004', 'RL003', 'GR002'),
('MATSHI30', 'Matcha Shizuoka High-firing Culinary', 'Matcha green tea powder from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 750.00, 87, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312112/MATSHI30_hiiveb.png', 'TT007', 'PV004', 'RL004', 'GR001'),
('MATSHI31', 'Matcha Shizuoka High-firing Ceremonial', 'Exquisite Matcha green tea powder from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1100.00, 18, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312115/MATSHI31_taltd5.png', 'TT007', 'PV004', 'RL004', 'GR002'),
('MATUJI00', 'Matcha Uji Non-firing Culinary', 'Matcha green tea powder from Uji, Japan. Known for its superior quality, this non-firing variety offers a vibrant green color and a fresh, grassy flavor. Ideal for baking, smoothies, or lattes. Packaged in 150g bags.', 620.00, 134, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312119/MATUJI00_tqqosz.png', 'TT007', 'PV005', 'RL001', 'GR001'),
('MATUJI01', 'Matcha Uji Non-firing Ceremonial', 'Premium Matcha green tea powder from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a smooth, umami-rich flavor and a delicate aroma. Packaged in 150g bags.', 920.00, 41, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312112/MATUJI01_fqdisu.png', 'TT007', 'PV005', 'RL001', 'GR002'),
('MATUJI10', 'Matcha Uji Low-firing Culinary', 'Matcha green tea powder from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications.', 670.00, 78, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312112/MATUJI10_ycaaby.png', 'TT007', 'PV005', 'RL002', 'GR001'),
('MATUJI11', 'Matcha Uji Low-firing Ceremonial', 'High-quality Matcha green tea powder from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags.', 970.00, 29, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312114/MATUJI11_qyqgmo.png', 'TT007', 'PV005', 'RL002', 'GR002'),
('MATUJI20', 'Matcha Uji Medium-firing Culinary', 'Matcha green tea powder from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses.', 720.00, 112, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312115/MATUJI20_kpb7p9.png', 'TT007', 'PV005', 'RL003', 'GR001'),
('MATUJI21', 'Matcha Uji Medium-firing Ceremonial', 'Premium Matcha green tea powder from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags.', 1020.00, 36, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312116/MATUJI21_ifnczv.png', 'TT007', 'PV005', 'RL003', 'GR002'),
('MATUJI30', 'Matcha Uji High-firing Culinary', 'Matcha green tea powder from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes. Packaged in 150g bags.', 770.00, 95, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312118/MATUJI30_s7dual.png', 'TT007', 'PV005', 'RL004', 'GR001'),
('MATUJI31', 'Matcha Uji High-firing Ceremonial', 'Exquisite Matcha green tea powder from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 1120.00, 21, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312120/MATUJI31_ladn62.png', 'TT007', 'PV005', 'RL004', 'GR002'),
('SENFUK00', 'Sencha Fukuoka Non-firing Culinary', 'Sencha green tea from Fukuoka, Japan. This non-firing variety offers a fresh, grassy flavor with a light, refreshing finish. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 480.00, 167, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311939/SENFUK00_ghz41k.png', 'TT008', 'PV001', 'RL001', 'GR001'),
('SENFUK01', 'Sencha Fukuoka Non-firing Ceremonial', 'Premium Sencha green tea from Fukuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a delicate aroma and a smooth, refined taste. Packaged in 150g bags, perfect for traditional tea ceremonies.', 720.00, 42, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311938/SENFUK01_f6mrsa.png', 'TT008', 'PV001', 'RL001', 'GR002'),
('SENFUK10', 'Sencha Fukuoka Low-firing Culinary', 'Sencha green tea from Fukuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications like marinades or desserts.', 530.00, 89, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311941/SENFUK10_zw01bx.png', 'TT008', 'PV001', 'RL002', 'GR001'),
('SENFUK11', 'Sencha Fukuoka Low-firing Ceremonial', 'High-quality Sencha green tea from Fukuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags, enjoy its elegant and soothing taste.', 780.00, 23, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311944/SENFUK11_x7jezq.png', 'TT008', 'PV001', 'RL002', 'GR002'),
('SENFUK20', 'Sencha Fukuoka Medium-firing Culinary', 'Sencha green tea from Fukuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses such as soups or sauces.', 580.00, 145, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311942/SENFUK20_wq9t1a.png', 'TT008', 'PV001', 'RL003', 'GR001'),
('SENFUK21', 'Sencha Fukuoka Medium-firing Ceremonial', 'Premium Sencha green tea from Fukuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags, a true delight for tea connoisseurs.', 830.00, 37, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311949/SENFUK21_fbxfbm.png', 'TT008', 'PV001', 'RL003', 'GR002'),
('SENFUK30', 'Sencha Fukuoka High-firing Culinary', 'Sencha green tea from Fukuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes like grilled meats or stews. Packaged in 150g bags.', 680.00, 98, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311941/SENFUK30_dy5tq0.png', 'TT008', 'PV001', 'RL004', 'GR001'),
('SENFUK31', 'Sencha Fukuoka High-firing Ceremonial', 'Exquisite Sencha green tea from Fukuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 880.00, 15, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311947/SENFUK31_gmjtrn.png', 'TT008', 'PV001', 'RL004', 'GR002'),
('SENKUM00', 'Sencha Kumamoto Non-firing Culinary', 'Sencha green tea from Kumamoto, Japan. This non-firing variety is perfect for everyday use, offering a fresh, grassy flavor with a light, refreshing finish. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 460.00, 123, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311944/SENKUM00_ohj5i6.png', 'TT008', 'PV002', 'RL001', 'GR001'),
('SENKUM01', 'Sencha Kumamoto Non-firing Ceremonial', 'Premium Sencha green tea from Kumamoto, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a delicate aroma and a smooth, refined taste. Packaged in 150g bags, perfect for traditional tea ceremonies.', 710.00, 56, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311947/SENKUM01_rebphh.png', 'TT008', 'PV002', 'RL001', 'GR002'),
('SENKUM10', 'Sencha Kumamoto Low-firing Culinary', 'Sencha green tea from Kumamoto, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications like marinades or desserts.', 520.00, 78, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311949/SENKUM10_qq8xgz.png', 'TT008', 'PV002', 'RL002', 'GR001'),
('SENKUM11', 'Sencha Kumamoto Low-firing Ceremonial', 'High-quality Sencha green tea from Kumamoto, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags, enjoy its elegant and soothing taste.', 770.00, 32, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311952/SENKUM11_nmp5po.png', 'TT008', 'PV002', 'RL002', 'GR002'),
('SENKUM20', 'Sencha Kumamoto Medium-firing Culinary', 'Sencha green tea from Kumamoto, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses such as soups or sauces.', 570.00, 134, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311950/SENKUM20_spwobs.png', 'TT008', 'PV002', 'RL003', 'GR001'),
('SENKUM21', 'Sencha Kumamoto Medium-firing Ceremonial', 'Premium Sencha green tea from Kumamoto, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags, a true delight for tea connoisseurs.', 820.00, 45, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311950/SENKUM21_b1csjy.png', 'TT008', 'PV002', 'RL003', 'GR002'),
('SENKUM30', 'Sencha Kumamoto High-firing Culinary', 'Sencha green tea from Kumamoto, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes like grilled meats or stews. Packaged in 150g bags.', 670.00, 102, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311952/SENKUM30_omjzyc.png', 'TT008', 'PV002', 'RL004', 'GR001'),
('SENKUM31', 'Sencha Kumamoto High-firing Ceremonial', 'Exquisite Sencha green tea from Kumamoto, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 870.00, 28, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311953/SENKUM31_br2dc6.png', 'TT008', 'PV002', 'RL004', 'GR002'),
('SENMIE00', 'Sencha Mie Non-firing Culinary', 'Sencha green tea from Mie, Japan. This non-firing variety is perfect for everyday use, offering a fresh, grassy flavor with a light, refreshing finish. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 450.00, 167, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311955/SENMIE00_ytdcac.png', 'TT008', 'PV003', 'RL001', 'GR001'),
('SENMIE01', 'Sencha Mie Non-firing Ceremonial', 'Premium Sencha green tea from Mie, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a delicate aroma and a smooth, refined taste. Packaged in 150g bags, perfect for traditional tea ceremonies.', 650.00, 92, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311952/SENMIE01_yhf40c.png', 'TT008', 'PV003', 'RL001', 'GR002'),
('SENMIE10', 'Sencha Mie Low-firing Culinary', 'Sencha green tea from Mie, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications like marinades or desserts.', 500.00, 123, 'Available', '2023-10-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311955/SENMIE10_h4ja8z.png', 'TT008', 'PV003', 'RL002', 'GR001'),
('SENMIE11', 'Sencha Mie Low-firing Ceremonial', 'High-quality Sencha green tea from Mie, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags, enjoy its elegant and soothing taste.', 700.00, 81, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311959/SENMIE11_xahpzn.png', 'TT008', 'PV003', 'RL002', 'GR002'),
('SENMIE20', 'Sencha Mie Medium-firing Culinary', 'Sencha green tea from Mie, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses such as soups or sauces.', 550.00, 105, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311957/SENMIE20_rrw7ka.png', 'TT008', 'PV003', 'RL003', 'GR001'),
('SENMIE21', 'Sencha Mie Medium-firing Ceremonial', 'Premium Sencha green tea from Mie, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags, a true delight for tea connoisseurs.', 750.00, 73, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311955/SENMIE21_rkl0yx.png', 'TT008', 'PV003', 'RL003', 'GR002'),
('SENMIE30', 'Sencha Mie High-firing Culinary', 'Sencha green tea from Mie, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes like grilled meats or stews. Packaged in 150g bags.', 600.00, 134, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311961/SENMIE30_vv1cso.png', 'TT008', 'PV003', 'RL004', 'GR001'),
('SENMIE31', 'Sencha Mie High-firing Ceremonial', 'Exquisite Sencha green tea from Mie, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 800.00, 67, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311962/SENMIE31_m4c24f.png', 'TT008', 'PV003', 'RL004', 'GR002'),
('SENSHI00', 'Sencha Shizuoka Non-firing Culinary', 'Sencha green tea from Shizuoka, Japan. This non-firing variety is perfect for everyday use, offering a fresh, grassy flavor with a light, refreshing finish. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 450.00, 100, 'Available', '2023-09-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311958/SENSHI00_nxalvs.png', 'TT008', 'PV004', 'RL001', 'GR001'),
('SENSHI01', 'Sencha Shizuoka Non-firing Ceremonial', 'Premium Sencha green tea from Shizuoka, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a delicate aroma and a smooth, refined taste. Packaged in 150g bags, perfect for traditional tea ceremonies.', 650.00, 80, 'Available', '2023-11-20', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311962/SENSHI01_aaczwg.png', 'TT008', 'PV004', 'RL001', 'GR002'),
('SENSHI10', 'Sencha Shizuoka Low-firing Culinary', 'Sencha green tea from Shizuoka, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications like marinades or desserts.', 500.00, 120, 'Available', '2023-12-05', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311959/SENSHI10_vsvcaq.png', 'TT008', 'PV004', 'RL002', 'GR001'),
('SENSHI11', 'Sencha Shizuoka Low-firing Ceremonial', 'High-quality Sencha green tea from Shizuoka, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags, enjoy its elegant and soothing taste.', 700.00, 90, 'Available', '2024-01-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311961/SENSHI11_rszxye.png', 'TT008', 'PV004', 'RL002', 'GR002'),
('SENSHI20', 'Sencha Shizuoka Medium-firing Culinary', 'Sencha green tea from Shizuoka, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses such as soups or sauces.', 550.00, 110, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311962/SENSHI20_vxstpr.png', 'TT008', 'PV004', 'RL003', 'GR001'),
('SENSHI21', 'Sencha Shizuoka Medium-firing Ceremonial', 'Premium Sencha green tea from Shizuoka, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags, a true delight for tea connoisseurs.', 750.00, 85, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311963/SENSHI21_wuqov2.png', 'TT008', 'PV004', 'RL003', 'GR002'),
('SENSHI30', 'Sencha Shizuoka High-firing Culinary', 'Sencha green tea from Shizuoka, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes like grilled meats or stews. Packaged in 150g bags.', 600.00, 100, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311966/SENSHI30_c7rsrs.png', 'TT008', 'PV004', 'RL004', 'GR001'),
('SENSHI31', 'Sencha Shizuoka High-firing Ceremonial', 'Exquisite Sencha green tea from Shizuoka, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 800.00, 70, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311965/SENSHI31_uwhrhk.png', 'TT008', 'PV004', 'RL004', 'GR002'),
('SENUJI00', 'Sencha Uji Non-firing Culinary', 'Sencha green tea from Uji, Japan. Known for its superior quality, this non-firing variety offers a fresh, grassy flavor with a light, refreshing finish. Packaged in 150g bags, ideal for culinary purposes like cooking or baking.', 500.00, 132, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311968/SENUJI00_spbujf.png', 'TT008', 'PV005', 'RL001', 'GR001'),
('SENUJI01', 'Sencha Uji Non-firing Ceremonial', 'Premium Sencha green tea from Uji, Japan. This non-firing variety is carefully selected for ceremonial use, featuring a delicate aroma and a smooth, refined taste. Packaged in 150g bags, perfect for traditional tea ceremonies.', 750.00, 87, 'Available', '2023-12-22', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311971/SENUJI01_sw08nw.png', 'TT008', 'PV005', 'RL001', 'GR002'),
('SENUJI10', 'Sencha Uji Low-firing Culinary', 'Sencha green tea from Uji, Japan. Lightly roasted to enhance its flavor, this low-firing variety offers a balanced taste with a hint of sweetness. Packaged in 150g bags, great for culinary applications like marinades or desserts.', 550.00, 145, 'Available', '2023-11-15', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311965/SENUJI10_nzdm9i.png', 'TT008', 'PV005', 'RL002', 'GR001'),
('SENUJI11', 'Sencha Uji Low-firing Ceremonial', 'High-quality Sencha green tea from Uji, Japan. This low-firing variety is lightly roasted to bring out a nuanced flavor profile, making it suitable for ceremonial occasions. Packaged in 150g bags, enjoy its elegant and soothing taste.', 800.00, 76, 'Available', '2024-02-14', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311975/SENUJI11_hn1ftc.png', 'TT008', 'PV005', 'RL002', 'GR002'),
('SENUJI20', 'Sencha Uji Medium-firing Culinary', 'Sencha green tea from Uji, Japan. Medium-roasted to create a richer flavor, this variety has a robust taste with a slightly toasty note. Packaged in 150g bags, ideal for culinary uses such as soups or sauces.', 600.00, 98, 'Available', '2023-10-30', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311971/SENUJI20_omfdqc.png', 'TT008', 'PV005', 'RL003', 'GR001'),
('SENUJI21', 'Sencha Uji Medium-firing Ceremonial', 'Premium Sencha green tea from Uji, Japan. This medium-roasted variety offers a deep, complex flavor with a smooth finish, perfect for ceremonial tea drinking. Packaged in 150g bags, a true delight for tea connoisseurs.', 850.00, 63, 'Available', '2023-12-10', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311968/SENUJI21_loqnfi.png', 'TT008', 'PV005', 'RL003', 'GR002'),
('SENUJI30', 'Sencha Uji High-firing Culinary', 'Sencha green tea from Uji, Japan. Heavily roasted for a bold, smoky flavor, this high-firing variety is excellent for culinary purposes, adding depth to dishes like grilled meats or stews. Packaged in 150g bags.', 700.00, 112, 'Available', '2024-01-07', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311972/SENUJI30_vth36p.png', 'TT008', 'PV005', 'RL004', 'GR001'),
('SENUJI31', 'Sencha Uji High-firing Ceremonial', 'Exquisite Sencha green tea from Uji, Japan. This high-roasted variety boasts a rich, smoky aroma and a full-bodied taste, making it a luxurious choice for ceremonial tea experiences. Packaged in 150g bags.', 900.00, 54, 'Available', '2023-11-28', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745311975/SENUJI31_gw1mo4.png', 'TT008', 'PV005', 'RL004', 'GR002');

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `ProvinceID` varchar(20) NOT NULL,
  `ProvinceName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`ProvinceID`, `ProvinceName`) VALUES
('PV001', 'Fukuoka'),
('PV002', 'Kumamoto'),
('PV003', 'Mie'),
('PV004', 'Shizuoka'),
('PV005', 'Uji');

--
-- Triggers `province`
--
DELIMITER $$
CREATE TRIGGER `before_insert_province` BEFORE INSERT ON `province` FOR EACH ROW BEGIN
   DECLARE new_id INT;
   
   -- ค้นหาค่าตัวเลขสูงสุดจาก ID ที่มีอยู่
   SELECT MAX(CAST(SUBSTRING(ProvinceID, 3) AS UNSIGNED)) INTO new_id FROM Province;
   
   -- กำหนดค่า ProvinceID ที่มี Prefix PV
   SET NEW.ProvinceID = CONCAT('PV', LPAD(new_id + 1, 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` varchar(20) NOT NULL,
  `OrderID` varchar(20) DEFAULT NULL,
  `ReportDate` datetime DEFAULT NULL,
  `ReportTopic` varchar(255) DEFAULT NULL,
  `ReportExplanation` text DEFAULT NULL,
  `ReportImage` varchar(255) DEFAULT NULL,
  `ReportStatus` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`ReportID`, `OrderID`, `ReportDate`, `ReportTopic`, `ReportExplanation`, `ReportImage`, `ReportStatus`) VALUES
('REP0000001', 'AA0000014', '2025-05-12 13:58:35', 'Damaged Percel', 'The parcel I received was quite damaged, and I noticed multiple tears in the product packaging. The item inside might be affected, so I would like to request a replacement.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745313393/REP0000001_s7yqfu.png', 'Accepted'),
('REP0000002', 'AA0000032', '2025-05-12 13:57:28', 'Wrong Item Received', 'I ordered Bancha Mie Low-firing Ceremonial tea, but instead, I received Bancha Uji Low-firing Ceremonial. This is not the product I originally purchased, so I would like to exchange it for the correct one.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312993/REP0000002_vipniw.png', 'Accepted'),
('REP0000003', 'AA0000059', '2025-05-12 13:59:06', 'Not All Item Received', 'I ordered 1 bag of Gyokuro Fukuoka High-firing Culinary tea and 2 bags of Genmaicha Shizuoka Low-firing Ceremonial tea. However, I only received 1 bag of each, missing 1 bag of Genmaicha Shizuoka Low-firing Ceremonial. I would like the missing item to be shipped or a refund for the missing portion.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312704/REP0000003_rogz64.png', 'Accepted');

--
-- Triggers `report`
--
DELIMITER $$
CREATE TRIGGER `before_insert_report` BEFORE INSERT ON `report` FOR EACH ROW BEGIN
   DECLARE new_id INT;

   -- ดึงเลขสูงสุดจาก ReportID แล้ว +1
   SELECT MAX(CAST(SUBSTRING(ReportID, 4) AS UNSIGNED)) INTO new_id FROM Report;

   -- สร้าง ReportID ใหม่โดยใช้ prefix REP และเลข 7 หลัก
   SET NEW.ReportID = CONCAT('REP', LPAD(new_id + 1, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` varchar(20) NOT NULL,
  `CustomerID` varchar(20) NOT NULL,
  `OrderdetailID` varchar(20) NOT NULL,
  `ProductID` varchar(20) NOT NULL,
  `Review_Date` datetime NOT NULL,
  `Comment` text NOT NULL,
  `ReviewImage` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`ReviewID`, `CustomerID`, `OrderdetailID`, `ProductID`, `Review_Date`, `Comment`, `ReviewImage`) VALUES
('RVW0000001', 'CUS0000003', 'AAAA0000001', 'SENSHI20', '2023-10-04 14:30:00', 'This tea powder has such a rich and smooth flavor. Perfect for starting my day!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312586/P1011_nqa0sh.png'),
('RVW0000002', 'CUS0000047', 'AAAA0000002', 'MATSHI31', '2023-10-07 10:45:00', 'I love how easy it is to prepare. Just mix with hot water, and it\'s ready to drink!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312587/P3013_relnk3.png'),
('RVW0000003', 'CUS0000047', 'AAAA0000003', 'KABMIE31', '2023-10-05 18:20:00', 'The aroma is so refreshing. It instantly lifts my mood.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312587/P6023_wrzmca.png'),
('RVW0000004', 'CUS0000012', 'AAAA0000004', 'BANFUK01', '2023-10-06 20:10:00', 'Great for a quick energy boost without the jitters.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312588/P4009_fmgpzv.png'),
('RVW0000005', 'CUS0000029', 'AAAA0000005', 'KABUJI21', '2023-10-07 09:15:00', 'The texture is so fine, and it dissolves perfectly in water.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312589/P4014_xkwvwl.png'),
('RVW0000006', 'CUS0000005', 'AAAA0000006', 'HOJMIE30', '2023-10-08 19:30:00', 'I enjoy the natural taste without any artificial flavors.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312589/P2017_k1fk8z.png'),
('RVW0000007', 'CUS0000038', 'AAAA0000007', 'BANUJI00', '2023-10-05 21:00:00', 'Perfect for making iced tea. Just add some ice and a slice of lemon!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745313398/P5009_iz07eh.png'),
('RVW0000008', 'CUS0000017', 'AAAA0000008', 'HOJUJI10', '2023-10-09 16:50:00', 'The packaging is convenient and keeps the tea powder fresh.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312964/P5023_e1fciw.png'),
('RVW0000009', 'CUS0000017', 'AAAA0000009', 'HOJMIE21', '2023-10-06 11:25:00', 'I love how versatile it is. You can use it for baking or smoothies too!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312587/P2017_1_yaqw2m.png'),
('RVW0000010', 'CUS0000017', 'AAAA0000010', 'HOJKUM00', '2023-10-07 08:30:00', 'The flavor is so balanced?not too bitter, not too sweet.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312590/P4021_v2ierd.png'),
('RVW0000011', 'CUS0000042', 'AAAA0000011', 'KABMIE20', '2023-10-12 17:40:00', 'It?s my go-to drink when I need to relax after a long day.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312591/P7007_yaqdz3.png'),
('RVW0000012', 'CUS0000009', 'AAAA0000012', 'SENUJI10', '2023-10-10 22:00:00', 'The tea powder is so fine; it feels like drinking a cloud!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312591/P4024_pspwxp.png'),
('RVW0000013', 'CUS0000021', 'AAAA0000013', 'GYOUJI20', '2023-10-14 12:15:00', 'I appreciate that it\'s made from high-quality ingredients.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312591/P4019_1_wxofr8.png'),
('RVW0000014', 'CUS0000035', 'AAAA0000014', 'MATFUK00', '2023-10-27 10:30:00', 'It?s a great alternative to coffee. I feel energized but calm.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312587/P2008_a9ftt2.png'),
('RVW0000015', 'CUS0000014', 'AAAA0000015', 'HOJUJI11', '2023-10-25 19:45:00', 'The color of the tea is so vibrant and inviting.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312592/P8007_zx8rxy.png'),
('RVW0000016', 'CUS0000039', 'AAAA0000051', 'BANMIE30', '2023-10-26 15:20:00', 'I love how it doesn\'t leave any residue at the bottom of my cup.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312596/P6012_f5i3ih.png'),
('RVW0000017', 'CUS0000020', 'AAAA0000052', 'MATUJI21', '2023-10-28 08:45:00', 'It?s so convenient to carry around. I can make tea anywhere!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312590/P4021_v2ierd.png'),
('RVW0000018', 'CUS0000044', 'AAAA0000053', 'KABUJI00', '2023-10-24 21:10:00', 'The flavor is so pure and natural. You can tell it?s made with care.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312599/P1007_sle94h.png'),
('RVW0000019', 'CUS0000010', 'AAAA0000054', 'SENKUM10', '2023-10-29 14:50:00', 'I?ve been using this tea powder daily, and I feel so much healthier.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312596/P5026_tzooxp.png'),
('RVW0000020', 'CUS0000034', 'AAAA0000055', 'GENSHI30', '2023-10-30 18:30:00', 'It?s perfect for making a quick matcha latte. Just add some milk!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312710/P6033_jiuh9w.png'),
('RVW0000021', 'CUS0000005', 'AAAA0000056', 'GYOFUK21', '2023-10-31 10:15:00', 'The aroma alone is enough to make me feel relaxed.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312704/P6019_decg2u.png'),
('RVW0000022', 'CUS0000005', 'AAAA0000057', 'BANUJI21', '2023-11-02 20:00:00', 'I love how it?s not overly processed. You can taste the authenticity.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312707/P1035_hs2uqn.png'),
('RVW0000023', 'CUS0000023', 'AAAA0000058', 'MATSHI21', '2023-11-01 12:30:00', 'It?s my secret ingredient for adding depth to my desserts.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312592/P6022_lxukhu.png'),
('RVW0000024', 'CUS0000047', 'AAAA0000059', 'KABMIE10', '2023-11-03 09:45:00', 'The tea powder is so versatile. I use it in both hot and cold drinks.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312705/P4019_urjthl.png'),
('RVW0000025', 'CUS0000012', 'AAAA0000060', 'HOJMIE10', '2023-11-02 16:20:00', 'I appreciate that it?s caffeine-free. Perfect for evening relaxation.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312750/P5014_usrnbx.png'),
('RVW0000026', 'CUS0000029', 'AAAA0000061', 'SENSHI30', '2023-10-05 13:10:00', 'The flavor is so unique?I?ve never tasted anything like it!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745313089/P2006_saurr9.png'),
('RVW0000027', 'CUS0000029', 'AAAA0000062', 'FUKSHI30', '2023-10-08 19:45:00', 'It?s so easy to adjust the strength. Just add more or less powder.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745313394/P3022_o7bxym.png'),
('RVW0000028', 'CUS0000002', 'AAAA0000063', 'GYOFUK01', '2023-10-09 10:20:00', 'I love how it?s packed with antioxidants. Great for my health!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745313395/P1005_nxmnow.png'),
('RVW0000029', 'CUS0000012', 'AAAA0000064', 'BANFUK01', '2023-10-11 15:30:00', 'The tea powder is so fine; it blends perfectly into my smoothies.', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745313398/P5026_1_aymr30.png'),
('RVW0000030', 'CUS0000035', 'AAAA0000065', 'MATFUK00', '2023-10-13 18:40:00', 'It?s become a staple in my pantry. I can?t imagine my day without it!', 'https://res.cloudinary.com/drcyehkac/image/upload/v1745312599/P1007_sle94h.png');

--
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `before_insert_review` BEFORE INSERT ON `review` FOR EACH ROW BEGIN
   DECLARE new_id INT;

   -- ดึงเลขสูงสุดจาก ReviewID แล้ว +1
   SELECT MAX(CAST(SUBSTRING(ReviewID, 4) AS UNSIGNED)) INTO new_id FROM Review;

   -- สร้าง ReviewID ใหม่โดยใช้ prefix RVW และเลข 7 หลัก
   SET NEW.ReviewID = CONCAT('RVW', LPAD(new_id + 1, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `roastlevel`
--

CREATE TABLE `roastlevel` (
  `RoastLevelID` varchar(20) NOT NULL,
  `RoastLevelName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roastlevel`
--

INSERT INTO `roastlevel` (`RoastLevelID`, `RoastLevelName`) VALUES
('RL001', 'Non-firing'),
('RL002', 'Low-firing'),
('RL003', 'Medium-firing'),
('RL004', 'High-firing');

--
-- Triggers `roastlevel`
--
DELIMITER $$
CREATE TRIGGER `before_insert_roastlevel` BEFORE INSERT ON `roastlevel` FOR EACH ROW BEGIN
   DECLARE new_id INT;
   
   -- ค้นหาค่าตัวเลขสูงสุดจาก ID ที่มีอยู่
   SELECT MAX(CAST(SUBSTRING(RoastLevelID, 3) AS UNSIGNED)) INTO new_id FROM RoastLevel;
   
   -- กำหนดค่า RoastLevelID ที่มี Prefix RL
   SET NEW.RoastLevelID = CONCAT('RL', LPAD(new_id + 1, 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `ShipmentID` varchar(10) NOT NULL,
  `OrderID` varchar(9) NOT NULL,
  `Ship_date` datetime DEFAULT NULL,
  `Shipment_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`ShipmentID`, `OrderID`, `Ship_date`, `Shipment_status`) VALUES
('SHI0000001', 'AA0000001', '2024-12-04 08:15:23', 'Delivered'),
('SHI0000002', 'AA0000002', '2024-12-05 18:15:43', 'Delivered'),
('SHI0000003', 'AA0000003', '2024-12-09 16:13:43', 'Delivered'),
('SHI0000004', 'AA0000004', '2024-12-09 16:13:43', 'Delivered'),
('SHI0000005', 'AA0000005', '2024-12-11 08:34:22', 'Delivered'),
('SHI0000006', 'AA0000006', '2024-12-13 12:45:16', 'Delivered'),
('SHI0000007', 'AA0000007', '2024-12-15 10:32:47', 'Delivered'),
('SHI0000008', 'AA0000008', '2024-12-18 15:48:12', 'Delivered'),
('SHI0000009', 'AA0000009', '2024-12-19 14:20:05', 'Delivered'),
('SHI0000010', 'AA0000010', '2024-12-22 09:33:56', 'Delivered'),
('SHI0000011', 'AA0000011', '2024-12-23 11:45:30', 'Delivered'),
('SHI0000012', 'AA0000012', '2024-12-26 07:22:18', 'Delivered'),
('SHI0000013', 'AA0000013', '2024-12-27 16:10:41', 'Delivered'),
('SHI0000014', 'AA0000014', '2024-12-29 11:27:45', 'Delivered'),
('SHI0000015', 'AA0000015', '2025-01-01 14:52:18', 'Delivered'),
('SHI0000016', 'AA0000016', '2025-01-02 15:08:33', 'Delivered'),
('SHI0000017', 'AA0000017', '2025-01-05 08:41:07', 'Delivered'),
('SHI0000018', 'AA0000018', '2025-01-06 12:19:22', 'Delivered'),
('SHI0000019', 'AA0000019', '2025-01-09 06:35:50', 'Delivered'),
('SHI0000020', 'AA0000020', '2025-01-10 17:24:11', 'Delivered'),
('SHI0000021', 'AA0000021', '2025-01-13 09:47:39', 'Delivered'),
('SHI0000022', 'AA0000022', '2025-01-14 14:03:56', 'Delivered'),
('SHI0000023', 'AA0000023', '2025-01-17 03:28:44', 'Delivered'),
('SHI0000024', 'AA0000024', '2025-01-18 14:36:52', 'Delivered'),
('SHI0000025', 'AA0000025', '2025-01-21 12:45:18', 'Delivered'),
('SHI0000026', 'AA0000026', '2025-01-22 09:51:07', 'Delivered'),
('SHI0000027', 'AA0000027', '2025-01-25 05:23:40', 'Delivered'),
('SHI0000028', 'AA0000028', '2025-01-26 16:08:29', 'Delivered'),
('SHI0000029', 'AA0000029', '2025-01-29 11:42:15', 'Delivered'),
('SHI0000030', 'AA0000030', '2025-01-31 07:54:33', 'Delivered'),
('SHI0000031', 'AA0000031', '2025-02-02 13:27:01', 'Delivered'),
('SHI0000032', 'AA0000032', '2025-02-04 08:19:46', 'Delivered'),
('SHI0000033', 'AA0000033', '2025-02-06 19:35:22', 'Delivered'),
('SHI0000034', 'AA0000034', '2025-02-08 10:12:57', 'Delivered'),
('SHI0000035', 'AA0000035', '2025-02-10 22:48:05', 'Delivered'),
('SHI0000036', 'AA0000036', '2025-02-12 04:30:11', 'Delivered'),
('SHI0000037', 'AA0000037', '2025-02-13 12:42:37', 'Delivered'),
('SHI0000038', 'AA0000038', '2025-02-16 03:18:55', 'Delivered'),
('SHI0000039', 'AA0000039', '2025-02-17 09:27:14', 'Delivered'),
('SHI0000040', 'AA0000040', '2025-02-20 06:51:28', 'Delivered'),
('SHI0000041', 'AA0000041', '2025-02-21 14:35:09', 'Delivered'),
('SHI0000042', 'AA0000042', '2025-02-24 10:08:42', 'Delivered'),
('SHI0000043', 'AA0000043', '2025-02-26 07:22:19', 'Delivered'),
('SHI0000044', 'AA0000044', '2025-02-28 16:45:33', 'Delivered'),
('SHI0000045', 'AA0000045', '2025-03-02 15:37:51', 'Delivered'),
('SHI0000046', 'AA0000046', '2025-03-04 11:54:27', 'Delivered'),
('SHI0000047', 'AA0000047', '2025-03-06 18:12:45', 'Delivered'),
('SHI0000048', 'AA0000048', '2025-03-08 09:31:08', 'Delivered'),
('SHI0000049', 'AA0000049', '2025-03-10 20:15:52', 'Delivered'),
('SHI0000050', 'AA0000050', '2025-03-12 14:48:16', 'Delivered'),
('SHI0000051', 'AA0000051', '2025-03-13 11:28:47', 'Delivered'),
('SHI0000052', 'AA0000052', '2025-03-16 14:52:19', 'Delivered'),
('SHI0000053', 'AA0000053', '2025-03-17 15:08:34', 'Delivered'),
('SHI0000054', 'AA0000054', '2025-03-20 08:41:08', 'Delivered'),
('SHI0000055', 'AA0000055', '2025-03-21 12:19:23', 'Delivered'),
('SHI0000056', 'AA0000056', '2025-03-24 06:35:51', 'Delivered'),
('SHI0000057', 'AA0000057', '2025-03-26 07:24:12', 'Delivered'),
('SHI0000058', 'AA0000058', '2025-03-28 13:47:40', 'Delivered'),
('SHI0000059', 'AA0000059', '2025-03-30 14:03:57', 'Delivered'),
('SHI0000060', 'AA0000060', '2025-04-01 13:28:45', 'Delivered'),
('SHI0000061', 'AA0000061', '2025-04-03 09:12:38', 'Delivered'),
('SHI0000062', 'AA0000062', '2025-04-05 17:45:22', 'Delivered'),
('SHI0000063', 'AA0000063', '2025-04-07 10:30:15', 'Delivered'),
('SHI0000064', 'AA0000064', '2025-04-09 21:18:33', 'Delivered'),
('SHI0000065', 'AA0000065', '2025-05-02 15:42:09', 'In Transit'),
('SHI0000066', 'AA0000066', '2025-05-02 15:42:09', 'In Transit'),
('SHI0000067', 'AA0000067', NULL, 'Preparing'),
('SHI0000070', 'AA0000070', NULL, 'Preparing');

--
-- Triggers `shipment`
--
DELIMITER $$
CREATE TRIGGER `before_insert_shipment` BEFORE INSERT ON `shipment` FOR EACH ROW BEGIN
   DECLARE new_id INT;

   -- ดึงเลขสูงสุดจาก ShipmentID แล้ว +1
   SELECT IFNULL(MAX(CAST(SUBSTRING(ShipmentID, 4) AS UNSIGNED)), 0)
   INTO new_id
   FROM Shipment;

   -- สร้าง ShipmentID ใหม่โดยใช้ prefix SHI และเลข 7 หลัก
   SET NEW.ShipmentID = CONCAT('SHI', LPAD(new_id + 1, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tea_type`
--

CREATE TABLE `tea_type` (
  `TeaTypeID` varchar(20) NOT NULL,
  `TeaTypeName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tea_type`
--

INSERT INTO `tea_type` (`TeaTypeID`, `TeaTypeName`) VALUES
('TT001', 'Bancha'),
('TT002', 'Fukamushi Sencha'),
('TT003', 'Genmaicha'),
('TT004', 'Gyokuro'),
('TT005', 'Hojicha'),
('TT006', 'Kabusecha'),
('TT007', 'Matcha'),
('TT008', 'Sencha');

--
-- Triggers `tea_type`
--
DELIMITER $$
CREATE TRIGGER `before_insert_tea_type` BEFORE INSERT ON `tea_type` FOR EACH ROW BEGIN
   DECLARE new_id INT;
   
   -- ค้นหาค่าตัวเลขสูงสุดจาก ID ที่มีอยู่
   SELECT MAX(CAST(SUBSTRING(TeaTypeID, 3) AS UNSIGNED)) INTO new_id FROM Tea_Type;
   
   -- กำหนดค่า TeaTypeID ที่มี Prefix TT
   SET NEW.TeaTypeID = CONCAT('TT', LPAD(new_id + 1, 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(20) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password_hash` varchar(255) NOT NULL,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) NOT NULL,
  `Shipping_Address` varchar(255) NOT NULL,
  `Tel_number` varchar(20) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `latest_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password_hash`, `Firstname`, `Lastname`, `Shipping_Address`, `Tel_number`, `Email`, `latest_update`, `Role`) VALUES
('ADM0000001', 'admin1', 'Pass1234', 'Emily', 'Walker', '', '039-123-4567', 'admin1@example.com', '2025-05-10 08:11:41', 'Admin'),
('ADM0000002', 'admin2', 'Pass5678', 'Michael', 'Scott', '', '040-234-5678', 'admin2@example.com', '2025-05-10 08:12:04', 'Admin'),
('ADM0000003', 'admin3', 'StrongPass', 'Sophia', 'King', '', '041-345-6789', 'admin3@example.com', '2025-05-10 08:12:10', 'Admin'),
('ADM0000004', 'admin4', 'Admin789', 'Daniel', 'Lopez', '', '042-456-7890', 'admin4@example.com', '2025-05-10 08:12:15', 'Admin'),
('ADM0000005', 'admin5', 'SafePass99', 'Olivia', 'Harris', '', '043-567-8901', 'admin5@example.com', '2025-05-10 08:12:19', 'Admin'),
('ADM0000006', 'b', '$2y$10$z53fpOrjuPB0z6d67kkxZOTvej.lyXrAt1mRTRqefBn/es4WxvQZe', 'b', 'b', '', '0987654321', 'b@gmail.com', '2025-05-10 08:54:22', 'Admin'),
('CUS0000001', 'meganhamilton', '2a83010dc99b7197', 'Sara', 'Pittman', '89385 Michael Drive, East Raymondstad, DE 06992', '091-234-5678  ', 'ivalentine@herring.org', '2025-05-11 08:12:42', 'Customer'),
('CUS0000002', 'heathermarshall', '0c7d72798d3d618e', 'James', 'Rhodes', '968 Miranda Row, East Derek, SC 05607', '092-345-6789  ', 'david83@gmail.com', '2025-01-06 07:00:09', 'Customer'),
('CUS0000003', 'gregory08', '12653aa5ac5cacaf', 'Tracey', 'Henderson', '87584 Williams Lights Suite 289, Matthewbury, IA 87558', '093-456-7890  ', 'william69@sutton-mcneil.com', '2025-02-12 20:40:32', 'Customer'),
('CUS0000004', 'amandakelly', 'c8dc0971c21ba0eb', 'Jermaine', 'Smith', '74136 Eric Route Suite 526, Matthewfurt, KY 83409', '094-567-8901  ', 'elijah65@adams-park.com', '2025-03-01 04:49:13', 'Customer'),
('CUS0000005', 'paul35', '147d7568bfc21ba5', 'Robert', 'Phillips', '802 Escobar Branch Suite 027, Jonathanberg, WI 23126', '095-678-9012  ', 'julie16@holland-chambers.com', '2025-02-27 08:10:59', 'Customer'),
('CUS0000006', 'kellywalters', '2ef73f0947596a4cb', 'Alexander', 'Holt', '136 Ford River Apt. 840, New Kristenton, PA 96640', '096-789-0123  ', 'randyjohnson@yahoo.com', '2025-03-04 14:40:41', 'Customer'),
('CUS0000007', 'brownhannah', '7463d96a2f44dda2', 'Joshua', 'Newman', '183 Dunlap Fork Apt. 253, Hudsonton, LA 49892', '097-890-1234  ', 'diana96@yahoo.com', '2025-02-04 01:03:56', 'Customer'),
('CUS0000008', 'thomas', '704c69d882aea288', 'Leah', 'Ray', '3965 Meyers Fork, Jessicaside, MD 86283', '098-901-2345  ', 'plong@hotmail.com', '2025-05-11 07:07:20', 'Customer'),
('CUS0000009', 'alanthomas', '2dff912f8e6917889', 'Michael', 'Smith', '58327 Stephanie Passage, Wendyview, IN 35080', '099-012-3456  ', 'davismelanie@gmail.com', '2025-02-19 00:09:35', 'Customer'),
('CUS0000010', 'schneiderryan', '4693c677749cf342', 'Justin', 'Hunter', '078 Shawn Mount, Port Karen, DC 96720', '090-123-4567  ', 'ksmith@gmail.com', '2025-01-23 17:58:47', 'Customer'),
('CUS0000011', 'joehess', 'd783fa30e6fc19e89', 'Rebecca', 'Lowe', '20849 Simpson Mews Apt. 807, Priceburgh, MN 74788', '081-234-5678  ', 'mary76@yahoo.com', '2025-02-01 10:17:08', 'Customer'),
('CUS0000012', 'browningjessica', '26f1cdbd061549b7', 'Francisco', 'Greene', '3333 Lee Underpass, Clarkview, WI 10110', '082-345-6789  ', 'jennifererickson@yahoo.com', '2025-01-27 05:21:04', 'Customer'),
('CUS0000013', 'caitlin62', 'b2e6d36be2800214', 'Mark', 'Perez', '8300 John Meadows, West Christopher, HI 65174', '083-456-7890  ', 'debra12@vance-drake.com', '2025-02-20 06:49:05', 'Customer'),
('CUS0000014', 'johnsonmary', 'd9a3758ae2895f25', 'Daniel', 'Valdez', '1882 Matthew Burg, Porterhaven, LA 03348', '084-567-8901  ', 'christopherharper@fischer.com', '2025-01-05 14:38:32', 'Customer'),
('CUS0000015', 'christianrichard', '7f4df7e4049d04f98', 'Ashley', 'Guerra', '3998 Brown Center, Lake Samantha, TN 62098', '085-678-9012  ', 'anthonyblack@burns.biz', '2025-03-02 00:33:44', 'Customer'),
('CUS0000016', 'spencerronald', 'bc27c9c39dfadf294', 'Christopher', 'Owens', '980 Pham Island Suite 296, Port Adam, UT 02141', '086-789-0123  ', 'lindseydurham@henson.org', '2025-01-15 11:44:05', 'Customer'),
('CUS0000017', 'rpotts', '8faf30afa90270fe9', 'Chad', 'Burns', '43214 Rebecca Ville Apt. 423, Warrenburgh, AL 88519', '087-890-1234  ', 'ejones@hotmail.com', '2025-02-12 21:03:49', 'Customer'),
('CUS0000018', 'jonescatherine', '9c28b77f7a859e7d', 'Jaclyn', 'Frazier', '9222 Dakota Pine Suite 569, Lake Lesliebury, NM 64796', '088-901-2345  ', 'rogerbell@yahoo.com', '2025-01-13 16:10:32', 'Customer'),
('CUS0000019', 'walkersandra', '8f81c31289add891', 'Catherine', 'Riley', '3519 Stewart Pine, Austinhaven, TX 83324', '089-012-3456  ', 'bryantnathaniel@gmail.com', '2025-02-16 15:56:19', 'Customer'),
('CUS0000020', 'michael27', '21c60a59d54ad2a', 'Gerald', 'Mathews', '48262 Kara Ferry, Port Natalie, RI 70180', '080-123-4567  ', 'jenkinsdean@shah.com', '2025-02-28 21:00:43', 'Customer'),
('CUS0000021', 'pperkins', '48262b8a0654018', 'Kevin', 'Schmidt', '10574 Stephanie Divide Suite 823, Taylorton, NY 63853', '061-234-5678  ', 'qcannon@nguyen.biz', '2025-02-25 04:03:03', 'Customer'),
('CUS0000022', 'wrightkrystal', '15d215520bc4a25', 'Jesus', 'Zimmerman', '951 Jacob Harbors Suite 449, Danielberg, RI 51818', '062-345-6789  ', 'psimpson@shelton.biz', '2025-03-07 19:56:31', 'Customer'),
('CUS0000023', 'donaldcampos', 'c913cd0dd4c6400', 'Amy', 'Li', '8921 Escobar Meadows Suite 928, Patrickville, DC 69683', '063-456-7890  ', 'jphillips@sanchez-randolph.org', '2025-01-04 07:10:25', 'Customer'),
('CUS0000024', 'mgamble', '520179d404d597e', 'Sandra', 'Jackson', '0022 Wilkins Parkways Apt. 607, North Hunter, TX 73370', '064-567-8901  ', 'andersonkelli@moore.com', '2025-01-24 19:35:33', 'Customer'),
('CUS0000025', 'careyyolanda', 'bf21e3d1bdec5655', 'Derek', 'James', '5668 Claudia Isle Apt. 263, Port Sean, MA 75363', '065-678-9012  ', 'patrickcheryl@yahoo.com', '2025-01-27 01:31:15', 'Customer'),
('CUS0000026', 'simmonsbrittany', '484ef53b1ba7057', 'Daniel', 'Pham', '8100 Pam Forks Suite 834, Tylerhaven, SC 29113', '066-789-0123  ', 'kevinwilliams@parker.com', '2025-01-07 03:47:10', 'Customer'),
('CUS0000027', 'gabrielpena', '7c2a1aa48d73790', 'Rebecca', 'Smith', 'PSC 2021, Box 7850, APO AP 62382', '067-890-1234  ', 'laura49@gmail.com', '2025-01-11 01:56:30', 'Customer'),
('CUS0000028', 'jonathan85', '5b99fde79a52e70e', 'Maria', 'Boyd', '545 Miller Parks, Heatherbury, GA 29727', '068-901-2345  ', 'jocelynroberson@shah.com', '2025-01-24 17:37:07', 'Customer'),
('CUS0000029', 'ayalanicholas', '7c649ba6cec71c67', 'Kelly', 'Kim', '54573 Arroyo Mountains Suite 863, Whitakermouth, SC 34487', '069-012-3456  ', 'victoria27@baird.com', '2025-01-06 09:08:56', 'Customer'),
('CUS0000030', 'donald79', '01c0699c61662fd', 'Jason', 'Estrada', '18600 Smith Bypass Suite 108, Port Samantha, CT 72064', '060-123-4567  ', 'wallerjack@wright-carpenter.com', '2025-02-09 12:56:23', 'Customer'),
('CUS0000031', 'madelinemccoy', '78ce806aec1d409', 'Michael', 'Snyder', '81465 Amber Ville Apt. 762, New Rebekah, KY 70062', '071-234-5678  ', 'elizabethburton@hotmail.com', '2025-02-27 10:02:06', 'Customer'),
('CUS0000032', 'patelduane', '11df5558c4359f94', 'Ashley', 'Burton', '31033 Sandra Way, Thomasborough, DE 21731', '072-345-6789  ', 'leahperez@nunez-sanchez.org', '2025-03-02 02:35:27', 'Customer'),
('CUS0000033', 'deborah88', '8d1241dd219ee2e', 'Cindy', 'Williams', '1778 Robbins Place, Port Randyshire, RI 15836', '073-456-7890  ', 'daniel61@gmail.com', '2025-01-14 12:24:23', 'Customer'),
('CUS0000034', 'robinbrock', '32fd695bbddce06a', 'Sarah', 'Thomas', 'Unit 4768 Box 1445, DPO AE 61279', '074-567-8901  ', 'michaelsutton@adkins-hale.com', '2025-02-26 23:17:59', 'Customer'),
('CUS0000035', 'gellis', 'ca176fab63812e14', 'Kara', 'Barajas', '01693 Linda Camp, New Laura, GA 22609', '075-678-9012  ', 'michaelleblanc@gmail.com', '2025-01-14 18:48:39', 'Customer'),
('CUS0000036', 'hensleybrandon', '7156b18cb00006e', 'Brandon', 'Stone', '395 Nichols Camp Suite 606, West Karen, MA 73157', '076-789-0123  ', 'rebeccaphillips@hotmail.com', '2025-02-08 11:12:37', 'Customer'),
('CUS0000037', 'jfarrell', '5957970dc302774', 'Randy', 'Solomon', '661 Dylan Meadow Suite 215, Hancockchester, MN 29707', '077-890-1234  ', 'shannonruiz@hotmail.com', '2025-02-20 02:24:08', 'Customer'),
('CUS0000038', 'vbrown', '8c54a0c7dfecd3ed', 'Joseph', 'Boyd', '357 Obrien Bypass, Joanfurt, KY 04882', '078-901-2345  ', 'hernandezstephanie@yahoo.com', '2025-02-02 00:58:30', 'Customer'),
('CUS0000039', 'cynthialopez', 'ee27dcea0adb258', 'Regina', 'Lawrence', '7539 Taylor Hollow, North Benjamin, SD 03437', '079-012-3456  ', 'rthomas@miller.net', '2025-01-23 10:00:40', 'Customer'),
('CUS0000040', 'jameschambers', 'ae1737e3335208f', 'Alan', 'Brooks', 'Unit 7171 Box 6846, DPO AA 50666', '070-123-4567  ', 'fhouston@taylor.com', '2025-03-10 17:45:37', 'Customer'),
('CUS0000041', 'andreadominguez', 'b4d02fdae2079a9', 'Barbara', 'Hicks', '72286 Morgan Centers Suite 028, Patriciaville, OR 18766', '031-234-5678  ', 'matthew23@torres.com', '2025-01-18 10:37:02', 'Customer'),
('CUS0000042', 'frederickfernandez', '919e7bb462477b', 'Amy', 'Jackson', '5971 Taylor Shore Apt. 178, Brockfurt, TN 10444', '032-345-6789  ', 'elizabeth47@clay.info', '2025-01-28 10:32:41', 'Customer'),
('CUS0000043', 'perezjames', 'ead666d2bd61e18', 'Donald', 'Lowe', '11752 Christopher Vista, Josephberg, AR 94088', '033-456-7890  ', 'michellevance@smith-riley.com', '2025-03-02 03:49:57', 'Customer'),
('CUS0000044', 'courtney97', '3baf16c283a5e57', 'James', 'Edwards', 'USNV Ware, FPO AP 47448', '034-567-8901  ', 'yanggregory@carroll.com', '2025-01-08 02:33:27', 'Customer'),
('CUS0000045', 'ijimenez', '2e1d04f6f4920e16', 'Adam', 'Parker', '54721 Johnson Manors, Carneyville, NE 21723', '035-678-9012  ', 'rogersamanda@white-craig.net', '2025-01-07 22:38:35', 'Customer'),
('CUS0000046', 'michaelherrera', '36208361fdaefc73', 'Sarah', 'Cowan', 'Unit 6140 Box 5993, DPO AA 55158', '036-789-0123  ', 'ileonard@gmail.com', '2025-02-24 12:02:39', 'Customer'),
('CUS0000047', 'josephatkins', 'a91342a27aa4bf6', 'Chelsea', 'Johnson', '7968 Howell Burgs, Lake Jason, WA 55846', '037-890-1234  ', 'wgarner@ramirez.com', '2025-01-18 00:03:25', 'Customer'),
('CUS0000048', 'erikamendez', 'afefd9e3651b7c54', 'Crystal', 'Thomas', '17694 Gary Coves Apt. 171, Lake Bradley, OR 20331', '038-901-2345  ', 'nathangonzalez@yahoo.com', '2025-03-10 01:16:58', 'Customer'),
('CUS0000049', 'spatton', 'd91be2ad32b5416', 'Robert', 'Thomas', 'PSC 9093, Box 8170, APO AA 20104', '039-012-3456  ', 'sarahriggs@yahoo.com', '2025-01-18 11:37:49', 'Customer'),
('CUS0000050', 'christophermiles', '58d287a35fac13e9', 'James', 'Friedman', '54980 Jacob Rapids, East Joshua, MO 64540', '030-123-4567  ', 'joseph69@thompson.biz', '2025-02-20 04:03:13', 'Customer'),
('CUS0000051', 'a', '$2y$10$uOTbHPh6UNsMDXFOSHh8Oubh34tT51g3kgstkdhjz2qz7qbdbZekC', 'a', 'a', 'a', '0123456789', 'a@gmail.com', '2025-05-07 13:41:06', 'Customer');

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `before_insert_user` BEFORE INSERT ON `user` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    IF NEW.Role = 'Customer' THEN
        SELECT IFNULL(MAX(CAST(SUBSTRING(UserID, 4) AS UNSIGNED)), 0)
        INTO new_id FROM User WHERE UserID LIKE 'CUS%';
        SET NEW.UserID = CONCAT('CUS', LPAD(new_id + 1, 7, '0'));

    ELSE
        SELECT IFNULL(MAX(CAST(SUBSTRING(UserID, 4) AS UNSIGNED)), 0)
        INTO new_id FROM User WHERE UserID LIKE 'ADM%';
        SET NEW.UserID = CONCAT('ADM', LPAD(new_id + 1, 7, '0'));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `create_cart_after_user_insert` AFTER INSERT ON `user` FOR EACH ROW BEGIN
    IF NEW.role = 'Customer' THEN
        INSERT INTO cart (CustomerID)
        VALUES (NEW.UserID);
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `cart_customer` (`CustomerID`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`Cart_itemID`),
  ADD UNIQUE KEY `CartID_2` (`CartID`,`ProductID`),
  ADD KEY `CartID` (`CartID`) USING BTREE,
  ADD KEY `ProductID` (`ProductID`) USING BTREE;

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`GradeID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `OD_CusID` (`CustomerID`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`OrderdetailID`),
  ADD KEY `ODD_orderID` (`OrderID`),
  ADD KEY `ODD_PDID` (`ProductID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD UNIQUE KEY `OrderID` (`OrderID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `product_teatype` (`TeaTypeID`),
  ADD KEY `product_province` (`ProvinceID`),
  ADD KEY `product_roastlevel` (`RoastLevelID`),
  ADD KEY `product_grade` (`GradeID`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`ProvinceID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `report_order` (`OrderID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `review_customer` (`CustomerID`),
  ADD KEY `review_product` (`ProductID`),
  ADD KEY `review_orderdetail` (`OrderdetailID`);

--
-- Indexes for table `roastlevel`
--
ALTER TABLE `roastlevel`
  ADD PRIMARY KEY (`RoastLevelID`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`ShipmentID`),
  ADD UNIQUE KEY `OrderID` (`OrderID`);

--
-- Indexes for table `tea_type`
--
ALTER TABLE `tea_type`
  ADD PRIMARY KEY (`TeaTypeID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_customer` FOREIGN KEY (`CustomerID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_cart` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`),
  ADD CONSTRAINT `cart_item_product` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `OD_CusID` FOREIGN KEY (`CustomerID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `ODD_PDID` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `ODD_orderID` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_grade` FOREIGN KEY (`GradeID`) REFERENCES `grade` (`GradeID`),
  ADD CONSTRAINT `product_province` FOREIGN KEY (`ProvinceID`) REFERENCES `province` (`ProvinceID`),
  ADD CONSTRAINT `product_roastlevel` FOREIGN KEY (`RoastLevelID`) REFERENCES `roastlevel` (`RoastLevelID`),
  ADD CONSTRAINT `product_teatype` FOREIGN KEY (`TeaTypeID`) REFERENCES `tea_type` (`TeaTypeID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_order` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
