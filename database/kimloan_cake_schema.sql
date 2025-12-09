-- KimLoan Cake schema (v2)
SET NAMES utf8mb4;
SET time_zone = "+07:00";
SET sql_mode = "STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION";

START TRANSACTION;

-- Core tables
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `order_status_history`;
DROP TABLE IF EXISTS `payment_transactions`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `shopping_carts`;
DROP TABLE IF EXISTS `customer_addresses`;
DROP TABLE IF EXISTS `payment_methods`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `product_category_links`;
DROP TABLE IF EXISTS `product_categories`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `promotion_products`;
DROP TABLE IF EXISTS `promotions`;
DROP TABLE IF EXISTS `coupon_users`;
DROP TABLE IF EXISTS `coupons`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `site_settings`;
DROP TABLE IF EXISTS `otp_tokens`;
DROP TABLE IF EXISTS `login_activities`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;


CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext DEFAULT NULL,
  `options` longtext DEFAULT NULL,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(30) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `avatar_url` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `status` enum('active','inactive','suspended','deleted') NOT NULL DEFAULT 'active',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `registered_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `internal_note` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `users_customer_code_unique` (`customer_code`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone_number`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `login_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device` varchar(150) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `is_success` tinyint(1) NOT NULL DEFAULT 1,
  `message` varchar(255) DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `login_activities_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `otp_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `purpose` enum('account_verification','password_reset','order_confirmation','payment_verification') NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_via` enum('email','sms','app') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `otp_tokens_user_purpose_index` (`user_id`,`purpose`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `group_key` varchar(100) NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `display_order` int NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(150) NOT NULL,
  `job_title` varchar(150) DEFAULT NULL,
  `content` text NOT NULL,
  `display_order` int NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payment_methods` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `method_code` varchar(30) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `method_type` enum('offline','online') NOT NULL,
  `configuration` json DEFAULT NULL,
  `display_order` tinyint unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_method_code_unique` (`method_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customer_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `receiver_name` varchar(150) NOT NULL,
  `receiver_phone` varchar(15) NOT NULL,
  `receiver_email` varchar(150) DEFAULT NULL,
  `district_code` varchar(10) DEFAULT NULL,
  `district_name` varchar(120) NOT NULL,
  `ward_code` varchar(15) DEFAULT NULL,
  `ward_name` varchar(150) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_addresses_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `shopping_carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `guest_token` char(36) DEFAULT NULL,
  `status` enum('active','ordered','abandoned') NOT NULL DEFAULT 'active',
  `coupon_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shopping_carts_guest_token_unique` (`guest_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_index` (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `primary_category_id` bigint unsigned NOT NULL,
  `product_code` varchar(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `storage_instruction` text DEFAULT NULL,
  `shelf_life` varchar(100) DEFAULT NULL,
  `listed_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `total_stock` int NOT NULL DEFAULT 0,
  `unit_name` varchar(50) NOT NULL DEFAULT 'cake',
  `weight_in_gram` decimal(8,2) DEFAULT NULL,
  `max_quantity_per_order` int unsigned DEFAULT NULL,
  `status` enum('draft','active','out_of_stock','archived') NOT NULL DEFAULT 'draft',
  `show_on_homepage` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `view_count` bigint unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_product_code_unique` (`product_code`),
  UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_category_links` (
  `product_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`, `category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `variant_name` varchar(120) NOT NULL,
  `sku` varchar(60) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `display_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promotion_code` varchar(50) NOT NULL,
  `title` varchar(150) NOT NULL,
  `promotion_type` enum('percent','amount') NOT NULL,
  `value` decimal(12,2) NOT NULL,
  `max_discount_value` decimal(12,2) DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `usage_limit` int unsigned DEFAULT NULL,
  `used_count` int unsigned NOT NULL DEFAULT 0,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `status` enum('draft','active','inactive','expired') NOT NULL DEFAULT 'draft',
  `show_badge` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promotions_code_unique` (`promotion_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `promotion_products` (
  `promotion_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `display_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`promotion_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(30) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percent','amount') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_discount_value` decimal(12,2) DEFAULT NULL,
  `minimum_order_value` decimal(12,2) DEFAULT NULL,
  `issued_quantity` int unsigned DEFAULT NULL,
  `used_quantity` int unsigned NOT NULL DEFAULT 0,
  `max_usage_per_user` int unsigned NOT NULL DEFAULT 1,
  `members_only` tinyint(1) NOT NULL DEFAULT 0,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `status` enum('upcoming','active','paused','expired') NOT NULL DEFAULT 'upcoming',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`coupon_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `coupon_users` (
  `coupon_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `usage_count` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`coupon_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `payment_method_id` tinyint unsigned NOT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `customer_email` varchar(150) DEFAULT NULL,
  `address_line` varchar(255) NOT NULL,
  `district_code` varchar(10) DEFAULT NULL,
  `district_name` varchar(120) NOT NULL,
  `ward_code` varchar(15) DEFAULT NULL,
  `ward_name` varchar(150) NOT NULL,
  `customer_note` varchar(255) DEFAULT NULL,
  `internal_note` varchar(255) DEFAULT NULL,
  `subtotal_amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL,
  `deposit_amount` decimal(12,2) DEFAULT NULL,
  `payment_status` enum('pending','processing','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `fulfillment_status` enum('pending','confirmed','preparing','shipping','delivered','cancelled','returned') NOT NULL DEFAULT 'pending',
  `source_channel` enum('website','facebook','zalo','store') NOT NULL DEFAULT 'website',
  `approved_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned NOT NULL,
  `product_name_snapshot` varchar(200) NOT NULL,
  `variant_name_snapshot` varchar(120) NOT NULL,
  `unit_name_snapshot` varchar(50) NOT NULL,
  `quantity` int NOT NULL,
  `list_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_status_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `old_status` enum('pending','confirmed','preparing','shipping','delivered','cancelled','returned') NOT NULL,
  `new_status` enum('pending','confirmed','preparing','shipping','delivered','cancelled','returned') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `changed_by` bigint unsigned DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_status_history_order_id_index` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `payment_method_id` tinyint unsigned NOT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','successful','failed','refunded') NOT NULL DEFAULT 'pending',
  `channel` varchar(100) DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data
INSERT INTO `users` (`id`, `customer_code`, `full_name`, `email`, `phone_number`, `password`, `role`, `status`, `email_verified`, `registered_at`, `created_at`, `updated_at`)
VALUES
(1, 'ADMIN001', 'Quản trị hệ thống', 'admin@kimloan.cake', '0900123456', '$2y$10$8N/7A0upyy1fe07PISA5iOMjVA6Lif9SP.OJbZI23cTxqSv7bWjue', 'admin', 'active', 1, '2025-01-01 00:00:00', NOW(), NOW()),
(2, 'STAFF001', 'Nhân viên cửa hàng', 'staff@kimloan.cake', '0900222333', '$2y$10$fCB3OtCCDAkIzB2gZkCVSOpgOeufwRBEV6s67mTUu7Q9xxvWmTfee', 'staff', 'active', 0, '2025-01-02 00:00:00', NOW(), NOW()),
(3, 'CUST001', 'Nguyễn Thị Kim Loan', 'khachhang@kimloan.cake', '0900111222', '$2y$10$62qdi/f73ti9pW3gAV4ZB.77SqeQIhmEqTv5oE/hQM7IqqsEXWKpy', 'customer', 'active', 1, '2025-01-03 00:00:00', NOW(), NOW());

INSERT INTO `payment_methods` (`id`, `method_code`, `name`, `description`, `method_type`, `configuration`, `display_order`, `is_active`)
VALUES
(1, 'cod', 'Thanh toán khi nhận hàng', 'Thanh toán trực tiếp bằng tiền mặt khi nhận hàng.', 'offline', NULL, 1, 1),
(2, 'transfer', 'Chuyển khoản ngân hàng', 'Chuyển khoản tới tài khoản được chỉ định.', 'offline', JSON_OBJECT('bank', 'VCB', 'account_no', '0123456789', 'account_name', 'KIM LOAN CAKE'), 2, 1);

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `description`, `group_key`, `updated_at`)
VALUES
('brand.name', 'KimLoan Cake', 'Tên thương hiệu hiển thị trên website.', 'branding', NOW()),
('brand.hotline', '0900 123 456', 'Hotline tư vấn khách hàng.', 'branding', NOW()),
('store.address', '123 Trần Hưng Đạo, Quận 1, TP.HCM', 'Địa chỉ cửa hàng chính.', 'store', NOW()),
('payment.qr_info', 'VCB - 0123456789 - KIM LOAN CAKE', 'Thông tin tài khoản nhận thanh toán QR.', 'payment', NOW());

INSERT INTO `banners` (`id`, `title`, `description`, `image_url`, `link_url`, `position`, `display_order`, `is_visible`, `created_at`, `updated_at`)
VALUES
(1, 'Ưu đãi mùa lễ hội', 'Giảm 15% cho combo bánh tiệc cưới.', '/images/demo/banner-festival.jpg', '/promotions', 'homepage', 1, 1, NOW(), NOW());

INSERT INTO `testimonials` (`id`, `customer_name`, `job_title`, `content`, `display_order`, `is_visible`, `created_at`, `updated_at`)
VALUES
(1, 'Trần Minh', 'Nhà thiết kế nội thất', 'Bánh kem của KimLoan rất ngon và giao hàng đúng hẹn, nhân viên tư vấn nhiệt tình.', 1, 1, NOW(), NOW());

INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `is_visible`, `display_order`, `created_at`, `updated_at`)
VALUES
(1, ''Banh kem truyen thong'', ''banh-kem-truyen-thong'', ''Nhung chiec banh kem thom ngon cho moi dip le.'', 1, 1, NOW(), NOW());

INSERT INTO `products` (`id`, `primary_category_id`, `product_code`, `name`, `slug`, `short_description`, `description`, `listed_price`, `sale_price`, `total_stock`, `unit_name`, `status`, `show_on_homepage`, `is_featured`, `created_at`, `updated_at`)
VALUES
(1, 1, ''CAKE001'', ''Banh kem dau tay'', ''banh-kem-dau-tay'', ''Banh kem mem min phu dau tuoi hap dan.'', ''Lop kem whipping beo ngay ket hop mut dau tay, phu hop sinh nhat va tiec nho.'', 450000.00, 420000.00, 20, ''cake'', ''active'', 1, 1, NOW(), NOW());

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `sku`, `price`, `sale_price`, `stock_quantity`, `status`, `is_default`, `created_at`, `updated_at`)
VALUES
(1, 1, ''Size 18cm'', ''CAKE001-18'', 450000.00, 420000.00, 12, ''active'', 1, NOW(), NOW());

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `alt_text`, `is_primary`, `display_order`, `created_at`, `updated_at`)
VALUES
(1, 1, ''/images/demo/cake-strawberry.jpg'', ''Banh kem dau tay'', 1, 1, NOW(), NOW());

INSERT INTO `promotions` (`id`, `promotion_code`, `title`, `promotion_type`, `value`, `max_discount_value`, `conditions`, `usage_limit`, `used_count`, `starts_at`, `ends_at`, `status`, `show_badge`, `created_at`, `updated_at`)
VALUES
(1, ''WELCOMETAKE10'', ''Uu dai khach hang moi 10%'', ''percent'', 10.00, 50000.00, ''Ap dung cho don hang dau tien, toi thieu 300000.'', 100, 0, NOW() - INTERVAL 1 DAY, NOW() + INTERVAL 30 DAY, ''active'', 1, NOW(), NOW());

INSERT INTO `promotion_products` (`promotion_id`, `product_id`, `display_order`)
VALUES (1, 1, 1);

INSERT INTO `coupons` (`id`, `coupon_code`, `title`, `description`, `discount_type`, `discount_value`, `max_discount_value`, `minimum_order_value`, `issued_quantity`, `used_quantity`, `max_usage_per_user`, `members_only`, `starts_at`, `ends_at`, `status`, `created_at`, `updated_at`)
VALUES
(1, ''FREESHIP20'', ''Freeship don tu 200k'', ''Ap dung freeship toi da 30000 cho don hang tu 200000.'', ''amount'', 30000.00, NULL, 200000.00, 200, 0, 1, 1, NOW() - INTERVAL 7 DAY, NOW() + INTERVAL 60 DAY, ''active'', NOW(), NOW());

INSERT INTO `customer_addresses` (`id`, `user_id`, `label`, `receiver_name`, `receiver_phone`, `receiver_email`, `district_code`, `district_name`, `ward_code`, `ward_name`, `address_line`, `note`, `is_default`, `created_at`, `updated_at`)
VALUES
(1, 3, ''Nha rieng'', ''Nguyen Thi Kim Loan'', ''0900111222'', ''khachhang@kimloan.cake'', ''760'', ''Quan 1'', ''26734'', ''Phuong Tan Dinh'', ''73/34 duong Dang Dung'', ''Lien he truoc khi giao.'', 1, NOW(), NOW());

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `payment_method_id`, `coupon_id`, `customer_name`, `customer_phone`, `customer_email`, `address_line`, `district_code`, `district_name`, `ward_code`, `ward_name`, `customer_note`, `internal_note`, `subtotal_amount`, `discount_amount`, `shipping_fee`, `grand_total`, `deposit_amount`, `payment_status`, `fulfillment_status`, `source_channel`, `ordered_at`, `created_at`, `updated_at`)
VALUES
(1, ''KL251101123456'', 3, 1, NULL, ''Nguyen Thi Kim Loan'', ''0900111222'', ''khachhang@kimloan.cake'', ''73/34 duong Dang Dung'', ''760'', ''Quan 1'', ''26734'', ''Phuong Tan Dinh'', ''Giao trong gio hanh chinh.'', NULL, 420000.00, 0.00, 0.00, 420000.00, NULL, ''pending'', ''pending'', ''website'', NOW(), NOW(), NOW());

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name_snapshot`, `variant_name_snapshot`, `unit_name_snapshot`, `quantity`, `list_price`, `sale_price`, `line_total`, `note`, `created_at`, `updated_at`)
VALUES
(1, 1, 1, 1, ''Banh kem dau tay'', ''Size 18cm'', ''cake'', 1, 450000.00, 420000.00, 420000.00, NULL, NOW(), NOW());

COMMIT;




