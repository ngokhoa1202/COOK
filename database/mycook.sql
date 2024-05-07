-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 07, 2024 at 09:27 PM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mycook`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `description` varchar(1022) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `menu_id`, `category_name`, `description`) VALUES
(10, 3, 'Portion size', 'Categorized main meals.'),
(11, 3, 'By nutrition', 'Categorized main meals by nutrition and protein content.'),
(12, 3, 'Vegatarian', 'Main meals prepared for vegatarian'),
(13, 3, 'By cuisine', 'Categorized main meal by regional and cultural taste.'),
(14, 5, 'Prepared party', ''),
(15, 5, 'Party dishes', 'Prepare some of savoury dishes to your party.'),
(16, 5, 'Vegatarian', 'Delicious vegatarian dishes for your party.'),
(17, 4, 'Portion size', 'Categorized puddings by portion size.'),
(18, 4, 'Hot puddings', 'Categorized main meals by portion size'),
(20, 6, 'Portion size', 'Categorized meal boxes by portion size.'),
(21, 6, 'Family', 'Healthy meal boxes for your own family'),
(54, 4, 'Cool puddings', 'My new pudding');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `content` varchar(4096) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `like_count` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int NOT NULL,
  `serve_id` int NOT NULL,
  `ingredient_name` varchar(255) NOT NULL,
  `percentage` varchar(16) DEFAULT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `description` varchar(1022) DEFAULT NULL
) ENGINE=InnoDB;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_name`, `description`) VALUES
(3, 'Main meals', 'Hundreds of main dish recipes. Choose from top-rated comfort food, healthy, and vegetarian options. Find your main dish star now!'),
(4, 'Puddings', 'Desserts, afters, pudding … whatever you call them, they’re always welcome to finish off an amazing dinner party main meal or casual Summer lunch. Handmade at COOK Puddings in Somerset, our easy puddings delivered means your special meal will end with big smiles and clean plates all round. Also perfect if you need some dinner party desserts to round out an evening of entertainment, our desserts delivered are both tasty and easy to prepare (either cooked from the freezer or defrosted and served). So order some desserts online, or pop into one of our shops and get your pud on! Funny'),
(5, 'Entertaining', 'Perfect party food prepared by hand, from canapes and starters to centre pieces and puddings. Discover the joys of hassle-free entertaining! Let&#39;s party. I love u. '),
(6, 'Meal boxes', 'Give someone you love time out of the kitchen and the gift of good food with one of our COOK meal boxes. The ideal one-click solution to stock up the freezer, one of our meal boxes delivered is the perfect present for new parents or those going through a difficult time. We have put together these combinations of delicious dishes in our food boxes to make the decision-making process that little bit easier....and to make your life even easier, we’ve created a meals subscription service so you can make the most out of the convenience of our meal box delivery.');

-- --------------------------------------------------------

--
-- Table structure for table `nutritions`
--

CREATE TABLE `nutritions` (
  `nutrition_id` int NOT NULL,
  `serve_id` int NOT NULL,
  `typical_values` varchar(255) NOT NULL,
  `per_100g` varchar(32) DEFAULT NULL,
  `per_portion` varchar(32) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `pre_total_cost` int NOT NULL DEFAULT '0',
  `promotion_cost` int NOT NULL DEFAULT '0',
  `delivery_cost` int NOT NULL DEFAULT '0',
  `final_cost` int NOT NULL DEFAULT '0',
  `delivery_date` date NOT NULL DEFAULT (curdate()),
  `leave_order_when_absent` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `order_product_id` int NOT NULL,
  `order_id` int NOT NULL,
  `serve_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `total_product_cost` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `type_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `type_id`, `product_name`, `description`) VALUES
