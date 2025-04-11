-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 06:33 AM
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
-- Database: `greenhive`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eligibility_checks`
--

CREATE TABLE `eligibility_checks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `farm_size` float NOT NULL,
  `crop_type` varchar(255) NOT NULL,
  `income_level` float NOT NULL,
  `result` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `farm_size` float NOT NULL,
  `annual_income` float NOT NULL,
  `loan_amount` float NOT NULL,
  `purpose` text NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `user_id`, `farm_size`, `annual_income`, `loan_amount`, `purpose`, `status`, `created_at`) VALUES
(1, 7, 5, 80000, 200000, 'farming', 'Pending', '2025-01-30 18:50:50'),
(2, 7, 5, 100000, 500000, 'farming', 'Rejected', '2025-01-30 19:08:34');

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `region` varchar(255) NOT NULL,
  `pdf_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `title`, `description`, `region`, `pdf_link`) VALUES
(1, 'Natural Farming Loan for Infrastructure Development', 'This loan aims to support farmers in developing infrastructure for natural farming practices. It provides financial assistance to farmers who are committed to adopting natural farming methods and need to build or upgrade facilities such as storage units, irrigation systems, and composting units.\r\nEligibility: Farmers who have been practicing natural farming for at least one year and have a minimum of 1 hectare of land under natural farming. The loan is also available to Farmer Producer Organizations (FPOs) and Self-Help Groups (SHGs) involved in natural farming.', 'Applicable across multiple states in India, including Andhra Pradesh, Himachal Pradesh, Gujarat, Kerala, Jharkhand, Odisha, Madhya Pradesh, Rajasthan, Uttar Pradesh, and Tamil Nadu', 'https://pib.gov.in/PressReleasePage.aspx?PRID=2077094');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('BP','Diabetes','Thyroid','Weight Gain','Weight Loss') NOT NULL,
  `imgurl` varchar(255) DEFAULT NULL,
  `heading` enum('Morning','Afternoon','Night') NOT NULL,
  `menu` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Shipped','Delivered') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`) VALUES
