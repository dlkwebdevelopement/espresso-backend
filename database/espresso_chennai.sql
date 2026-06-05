-- Espresso Cafe Database Dump
-- Compatible with MySQL/MariaDB
-- Includes Chennai and Bahrain locations and 24 catalog item images

SET FOREIGN_KEY_CHECKS=0;

-- -------------------------------------------------------------
-- Table structure for table `users`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `favorites` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `wallet_balance` decimal(10,3) NOT NULL DEFAULT '50.000',
  `dob` date DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `deletion_requested` tinyint(1) NOT NULL DEFAULT '0',
  `deletion_reason` text DEFAULT NULL,
  `deletion_requested_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping user data
INSERT INTO `users` (`id`, `name`, `email`, `mobile_number`, `favorites`, `avatar`, `wallet_balance`, `dob`, `otp`, `otp_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Demo User', 'demo@espresso.com', '+97336001234', NULL, NULL, 50.000, '1995-01-01', NULL, NULL, NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `sessions`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `stores`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping store data
INSERT INTO `stores` (`id`, `name`, `location`, `contact_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Main Espresso Bahrain', 'Manama, Bahrain', '+973 12345678', 1, NOW(), NOW()),
(2, 'Main Espresso Chennai', 'Chennai, India', '+91 44 1234 5678', 1, NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `admins`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  CONSTRAINT `admins_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping admin data
INSERT INTO `admins` (`id`, `store_id`, `name`, `email`, `password`, `is_super_admin`, `created_at`, `updated_at`) VALUES
(1, 1, 'Espresso Admin', 'admin@espresso.com', '$2y$12$R.S4w6eF6Uj9V64c6p2u/.pZ23Z7L2r4s7X9Y.7O.6w6eF6Uj9V64', 1, NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `categories`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping categories
INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Beverages', 'beverages', 1, NOW(), NOW()),
(2, 'Bakery', 'bakery', 1, NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `sub_categories`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE `sub_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sub_categories_slug_unique` (`slug`),
  CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping subcategories
INSERT INTO `sub_categories` (`id`, `category_id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hot Coffee', 'hot-coffee', 1, NOW(), NOW()),
(2, 1, 'Cold Coffee', 'cold-coffee', 1, NOW(), NOW()),
(3, 1, 'Signature', 'signature', 1, NOW(), NOW()),
(4, 2, 'Croissants', 'croissants', 1, NOW(), NOW()),
(5, 2, 'Muffins', 'muffins', 1, NOW(), NOW()),
(6, 2, 'Cakes', 'cakes', 1, NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `items`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `sub_category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,3) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_weekly_favorite` tinyint(1) NOT NULL DEFAULT '0',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `items_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `items_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping items for Bahrain Store (id: 1..24) and Chennai Store (id: 25..48)
INSERT INTO `items` (`id`, `store_id`, `sub_category_id`, `name`, `description`, `base_price`, `image_path`, `is_available`, `is_weekly_favorite`, `is_popular`, `created_at`, `updated_at`) VALUES
-- Bahrain Store Items
(1, 1, 1, 'Spanish Latte', 'Freshly prepared Spanish Latte from Espresso Bahrain.', 2.500, 'uploads/items/imgg.jpeg', 1, 0, 0, NOW(), NOW()),
(2, 1, 1, 'Flat White', 'Freshly prepared Flat White from Espresso Bahrain.', 2.300, 'uploads/items/imgg1.jpeg', 1, 0, 0, NOW(), NOW()),
(3, 1, 1, 'Cappuccino', 'Freshly prepared Cappuccino from Espresso Bahrain.', 2.000, 'uploads/items/imgg2.jpeg', 1, 0, 0, NOW(), NOW()),
(4, 1, 1, 'Americano', 'Freshly prepared Americano from Espresso Bahrain.', 1.800, 'uploads/items/imgg3.jpeg', 1, 0, 0, NOW(), NOW()),
(5, 1, 1, 'Macchiato', 'Freshly prepared Macchiato from Espresso Bahrain.', 2.100, 'uploads/items/imgg4.jpeg', 1, 0, 0, NOW(), NOW()),
(6, 1, 1, 'Latte', 'Freshly prepared Latte from Espresso Bahrain.', 2.200, 'uploads/items/imgg12.jpeg', 1, 0, 0, NOW(), NOW()),
(7, 1, 2, 'Iced Spanish Latte', 'Freshly prepared Iced Spanish Latte from Espresso Bahrain.', 2.800, 'uploads/items/imgg5.jpeg', 1, 0, 0, NOW(), NOW()),
(8, 1, 2, 'Cold Brew', 'Freshly prepared Cold Brew from Espresso Bahrain.', 2.500, 'uploads/items/imgg6.jpeg', 1, 0, 0, NOW(), NOW()),
(9, 1, 2, 'Iced Americano', 'Freshly prepared Iced Americano from Espresso Bahrain.', 2.200, 'uploads/items/imgg7.jpeg', 1, 0, 0, NOW(), NOW()),
(10, 1, 2, 'Iced Mocha', 'Freshly prepared Iced Mocha from Espresso Bahrain.', 2.900, 'uploads/items/imgg8.jpeg', 1, 0, 0, NOW(), NOW()),
(11, 1, 2, 'Brown Sugar Shaken Espresso', 'Freshly prepared Brown Sugar Shaken Espresso from Espresso Bahrain.', 3.100, 'uploads/items/imgg13.jpeg', 1, 0, 0, NOW(), NOW()),
(12, 1, 2, 'Honey Almond Cold Brew', 'Freshly prepared Honey Almond Cold Brew from Espresso Bahrain.', 3.200, 'uploads/items/img.jpeg', 1, 0, 0, NOW(), NOW()),
(13, 1, 3, 'Espresso Special', 'Freshly prepared Espresso Special from Espresso Bahrain.', 3.500, 'uploads/items/img1.jpeg', 1, 0, 0, NOW(), NOW()),
(14, 1, 3, 'Caramel Macchiato Special', 'Freshly prepared Caramel Macchiato Special from Espresso Bahrain.', 3.800, 'uploads/items/img2.jpeg', 1, 0, 0, NOW(), NOW()),
(15, 1, 3, 'Honey Almond Latte', 'Freshly prepared Honey Almond Latte from Espresso Bahrain.', 4.200, 'uploads/items/img3.jpeg', 1, 1, 0, NOW(), NOW()),
(16, 1, 3, 'Salted Caramel Mocha', 'Freshly prepared Salted Caramel Mocha from Espresso Bahrain.', 4.500, 'uploads/items/img4.jpeg', 1, 0, 1, NOW(), NOW()),
(17, 1, 3, 'Lavender Oat Latte', 'Freshly prepared Lavender Oat Latte from Espresso Bahrain.', 4.300, 'uploads/items/img5.jpeg', 1, 0, 0, NOW(), NOW()),
(18, 1, 4, 'Butter Croissant', 'Freshly prepared Butter Croissant from Espresso Bahrain.', 1.200, 'uploads/items/img6.jpeg', 1, 0, 0, NOW(), NOW()),
(19, 1, 4, 'Cheese Croissant', 'Freshly prepared Cheese Croissant from Espresso Bahrain.', 1.400, 'uploads/items/img7.jpeg', 1, 0, 0, NOW(), NOW()),
(20, 1, 4, 'Almond Croissant', 'Freshly prepared Almond Croissant from Espresso Bahrain.', 1.600, 'uploads/items/img8.jpeg', 1, 0, 0, NOW(), NOW()),
(21, 1, 5, 'Blueberry Muffin', 'Freshly prepared Blueberry Muffin from Espresso Bahrain.', 1.500, 'uploads/items/img9.jpeg', 1, 0, 0, NOW(), NOW()),
(22, 1, 5, 'Double Chocolate Muffin', 'Freshly prepared Double Chocolate Muffin from Espresso Bahrain.', 1.600, 'uploads/items/imgg9.jpeg', 1, 0, 0, NOW(), NOW()),
(23, 1, 6, 'Tiramisu', 'Freshly prepared Tiramisu from Espresso Bahrain.', 2.500, 'uploads/items/imgg10.jpeg', 1, 1, 0, NOW(), NOW()),
(24, 1, 6, 'Saffron Cake', 'Freshly prepared Saffron Cake from Espresso Bahrain.', 2.800, 'uploads/items/imgg11.jpeg', 1, 0, 1, NOW(), NOW()),

-- Chennai Store Items
(25, 2, 1, 'Spanish Latte', 'Freshly prepared Spanish Latte from Espresso Chennai.', 2.500, 'uploads/items/imgg.jpeg', 1, 0, 0, NOW(), NOW()),
(26, 2, 1, 'Flat White', 'Freshly prepared Flat White from Espresso Chennai.', 2.300, 'uploads/items/imgg1.jpeg', 1, 0, 0, NOW(), NOW()),
(27, 2, 1, 'Cappuccino', 'Freshly prepared Cappuccino from Espresso Chennai.', 2.000, 'uploads/items/imgg2.jpeg', 1, 0, 0, NOW(), NOW()),
(28, 2, 1, 'Americano', 'Freshly prepared Americano from Espresso Chennai.', 1.800, 'uploads/items/imgg3.jpeg', 1, 0, 0, NOW(), NOW()),
(29, 2, 1, 'Macchiato', 'Freshly prepared Macchiato from Espresso Chennai.', 2.100, 'uploads/items/imgg4.jpeg', 1, 0, 0, NOW(), NOW()),
(30, 2, 1, 'Latte', 'Freshly prepared Latte from Espresso Chennai.', 2.200, 'uploads/items/imgg12.jpeg', 1, 0, 0, NOW(), NOW()),
(31, 2, 2, 'Iced Spanish Latte', 'Freshly prepared Iced Spanish Latte from Espresso Chennai.', 2.800, 'uploads/items/imgg5.jpeg', 1, 0, 0, NOW(), NOW()),
(32, 2, 2, 'Cold Brew', 'Freshly prepared Cold Brew from Espresso Chennai.', 2.500, 'uploads/items/imgg6.jpeg', 1, 0, 0, NOW(), NOW()),
(33, 2, 2, 'Iced Americano', 'Freshly prepared Iced Americano from Espresso Chennai.', 2.200, 'uploads/items/imgg7.jpeg', 1, 0, 0, NOW(), NOW()),
(34, 2, 2, 'Iced Mocha', 'Freshly prepared Iced Mocha from Espresso Chennai.', 2.900, 'uploads/items/imgg8.jpeg', 1, 0, 0, NOW(), NOW()),
(35, 2, 2, 'Brown Sugar Shaken Espresso', 'Freshly prepared Brown Sugar Shaken Espresso from Espresso Chennai.', 3.100, 'uploads/items/imgg13.jpeg', 1, 0, 0, NOW(), NOW()),
(36, 2, 2, 'Honey Almond Cold Brew', 'Freshly prepared Honey Almond Cold Brew from Espresso Chennai.', 3.200, 'uploads/items/img.jpeg', 1, 0, 0, NOW(), NOW()),
(37, 2, 3, 'Espresso Special', 'Freshly prepared Espresso Special from Espresso Chennai.', 3.500, 'uploads/items/img1.jpeg', 1, 0, 0, NOW(), NOW()),
(38, 2, 3, 'Caramel Macchiato Special', 'Freshly prepared Caramel Macchiato Special from Espresso Chennai.', 3.800, 'uploads/items/img2.jpeg', 1, 0, 0, NOW(), NOW()),
(39, 2, 3, 'Honey Almond Latte', 'Freshly prepared Honey Almond Latte from Espresso Chennai.', 4.200, 'uploads/items/img3.jpeg', 1, 1, 0, NOW(), NOW()),
(40, 2, 3, 'Salted Caramel Mocha', 'Freshly prepared Salted Caramel Mocha from Espresso Chennai.', 4.500, 'uploads/items/img4.jpeg', 1, 0, 1, NOW(), NOW()),
(41, 2, 3, 'Lavender Oat Latte', 'Freshly prepared Lavender Oat Latte from Espresso Chennai.', 4.300, 'uploads/items/img5.jpeg', 1, 0, 0, NOW(), NOW()),
(42, 2, 4, 'Butter Croissant', 'Freshly prepared Butter Croissant from Espresso Chennai.', 1.200, 'uploads/items/img6.jpeg', 1, 0, 0, NOW(), NOW()),
(43, 2, 4, 'Cheese Croissant', 'Freshly prepared Cheese Croissant from Espresso Chennai.', 1.400, 'uploads/items/img7.jpeg', 1, 0, 0, NOW(), NOW()),
(44, 2, 4, 'Almond Croissant', 'Freshly prepared Almond Croissant from Espresso Chennai.', 1.600, 'uploads/items/img8.jpeg', 1, 0, 0, NOW(), NOW()),
(45, 2, 5, 'Blueberry Muffin', 'Freshly prepared Blueberry Muffin from Espresso Chennai.', 1.500, 'uploads/items/img9.jpeg', 1, 0, 0, NOW(), NOW()),
(46, 2, 5, 'Double Chocolate Muffin', 'Freshly prepared Double Chocolate Muffin from Espresso Chennai.', 1.600, 'uploads/items/imgg9.jpeg', 1, 0, 0, NOW(), NOW()),
(47, 2, 6, 'Tiramisu', 'Freshly prepared Tiramisu from Espresso Chennai.', 2.500, 'uploads/items/imgg10.jpeg', 1, 1, 0, NOW(), NOW()),
(48, 2, 6, 'Saffron Cake', 'Freshly prepared Saffron Cake from Espresso Chennai.', 2.800, 'uploads/items/imgg11.jpeg', 1, 0, 1, NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `item_attributes`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `item_attributes`;
CREATE TABLE `item_attributes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `item_attributes_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping attributes for beverages (Bahrain: 1..17, Chennai: 25..41)
INSERT INTO `item_attributes` (`id`, `item_id`, `name`, `created_at`, `updated_at`) VALUES
-- Bahrain Store Beverages
(1, 1, 'Size', NOW(), NOW()), (2, 1, 'Sugar Level', NOW(), NOW()),
(3, 2, 'Size', NOW(), NOW()), (4, 2, 'Sugar Level', NOW(), NOW()),
(5, 3, 'Size', NOW(), NOW()), (6, 3, 'Sugar Level', NOW(), NOW()),
(7, 4, 'Size', NOW(), NOW()), (8, 4, 'Sugar Level', NOW(), NOW()),
(9, 5, 'Size', NOW(), NOW()), (10, 5, 'Sugar Level', NOW(), NOW()),
(11, 6, 'Size', NOW(), NOW()), (12, 6, 'Sugar Level', NOW(), NOW()),
(13, 7, 'Size', NOW(), NOW()), (14, 7, 'Sugar Level', NOW(), NOW()),
(15, 8, 'Size', NOW(), NOW()), (16, 8, 'Sugar Level', NOW(), NOW()),
(17, 9, 'Size', NOW(), NOW()), (18, 9, 'Sugar Level', NOW(), NOW()),
(19, 10, 'Size', NOW(), NOW()), (20, 10, 'Sugar Level', NOW(), NOW()),
(21, 11, 'Size', NOW(), NOW()), (22, 11, 'Sugar Level', NOW(), NOW()),
(23, 12, 'Size', NOW(), NOW()), (24, 12, 'Sugar Level', NOW(), NOW()),
(25, 13, 'Size', NOW(), NOW()), (26, 13, 'Sugar Level', NOW(), NOW()),
(27, 14, 'Size', NOW(), NOW()), (28, 14, 'Sugar Level', NOW(), NOW()),
(29, 15, 'Size', NOW(), NOW()), (30, 15, 'Sugar Level', NOW(), NOW()),
(31, 16, 'Size', NOW(), NOW()), (32, 16, 'Sugar Level', NOW(), NOW()),
(33, 17, 'Size', NOW(), NOW()), (34, 17, 'Sugar Level', NOW(), NOW()),
-- Chennai Store Beverages
(35, 25, 'Size', NOW(), NOW()), (36, 25, 'Sugar Level', NOW(), NOW()),
(37, 26, 'Size', NOW(), NOW()), (38, 26, 'Sugar Level', NOW(), NOW()),
(39, 27, 'Size', NOW(), NOW()), (40, 27, 'Sugar Level', NOW(), NOW()),
(41, 28, 'Size', NOW(), NOW()), (42, 28, 'Sugar Level', NOW(), NOW()),
(43, 29, 'Size', NOW(), NOW()), (44, 29, 'Sugar Level', NOW(), NOW()),
(45, 30, 'Size', NOW(), NOW()), (46, 30, 'Sugar Level', NOW(), NOW()),
(47, 31, 'Size', NOW(), NOW()), (48, 31, 'Sugar Level', NOW(), NOW()),
(49, 32, 'Size', NOW(), NOW()), (50, 32, 'Sugar Level', NOW(), NOW()),
(51, 33, 'Size', NOW(), NOW()), (52, 33, 'Sugar Level', NOW(), NOW()),
(53, 34, 'Size', NOW(), NOW()), (54, 34, 'Sugar Level', NOW(), NOW()),
(55, 35, 'Size', NOW(), NOW()), (56, 35, 'Sugar Level', NOW(), NOW()),
(57, 36, 'Size', NOW(), NOW()), (58, 36, 'Sugar Level', NOW(), NOW()),
(59, 37, 'Size', NOW(), NOW()), (60, 37, 'Sugar Level', NOW(), NOW()),
(61, 38, 'Size', NOW(), NOW()), (62, 38, 'Sugar Level', NOW(), NOW()),
(63, 39, 'Size', NOW(), NOW()), (64, 39, 'Sugar Level', NOW(), NOW()),
(65, 40, 'Size', NOW(), NOW()), (66, 40, 'Sugar Level', NOW(), NOW()),
(67, 41, 'Size', NOW(), NOW()), (68, 41, 'Sugar Level', NOW(), NOW());

-- -------------------------------------------------------------
-- Table structure for table `item_attribute_options`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `item_attribute_options`;
CREATE TABLE `item_attribute_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_attribute_id` bigint(20) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  `price_adjustment` decimal(10,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `item_attribute_options_item_attribute_id_foreign` FOREIGN KEY (`item_attribute_id`) REFERENCES `item_attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping options for sizes and sugar levels (68 attributes total)
-- Size options: Small (price+0), Medium (price+0.4), Large (price+0.7)
-- Sugar options: No Sugar (0), Half Sugar (0), Normal Sugar (0)
DROP PROCEDURE IF EXISTS InsertAttributeOptions;
DELIMITER $$
CREATE PROCEDURE InsertAttributeOptions()
BEGIN
  DECLARE i INT DEFAULT 1;
  WHILE i <= 68 DO
    IF i % 2 = 1 THEN
      -- Size attribute
      INSERT INTO `item_attribute_options` (`item_attribute_id`, `value`, `price_adjustment`, `created_at`, `updated_at`) VALUES
      (i, 'Small', 0.000, NOW(), NOW()),
      (i, 'Medium', 0.400, NOW(), NOW()),
      (i, 'Large', 0.700, NOW(), NOW());
    ELSE
      -- Sugar Level attribute
      INSERT INTO `item_attribute_options` (`item_attribute_id`, `value`, `price_adjustment`, `created_at`, `updated_at`) VALUES
      (i, 'No Sugar', 0.000, NOW(), NOW()),
      (i, 'Half Sugar', 0.000, NOW(), NOW()),
      (i, 'Normal Sugar', 0.000, NOW(), NOW());
    END IF;
    SET i = i + 1;
  END WHILE;
END$$
DELIMITER ;

CALL InsertAttributeOptions();
DROP PROCEDURE IF EXISTS InsertAttributeOptions;

-- -------------------------------------------------------------
-- Table structure for table `orders`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `items` json NOT NULL,
  `subtotal` decimal(8,3) NOT NULL,
  `service_charge` decimal(8,3) NOT NULL,
  `vat` decimal(8,3) NOT NULL,
  `tip` decimal(8,3) NOT NULL DEFAULT '0.000',
  `total` decimal(8,3) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ordered',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `refund_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_id_unique` (`order_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `wallet_transactions`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE `wallet_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,3) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `cache`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `cache_locks`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `jobs`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `job_batches`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for table `failed_jobs`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
