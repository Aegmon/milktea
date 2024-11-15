-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 06:27 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `attributes` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `Category` text COLLATE utf8_spanish_ci NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `Category`, `Date`) VALUES
(16, 'REGULAR', '2024-10-31 05:28:59'),
(17, 'PREMIUM', '2024-10-31 06:00:24'),
(18, 'YAKULT BLEND', '2024-10-31 06:00:40'),
(19, 'BUTTERFLYPEA TEA DRINKS', '2024-10-31 06:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_spanish_ci NOT NULL,
  `idDocument` int(11) NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `phone` text COLLATE utf8_spanish_ci NOT NULL,
  `address` text COLLATE utf8_spanish_ci NOT NULL,
  `birthdate` date NOT NULL,
  `purchases` int(11) NOT NULL,
  `lastPurchase` datetime NOT NULL,
  `registerDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `idDocument`, `email`, `phone`, `address`, `birthdate`, `purchases`, `lastPurchase`, `registerDate`) VALUES
(1, 'David Cullison', 123456, 'davidc@mail.com', '(555)567-9999', '27 Joseph Street', '1986-01-05', 48, '2024-10-14 07:01:24', '2024-10-14 12:01:24'),
(12, 'Juan Delacruz', 1, 'test@email.com', '(092) 132-3232', 'test', '1995-02-19', 0, '0000-00-00 00:00:00', '2024-10-14 08:35:13'),
(13, 'Juan Delacruz', 2, 'test2@email.com', '(092) 132-1312', 'test', '2010-07-09', 0, '0000-00-00 00:00:00', '2024-10-14 08:35:43');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `ingredient` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` enum('grams','kilograms','liters','mililiters') NOT NULL,
  `addons_price` int(11) NOT NULL,
  `addons_measurement` int(11) NOT NULL,
  `stockalert` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `ingredient`, `quantity`, `size`, `addons_price`, `addons_measurement`, `stockalert`) VALUES
(17, 'MILK', -27, 'mililiters', 10, 5, 145),
(18, 'SYRUP', -101, 'mililiters', 10, 0, 145),
(19, 'YAKULT', 30, 'mililiters', 10, 0, 145),
(20, 'CREAM PUFF', 125, 'grams', 10, 0, 145),
(23, 'CLASSIC MILK TEA POWDER', -48, 'grams', 10, 0, 145),
(24, 'WINTERMELON POWDER', 34, 'grams', 10, 0, 145),
(25, 'DARK CHOCOLATE POWDER', -630, 'grams', 10, 0, 145),
(26, 'TARO POWDER', -450, 'grams', 10, 0, 145),
(27, 'COOKIES AND CREAM POWDER', 9, 'grams', 10, 0, 0),
(28, 'SALTED CARAMEL POWDER', 100, 'grams', 10, 0, 0),
(29, 'OKINAWA POWDER', 206, 'grams', 10, 0, 0),
(30, 'HOKKAIDO POWDER', 65, 'grams', 10, 0, 0),
(31, 'DARK WINTERMELON POWDER', 59, 'grams', 10, 0, 0),
(32, 'CHOCO HAZEL NUTS POWDER', 332, 'grams', 10, 0, 0),
(33, 'CARAMEL MACHIATO POWDER', 35, 'grams', 10, 0, 0),
(34, 'MATCHA POWDER', 111, 'grams', 10, 0, 0),
(35, 'MATCHA OREO POWDER', 131, 'grams', 10, 0, 0),
(37, 'CHOCOLATE POWDER', 115, 'grams', 10, 0, 0),
(38, 'PEARL', 60, 'grams', 10, 2, 35),
(39, 'GROSS JELLY', 50, 'grams', 10, 2, 35),
(40, 'MANGO BOBA', 70, 'grams', 10, 2, 35),
(41, 'STRAWBERRY BOBA', 78, 'grams', 10, 2, 50),
(42, 'OREO', 35, 'grams', 10, 2, 100),
(43, 'CHEESEMILK FOAM', 49, 'grams', 20, 5, 39),
(44, 'FRUITY NATA', 78, 'grams', 10, 4, 99),
(45, 'LECHE NATA', 48, 'grams', 10, 3, 50),
(46, 'YOGURT NATA', 39, 'grams', 10, 3, 50),
(47, 'CHIA SEEDS', 50, 'grams', 10, 2, 100);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `idCategory` int(11) NOT NULL,
  `code` text COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `image` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `size` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `buyingPrice` float NOT NULL,
  `sellingPrice` float NOT NULL,
  `sales` int(11) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `idCategory`, `code`, `description`, `image`, `stock`, `size`, `buyingPrice`, `sellingPrice`, `sales`, `date`) VALUES