(1, 6, 6, 1, 100.00, 'Confirmed', '2025-01-25 10:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` set('Loan','Subsidy','Insurance','Other') NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `pdf_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `title`, `description`, `type`, `region`, `pdf_link`, `created_at`) VALUES
(1, 'National Mission on Natural Farming (NMNF)', 'NMNF aims to promote natural farming practices across India, focusing on reducing the input cost of cultivation, enhancing soil health, and providing safe and nutritious food. The mission supports farmers in adopting chemical-free farming methods, integrating local livestock, and using diversified crop systems', 'Loan,Subsidy', 'NMNF is implemented across multiple states in India, including Andhra Pradesh, Himachal Pradesh, Guj', 'https://naturalfarming.dac.gov.in/uploads/Final_Guidelines.pdf', '2025-01-30 17:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` enum('Vegetables','Fruits','Grains','Leafy Veg') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` enum('kg','piece') NOT NULL,
  `total_stock` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `farmer_id`, `name`, `category`, `price`, `unit`, `total_stock`, `image_url`, `created_at`) VALUES
(6, 4, 'rice', 'Grains', 100.00, 'kg', 96, 'https://img.freepik.com/free-photo/bowl-with-rice-grains-arrangement_23-2149359447.jpg?ga=GA1.1.305123127.1726487500&semt=ais_hybrid', '2025-01-25 09:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `health_goals` text NOT NULL,
  `plan` enum('Weekly','Monthly') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `height`, `weight`, `age`, `health_goals`, `plan`, `start_date`, `end_date`, `notes`, `created_at`) VALUES
(1, 2, 120, 60, 20, 'High BP', 'Weekly', '0000-00-00', '0000-00-00', '', '2025-01-25 06:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `subsidies`
--

CREATE TABLE `subsidies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `eligibility_criteria` text NOT NULL,
  `application_process` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subsidies`
--

INSERT INTO `subsidies` (`id`, `title`, `description`, `eligibility_criteria`, `application_process`, `created_at`) VALUES
(1, 'Natural Farming Subsidy for On-Farm Input Production', 'This subsidy aims to support farmers in creating on-farm input production infrastructure for natural farming. It provides financial assistance to farmers who commit to adopting natural farming practices and have started implementing them on their fields.', 'Farmers who have been practicing natural farming for at least one year and have a minimum of 1 hectare of land under natural farming. The loan is also available to Farmer Producer Organizations (FPOs) and Self-Help Groups (SHGs) involved in natural farming', 'You can find more detailed guidelines and information about this subsidy here: https://naturalfarming.dac.gov.in/uploads/Final_Guidelines.pdf', '2025-01-30 19:21:32');

-- --------------------------------------------------------

--
-- Table structure for table `subsidy_policies`
--

CREATE TABLE `subsidy_policies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `region` varchar(255) NOT NULL,
  `pdf_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subsidy_policies`
--

INSERT INTO `subsidy_policies` (`id`, `title`, `description`, `region`, `pdf_link`) VALUES
(1, 'Natural Farming Subsidy for On-Farm Input Production', 'This subsidy aims to support farmers in creating on-farm input production infrastructure for natural farming. It provides financial assistance to farmers who commit to adopting natural farming practices and have started implementing them on their fields.', 'Applicable across multiple states in India, including Andhra Pradesh, Himachal Pradesh, Gujarat, Kerala, Jharkhand, Odisha, Madhya Pradesh, Rajasthan, Uttar Pradesh, and Tamil Nadu', 'https://naturalfarming.dac.gov.in/uploads/Final_Guidelines.pdf'),
(2, 'Natural Farming Subsidy for On-Farm Input Production', 'This subsidy aims to support farmers in creating on-farm input production infrastructure for natural farming. It provides financial assistance to farmers who commit to adopting natural farming practices and have started implementing them on their fields.', 'Applicable across multiple states in India, including Andhra Pradesh, Himachal Pradesh, Gujarat, Kerala, Jharkhand, Odisha, Madhya Pradesh, Rajasthan, Uttar Pradesh, and Tamil Nadu', 'https://naturalfarming.dac.gov.in/uploads/Final_Guidelines.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `role` enum('Customer','Farmer','Admin') DEFAULT 'Customer',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `age`, `gender`, `role`, `password`, `created_at`) VALUES
(2, 'sree', 'sree@gmail.com', '1234567890', 19, 'Male', 'Customer', '$2y$10$evsMocb5i3c/xiDq1Dg/R.YoTNXjhbvj/VV5xn1dulHHgMQa6rpMK', '2025-01-25 04:24:24'),
(4, 'admin', 'admin@admin', '7894561230', 20, 'Male', 'Admin', '$2y$10$Mt2PRrISuoWN.iqmSlWJZubpH9tL63wwD4CffPOzmkrAg/HGF98/q', '2025-01-25 07:19:26'),
(5, 'thisyanth', 'thisyanth@gmail.com', '123456789', 20, 'Male', 'Farmer', '$2y$10$sjB/y5uIxd7JoUCzk2kW2usTcPiFk1YCJxF.sDDBfzokdrErG3bLO', '2025-01-25 08:55:39'),
(6, 'dattu', 'dattu@gmail.com', '7894561230', 20, 'Male', 'Customer', '$2y$10$2da8LQa6bihFZl02ErBvveUvYmqBxi7gqpsSHx87oAZQgDvSiSSp2', '2025-01-25 09:33:10'),
(7, 'rana', 'rana@gmail.com', '7896541300', 20, 'Male', 'Farmer', '$2y$10$6TtaFxZ3QU9EnpnOTpqS8.pKAvOLG6k1RXiKGApUj017Zr0B1pTYW', '2025-01-30 16:30:30'),
(8, 'jeshmitha', 'starlet@gmail.com', '753159648', 20, 'Female', 'Customer', '$2y$10$Fs.e8NknxyJxmxiDQ8CBk.dIUZyp2qU4t5rQDGrfX1.Rj0XvM3qhu', '2025-02-03 08:45:10');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_menu`
--

CREATE TABLE `weekly_menu` (
  `id` int(11) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `meal_type` enum('Morning','Afternoon','Night') NOT NULL,
  `category` enum('BP','Diabetes','Thyroid','Weight Gain','Weight Loss') NOT NULL,
  `menu` text NOT NULL,
  `imgurl` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekly_menu`
--

INSERT INTO `weekly_menu` (`id`, `day`, `meal_type`, `category`, `menu`, `imgurl`, `created_at`) VALUES
(1, 'Monday', 'Morning', 'BP', 'Oats with almond milk, walnuts, green tea', 'https://img.freepik.com/free-photo/oatmeal-porridge-with-apples-cinnamon_114579-29960.jpg?ga=GA1.1.305123127.1726487500&semt=ais_hybrid', '2025-01-25 09:25:15'),
(2, 'Monday', 'Afternoon', 'Weight Gain', '\'Grilled chicken salad with olive oil dressing\'', 'https://img.freepik.com/free-photo/salad-with-grilled-shrimps-tomatoes-table_140725-4025.jpg?ga=GA1.1.305123127.1726487500&semt=ais_hybrid', '2025-01-25 09:31:42'),
(3, 'Monday', 'Night', 'Weight Gain', 'Steamed broccoli, quinoa, and baked salmon', 'https://img.freepik.com/free-photo/baked-meatballs-chicken-fillet-with-garnish-with-quinoa-boiled-broccoli-proper-nutrition-sports-nutrition-dietary-menu_2829-20085.jpg?ga=GA1.1.305123127.1726487500&semt=ais_hybrid', '2025-01-25 09:49:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `eligibility_checks`
--
ALTER TABLE `eligibility_checks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subsidies`
--
ALTER TABLE `subsidies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subsidy_policies`
--
ALTER TABLE `subsidy_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weekly_menu`
--
ALTER TABLE `weekly_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `eligibility_checks`
--
ALTER TABLE `eligibility_checks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subsidies`
--
ALTER TABLE `subsidies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subsidy_policies`
--
ALTER TABLE `subsidy_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `weekly_menu`
--
ALTER TABLE `weekly_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eligibility_checks`
--
ALTER TABLE `eligibility_checks`
  ADD CONSTRAINT `eligibility_checks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