(1, 1, 'Cheeseburger', 'A classic cheeseburger with juicy patty, melted cheese, and fresh toppings'),
(2, 2, 'Spaghetti Bolognese', 'A hearty pasta dish with ground meat sauce'),
(3, 3, 'Chocolate Cake', 'A rich and decadent chocolate cake'),
(4, 4, 'Apple Pie', 'A classic American dessert with a flaky crust and sweet apple filling'),
(5, 5, 'Latte', 'A smooth and creamy coffee drink with steamed milk'),
(6, 6, 'Black Coffee', 'A strong and bold cup of black coffee'),
(7, 7, 'Classic Potato Chips', 'Salted and crispy potato chips'),
(8, 8, 'Soft Pretzels', 'Warm and buttery pretzels with a hint of salt'),
(9, 9, 'Tomato Soup', 'A classic creamy tomato soup');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int NOT NULL,
  `product_id` int NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_url` varchar(4096) DEFAULT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `serves`
--

CREATE TABLE `serves` (
  `serve_id` int NOT NULL,
  `product_id` int NOT NULL,
  `serve_name` varchar(255) NOT NULL,
  `price` int NOT NULL DEFAULT '0',
  `discount` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `instruction` varchar(8192) DEFAULT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `type_id` int NOT NULL,
  `category_id` int NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `description` varchar(1022) DEFAULT NULL
) ENGINE=InnoDB;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`type_id`, `category_id`, `type_name`, `description`) VALUES
(1, 10, 'One', 'Main meals have portion size for one person. Love from staffs. Love you so much'),
(2, 10, 'Two people', 'Main meals have portion size for two people.'),
(3, 10, 'Four people', 'Main meals have portion size for four people.'),
(4, 11, 'Chicken meals', 'Main meals made from chicken.'),
(5, 11, 'Pork meals', 'Main meals made from pork.'),
(6, 11, 'Beef meals', 'Main meals made from beef.'),
(7, 12, 'Vegan meals', 'Main meals for people who avoid all animal foods and eat only plant-based food.'),
(8, 12, 'Lacto-vegatarian meals', 'Main meals for people who do not eat meat, seafood and eggs, but include dairy foods and plant foods.'),
(9, 14, 'Wedding party', ''),
(10, 14, 'Formal celebration', 'Prepare your formal celebration such as weddings, business meeting or anniversary.'),
(13, 15, 'Soup', 'Soup dishes for party.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'member',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `avatar`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin@example.com', 'admin@example.com', 'password123', '', 'admin', 'active', '2024-04-28 14:32:56', '2024-05-02 03:07:27'),
(2, 'user1', 'user1@example.com', 'password456', NULL, 'member', 'active', '2024-04-28 14:32:56', '2024-04-28 14:32:56'),
(3, 'sas@gmail.com', 'sas@gmail.com', 'a2ef810ad744ccfbe904838da020aa7786b1ad657bb46fde3fdf247c93be71d8', '', 'member', 'inactive', '2024-04-28 14:33:29', '2024-05-02 09:13:47'),
(4, 'mrpeabody@gmail.com', 'mrpeabody@gmail.com', 'bf6da92ea7949383def4254e36de7f3f217a626e9bcabd401d6bfe077abfb848', '', 'member', 'inactive', '2024-04-28 14:34:01', '2024-05-02 08:44:03'),
(5, 'conmeolala@yahoo.com', 'conmeolala@yahoo.com', '57df855fbb1f8e19f8cc3c02083ae670b2a0c973972929e953b53e79e5d79701', '', 'member', 'inactive', '2024-04-28 14:34:18', '2024-05-07 13:00:58'),
(6, 'johnt@yahoo.com', 'johnt@yahoo.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:35:25', '2024-04-29 03:28:38'),
(7, 'conmeodeptrai', 'cat@yahoo.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:35:31', '2024-05-01 11:07:42'),
(8, 'lab@yahoo.com', 'lab@yahoo.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:35:35', '2024-05-02 08:47:19'),
(9, 'conheoxinhxinh@yahoo.com', 'conheoxinhxinh@yahoo.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'admin', 'active', '2024-04-28 14:35:41', '2024-05-02 08:25:34'),
(10, 'abc@yahoo.com', 'abc@yahoo.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:35:50', '2024-05-02 09:22:37'),
(12, 'jackhack@hotmail.com', 'jackhack@hotmail.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:36:17', '2024-04-29 03:28:38'),
(13, 'loveu300@hotmail.com', 'loveu300@hotmail.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'active', '2024-04-28 14:36:33', '2024-05-02 09:04:03'),
(14, 'peabody@hotmail.com', 'peabody@hotmail.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:36:37', '2024-04-29 03:28:38'),
(18, 'anhyeuem@hotmail.com', 'anhyeuem@hotmail.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:37:04', '2024-04-29 03:28:38'),
(19, 'tuiquametmoi@hotmail.com', 'tuiquametmoi@hotmail.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:37:09', '2024-05-02 08:41:28'),
(20, 'hellomoinguoi@hotmail.com', 'hellomoinguoi@hotmail.com', '25c69241710a6c113eacf92d59d341b810d9e91eeac8d68b52678cd7f86b1ef5', '', 'member', 'inactive', '2024-04-28 14:37:16', '2024-05-02 08:42:21'),
(22, 'alice@gmail.com', 'alice@gmail.com', '123456789', NULL, 'member', 'active', '2024-04-29 03:14:27', '2024-04-29 03:14:27'),
(54, 'sarah_doe', 'sarah@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(55, 'adam_smith', 'adam@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(56, 'emily_brown', 'emily@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(57, 'david_johnson', 'david@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(58, 'olivia_davis', 'olivia@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(59, 'michael_taylor', 'michael@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(60, 'samantha_clark', 'samantha@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(61, 'benjamin_miller', 'benjamin@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(62, 'william_anderson', 'william@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(63, 'madison_thomas', 'madison@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(64, 'jacob_wilson', 'jacob@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(65, 'chloe_hernandez', 'chloe@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(66, 'ethan_martin', 'ethan@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(67, 'mia_jackson', 'mia@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(68, 'alex@example.com', 'alex@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:06:34', '2024-05-02 09:12:27'),
(69, 'nataysha@example.com', 'nataysha@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:06:34', '2024-05-02 09:08:22'),
(70, 'daniel_torres', 'daniel@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(71, 'lily_wood', 'lily@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(72, 'matthew_lopez', 'matthew@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(73, 'grace_hall', 'grace@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(74, 'andrew_scott', 'andrew@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(75, 'emma_green', 'emma@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(76, 'james_morris', 'james@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(77, 'ava_rivera', 'ava@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(78, 'ryan_lee', 'ryan@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(79, 'aubrey_ward', 'aubrey@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:06:34', '2024-04-30 04:06:34'),
(180, 'shrek@example.com', 'shrek@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 08:46:14'),
(181, 'user3', 'user3@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(182, 'user4', 'user4@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(183, 'user5', 'user5@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(184, 'user6', 'user6@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(185, 'ineedyou@example.com', 'ineedyou@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 09:14:45'),
(186, 'comedy@example.com', 'comedy@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 08:53:55'),
(187, 'changeme@example.com', 'changeme@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 09:15:45'),
(188, 'hi@example.com', 'hi@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 09:12:40'),
(189, 'iamsuperman@yahoo.com', 'iamsuperman@yahoo.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 09:24:08'),
(190, 'user12', 'user12@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(191, 'user13', 'user13@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(192, 'user14', 'user14@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(193, 'user15', 'user15@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(194, 'user16', 'user16@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(195, 'user17', 'user17@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(196, 'user18', 'user18@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(197, 'user19', 'user19@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(198, 'user20', 'user20@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(199, 'user21', 'user21@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(200, 'user22', 'user22@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(201, 'user23', 'user23@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(202, 'user24', 'user24@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(203, 'user25', 'user25@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(204, 'user26', 'user26@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(205, 'user27', 'user27@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(206, 'user28', 'user28@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(207, 'user29', 'user29@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(208, 'chicken@example.com', 'chicken@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 08:54:38'),
(209, 'i@example.com', 'i@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 09:03:44'),
(210, 'user32', 'user32@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(211, 'user33', 'user33@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(212, 'user34', 'user34@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(213, 'user35', 'user35@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(214, 'user36', 'user36@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(215, 'user37', 'user37@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(216, 'user38', 'user38@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(217, 'user39', 'user39@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(218, 'user40', 'user40@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(219, 'user41', 'user41@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(220, 'user42', 'user42@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(221, 'user43', 'user43@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(222, 'user44', 'user44@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(223, 'user45', 'user45@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(224, 'user46', 'user46@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(225, 'user47', 'user47@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(226, 'user48', 'user48@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(227, 'suagaugau@example.com', 'suagaugau@example.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 09:28:29'),
(228, 'user50', 'user50@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(229, 'user51', 'user51@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(230, 'user52', 'user52@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(231, 'user53', 'user53@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(232, 'user54', 'user54@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(233, 'user55', 'user55@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(234, 'user56', 'user56@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(235, 'user57', 'user57@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(236, 'user58', 'user58@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(237, 'user59', 'user59@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(238, 'user60', 'user60@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(239, 'user61', 'user61@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(240, 'user62', 'user62@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(241, 'user63', 'user63@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(242, 'user64', 'user64@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(243, 'user65', 'user65@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(244, 'user66', 'user66@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(245, 'user67', 'user67@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(246, 'user68', 'user68@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(247, 'user69', 'user69@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(248, 'user70', 'user70@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(249, 'sspanzer@yahoo.com', 'sspanzer@yahoo.com', 'password123', '', 'member', 'active', '2024-04-30 04:09:10', '2024-05-02 08:44:50'),
(250, 'user72', 'user72@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(251, 'user73', 'user73@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(252, 'user74', 'user74@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(253, 'user75', 'user75@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(254, 'user76', 'user76@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(255, 'user77', 'user77@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(256, 'user78', 'user78@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(257, 'user79', 'user79@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(258, 'user80', 'user80@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(259, 'user81', 'user81@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(260, 'user82', 'user82@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(261, 'user83', 'user83@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(262, 'user84', 'user84@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(263, 'user85', 'user85@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(264, 'user86', 'user86@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(265, 'user87', 'user87@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(266, 'user88', 'user88@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(267, 'user89', 'user89@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(268, 'user90', 'user90@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(269, 'user91', 'user91@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(270, 'user92', 'user92@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(271, 'user93', 'user93@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(272, 'user94', 'user94@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(273, 'user95', 'user95@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(274, 'user96', 'user96@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(275, 'user97', 'user97@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(276, 'user98', 'user98@example.com', 'password123', NULL, 'member', 'active', '2024-04-30 04:09:10', '2024-04-30 04:09:10'),
(280, 'concholalala@abc.com', 'concholalala@abc.com', '57df855fbb1f8e19f8cc3c02083ae670b2a0c973972929e953b53e79e5d79701', '', 'member', 'offline', '2024-05-07 14:14:53', '2024-05-07 14:14:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `UC_categories` (`menu_id`,`category_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD KEY `serve_id` (`serve_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD UNIQUE KEY `menu_name` (`menu_name`);

--
-- Indexes for table `nutritions`
--
ALTER TABLE `nutritions`
  ADD PRIMARY KEY (`nutrition_id`),
  ADD KEY `serve_id` (`serve_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `serve_id` (`serve_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `serves`
--
ALTER TABLE `serves`
  ADD PRIMARY KEY (`serve_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `UC_types` (`category_id`,`type_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `nutritions`
--
ALTER TABLE `nutritions`
  MODIFY `nutrition_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serves`
--
ALTER TABLE `serves`
  MODIFY `serve_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`serve_id`) REFERENCES `serves` (`serve_id`);

--
-- Constraints for table `nutritions`
--
ALTER TABLE `nutritions`
  ADD CONSTRAINT `nutritions_ibfk_1` FOREIGN KEY (`serve_id`) REFERENCES `serves` (`serve_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_products_ibfk_2` FOREIGN KEY (`serve_id`) REFERENCES `serves` (`serve_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types` (`type_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `serves`
--
ALTER TABLE `serves`
  ADD CONSTRAINT `serves_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `types`
--
ALTER TABLE `types`
  ADD CONSTRAINT `types_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