(54, 16, '', 'CLASSIC MILKTEA ', 'views/img/products/default/anonymous.png', -3, 'Regular', 0, 69, 3, '2024-11-13 09:31:29'),
(56, 16, '', 'WINTERMELON', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 06:34:48'),
(57, 16, '', 'CHOCOLATE', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 06:37:23'),
(58, 16, '', 'DARK CHOCOLATE', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 06:38:42'),
(59, 16, '', 'COOKIES AND CREAM', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 06:40:23'),
(60, 16, '', 'TARO', 'views/img/products/default/anonymous.png', -1, 'Regular', 0, 69, 1, '2024-11-08 12:29:52'),
(61, 16, '', 'SALTED CARAMEL', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 06:42:48'),
(62, 16, '', 'CHOCOLATE', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 06:45:14'),
(63, 16, '', 'CLASSIC MILKTEA ', 'views/img/products/default/anonymous.png', -3, 'Large', 0, 79, 3, '2024-11-13 09:32:04'),
(64, 16, '', 'COOKIES AND CREAM', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 06:50:12'),
(65, 16, '', 'DARK CHOCOLATE', 'views/img/products/default/anonymous.png', -6, 'Large', 0, 79, 6, '2024-11-13 09:28:23'),
(66, 16, '', 'SALTED CARAMEL', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 06:52:47'),
(67, 16, '', 'TARO', 'views/img/products/default/anonymous.png', -5, 'Large', 0, 79, 5, '2024-11-13 09:30:30'),
(68, 16, '', 'WINTERMELON', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 06:57:16'),
(69, 17, '', 'OKINAWA', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 07:03:59'),
(70, 17, '', 'OKINAWA', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 07:05:46'),
(71, 17, '', 'HOKKAIDO', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 79, 0, '2024-10-31 07:09:30'),
(72, 17, '', 'HOKKAIDO', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 89, 0, '2024-10-31 07:13:39'),
(73, 17, '', 'DARK WINTERMELON', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 79, 0, '2024-10-31 07:15:23'),
(74, 17, '', 'DARK WINTERMELON', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 89, 0, '2024-10-31 07:17:10'),
(75, 17, '', 'CHOCO HAZEL NUTS', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 79, 0, '2024-10-31 07:19:01'),
(76, 17, '', 'CHOCO HAZEL NUTS', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 89, 0, '2024-10-31 07:20:21'),
(77, 17, '', 'CARAMEL MACHIATO', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 79, 0, '2024-10-31 07:22:07'),
(78, 17, '', 'CARAMEL MACHIATO', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 89, 0, '2024-10-31 07:23:31'),
(79, 17, '', 'MATCHA', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 79, 0, '2024-10-31 07:25:09'),
(80, 17, '', 'MATCHA', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 89, 0, '2024-10-31 07:26:51'),
(81, 17, '', 'MATCHA OREO', 'views/img/products/default/anonymous.png', -1, 'Regular', 0, 79, 1, '2024-11-11 19:14:41'),
(82, 17, '', 'MATCHA OREO', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 89, 0, '2024-10-31 07:29:46'),
(83, 18, '', 'LYCHEE NATA', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 07:34:47'),
(84, 18, '', 'GREEN APPLE', 'views/img/products/default/anonymous.png', -1, 'Large', 0, 79, 1, '2024-11-11 13:25:24'),
(85, 18, '', 'PEACH', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 07:39:00'),
(86, 18, '', 'KIWI', 'views/img/products/default/anonymous.png', 0, 'Large', 0, 79, 0, '2024-10-31 07:40:12'),
(87, 18, '', 'STRAWBERRY', 'views/img/products/default/anonymous.png', -1, 'Large', 0, 79, 1, '2024-11-08 12:22:33'),
(88, 18, '', 'BLUEBERRY YAKULT', 'views/img/products/default/anonymous.png', -2, 'Large', 0, 79, 2, '2024-11-11 14:09:36'),
(89, 18, '', 'LYCHEE NATA', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 07:47:56'),
(91, 18, '', 'GREEN APPLE', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 07:49:05'),
(92, 18, '', 'PEACH', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 07:50:10'),
(93, 18, '', 'KIWI', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 69, 0, '2024-10-31 07:51:00'),
(94, 18, '', 'STRAWBERRY', 'views/img/products/default/anonymous.png', -1, 'Regular', 0, 69, 1, '2024-11-07 11:36:38'),
(95, 18, '', 'BLUEBERRY YAKULT', 'views/img/products/default/anonymous.png', -1, 'Regular', 0, 69, 1, '2024-11-11 19:14:41'),
(98, 19, '', 'LYCHEE', 'views/img/products/default/anonymous.png', 0, 'Regular', 0, 49, 0, '2024-10-31 08:06:47'),
(102, 19, '', 'LYCHEE', 'views/img/products/default/anonymous.png', -4, 'Large', 0, 59, 4, '2024-11-11 19:14:41'),
(104, 19, '', 'SUNSET FRIZZ', 'views/img/products/default/anonymous.png', -2, 'Regular', 0, 69, 3, '2024-11-11 13:25:24'),
(105, 19, '', 'KIWI LYCHEE', 'views/img/products/default/anonymous.png', -8, 'Regular', 0, 69, 9, '2024-11-13 12:25:58'),
(107, 19, '', 'SUNSET FRIZZ', 'views/img/products/default/anonymous.png', -3, 'Large', 0, 79, 6, '2024-11-11 19:14:41'),
(108, 19, '', 'KIWI LYCHEE', 'views/img/products/default/anonymous.png', -8, 'Large', 0, 79, 12, '2024-11-11 19:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `productsingredients`
--

CREATE TABLE `productsingredients` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `productsingredients`
--

INSERT INTO `productsingredients` (`id`, `product_id`, `ingredient_id`, `size`) VALUES
(17, 123, 6, 12),
(22, 18, 7, 10),
(23, 18, 6, 5),
(24, 19, 14, 5),
(25, 19, 7, 7),
(26, 19, 12, 3),
(27, 20, 6, 4),
(28, 21, 11, 3),
(29, 22, 11, 4),
(30, 22, 6, 1),
(31, 23, 13, 2),
(32, 26, 11, 3),
(33, 26, 6, 3),
(34, 26, 6, 5),
(35, 28, 11, 6),
(36, 28, 14, 4),
(37, 28, 13, 2),
(38, 222, 12, 2),
(39, 29, 7, 10),
(40, 29, 6, 34),
(41, 333, 7, 10),
(42, 234, 7, 10),
(43, 415, 6, 500),
(44, 415, 11, 500),
(45, 461, 14, 500),
(46, 461, 6, 500),
(47, 111, 11, 5),
(48, 111, 6, 5),
(49, 10, 6, 100),
(50, 11, 6, 100),
(51, 11, 7, 100),
(52, 12, 6, 100),
(53, 12, 7, 100),
(54, 13, 6, 100),
(55, 14, 6, 100),
(56, 15, 12, 100),
(57, 16, 12, 100),
(58, 17, 12, 100),
(59, 18, 12, 100),
(60, 19, 12, 100),
(61, 20, 12, 100),
(62, 21, 12, 100),
(63, 22, 12, 100),
(64, 23, 12, 100),
(65, 24, 12, 100),
(66, 25, 12, 100),
(67, 26, 12, 100),
(68, 27, 12, 100),
(69, 28, 12, 50),
(70, 54, 29, 90),
(71, 54, 18, 2),
(72, 55, 18, 10),
(73, 55, 30, 10),
(74, 56, 24, 90),
(75, 56, 22, 2),
(76, 56, 18, 8),
(77, 57, 37, 90),
(78, 57, 22, 2),
(79, 57, 18, 8),
(80, 58, 25, 90),
(81, 58, 22, 2),
(82, 58, 18, 8),
(83, 59, 27, 90),
(84, 59, 22, 2),
(85, 59, 18, 8),
(86, 60, 26, 90),
(87, 60, 22, 2),
(88, 60, 18, 8),
(89, 61, 28, 90),
(90, 61, 22, 2),
(91, 61, 18, 8),
(92, 62, 37, 90),
(93, 62, 22, 2),
(94, 62, 18, 8),
(95, 63, 23, 90),
(96, 63, 22, 2),
(97, 63, 18, 8),
(98, 64, 27, 120),
(99, 64, 22, 2),
(100, 64, 18, 8),
(101, 65, 25, 120),
(102, 65, 22, 2),
(103, 65, 18, 8),
(104, 66, 28, 120),
(105, 66, 22, 2),
(106, 66, 18, 8),
(107, 67, 26, 120),
(108, 67, 22, 2),
(109, 67, 18, 8),
(110, 68, 24, 120),
(111, 68, 22, 2),
(112, 68, 18, 8),
(113, 69, 29, 90),
(114, 69, 22, 2),
(115, 69, 17, 90),
(116, 69, 18, 8),
(117, 70, 17, 90),
(118, 70, 29, 120),
(119, 70, 22, 2),
(120, 70, 18, 8),
(121, 71, 30, 90),
(122, 71, 22, 2),
(123, 71, 18, 8),
(124, 71, 17, 120),
(125, 72, 17, 120),
(126, 72, 18, 8),
(127, 72, 22, 2),
(128, 72, 30, 120),
(129, 73, 17, 120),
(130, 73, 18, 8),
(131, 73, 22, 2),
(132, 73, 31, 90),
(133, 74, 17, 120),
(134, 74, 18, 90),
(135, 74, 22, 2),
(136, 74, 31, 120),
(137, 75, 17, 120),
(138, 75, 18, 8),
(139, 75, 22, 2),
(140, 75, 32, 90),
(141, 76, 17, 120),
(142, 76, 18, 8),
(143, 76, 22, 2),
(144, 76, 32, 120),
(145, 77, 17, 120),
(146, 77, 18, 8),
(147, 77, 22, 2),
(148, 77, 33, 90),
(149, 78, 17, 120),
(150, 78, 18, 8),
(151, 78, 22, 2),
(152, 78, 33, 120),
(153, 79, 17, 120),
(154, 79, 18, 8),
(155, 79, 22, 2),
(156, 79, 34, 90),
(157, 80, 17, 120),
(158, 80, 18, 8),
(159, 80, 22, 2),
(160, 80, 34, 120),
(161, 81, 17, 120),
(162, 81, 18, 8),
(163, 81, 22, 2),
(164, 81, 35, 90),
(165, 82, 17, 120),
(166, 82, 18, 8),
(167, 82, 22, 2),
(168, 82, 35, 120),
(169, 83, 19, 5),
(170, 83, 18, 10),
(171, 84, 18, 10),
(172, 84, 22, 10),
(173, 85, 22, 10),
(174, 86, 19, 10),
(175, 87, 18, 10),
(176, 88, 19, 10),
(177, 89, 19, 10),
(178, 90, 19, 10),
(179, 91, 17, 10),
(180, 91, 19, 10),
(181, 92, 19, 10),
(182, 92, 19, 11),
(183, 93, 19, 10),
(184, 94, 19, 10),
(185, 95, 19, 10),
(186, 95, 22, 10),
(187, 96, 18, 10),
(188, 97, 18, 10),
(189, 98, 18, 10),
(190, 99, 22, 10),
(191, 100, 20, 10),
(192, 101, 17, 10),
(193, 102, 17, 10),
(194, 103, 22, 2),
(195, 104, 19, 10),
(196, 105, 23, 11),
(197, 106, 19, 10),
(198, 107, 24, 11),
(199, 108, 18, 10),
(200, 109, 19, 10),
(201, 110, 25, 22),
(202, 110, 30, 23),
(203, 110, 25, 15),
(204, 110, 33, 35),
(205, 110, 27, 16),
(206, 110, 29, 24),
(207, 111, 17, 5),
(208, 112, 17, 23),
(209, 113, 17, 10),
(210, 113, 31, 10);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `idCustomer` int(11) NOT NULL,
  `idSeller` int(11) NOT NULL,
  `products` text COLLATE utf8_spanish_ci NOT NULL,
  `addons` text COLLATE utf8_spanish_ci NOT NULL,
  `tax` int(11) NOT NULL,
  `netPrice` float NOT NULL,
  `totalPrice` float NOT NULL,
  `paymentMethod` text COLLATE utf8_spanish_ci NOT NULL,
  `saledate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `code`, `idCustomer`, `idSeller`, `products`, `addons`, `tax`, `netPrice`, `totalPrice`, `paymentMethod`, `saledate`) VALUES
(1, 10001, 2, 1, '[{\"id\":\"18\",\"description\":\"Cookies and Cream\",\"quantity\":\"1\",\"stock\":\"149\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-10-22 12:39:22'),
(2, 10002, 2, 1, '[{\"id\":\"52\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"stock\":\"99\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-22 13:22:41'),
(3, 10003, 2, 1, '[{\"id\":\"18\",\"description\":\"COOKIES AND CREAM\",\"quantity\":\"1\",\"stock\":\"144\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-10-22 14:13:23'),
(4, 10004, 2, 2, '[{\"id\":\"52\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"stock\":\"98\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-23 08:59:20'),
(5, 10005, 2, 2, '[{\"id\":\"52\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"stock\":\"97\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-23 10:16:38'),
(6, 10006, 2, 2, '[{\"id\":\"44\",\"description\":\"STRAWBERRY\",\"quantity\":\"1\",\"stock\":\"99\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-23 10:18:17'),
(7, 10007, 2, 1, '[{\"id\":\"18\",\"description\":\"COOKIES AND CREAM\",\"quantity\":\"1\",\"stock\":\"143\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-10-23 11:27:43'),
(8, 10008, 2, 2, '[{\"id\":\"52\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"stock\":\"96\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-23 11:36:34'),
(9, 10009, 2, 1, '[{\"id\":\"39\",\"description\":\"MATCHA OREO\",\"quantity\":\"1\",\"stock\":\"99\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-10-24 09:27:15'),
(10, 10010, 2, 1, '[{\"id\":\"52\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"stock\":\"95\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-24 09:35:18'),
(11, 10011, 2, 2, '[{\"id\":\"52\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"stock\":\"94\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 69, 'cash', '2024-10-24 09:41:33'),
(12, 10012, 2, 1, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-11-01 14:30:52'),
(13, 10013, 2, 1, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-11-02 00:30:48'),
(14, 10014, 2, 1, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"108\",\"description\":\"KIWI LYCHEE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"107\",\"description\":\"SUNSET FRIZZ\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 237, 'cash', '2024-11-02 16:37:00'),
(15, 10015, 2, 1, '[{\"id\":\"105\",\"description\":\"KIWI LYCHEE\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69\"},{\"id\":\"106\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69\"},{\"id\":\"107\",\"description\":\"SUNSET FRIZZ\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"103\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"59\",\"totalPrice\":\"59\"},{\"id\":\"104\",\"description\":\"SUNSET FRIZZ\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69\"},{\"id\":\"108\",\"description\":\"KIWI LYCHEE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 819, 'cash', '2024-11-02 16:37:32'),
(16, 10016, 2, 1, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"108\",\"description\":\"KIWI LYCHEE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 158, 'cash', '2024-11-02 17:37:11'),
(17, 10016, 2, 2, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 395, 'cash', '2024-11-02 17:38:16'),
(18, 10017, 2, 1, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-11-03 15:29:50'),
(19, 10018, 2, 1, '[{\"id\":\"109\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"108\",\"description\":\"KIWI LYCHEE\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"107\",\"description\":\"SUNSET FRIZZ\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"106\",\"description\":\"GREEN APPLE\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69\"}]', '', 0, 0, 306, 'cash', '2024-11-03 15:49:08'),
(20, 10001, 2, 1, '[{\"id\":\"31\",\"description\":\"matcha\",\"quantity\":\"1\",\"stock\":\"35\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-10-22 11:45:03'),
(21, 10002, 2, 1, '[{\"id\":\"18\",\"description\":\"Cookies and Cream\",\"quantity\":\"1\",\"stock\":\"131\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '', 0, 0, 79, 'cash', '2024-10-22 13:21:21'),
(22, 10003, 2, 1, '[{\"id\":\"34\",\"description\":\"testest\",\"price\":\"2\",\"totalPrice\":\"2\"}]', '', 0, 0, 2, 'cash', '2024-10-24 13:34:35'),
(23, 10004, 2, 1, '[{\"id\":\"36\",\"description\":\"asdas\",\"price\":\"3\",\"totalPrice\":\"3\"}]', '', 0, 0, 3, 'cash', '2024-10-24 13:35:56'),
(24, 10005, 2, 1, '[{\"id\":\"40\",\"description\":\"21321\",\"quantity\":\"1\",\"price\":\"23\",\"totalPrice\":\"23\"},{\"id\":\"39\",\"description\":\"milktea2\",\"quantity\":\"1\",\"price\":\"70\",\"totalPrice\":\"70\"}]', '', 0, 0, 93, 'cash', '2024-10-30 11:56:43'),
(25, 10006, 2, 1, '[{\"id\":\"39\",\"description\":\"milktea2\",\"quantity\":\"1\",\"price\":\"70\",\"totalPrice\":\"70\"},{\"id\":\"38\",\"description\":\"milktea\",\"quantity\":\"1\",\"price\":\"2\",\"totalPrice\":\"2\"}]', '', 0, 0, 72, 'cash', '2024-11-01 12:11:54'),
(26, 10007, 2, 1, '[{\"id\":\"38\",\"description\":\"milktea\",\"quantity\":\"1\",\"price\":\"2\",\"totalPrice\":\"2\"}]', '', 0, 0, 37, 'cash', '2024-11-01 14:20:57'),
(27, 10008, 2, 1, '[{\"id\":\"39\",\"description\":\"milktea2\",\"quantity\":\"1\",\"price\":\"70\",\"totalPrice\":\"70\"}]', '', 0, 0, 70, 'cash', '2024-11-01 14:28:45'),
(28, 10009, 2, 1, '[{\"id\":\"38\",\"description\":\"milktea\",\"quantity\":\"1\",\"price\":\"2\",\"totalPrice\":\"2\"}]', '', 0, 0, 37, 'cash', '2024-11-01 14:59:02'),
(29, 10010, 2, 1, '[{\"id\":\"38\",\"description\":\"milktea (Regular)\",\"quantity\":\"1\",\"price\":\"2\",\"totalPrice\":\"2\"},{\"id\":\"39\",\"description\":\"milktea (Large)\",\"quantity\":\"1\",\"price\":\"70\",\"totalPrice\":\"70\"}]', '', 0, 0, 72, 'cash', '2024-11-04 05:37:16'),
(30, 10011, 2, 1, '[{\"id\":\"38\",\"description\":\"milktea (Regular)\",\"quantity\":\"1\",\"price\":\"2\",\"totalPrice\":\"2\"},{\"id\":\"39\",\"description\":\"milktea (Large)\",\"quantity\":\"1\",\"price\":\"70\",\"totalPrice\":\"70\"}]', '[{\"id\":\"6\",\"addon\":\"Milk\",\"quantity\":\"1\",\"price\":\"35\",\"totalPrice\":\"35\"}]', 0, 0, 107, 'cash', '2024-11-04 05:52:40'),
(31, 10012, 2, 1, '[{\"id\":\"38\",\"description\":\"milktea (Regular)\",\"quantity\":\"1\",\"price\":\"2\",\"totalPrice\":\"2\"}]', '', 0, 0, 2, 'cash', '2024-11-04 05:53:28'),
(32, 10013, 2, 1, '[{\"id\":\"39\",\"description\":\"milktea (Large)\",\"quantity\":\"1\",\"price\":\"70\",\"totalPrice\":\"70\"}]', '[{\"id\":\"6\",\"addon\":\"Milk\",\"quantity\":\"1\",\"price\":\"35\",\"totalPrice\":\"35\"}]', 0, 0, 105, 'cash', '2024-11-04 05:54:08'),
(33, 48200, 0, 0, '', '', 0, 23.6383, 23.6383, 'cash', '2022-08-20 16:00:00'),
(34, 42330, 0, 0, '', '', 0, 95.981, 95.981, 'cash', '2022-12-12 16:00:00'),
(35, 31495, 0, 0, '', '', 0, 35.6903, 35.6903, 'cash', '2023-02-05 16:00:00'),
(36, 21287, 0, 0, '', '', 0, 83.5834, 83.5834, 'cash', '2024-04-05 16:00:00'),
(37, 73915, 0, 0, '', '', 0, 58.6763, 58.6763, 'cash', '2019-09-07 16:00:00'),
(38, 52848, 0, 0, '', '', 0, 47.8908, 47.8908, 'cash', '2021-01-05 16:00:00'),
(39, 96477, 0, 0, '', '', 0, 9.21073, 9.21073, 'cash', '2019-06-14 16:00:00'),
(40, 71494, 0, 0, '', '', 0, 62.8582, 62.8582, 'cash', '2020-09-08 16:00:00'),
(41, 83375, 0, 0, '', '', 0, 13.7353, 13.7353, 'cash', '2024-09-25 16:00:00'),
(42, 91483, 0, 0, '', '', 0, 6.95893, 6.95893, 'cash', '2019-12-28 16:00:00'),
(43, 28269, 0, 0, '', '', 0, 73.2877, 73.2877, 'cash', '2020-09-15 16:00:00'),
(44, 21042, 0, 0, '', '', 0, 75.2983, 75.2983, 'cash', '2024-07-09 16:00:00'),
(45, 91079, 0, 0, '', '', 0, 87.6071, 87.6071, 'cash', '2021-02-23 16:00:00'),
(46, 85789, 0, 0, '', '', 0, 42.7211, 42.7211, 'cash', '2019-03-03 16:00:00'),
(47, 84944, 0, 0, '', '', 0, 72.5438, 72.5438, 'cash', '2023-08-20 16:00:00'),
(48, 54206, 0, 0, '', '', 0, 56.7594, 56.7594, 'cash', '2022-10-14 16:00:00'),
(49, 47706, 0, 0, '', '', 0, 10.8268, 10.8268, 'cash', '2022-03-22 16:00:00'),
(50, 12448, 0, 0, '', '', 0, 22.5021, 22.5021, 'cash', '2019-10-12 16:00:00'),
(51, 17218, 0, 0, '', '', 0, 27.9514, 27.9514, 'cash', '2022-12-31 16:00:00'),
(52, 46572, 0, 0, '', '', 0, 38.2125, 38.2125, 'cash', '2021-04-02 16:00:00'),
(53, 14893, 0, 0, '', '', 0, 91.1486, 91.1486, 'cash', '2019-01-24 16:00:00'),
(54, 53650, 0, 0, '', '', 0, 53.8127, 53.8127, 'cash', '2024-01-17 16:00:00'),
(55, 26442, 0, 0, '', '', 0, 73.6746, 73.6746, 'cash', '2020-01-20 16:00:00'),
(56, 26443, 2, 1, '[{\"id\":\"110\",\"description\":\"KIWI CHOCOLATE (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"}]', '', 0, 0, 69, 'cash', '2024-11-04 13:39:53'),
(57, 26444, 2, 1, '[{\"id\":\"107\",\"description\":\"SUNSET FRIZZ (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"102\",\"description\":\"LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"59\",\"totalPrice\":\"59.00\"}]', '[{\"id\":\"27\",\"addon\":\"COOKIES AND CREAM POWDER\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"18\",\"addon\":\"SYRUP\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"19\",\"addon\":\"YAKULT\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"20\",\"addon\":\"CREAM PUFF\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 178, 'cash', '2024-11-04 13:53:38'),
(58, 26445, 2, 1, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"94\",\"description\":\"STRAWBERRY (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"}]', '[{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"18\",\"addon\":\"SYRUP\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 168, 'cash', '2024-11-07 11:36:38'),
(59, 26446, 2, 1, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '', 0, 0, 79, 'cash', '2024-11-07 11:36:56'),
(60, 26447, 2, 1, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '[{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 89, 'cash', '2024-11-07 11:37:25'),
(61, 26448, 2, 1, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"105\",\"description\":\"KIWI LYCHEE (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"}]', '', 0, 0, 148, 'Gcash-132132', '2024-11-07 12:20:45'),
(62, 26449, 2, 1, '[{\"id\":\"105\",\"description\":\"KIWI LYCHEE (Regular)\",\"quantity\":\"5\",\"price\":\"69\",\"totalPrice\":\"345.00\"},{\"id\":\"102\",\"description\":\"LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"59\",\"totalPrice\":\"59.00\"}]', '', 0, 0, 404, 'cash', '2024-11-08 11:57:00'),
(63, 26450, 2, 2, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79\"},{\"id\":\"104\",\"description\":\"SUNSET FRIZZ (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69\"},{\"id\":\"102\",\"description\":\"LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"59\",\"totalPrice\":\"59\"}]', '[{\"id\":\"18\",\"addon\":\"SYRUP\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10\"},{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10\"}]', 0, 0, 227, 'cash', '2024-11-08 12:17:28'),
(64, 26451, 2, 2, '[{\"id\":\"88\",\"description\":\"BLUEBERRY YAKULT (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"87\",\"description\":\"STRAWBERRY (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '[{\"id\":\"18\",\"addon\":\"SYRUP\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 168, 'cash', '2024-11-08 12:22:33'),
(65, 26452, 2, 2, '[{\"id\":\"60\",\"description\":\"TARO (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"}]', '[{\"id\":\"42\",\"addon\":\"OREO\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 79, 'cash', '2024-11-08 12:29:52'),
(66, 26453, 2, 1, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE\",\"quantity\":\"1\",\"stock\":\"-6\",\"price\":\"79\",\"totalPrice\":\"79\"}]', '[{\"id\":\"41\",\"addon\":\"STRAWBERRY BOBA\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 89, 'Gcash-151523', '2024-11-09 13:12:38'),
(67, 26454, 2, 8, '[{\"id\":\"105\",\"description\":\"KIWI LYCHEE (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"},{\"id\":\"104\",\"description\":\"SUNSET FRIZZ (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"},{\"id\":\"84\",\"description\":\"GREEN APPLE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '[{\"id\":\"43\",\"addon\":\"CHEESEMILK FOAM\",\"quantity\":\"1\",\"price\":\"20\",\"totalPrice\":\"20.00\"},{\"id\":\"43\",\"addon\":\"CHEESEMILK FOAM\",\"quantity\":\"1\",\"price\":\"0\",\"totalPrice\":\"0.00\"},{\"id\":\"18\",\"addon\":\"SYRUP\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 247, 'cash', '2024-11-11 13:25:24'),
(68, 26455, 2, 8, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"107\",\"description\":\"SUNSET FRIZZ (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"88\",\"description\":\"BLUEBERRY YAKULT (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '[{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"0\",\"totalPrice\":\"0.00\"}]', 0, 0, 247, 'cash', '2024-11-11 14:09:36'),
(69, 26456, 2, 11, '[{\"id\":\"108\",\"description\":\"KIWI LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"102\",\"description\":\"LYCHEE (Large)\",\"quantity\":\"1\",\"price\":\"59\",\"totalPrice\":\"59.00\"},{\"id\":\"107\",\"description\":\"SUNSET FRIZZ (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"},{\"id\":\"95\",\"description\":\"BLUEBERRY YAKULT (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"},{\"id\":\"81\",\"description\":\"MATCHA OREO (Regular)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '[{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"38\",\"addon\":\"PEARL\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"20\",\"addon\":\"CREAM PUFF\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"},{\"id\":\"19\",\"addon\":\"YAKULT\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 405, 'cash', '2024-11-11 19:14:41'),
(70, 26457, 2, 11, '[{\"id\":\"65\",\"description\":\"DARK CHOCOLATE (Large)\",\"quantity\":\"1\",\"price\":\"79\",\"totalPrice\":\"79.00\"}]', '[{\"id\":\"17\",\"addon\":\"MILK\",\"quantity\":\"1\",\"price\":\"10\",\"totalPrice\":\"10.00\"}]', 0, 0, 89, 'cash', '2024-11-13 09:27:12'),
(71, 26458, 2, 11, '[{\"id\":\"65\",\"description\":\"DARK CHOCOLATE (Large)\",\"quantity\":\"5\",\"price\":\"79\",\"totalPrice\":\"395.00\"}]', '', 0, 0, 395, 'cash', '2024-11-13 09:28:23'),
(72, 26459, 2, 11, '[{\"id\":\"67\",\"description\":\"TARO (Large)\",\"quantity\":\"5\",\"price\":\"79\",\"totalPrice\":\"395.00\"}]', '', 0, 0, 395, 'cash', '2024-11-13 09:30:30'),
(73, 26460, 2, 11, '[{\"id\":\"54\",\"description\":\"CLASSIC MILKTEA  (Regular)\",\"quantity\":\"3\",\"price\":\"69\",\"totalPrice\":\"207.00\"}]', '', 0, 0, 207, 'cash', '2024-11-13 09:31:29'),
(74, 26461, 2, 11, '[{\"id\":\"63\",\"description\":\"CLASSIC MILKTEA  (Large)\",\"quantity\":\"3\",\"price\":\"79\",\"totalPrice\":\"237.00\"}]', '', 0, 0, 237, 'cash', '2024-11-13 09:32:04'),
(75, 26462, 2, 7, '[{\"id\":\"105\",\"description\":\"KIWI LYCHEE (Regular)\",\"quantity\":\"1\",\"price\":\"69\",\"totalPrice\":\"69.00\"}]', '', 0, 0, 69, 'cash', '2024-11-13 12:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_spanish_ci NOT NULL,
  `user` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `profile` text COLLATE utf8_spanish_ci NOT NULL,
  `photo` text COLLATE utf8_spanish_ci NOT NULL,
  `status` int(1) NOT NULL,
  `lastLogin` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `otp` int(11) NOT NULL,
  `otpexpiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user`, `password`, `profile`, `photo`, `status`, `lastLogin`, `date`, `otp`, `otpexpiry`) VALUES
(1, 'Administrator', 'admin@email.com', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrator', 'views/img/users/admin/924.jpg', 1, '2024-11-15 10:28:21', '2024-11-15 02:28:21', 671414, '2024-11-15 10:33:21'),
(2, 'Employee', 'employee', '$2a$07$asxx54ahjppf45sd87a5auGkn32XeInRtxd5CrDpUvLY/7fXuy1S2', 'Employee', 'views/img/users/jonathan/239.jpg', 0, '2024-11-11 18:01:11', '2024-11-11 13:49:33', 0, NULL),
(7, 'IT expert', 'pinedabennor@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrator', 'views/img/users/default/prfplaceholder.png', 1, '2024-11-13 20:24:37', '2024-11-13 12:24:37', 137605, '2024-11-13 20:29:37'),
(8, 'Employee', 'kay614997@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auGkn32XeInRtxd5CrDpUvLY/7fXuy1S2', 'Employee', 'views/img/users/default/prfplaceholder.png', 1, '2024-11-11 22:06:25', '2024-11-11 14:06:25', 854636, '2024-11-11 22:11:25'),
(9, 'Administrator', 'taipeiroyaltea0622@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrator', 'views/img/users/default/prfplaceholder.png', 1, '2024-11-11 23:43:48', '2024-11-11 15:43:48', 788886, '2024-11-11 23:48:48'),
(10, 'Administrator ', 'floresroseanne614@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auGkn32XeInRtxd5CrDpUvLY/7fXuy1S2', 'administrator', 'views/img/users/default/prfplaceholder.png', 1, '2024-11-13 09:20:02', '2024-11-13 01:20:02', 142612, '2024-11-13 09:25:02'),
(11, 'Administrator ', 'renbsurat@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrator', 'views/img/users/default/prfplaceholder.png', 1, '2024-11-13 17:22:42', '2024-11-13 09:22:42', 204126, '2024-11-13 17:27:42'),
(12, 'Administrator ', 'eplevondorf809@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrator', 'views/img/users/default/prfplaceholder.png', 1, '2024-11-13 06:57:05', '2024-11-12 22:57:05', 719564, '2024-11-13 07:02:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productsingredients`
--
ALTER TABLE `productsingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `productsingredients`
--
ALTER TABLE `productsingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
