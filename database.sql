-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 25, 2025 lúc 07:36 AM
-- Phiên bản máy phục vụ: 10.6.24-MariaDB-log
-- Phiên bản PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dihoast2_kimloancake`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `variant_id`, `quantity`, `note`, `created_at`, `updated_at`) VALUES
(13, 30, 1, NULL, 1, NULL, '2025-11-24 12:37:23', '2025-11-24 12:37:23'),
(14, 31, 1, NULL, 1, NULL, '2025-11-24 12:37:23', '2025-11-24 12:37:23'),
(15, 31, 9, NULL, 2, NULL, '2025-11-24 12:37:26', '2025-11-24 12:37:26'),
(19, 36, 10, NULL, 1, NULL, '2025-11-25 00:12:31', '2025-11-25 00:12:31'),
(20, 37, 10, NULL, 1, NULL, '2025-11-25 00:12:31', '2025-11-25 00:12:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('pending','answered','closed') NOT NULL DEFAULT 'pending',
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','amount') NOT NULL,
  `discount_value` decimal(15,2) NOT NULL,
  `max_discount_value` decimal(15,2) DEFAULT NULL,
  `minimum_order_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `issued_quantity` int(11) NOT NULL DEFAULT 0,
  `used_quantity` int(11) NOT NULL DEFAULT 0,
  `max_usage_per_user` int(11) NOT NULL DEFAULT 1,
  `applies_to_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `applies_to_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'active',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `title`, `description`, `discount_type`, `discount_value`, `max_discount_value`, `minimum_order_value`, `issued_quantity`, `used_quantity`, `max_usage_per_user`, `applies_to_product_id`, `applies_to_category_id`, `starts_at`, `ends_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME10', 'Giảm 10% cho đơn đầu tiên', 'Áp dụng cho khách hàng mới, tối đa giảm 50.000đ.', 'percentage', 10.00, 50000.00, 200000.00, 500, 0, 1, NULL, NULL, '2025-09-30 17:00:00', '2026-01-31 16:59:59', 'active', 1, '2025-09-30 17:00:00', '2025-09-30 17:00:00'),
(2, 'TRAMISU50K', 'Giảm 50K cho Tiramisu Cà Phê', 'Chỉ áp dụng cho sản phẩm Tiramisu Cà Phê.', 'amount', 50000.00, 50000.00, 150000.00, 200, 0, 2, 12, NULL, '2025-10-31 17:00:00', '2026-02-28 16:59:59', 'active', 1, '2025-10-31 17:00:00', '2025-10-31 17:00:00'),
(3, 'BANHKEM15', 'Giảm 15% cho danh mục Bánh Kem', 'Áp dụng cho mọi sản phẩm trong danh mục Bánh Kem.', 'percentage', 15.00, 80000.00, 250000.00, 300, 0, 1, NULL, 2, '2025-11-14 17:00:00', '2026-03-31 16:59:59', 'active', 1, '2025-11-14 17:00:00', '2025-11-14 17:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_phone` varchar(15) NOT NULL,
  `receiver_email` varchar(100) DEFAULT NULL,
  `district_code` varchar(20) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `ward_code` varchar(20) NOT NULL,
  `ward_name` varchar(100) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `user_id`, `label`, `receiver_name`, `receiver_phone`, `receiver_email`, `district_code`, `district_name`, `ward_code`, `ward_name`, `address_line`, `note`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 4, 'Nhà riêng', 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '79', 'Thành phố Hồ Chí Minh', '27031', 'Phường Tân Phú', '173/100 Khuông Việt', 'Giao sau 17h', 1, '2025-11-24 05:33:23', '2025-11-24 05:33:23'),
(2, 6, 'Truong Van Phu Khanh', 'Truong Van Phu Khanh 2', '0949125111', 'ppkhanh1910404@gmail.com', '79', 'Thành phố Hồ Chí Minh', '25843', 'Phường Tây Nam', 'H.No. 27, Block A, Lajpat Nagar II', 'Lajpat Nagar II', 0, '2025-11-24 13:09:56', '2025-11-24 13:09:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_receipts`
--

CREATE TABLE `inventory_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_code` varchar(20) NOT NULL,
  `reference_code` varchar(50) DEFAULT NULL,
  `supplier_name` varchar(255) NOT NULL DEFAULT 'Nội bộ',
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_receipts`
--

INSERT INTO `inventory_receipts` (`id`, `receipt_code`, `reference_code`, `supplier_name`, `total_cost`, `note`, `status`, `created_by`, `approved_by`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(6, 'PN25112414233062', NULL, 'Nội bộ', 0.00, NULL, 'pending', 4, NULL, NULL, '2025-11-24 07:23:30', '2025-11-24 07:23:30'),
(7, 'PN25112415000243', NULL, 'Nội bộ', 0.00, NULL, 'pending', 4, NULL, NULL, '2025-11-24 08:00:02', '2025-11-24 08:00:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_receipt_items`
--

CREATE TABLE `inventory_receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `line_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_receipt_items`
--

INSERT INTO `inventory_receipt_items` (`id`, `receipt_id`, `product_id`, `variant_id`, `quantity`, `unit_cost`, `line_total`, `created_at`, `updated_at`) VALUES
(3, 6, 46, NULL, 10, 15000.00, 150000.00, '2025-11-24 07:23:30', '2025-11-24 07:23:30'),
(4, 7, 7, NULL, 5, 35000.00, 175000.00, '2025-11-24 08:00:02', '2025-11-24 08:00:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_04_073622_create_sepay_table', 1),
(2, '2025_11_23_000001_make_sale_price_nullable', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `link`, `read_at`, `created_at`, `updated_at`) VALUES
(17, 4, 'Đơn hàng #KL25112414085459 cập nhật', 'Đơn hàng của bạn đã được cập nhật.', 'http://127.0.0.1:8000/orders/KL25112414085459', NULL, '2025-11-24 07:42:42', '2025-11-24 07:42:42'),
(18, 4, 'Đơn hàng #KL25112414085459 cập nhật', 'Đơn hàng đã giao thành công.', 'http://127.0.0.1:8000/orders/KL25112414085459', NULL, '2025-11-24 07:42:50', '2025-11-24 07:42:50'),
(19, 1, 'Đơn hàng POS mới #POS25112418115862', 'Nhân viên vừa tạo đơn tại quầy trị giá 390.000 ₫.', 'http://127.0.0.1:8000/admin/orders/35', NULL, '2025-11-24 11:11:58', '2025-11-24 11:11:58'),
(20, 1, 'Đơn hàng POS mới #POS25112418134856', 'Nhân viên vừa tạo đơn tại quầy trị giá 390.000 ₫.', 'http://127.0.0.1:8000/admin/orders/36', NULL, '2025-11-24 11:13:49', '2025-11-24 11:13:49'),
(21, 1, 'Đơn hàng POS mới #POS25112507154886', 'Nhân viên vừa tạo đơn tại quầy trị giá 70.000 ₫.', 'https://luukiet.id.vn/admin/orders/42', NULL, '2025-11-25 00:15:51', '2025-11-25 00:15:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_provider` varchar(50) NOT NULL DEFAULT 'none',
  `paid_at` timestamp NULL DEFAULT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `address_line` varchar(255) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `ward_name` varchar(100) NOT NULL,
  `customer_note` text DEFAULT NULL,
  `subtotal_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL,
  `payment_status` enum('pending','processing','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `fulfillment_status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `source_channel` enum('website','store','phone','other') NOT NULL DEFAULT 'website',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ordered_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `payment_method`, `payment_provider`, `paid_at`, `coupon_id`, `customer_name`, `customer_phone`, `customer_email`, `address_line`, `district_name`, `ward_name`, `customer_note`, `subtotal_amount`, `discount_amount`, `shipping_fee`, `grand_total`, `payment_status`, `fulfillment_status`, `source_channel`, `approved_by`, `ordered_at`, `created_at`, `updated_at`) VALUES
(30, 'KL25112412390410', 4, 'sepay', 'sepay', NULL, NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 85000.00, 0.00, 30000.00, 2000.00, 'processing', 'pending', 'website', NULL, '2025-11-24 12:39:04', '2025-11-24 05:39:04', '2025-11-24 05:39:04'),
(31, 'KL25112414085459', 4, 'sepay', 'sepay', '2025-11-24 07:18:07', NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 140000.00, 0.00, 0.00, 2000.00, 'paid', 'delivered', 'website', NULL, '2025-11-24 14:08:54', '2025-11-24 07:08:54', '2025-11-24 07:42:50'),
(32, 'KL25112417522148', 4, 'cod', 'cod', NULL, NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 825000.00, 0.00, 0.00, 825000.00, 'pending', 'pending', 'website', NULL, '2025-11-24 17:52:21', '2025-11-24 10:52:21', '2025-11-24 10:52:21'),
(33, 'KL25112417534022', 4, 'cod', 'cod', NULL, NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 390000.00, 0.00, 0.00, 390000.00, 'pending', 'pending', 'website', NULL, '2025-11-24 17:53:40', '2025-11-24 10:53:40', '2025-11-24 10:53:40'),
(34, 'KL25112417592378', 4, 'cod', 'cod', NULL, NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 780000.00, 0.00, 0.00, 780000.00, 'pending', 'pending', 'website', NULL, '2025-11-24 17:59:23', '2025-11-24 10:59:23', '2025-11-24 10:59:23'),
(35, 'POS25112418115862', NULL, 'cash', 'cash', '2025-11-24 11:11:58', NULL, 'Kiệt', '0327749747', NULL, 'Nhận tại cửa hàng Kim Loan', 'Gò Vấp', 'Phường 9', NULL, 390000.00, 0.00, 0.00, 390000.00, 'paid', 'pending', 'store', NULL, '2025-11-24 18:11:58', '2025-11-24 11:11:58', '2025-11-24 11:11:58'),
(36, 'POS25112418134856', NULL, 'sepay', 'sepay', NULL, NULL, 'Kiệt', '0327749747', 'luukie0401@gmail.com', 'Nhận tại cửa hàng Kim Loan', 'Gò Vấp', 'Phường 9', NULL, 390000.00, 0.00, 0.00, 390000.00, 'failed', 'cancelled', 'store', NULL, '2025-11-24 18:13:48', '2025-11-24 11:13:48', '2025-11-24 11:45:32'),
(37, 'KL25112420100961', 6, 'cod', 'cod', NULL, NULL, 'Truong Van Phu Khanh 2', '0949125111', 'ppkhanh1910404@gmail.com', 'H.No. 27, Block A, Lajpat Nagar II', 'Thành phố Hồ Chí Minh', 'Phường Tây Nam', '', 22000.00, 0.00, 30000.00, 52000.00, 'pending', 'pending', 'website', NULL, '2025-11-24 20:10:09', '2025-11-24 13:10:09', '2025-11-24 13:10:09'),
(38, 'KL25112420132027', 6, 'sepay', 'sepay', NULL, NULL, 'Truong Van Phu Khanh 2', '0949125111', 'ppkhanh1910404@gmail.com', 'H.No. 27, Block A, Lajpat Nagar II', 'Thành phố Hồ Chí Minh', 'Phường Tây Nam', '', 450000.00, 0.00, 0.00, 450000.00, 'processing', 'processing', 'website', NULL, '2025-11-24 20:13:20', '2025-11-24 13:13:20', '2025-11-25 00:16:59'),
(39, 'KL25112500234048', 4, 'cod', 'cod', NULL, NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 190000.00, 0.00, 0.00, 190000.00, 'pending', 'processing', 'website', NULL, '2025-11-25 00:23:40', '2025-11-24 17:23:40', '2025-11-25 00:16:49'),
(40, 'KL25112507115413', 4, 'sepay', 'sepay', '2025-11-25 00:14:35', NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 420000.00, 0.00, 0.00, 2000.00, 'paid', 'delivered', 'website', NULL, '2025-11-25 07:11:54', '2025-11-25 00:11:54', '2025-11-25 00:14:35'),
(41, 'KL25112507132219', 4, 'cod', 'cod', NULL, NULL, 'Lưu Kiệt', '0327749747', 'luukie0401@gmail.com', '173/100 Khuông Việt', 'Thành phố Hồ Chí Minh', 'Phường Tân Phú', '', 1800000.00, 0.00, 0.00, 1800000.00, 'pending', 'processing', 'website', NULL, '2025-11-25 07:13:22', '2025-11-25 00:13:22', '2025-11-25 00:16:42'),
(42, 'POS25112507154886', NULL, 'cash', 'cash', '2025-11-25 00:15:48', NULL, 'Hồ Ngọc Khánh', '0337236327', 'hokkhanh97@gmail.com', 'Nhận tại cửa hàng Kim Loan', 'Gò Vấp', 'Phường 9', NULL, 70000.00, 0.00, 0.00, 70000.00, 'paid', 'delivered', 'store', NULL, '2025-11-25 07:15:48', '2025-11-25 00:15:48', '2025-11-25 00:15:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name_snapshot` varchar(255) NOT NULL,
  `variant_name_snapshot` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `list_price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `line_total` decimal(15,2) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name_snapshot`, `variant_name_snapshot`, `quantity`, `list_price`, `sale_price`, `line_total`, `note`, `created_at`, `updated_at`) VALUES
(28, 30, 15, NULL, 'Tea Break Mini Cupcake', '', 1, 100000.00, 85000.00, 85000.00, NULL, '2025-11-24 05:39:04', '2025-11-24 05:39:04'),
(29, 31, 9, NULL, 'Bánh Mousse Chanh Dây', '', 1, 170000.00, 140000.00, 140000.00, NULL, '2025-11-24 07:08:54', '2025-11-24 07:08:54'),
(30, 32, 11, NULL, 'Bánh Mousse Dâu Kem Phô Mai', '', 1, 180000.00, 155000.00, 155000.00, NULL, '2025-11-24 10:52:22', '2025-11-24 10:52:22'),
(31, 32, 10, NULL, 'Bánh Mousse Việt Quất', '', 1, 175000.00, 145000.00, 145000.00, NULL, '2025-11-24 10:52:22', '2025-11-24 10:52:22'),
(32, 32, 3, NULL, 'Bánh Kem Dâu Tây', '', 1, 150000.00, 120000.00, 120000.00, NULL, '2025-11-24 10:52:22', '2025-11-24 10:52:22'),
(33, 32, 5, NULL, 'Bánh Kem Bắp Sữa Tươi', '', 3, 160000.00, 135000.00, 405000.00, NULL, '2025-11-24 10:52:22', '2025-11-24 10:52:22'),
(34, 33, 46, NULL, 'Bánh Tiramisu', '', 1, 390000.00, 390000.00, 390000.00, NULL, '2025-11-24 10:53:40', '2025-11-24 10:53:40'),
(35, 34, 46, NULL, 'Bánh Tiramisu', '', 2, 390000.00, 390000.00, 780000.00, NULL, '2025-11-24 10:59:23', '2025-11-24 10:59:23'),
(36, 35, 46, NULL, 'Bánh Tiramisu', 'Mặc định', 1, 390000.00, 390000.00, 390000.00, NULL, '2025-11-24 11:11:58', '2025-11-24 11:11:58'),
(37, 36, 46, NULL, 'Bánh Tiramisu', 'Mặc định', 1, 390000.00, 390000.00, 390000.00, NULL, '2025-11-24 11:13:48', '2025-11-24 11:13:48'),
(38, 37, 30, NULL, 'Combo Bánh Ngọt 22k', '', 1, 22000.00, 22000.00, 22000.00, NULL, '2025-11-24 13:10:09', '2025-11-24 13:10:09'),
(39, 38, 1, NULL, 'Bánh kem dâu tây', '', 1, 450000.00, 450000.00, 450000.00, NULL, '2025-11-24 13:13:20', '2025-11-24 13:13:20'),
(40, 39, 6, NULL, 'Bánh Ngọt Bơ Sữa', '', 2, 120000.00, 95000.00, 190000.00, NULL, '2025-11-24 17:23:40', '2025-11-24 17:23:40'),
(41, 40, 22, NULL, 'Bliss Box 12 Hộp', '', 1, 420000.00, 420000.00, 420000.00, NULL, '2025-11-25 00:11:54', '2025-11-25 00:11:54'),
(42, 41, 1, NULL, 'Bánh kem dâu tây', '', 4, 450000.00, 450000.00, 1800000.00, NULL, '2025-11-25 00:13:22', '2025-11-25 00:13:22'),
(43, 42, 7, NULL, 'Bánh Cupcake Phô Mai', 'Mặc định', 1, 80000.00, 70000.00, 70000.00, NULL, '2025-11-25 00:15:48', '2025-11-25 00:15:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status_type` enum('payment','fulfillment') NOT NULL,
  `status_value` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `method_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `method_type` enum('online','offline') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` enum('pending','successful','failed','refunded') NOT NULL,
  `reference_code` varchar(100) DEFAULT NULL,
  `channel` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `order_id`, `amount`, `method`, `status`, `reference_code`, `channel`, `created_at`, `updated_at`) VALUES
(1, 30, 2000.00, 'sepay', 'pending', NULL, 'sepay', '2025-11-24 05:39:04', '2025-11-24 05:39:04'),
(2, 31, 2000.00, 'sepay', 'successful', 'FT25328493768999', 'MBBank', '2025-11-24 07:08:54', '2025-11-24 07:18:07'),
(3, 32, 825000.00, 'cod', 'pending', NULL, 'cod', '2025-11-24 10:52:22', '2025-11-24 10:52:22'),
(4, 33, 390000.00, 'cod', 'pending', NULL, 'cod', '2025-11-24 10:53:40', '2025-11-24 10:53:40'),
(5, 34, 780000.00, 'cod', 'pending', NULL, 'cod', '2025-11-24 10:59:23', '2025-11-24 10:59:23'),
(6, 35, 390000.00, 'cash', 'successful', NULL, 'cash', '2025-11-24 11:11:58', '2025-11-24 11:11:58'),
(7, 36, 390000.00, 'sepay', 'failed', NULL, 'sepay', '2025-11-24 11:13:48', '2025-11-24 11:45:32'),
(8, 37, 52000.00, 'cod', 'pending', NULL, 'cod', '2025-11-24 13:10:09', '2025-11-24 13:10:09'),
(9, 38, 450000.00, 'sepay', 'pending', NULL, 'sepay', '2025-11-24 13:13:20', '2025-11-24 13:13:20'),
(10, 39, 190000.00, 'cod', 'pending', NULL, 'cod', '2025-11-24 17:23:40', '2025-11-24 17:23:40'),
(11, 40, 2000.00, 'sepay', 'successful', 'FT25329209494341', 'MBBank', '2025-11-25 00:11:54', '2025-11-25 00:14:35'),
(12, 41, 1800000.00, 'cod', 'pending', NULL, 'cod', '2025-11-25 00:13:22', '2025-11-25 00:13:22'),
(13, 42, 70000.00, 'cash', 'successful', NULL, 'cash', '2025-11-25 00:15:48', '2025-11-25 00:15:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `primary_category_id` bigint(20) UNSIGNED NOT NULL,
  `product_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `listed_price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `total_stock` int(11) NOT NULL DEFAULT 0,
  `unit_name` varchar(50) NOT NULL DEFAULT 'cái',
  `status` enum('active','inactive','archived') NOT NULL DEFAULT 'active',
  `show_on_homepage` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `primary_category_id`, `product_code`, `name`, `slug`, `short_description`, `description`, `listed_price`, `sale_price`, `total_stock`, `unit_name`, `status`, `show_on_homepage`, `is_featured`, `view_count`, `created_at`, `updated_at`) VALUES
(1, 2, 'CAKE001', 'Bánh kem dâu tây', 'banh-kem-dau-tay', 'Bánh kem mềm mịn phủ dâu tươi hấp dẫn.', 'Lớp kem whipping béo ngậy kết hợp mứt dâu tây, phù hợp sinh nhật và tiệc nhỏ.', 450000.00, 450000.00, 15, 'cake', 'active', 1, 1, 0, '2025-10-30 13:42:20', '2025-11-25 00:13:22'),
(3, 2, 'SPXSNJQLXW', 'Bánh Kem Dâu Tây', 'banh-kem-dau-tay-1', 'Bánh kem dâu tươi ngon, mềm mịn và vị chua nhẹ tự nhiên.', 'Bánh kem dâu tươi ngon, mềm mịn và vị chua nhẹ tự nhiên. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 150000.00, 120000.00, 52, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(4, 2, 'SPHTPOIX3W', 'Bánh Kem Socola Hạnh Nhân', 'banh-kem-socola-hanh-nhan-2', 'Bánh kem socola phủ hạnh nhân rang giòn tan.', 'Bánh kem socola phủ hạnh nhân rang giòn tan. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 180000.00, 150000.00, 60, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(5, 2, 'SPPUGSFZKT', 'Bánh Kem Bắp Sữa Tươi', 'banh-kem-bap-sua-tuoi-3', 'Ngọt dịu với vị sữa và hương thơm đặc trưng của bắp.', 'Ngọt dịu với vị sữa và hương thơm đặc trưng của bắp. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 160000.00, 135000.00, 73, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-11-03 14:12:37'),
(6, 3, 'SPHLT22NY8', 'Bánh Ngọt Bơ Sữa', 'banh-ngot-bo-sua-4', 'Bánh ngọt mềm xốp, vị bơ sữa đậm đà.', 'Bánh ngọt mềm xốp, vị bơ sữa đậm đà. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 120000.00, 95000.00, 44, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-11-24 17:23:40'),
(7, 3, 'SPAITDBLYD', 'Bánh Cupcake Phô Mai', 'banh-cupcake-pho-mai-5', 'Cupcake phô mai tan chảy, thơm nức mũi.', 'Cupcake phô mai tan chảy, thơm nức mũi. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 80000.00, 70000.00, 61, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-11-25 00:15:48'),
(8, 3, 'SPBALGWGZC', 'Bánh Muffin Socola Chip', 'banh-muffin-socola-chip-6', 'Muffin mềm ẩm, đầy ắp socola chip.', 'Muffin mềm ẩm, đầy ắp socola chip. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 95000.00, 85000.00, 51, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(9, 4, 'SPHSFYBTXC', 'Bánh Mousse Chanh Dây', 'banh-mousse-chanh-day-7', 'Mousse vị chanh dây thanh mát, ngọt nhẹ và chua dịu.', 'Mousse vị chanh dây thanh mát, ngọt nhẹ và chua dịu. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 170000.00, 140000.00, 32, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(10, 4, 'SP0VAR8BJA', 'Bánh Mousse Việt Quất', 'banh-mousse-viet-quat-8', 'Mousse việt quất tươi, vị trái cây tự nhiên.', 'Mousse việt quất tươi, vị trái cây tự nhiên. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 175000.00, 145000.00, 35, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(11, 4, 'SPTCRX0NXH', 'Bánh Mousse Dâu Kem Phô Mai', 'banh-mousse-dau-kem-pho-mai-9', 'Sự kết hợp hoàn hảo giữa kem phô mai và dâu tươi.', 'Sự kết hợp hoàn hảo giữa kem phô mai và dâu tươi. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 180000.00, 155000.00, 45, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(12, 5, 'SPZCUJKUZ7', 'Bánh Tiramisu Cà Phê', 'banh-tiramisu-ca-phe-10', 'Tiramisu truyền thống hương vị cà phê Ý đặc trưng.', 'Tiramisu truyền thống hương vị cà phê Ý đặc trưng. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 190000.00, 160000.00, 49, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(13, 5, 'SPHVATJYWC', 'Bánh Tiramisu Matcha', 'banh-tiramisu-matcha-11', 'Tiramisu trà xanh thanh mát, béo nhẹ, hấp dẫn.', 'Tiramisu trà xanh thanh mát, béo nhẹ, hấp dẫn. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 200000.00, 170000.00, 55, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(14, 5, 'SPUG3ETUEU', 'Bánh Tiramisu Dâu Tây', 'banh-tiramisu-dau-tay-12', 'Phiên bản ngọt ngào của tiramisu với dâu tươi.', 'Phiên bản ngọt ngào của tiramisu với dâu tươi. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 195000.00, 165000.00, 62, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(15, 6, 'SPNJHJ1F6V', 'Tea Break Mini Cupcake', 'tea-break-mini-cupcake-13', 'Cupcake nhỏ gọn, tiện lợi cho tiệc trà.', 'Cupcake nhỏ gọn, tiện lợi cho tiệc trà. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 100000.00, 85000.00, 55, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(16, 6, 'SPQH0FWMLL', 'Tea Break Bánh Mặn Phô Mai', 'tea-break-banh-man-pho-mai-14', 'Bánh mặn giòn rụm, vị phô mai tan chảy.', 'Bánh mặn giòn rụm, vị phô mai tan chảy. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 95000.00, 82000.00, 21, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(17, 6, 'SPDJXH1SLK', 'Tea Break Bánh Trà Xanh', 'tea-break-banh-tra-xanh-15', 'Hương vị trà xanh đậm đà, nhẹ nhàng tinh tế.', 'Hương vị trà xanh đậm đà, nhẹ nhàng tinh tế. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 98000.00, 87000.00, 36, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(18, 7, 'SPKVO9M7QV', 'Bánh Sinh Nhật Dâu Kem', 'banh-sinh-nhat-dau-kem-16', 'Bánh sinh nhật vị dâu kem tươi, trang trí đẹp mắt.', 'Bánh sinh nhật vị dâu kem tươi, trang trí đẹp mắt. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 220000.00, 190000.00, 65, 'cái', 'active', 1, 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(19, 7, 'SPQXGHLNC7', 'Bánh Sinh Nhật Socola', 'banh-sinh-nhat-socola-17', 'Món bánh sinh nhật truyền thống với lớp phủ socola đậm.', 'Món bánh sinh nhật truyền thống với lớp phủ socola đậm. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 250000.00, 210000.00, 55, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(20, 7, 'SP6RA2O27Q', 'Bánh Sinh Nhật Trái Cây Tươi', 'banh-sinh-nhat-trai-cay-tuoi-18', 'Bánh trang trí bằng nhiều loại trái cây tươi.', 'Bánh trang trí bằng nhiều loại trái cây tươi. Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.', 260000.00, 220000.00, 23, 'cái', 'active', 1, 0, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(21, 2, 'SP48MQFTJAV', 'Bánh Kem Sữa Tươi', 'banh-kem-sua-tuoi', 'Bánh kem sữa tươi vẽ hình là sự kết hợp tinh tế giữa hương vị ngọt ngào của kem sữa tươi và nghệ thuật vẽ hình.', 'Bánh kem sữa tươi vẽ hình là sự kết hợp tinh tế giữa hương vị ngọt ngào của kem sữa tươi và nghệ thuật vẽ hình. Mỗi chiếc bánh được tạo hình một cách tỉ mỉ và sáng tạo, từ những hình ảnh đơn giản như hoa, trái cây đến những biểu tượng phổ biến như nhân vật hot trend, logo công ty. Với sự sáng tạo và tay nghề của các đầu bếp, bánh kem sữa tươi vẽ hình không chỉ là một món trang trí bắt mắt mà còn là một tác phẩm nghệ thuật đầy ấn tượng và độc đáo trong mỗi dịp đặc biệt', 340000.00, 340000.00, 10, 'cái', 'active', 1, 0, 0, '2025-11-23 10:25:02', '2025-11-23 10:25:02'),
(22, 2, 'SPR90BNKYJR', 'Bliss Box 12 Hộp', 'bliss-box-12-hop', 'Với 12 sắc màu rực rỡ, BlissBox từ Tiệm Bánh Kim Loan mang đến hương vị ngọt ngào, thanh mát từ những lớp mousse trái cây tươi mọng như việt quất, xoài, và chanh dây.', 'Với 12 sắc màu rực rỡ, BlissBox từ Tiệm Bánh Kim Loan mang đến hương vị ngọt ngào, thanh mát từ những lớp mousse trái cây tươi mọng như việt quất, xoài, và chanh dây. Kết hợp cùng tiramisu đậm đà và mousse chocolate quyến rũ, mỗi ô nhỏ tạo nên sự đa dạng hoàn hảo, đem lại cảm giác hạnh phúc, sảng khoái và trọn vẹn trong từng miếng thưởng thức.', 420000.00, 420000.00, 9, 'hộp', 'active', 1, 0, 0, '2025-11-23 10:27:01', '2025-11-25 00:11:54'),
(23, 2, 'SPU21YESD5I', 'Rau Câu Bánh Flan', 'rau-cau-banh-flan', 'Bánh kem rau câu flan tại Tiệm Bánh Kim Loan là sự kết hợp hoàn hảo giữa lớp rau câu mát lạnh và nhân flan mềm mịn, thơm ngon.', 'Bánh kem rau câu flan tại Tiệm Bánh Kim Loan là sự kết hợp hoàn hảo giữa lớp rau câu mát lạnh và nhân flan mềm mịn, thơm ngon. Với hương vị ngọt dịu, thanh mát cùng màu sắc tươi sáng, bánh kem rau câu flan không chỉ hấp dẫn vị giác mà còn tạo điểm nhấn đẹp mắt cho mọi bữa tiệc. Đây là lựa chọn lý tưởng cho những ai yêu thích sự mới lạ và tinh tế trong từng miếng bánh', 360000.00, 360000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:29:53', '2025-11-23 10:33:14'),
(24, 4, 'SPPKJNPN8VA', 'Mousse Dưa Lưới', 'mousse-dua-luoi', 'Bánh mousse dưa lưới nổi bật với hương vị ngọt thanh của dưa lưới, hòa quyện với lớp yagout béo ngậy.', 'Bánh mousse dưa lưới nổi bật với hương vị ngọt thanh của dưa lưới, hòa quyện với lớp yagout béo ngậy. Màu xanh dịu mát của dưa lưới không chỉ làm nổi bật vẻ ngoài tươi mới của bánh mà còn tạo cảm giác thư thái khi thưởng thức. Đặc biệt, mousse dưa lưới mang đến một trải nghiệm vị giác vừa tinh tế vừa sảng khoái, thích hợp cho cả người lớn và trẻ nhỏ.', 490000.00, 490000.00, 4, 'cái', 'active', 1, 0, 0, '2025-11-23 10:32:46', '2025-11-23 10:33:04'),
(25, 4, 'SPWDJRA47ZJ', 'Mousse Xoài', 'mousse-xoai', 'Với sắc vàng tươi tắn, mousse xoài không chỉ bắt mắt mà còn mang đến hương vị ngọt dịu, thanh mát từ những miếng xoài tươi chín mọng.', 'Với sắc vàng tươi tắn, mousse xoài không chỉ bắt mắt mà còn mang đến hương vị ngọt dịu, thanh mát từ những miếng xoài tươi chín mọng. Lớp mousse mềm mịn kết hợp cùng xoài tạo ra một sự hòa quyện hoàn hảo, mang lại cảm giác nhẹ nhàng và sảng khoái trong mỗi lần thưởng thức.', 490000.00, 490000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:35:01', '2025-11-23 10:35:01'),
(26, 4, 'SPW3O2CUL8K', 'Mousse Bơ', 'mousse-bo', 'Bơ tươi được chọn lọc kỹ lưỡng, mang đến một hương vị đậm đà và ngọt ngào mà không bị ngấy. Kết cấu mềm mịn của mousse bơ giúp bánh tan chảy ngay khi chạm vào đầu lưỡi, để lại dư vị dễ chịu và phong phú.', 'Bơ tươi được chọn lọc kỹ lưỡng, mang đến một hương vị đậm đà và ngọt ngào mà không bị ngấy. Kết cấu mềm mịn của mousse bơ giúp bánh tan chảy ngay khi chạm vào đầu lưỡi, để lại dư vị dễ chịu và phong phú. Đây là một lựa chọn lý tưởng cho những buổi tiệc hay những dịp đặc biệt khi bạn muốn mang đến một món tráng miệng vừa ngon miệng vừa giàu dinh dưỡng.', 400000.00, 400000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:38:09', '2025-11-23 10:38:09'),
(27, 4, 'SP4MEKMW5EI', 'Mousse Táo Xanh', 'mousse-tao-xanh', 'Với lớp mousse mềm mịn kết hợp cùng táo xanh tươi mát, mỗi miếng bánh mang đến một cảm giác sảng khoái và thỏa mãn.', 'Với lớp mousse mềm mịn kết hợp cùng táo xanh tươi mát, mỗi miếng bánh mang đến một cảm giác sảng khoái và thỏa mãn. Điểm đặc biệt của bánh là lớp trang trí bằng trái cây tươi như nho, dâu, và việt quất, không chỉ làm nổi bật vẻ đẹp mà còn tăng thêm sự phong phú về hương vị. Mousse táo xanh thực sự là một bản giao hưởng ngọt ngào dành cho những ai yêu thích trải nghiệm ẩm thực đa dạng.', 490000.00, 490000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:39:14', '2025-11-23 10:39:14'),
(28, 3, 'SPN249L68FQ', 'Combo Bánh Ngọt 35k', 'combo-banh-ngot-cb38', 'Combo bánh ngọt với 60 món bánh teabreak đa dạng và hấp dẫn.', 'Với combo bánh ngọt với 60 món bánh teabreak đa dạng và hấp dẫn, quý khách có thể thỏa sức lựa chọn những hương vị yêu thích để chiêu đãi và làm quà cho khách mời. Từ bánh ngọt, bánh mặn, đến các loại bánh cookie, macaron, tất cả đều được chế biến từ nguyên liệu tươi ngon, đảm bảo vệ sinh an toàn thực phẩm. Đặc biệt, khi đặt số lượng lớn, quý khách sẽ nhận được ưu đãi hấp dẫn, giúp tiết kiệm chi phí mà vẫn đảm bảo chất lượng dịch vụ tốt nhất', 35000.00, 35000.00, 3, 'combo', 'active', 1, 0, 0, '2025-11-23 10:44:01', '2025-11-24 05:28:51'),
(29, 3, 'SP4J9HKOJ8E', 'Combo Bánh Ngọt 29k', 'combo-banh-ngot-cb35', 'Combo bánh ngọt với 60 món bánh teabreak đa dạng và hấp dẫn.', 'Với combo bánh ngọt với 60 món bánh teabreak đa dạng và hấp dẫn, quý khách có thể thỏa sức lựa chọn những hương vị yêu thích để chiêu đãi và làm quà cho khách mời. Từ bánh ngọt, bánh mặn, đến các loại bánh cookie, macaron, tất cả đều được chế biến từ nguyên liệu tươi ngon, đảm bảo vệ sinh an toàn thực phẩm. Đặc biệt, khi đặt số lượng lớn, quý khách sẽ nhận được ưu đãi hấp dẫn, giúp tiết kiệm chi phí mà vẫn đảm bảo chất lượng dịch vụ tốt nhất', 29000.00, 29000.00, 3, 'combo', 'active', 1, 0, 0, '2025-11-23 10:45:48', '2025-11-24 05:28:18'),
(30, 3, 'SPQ3J85X7XM', 'Combo Bánh Ngọt 22k', 'combo-banh-ngot-cb24', 'Combo bánh ngọt với 60 món bánh teabreak đa dạng và hấp dẫn.', 'Với combo bánh ngọt với 60 món bánh teabreak đa dạng và hấp dẫn, quý khách có thể thỏa sức lựa chọn những hương vị yêu thích để chiêu đãi và làm quà cho khách mời. Từ bánh ngọt, bánh mặn, đến các loại bánh cookie, macaron, tất cả đều được chế biến từ nguyên liệu tươi ngon, đảm bảo vệ sinh an toàn thực phẩm. Đặc biệt, khi đặt số lượng lớn, quý khách sẽ nhận được ưu đãi hấp dẫn, giúp tiết kiệm chi phí mà vẫn đảm bảo chất lượng dịch vụ tốt nhất', 22000.00, 22000.00, 1, 'combo', 'active', 1, 0, 0, '2025-11-23 10:47:07', '2025-11-24 13:10:09'),
(31, 7, 'SP8P7ASBA9K', 'Bánh Kem Hoa', 'banh-kem-hoa-bkh-30143', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 340000.00, 340000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:49:23', '2025-11-24 05:27:08'),
(32, 7, 'SPK2ARHK5A0', 'Bánh Kem Hoa', 'banh-kem-hoa-bkh-30166', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 340000.00, 340000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:50:32', '2025-11-24 05:26:40'),
(33, 7, 'SP7AA0W102R', 'Bánh Kem Hoa Hồng', 'banh-kem-hoa-bkh-30198', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 360000.00, 360000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:51:28', '2025-11-24 05:24:10'),
(34, 7, 'SPEXXXDYL4R', 'Bánh Kem Hoa', 'banh-kem-hoa-bkh-30196', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 360000.00, 360000.00, 2, 'cái', 'active', 1, 0, 0, '2025-11-23 10:52:35', '2025-11-24 05:26:07'),
(35, 7, 'SP7FM4L90BK', 'Bánh Kem Hoa', 'banh-kem-hoa-bkh-30214', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 340000.00, 340000.00, 3, 'cái', 'active', 1, 0, 0, '2025-11-23 10:54:11', '2025-11-24 05:26:15'),
(36, 7, 'SPEHQ6647KE', 'Bánh Kem Hoa Sen', 'banh-kem-hoa-bkh-30195', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 340000.00, 340000.00, 4, 'cái', 'active', 1, 0, 0, '2025-11-23 10:55:13', '2025-11-24 05:27:21'),
(37, 7, 'SPVZAP8HB24', 'Bánh Kem Nơ', 'banh-kem-no', 'Bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật.', 'Tại tiệm bánh Kim Loan, những chiếc bánh kem hoa được tạo nên bởi bàn tay khéo léo của người thợ làm bánh, biến những bông hoa kem thành tác phẩm nghệ thuật. Từ hoa hồng rực rỡ, hoa ly thanh tao đến những bông hoa nhỏ xinh xắn, mỗi chiếc bánh đều mang một vẻ đẹp riêng, như một lời chúc ngọt ngào và lãng mạn. Hương vị ngọt ngào, mềm mịn hòa quyện với hương thơm dịu nhẹ của hoa, khiến bạn không thể cưỡng lại sự hấp dẫn của những chiếc bánh kem hoa tại Kim Loan.', 340000.00, 340000.00, 3, 'cái', 'active', 1, 0, 0, '2025-11-23 10:56:33', '2025-11-23 10:56:33'),
(38, 5, 'SP27EAZVS74', 'Bánh Tiramisu dâu', 'banh-tiramisu-bkt-83413', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh.', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh. Mỗi chiếc bánh không chỉ đẹp mắt mà còn tạo cảm giác hài hòa, giúp khách hàng thưởng thức hương vị truyền thống nhưng lại đầy mới mẻ.', 390000.00, NULL, 4, 'cái', 'active', 1, 0, 0, '2025-11-23 10:58:19', '2025-11-24 05:23:22'),
(39, 5, 'SPV3FSU34ER', 'Bánh Tiramisu táo', 'banh-tiramisu-bkt-83405', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh.', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh. Mỗi chiếc bánh không chỉ đẹp mắt mà còn tạo cảm giác hài hòa, giúp khách hàng thưởng thức hương vị truyền thống nhưng lại đầy mới mẻ.', 260000.00, 260000.00, 4, 'cái', 'active', 1, 0, 0, '2025-11-23 10:59:51', '2025-11-24 05:22:07'),
(40, 6, 'SP5WBWI5UUY', 'Donut', 'donut', 'Bánh được tạo hình trông như chiếc nhẫn với dạng hình tròn và lỗ nhỏ ở giữa.', 'Bánh được tạo hình trông như chiếc nhẫn với dạng hình tròn và lỗ nhỏ ở giữa. Bên ngoài bánh được phủ lớp kem cùng với phần các hạt cốm/đường bột màu sắc bắt mắt.', 30000.00, NULL, 5, 'cái', 'active', 1, 0, 0, '2025-11-23 11:03:49', '2025-11-24 05:23:34'),
(41, 6, 'SPU7YK822K7', 'Panna cotta', 'panna-cotta', 'Đây là món tráng miệng truyền thống của Ý với kết cấu mềm mịn, mát lạnh.', 'Đây là món tráng miệng truyền thống của Ý với kết cấu mềm mịn, mát lạnh. Bánh được làm từ kem tươi, đường và gelatin, thường ăn kèm với sốt trái cây hoặc caramen.', 30000.00, 30000.00, 5, 'cái', 'active', 1, 1, 0, '2025-11-23 11:05:12', '2025-11-24 07:30:12'),
(42, 6, 'SPMPUG0MKM8', 'Fruit Tart', 'fruit-tart', 'Sự kết hợp tuyệt vời giữa vị ngọt của các loại trái cây và phần bánh nền béo ngậy “đốn tim” thực khách.', 'Sự kết hợp tuyệt vời giữa vị ngọt của các loại trái cây và phần bánh nền béo ngậy “đốn tim” thực khách.', 32000.00, 32000.00, 5, 'cái', 'active', 1, 0, 0, '2025-11-23 11:06:28', '2025-11-23 11:06:28'),
(43, 5, 'SPVI1C5WSRN', 'Bánh Tiramisu trái cây', 'banh-tiramisu-trai-cay', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh.', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh. Mỗi chiếc bánh không chỉ đẹp mắt mà còn tạo cảm giác hài hòa, giúp khách hàng thưởng thức hương vị truyền thống nhưng lại đầy mới mẻ.', 390000.00, NULL, 3, 'sản phẩm', 'active', 1, 0, 0, '2025-11-24 05:31:57', '2025-11-24 05:31:57'),
(44, 5, 'SPK4NPFXXSX', 'Bánh Tiramisu Nho', 'banh-tiramisu-nho', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh.', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh. Mỗi chiếc bánh không chỉ đẹp mắt mà còn tạo cảm giác hài hòa, giúp khách hàng thưởng thức hương vị truyền thống nhưng lại đầy mới mẻ.', 390000.00, NULL, 4, 'sản phẩm', 'active', 1, 0, 0, '2025-11-24 05:34:15', '2025-11-24 05:34:15'),
(45, 5, 'SPDPSIOIYDT', 'Bánh Tiramisu Socola', 'banh-tiramisu-socola', 'Tiramisu là một món tráng miệng ngọt ngào với hương vị đậm đà của cà phê, được coi là biểu tượng của ẩm thực Ý.', 'Tiramisu là một món tráng miệng ngọt ngào với hương vị đậm đà của cà phê, được coi là biểu tượng của ẩm thực Ý. Món này thường được chế biến từ các lớp bánh Savoiardi, được ngâm trong cà phê và xen kẽ với hỗn hợp trứng, đường và kem phô mai mascarpone bông. Cuối cùng, bột cacao thường được rắc lên trên cùng để tạo điểm nhấn hấp dẫn trên mỗi chiếc bánh', 390000.00, NULL, 3, 'sản phẩm', 'active', 1, 0, 0, '2025-11-24 05:36:01', '2025-11-24 05:36:01'),
(46, 5, 'SP7SSMOPKS4', 'Bánh Tiramisu', 'banh-tiramisu', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh.', 'Bánh Tiramisu tại Tiệm Bánh Kim Loan mang đến một trải nghiệm độc đáo với sự hòa quyện giữa vị ngọt nhẹ nhàng và lớp trái cây tươi mát trang trí trên mặt bánh. Mỗi chiếc bánh không chỉ đẹp mắt mà còn tạo cảm giác hài hòa, giúp khách hàng thưởng thức hương vị truyền thống nhưng lại đầy mới mẻ.', 390000.00, NULL, 10, 'sản phẩm', 'active', 1, 0, 0, '2025-11-24 05:37:55', '2025-11-24 11:45:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `slug`, `image_url`, `parent_id`, `short_description`, `display_order`, `is_visible`, `created_at`, `updated_at`) VALUES
(2, 'Bánh Kem', 'banh-kem', 'categories/SpLWwjZB2xb0KNJdfkJz5NQ0G0tSG6msMAIk2n3r.png', NULL, 'Bánh kem mềm mịn, béo ngậy với lớp trang trí tinh tế, phù hợp cho sinh nhật, kỷ niệm hay các dịp đặc biệt. Là lựa chọn hoàn hảo để gửi gắm lời chúc ngọt ngào.', 0, 1, '2025-10-31 21:07:53', '2025-10-31 21:07:53'),
(3, 'Bánh Ngọt', 'banh-ngot', 'categories/MCGjfZU4G4QxijZ9fLResrQHRLPkwbGBPA0TA5iF.jpg', NULL, 'Những chiếc bánh ngọt nhỏ xinh, hương vị phong phú từ socola, vani đến matcha. Thích hợp cho bữa sáng, trà chiều hoặc món tráng miệng nhẹ nhàng.', 0, 1, '2025-10-31 21:08:14', '2025-10-31 21:08:14'),
(4, 'Bánh Mousse Trái Cây', 'banh-mousse-trai-cay', 'categories/ZvThyrKDg81ks3VQ3pPUyqpCWuTEuQn2ttWFqEKv.jpg', NULL, 'Bánh mousse trái cây thanh mát, mềm mịn, hòa quyện vị ngọt béo và chua nhẹ tự nhiên từ dâu, xoài, chanh dây. Mang đến cảm giác tươi mới, nhẹ nhàng.', 0, 1, '2025-10-31 21:08:48', '2025-10-31 21:08:48'),
(5, 'Bánh Tiramisu', 'banh-tiramisu', 'categories/Lo7nO5UDvJPkjk3xRUS29rNhfEoruHg2CMRy44LT.jpg', NULL, 'Tiramisu mang hương vị Ý đặc trưng với vị cà phê đậm, kem mascarpone béo ngậy và lớp cacao phủ nhẹ, mang lại trải nghiệm ngọt ngào và tinh tế.', 0, 1, '2025-10-31 21:09:07', '2025-11-24 10:31:02'),
(6, 'Tea Break', 'tea-break', 'categories/IaJE7wpyjs3KdZ7DNKSOmTiinUNKrYO0zY0TUjcE.jpg', NULL, 'Set bánh Tea Break tiện lợi cho hội nghị, sự kiện, gồm bánh mặn và ngọt đa dạng. Hương vị hài hòa, trình bày sang trọng, thích hợp cho tiệc nhẹ chuyên nghiệp.', 0, 1, '2025-10-31 21:09:25', '2025-10-31 21:09:25'),
(7, 'Bánh Sinh Nhật', 'banh-sinh-nhat', 'categories/1yyCeMs7pagcDF4OtSapUCFb62GD2EnvQBhUyaxP.jpg', NULL, 'Bánh sinh nhật đẹp mắt, đa dạng kiểu dáng và hương vị. Được trang trí thủ công, mang ý nghĩa đặc biệt cho ngày vui của bạn và người thân.', 0, 1, '2025-10-31 21:09:44', '2025-10-31 21:09:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `alt_text`, `is_primary`, `display_order`, `created_at`, `updated_at`) VALUES
(4, 1, 'https://th.bing.com/th/id/OIP.TEKMSSzNGtl7dbOHT9CLyQHaD-?w=288&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh kem dâu tây', 1, 0, '2025-10-30 13:42:20', '2025-11-02 20:01:43'),
(5, 3, 'https://th.bing.com/th/id/OIP.-9pWbRINvp2vofeGxB_aDgHaFc?w=234&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Kem Dâu Tây', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(6, 4, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-sinh-nhat-socola-24-4808.jpg', 'Bánh Kem Socola Hạnh Nhân', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(7, 5, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-kem-bap-1-9130.jpg', 'Bánh Kem Bắp Sữa Tươi', 1, 0, '2025-10-31 21:50:57', '2025-11-03 14:12:37'),
(8, 6, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-mousse-bo-5831.jpg', 'Bánh Ngọt Bơ Sữa', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(9, 7, 'https://th.bing.com/th/id/OIP.524ed0KHqPXam_OvsAUbswHaE8?w=260&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Cupcake Phô Mai', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(10, 8, 'https://th.bing.com/th/id/OIP.z3qGQwMwf36S8PWc3slBKgHaHa?w=172&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Muffin Socola Chip', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(11, 9, 'https://bizweb.dktcdn.net/100/004/714/articles/banh-mousse-chanh-leo.png?v=1654772212810', 'Bánh Mousse Chanh Dây', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(12, 10, 'https://bizweb.dktcdn.net/thumb/grande/100/004/714/products/set-ba-nh-mousse-tra-i-ca-y-2.jpg?v=1710173916867', 'Bánh Mousse Việt Quất', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(13, 11, 'https://th.bing.com/th/id/OIP.fWHnND1Lx629jBvjrrasdQHaHa?w=184&h=184&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Mousse Dâu Kem Phô Mai', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(14, 12, 'https://th.bing.com/th/id/OIP.evuk_bE8IX2oan-e4EuFwgHaEJ?w=324&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Tiramisu Cà Phê', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(15, 13, 'https://th.bing.com/th/id/OIP.M-eZEoauH1938MO5SKYYjgHaE8?w=292&h=194&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Tiramisu Matcha', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(16, 14, 'https://banhkemthithi.com/uploads/source/product/banh-teramisu/7.png', 'Bánh Tiramisu Dâu Tây', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(17, 15, 'https://anmedia.vn/wp-content/uploads/2024/09/banh-cupcake-nho-tiec-tea-break.webp', 'Tea Break Mini Cupcake', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(18, 16, 'https://traicayvuongtron.vn/resources/cache/600x314x1/WEBSITE%202023/menu/menubangiaodi/banhteabreak/B%C3%A1nh%20m%C3%AC%20ph%C3%B4%20mai%20ho%C3%A0ng%20kim/banh-teabreak-banh-mi-pho-mai-hoang-kim%20(1).jpg.webp', 'Tea Break Bánh Mặn Phô Mai', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(19, 17, 'https://th.bing.com/th/id/OIP.wDoru3IsgB8KIuRySpchRAHaHa?w=176&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Tea Break Bánh Trà Xanh', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(20, 18, 'https://th.bing.com/th/id/OIP.gPL2csheiZe6DW14vfaSvwHaHa?w=200&h=200&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Sinh Nhật Dâu Kem', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(21, 19, 'https://th.bing.com/th/id/OIP.DqW3G7qTxXI0bvBIj_djvQHaHa?w=257&h=193&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Sinh Nhật Socola', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(22, 20, 'https://th.bing.com/th/id/OIP.u-x1eRHS_zOAdL-1TywiuwHaHa?w=183&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Bánh Sinh Nhật Trái Cây Tươi', 1, 0, '2025-10-31 21:50:57', '2025-10-31 21:50:57'),
(23, 21, 'https://tse4.mm.bing.net/th/id/OIP.zJI9uXBNwtT2K73Je5sLmAHaHj?w=728&h=743&rs=1&pid=ImgDetMain&o=7&rm=3', 'Bánh Kem Sữa Tươi Vẽ Hình', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(24, 22, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/z724692100930565869c470086cc5d551e8268f56cf6f9-5095.jpg', 'Bliss Box 12 Hộp', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(25, 23, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-sinh-nhat-rau-cau-flan-4-4469.jpg', 'Rau Câu Bánh Flan', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(26, 24, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-mousse-dua-luoi-1-6003.jpg', 'Mousse Dưa Lưới', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(27, 25, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-mousse-xoai-9754.jpg', 'Mousse Xoài', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(28, 26, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-mousse-bo-5831.jpg', 'Mousse Bơ', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(29, 27, 'https://tiembanhkimloan.com/watermark/product/285x285x1/upload/product/banh-mousse-tao-xanh-4-5367.jpg', 'Mousse Táo Xanh', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(44, 42, 'https://th.bing.com/th/id/OIP.rMvEO-ql3fYWqHWVaXJqRQHaE7?w=248&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 'Fruit Tart', 1, 0, '2025-11-24 04:57:07', '2025-11-24 04:57:07'),
(45, 37, 'storage/products/RxejaOgghnfDcohshhDymlzoJpxUSWsbBEB9CThP.jpg', 'Bánh Kem Nơ', 1, 0, '2025-11-24 05:22:53', '2025-11-24 05:22:53'),
(47, 38, 'storage/products/5Az42ZFO2IthIXCyPjcOCol4M5WVEgu830PMqui0.jpg', 'Bánh Tiramisu dâu', 1, 0, '2025-11-24 05:23:22', '2025-11-24 05:23:22'),
(48, 40, 'storage/products/b1xf0RnU8LdvijNdPfJ0Aeh5BMTSEO3m51JLYUkt.webp', 'Donut', 1, 0, '2025-11-24 05:23:50', '2025-11-24 05:23:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_name` varchar(100) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `sku`, `price`, `sale_price`, `stock_quantity`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Size 18cm', 'CAKE001-18', 450000.00, 450000.00, 7, 'active', 1, '2025-10-30 13:42:20', '2025-10-31 20:02:28'),
(2, 43, 'Mặc định', NULL, 390000.00, NULL, 3, 'active', 1, '2025-11-24 05:31:57', '2025-11-24 05:31:57'),
(3, 44, 'Mặc định', NULL, 390000.00, NULL, 4, 'active', 1, '2025-11-24 05:34:15', '2025-11-24 05:34:15'),
(4, 45, 'Mặc định', NULL, 390000.00, NULL, 3, 'active', 1, '2025-11-24 05:36:01', '2025-11-24 05:36:01'),
(5, 46, 'Mặc định', NULL, 390000.00, NULL, 3, 'active', 1, '2025-11-24 05:37:55', '2025-11-24 05:37:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `discount_type` enum('percentage','amount') NOT NULL,
  `discount_value` decimal(15,2) NOT NULL,
  `max_discount_value` decimal(15,2) DEFAULT NULL,
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'active',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `slug`, `description`, `banner_url`, `start_date`, `end_date`, `discount_type`, `discount_value`, `max_discount_value`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Khuyến mãi NOEL 2025', 'NOEL2025', NULL, 'images/promotion_herobackground.jpg', '2025-11-24 22:44:00', '2025-12-31 22:45:00', 'percentage', 5.00, 50000.00, 'active', 4, '2025-11-23 08:45:13', '2025-11-23 08:45:13'),
(2, 'Ưu đãi cuối tuần: Giảm 5% Bánh Kem', 'BANHKEM5', NULL, 'images/promotions.jpg', '2025-11-23 22:46:00', '2025-11-30 22:46:00', 'percentage', 5.00, 50000.00, 'active', 4, '2025-11-23 08:46:53', '2025-11-23 08:46:53'),
(3, 'Freeship cho đơn hàng Tiramisu', 'FSTIRAMISU', NULL, 'images/promotions.jpg', '2025-11-23 22:48:00', '2025-11-27 22:48:00', 'amount', 20000.00, 10000.00, 'active', 4, '2025-11-23 08:48:53', '2025-11-23 08:48:53'),
(4, 'Sale Khủng: Giảm 30K cho Bánh Muffin', 'MF30K', NULL, 'images/promotions.jpg', '2025-11-23 22:49:00', '2025-11-27 22:49:00', 'amount', 30000.00, 30000.00, 'active', 4, '2025-11-23 08:49:43', '2025-11-23 08:49:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sepay_transactions`
--

CREATE TABLE `sepay_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `transactionDate` varchar(255) NOT NULL,
  `accountNumber` varchar(255) NOT NULL,
  `subAccount` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL,
  `transferType` varchar(255) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `transferAmount` bigint(20) NOT NULL,
  `referenceCode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sepay_transactions`
--

INSERT INTO `sepay_transactions` (`id`, `gateway`, `transactionDate`, `accountNumber`, `subAccount`, `code`, `content`, `transferType`, `description`, `transferAmount`, `referenceCode`, `created_at`, `updated_at`) VALUES
(32109582, 'MBBank', '2025-11-24 12:40:00', '0327749747', '', '', 'IBFT HDKL25112412390410   Ma giao dich  Trace001042 Trace 001042', 'in', 'BankAPINotify IBFT HDKL25112412390410   Ma giao dich  Trace001042 Trace 001042', 2000, 'FT25328714450886', '2025-11-24 05:40:49', '2025-11-24 05:40:49'),
(32115313, 'MBBank', '2025-11-24 13:32:00', '0327749747', '', '', 'HDKL25112413324688', 'in', 'BankAPINotify HDKL25112413324688', 32000, 'FT25328000294575', '2025-11-24 06:32:58', '2025-11-24 06:32:58'),
(32117964, 'MBBank', '2025-11-24 13:56:00', '0327749747', '', '', '108461353730-HDKL25112413554396-CHUYEN TIEN-OQCH00042dz0-MOMO108461353730MOMO', 'in', 'BankAPINotify 108461353730-HDKL25112413554396-CHUYEN TIEN-OQCH00042dz0-MOMO108461353730MOMO', 32000, 'FT25328654565006', '2025-11-24 06:56:00', '2025-11-24 06:56:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shopping_carts`
--

CREATE TABLE `shopping_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_token` varchar(64) DEFAULT NULL,
  `status` enum('active','ordered','expired') NOT NULL DEFAULT 'active',
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `shopping_carts`
--

INSERT INTO `shopping_carts` (`id`, `user_id`, `guest_token`, `status`, `coupon_id`, `created_at`, `updated_at`) VALUES
(23, 4, NULL, 'ordered', NULL, '2025-11-24 05:32:37', '2025-11-24 05:39:04'),
(24, 6, NULL, 'ordered', NULL, '2025-11-24 06:19:56', '2025-11-24 13:10:09'),
(25, 4, NULL, 'ordered', NULL, '2025-11-24 07:08:23', '2025-11-24 07:08:54'),
(26, 5, NULL, 'active', NULL, '2025-11-24 07:20:27', '2025-11-24 07:20:27'),
(27, 4, NULL, 'ordered', NULL, '2025-11-24 09:09:52', '2025-11-24 10:52:22'),
(28, 4, NULL, 'ordered', NULL, '2025-11-24 10:53:31', '2025-11-24 10:53:40'),
(29, 4, NULL, 'ordered', NULL, '2025-11-24 10:59:05', '2025-11-24 10:59:23'),
(30, NULL, '1ebca1f3-3f03-424e-bd61-d09869b021f9', 'active', NULL, '2025-11-24 12:37:23', '2025-11-24 12:37:23'),
(31, NULL, '712157e9-1658-4e5e-81a5-c2c90f57c0e5', 'active', NULL, '2025-11-24 12:37:23', '2025-11-24 12:37:23'),
(32, 6, NULL, 'ordered', NULL, '2025-11-24 13:13:11', '2025-11-24 13:13:20'),
(33, 6, NULL, 'active', NULL, '2025-11-24 13:17:31', '2025-11-24 13:17:31'),
(34, 4, NULL, 'ordered', NULL, '2025-11-24 17:23:09', '2025-11-24 17:23:40'),
(35, 4, NULL, 'ordered', NULL, '2025-11-25 00:11:43', '2025-11-25 00:11:54'),
(36, NULL, '8e2536e4-b5c7-4e4c-88cc-78e1014fbfb3', 'active', NULL, '2025-11-25 00:12:31', '2025-11-25 00:12:31'),
(37, NULL, '5be272e7-a0d0-4092-9427-0305bf759199', 'active', NULL, '2025-11-25 00:12:31', '2025-11-25 00:12:31'),
(38, 4, NULL, 'ordered', NULL, '2025-11-25 00:12:44', '2025-11-25 00:13:22'),
(39, 4, NULL, 'active', NULL, '2025-11-25 00:32:23', '2025-11-25 00:32:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `group_key` varchar(50) NOT NULL DEFAULT 'general',
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_code` varchar(15) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `avatar_url` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `customer_code`, `full_name`, `email`, `phone_number`, `password`, `role`, `avatar_url`, `gender`, `date_of_birth`, `status`, `email_verified`, `phone_verified`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN001', 'Quản trị hệ thống', 'admin@kimloan.cake', '0900123456', '$2y$12$.Pr.PPuq08XIfwNAoAHfQuJzbk74lVlWSkUW.JxNB9lmtAx9iUtQK', 'admin', NULL, NULL, NULL, 'active', 1, 0, NULL, '2025-10-30 13:42:19', '2025-10-30 13:42:19'),
(2, 'STAFF001', 'Nhân viên cửa hàng', 'staff@kimloan.cake', '0900222333', '$2y$12$bOD9G.kI/cO.RZapn8t7zuCJPRibrIfs40R2DkkxZwcyzXD9XK48C', 'staff', NULL, NULL, NULL, 'active', 0, 0, NULL, '2025-10-30 13:42:19', '2025-10-30 13:42:19'),
(3, 'CUST001', 'Nguyễn Thị Kim Loan', 'khachhang@kimloan.cake', '0900111222', '$2y$12$8osDx8na07ZnMIWoJK.ygO/jNaIRbTaCTGtKA64UeYTcKAV.hSUH2', 'customer', NULL, NULL, NULL, 'active', 1, 0, NULL, '2025-10-30 13:42:20', '2025-10-30 13:42:20'),
(4, 'PLEHXEMO', 'Lưu Kiệt', 'luukie0401@gmail.com', '0327749747', '$2y$12$et3o3rk8JPE/70g01EW4C.r7x7EdDGZpNrDCaBnEUImRlWOpMZpdu', 'admin', NULL, 'male', '2004-01-04', 'active', 0, 0, '2025-11-25 00:32:05', '2025-10-30 13:42:46', '2025-11-25 00:32:05'),
(5, 'MYFDVHW8', 'Hồ Ngọc Khánh', 'hokkhanh97@gmail.com', '0337236327', '$2y$12$IlP5M.hGXdE1YviBkKPuR.o4s4UAaK3OMIHY02hdxvFUcKQfjSCwe', 'customer', NULL, NULL, NULL, 'active', 0, 0, '2025-11-24 07:19:39', '2025-11-03 03:18:16', '2025-11-24 07:19:39'),
(6, 'UYO8DYHG', 'Truong Van Phu Khanh 2', 'ppkhanh1910404@gmail.com', '0949125111', '$2y$12$5uHu2S8uf1C37h1U8dXlm.INM8yG2dWp1VB/rgmNx9fe..C8cUxJa', 'customer', NULL, NULL, NULL, 'active', 0, 0, '2025-11-24 12:43:15', '2025-11-24 06:19:08', '2025-11-24 12:43:15');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_variant_id_foreign` (`variant_id`);

--
-- Chỉ mục cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_messages_handled_by_foreign` (`handled_by`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_coupon_code_unique` (`coupon_code`),
  ADD KEY `coupons_created_by_foreign` (`created_by`),
  ADD KEY `coupons_applies_to_product_index` (`applies_to_product_id`),
  ADD KEY `coupons_applies_to_category_index` (`applies_to_category_id`);

--
-- Chỉ mục cho bảng `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_addresses_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_receipts_receipt_code_unique` (`receipt_code`),
  ADD KEY `inventory_receipts_approved_by_foreign` (`approved_by`),
  ADD KEY `inventory_receipts_created_by_foreign` (`created_by`);

--
-- Chỉ mục cho bảng `inventory_receipt_items`
--
ALTER TABLE `inventory_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_receipt_items_product_id_foreign` (`product_id`),
  ADD KEY `inventory_receipt_items_receipt_id_foreign` (`receipt_id`),
  ADD KEY `inventory_receipt_items_variant_id_foreign` (`variant_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_approved_by_foreign` (`approved_by`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_variant_id_foreign` (`variant_id`);

--
-- Chỉ mục cho bảng `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_history_order_id_foreign` (`order_id`),
  ADD KEY `order_status_history_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_method_code_unique` (`method_code`);

--
-- Chỉ mục cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_transactions_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_product_code_unique` (`product_code`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_primary_category_id_foreign` (`primary_category_id`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_categories_slug_unique` (`slug`),
  ADD KEY `product_categories_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotions_slug_unique` (`slug`),
  ADD KEY `promotions_created_by_foreign` (`created_by`);

--
-- Chỉ mục cho bảng `sepay_transactions`
--
ALTER TABLE `sepay_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shopping_carts_coupon_id_foreign` (`coupon_id`),
  ADD KEY `shopping_carts_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_key`),
  ADD KEY `site_settings_updated_by_foreign` (`updated_by`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_customer_code_unique` (`customer_code`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `inventory_receipt_items`
--
ALTER TABLE `inventory_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `sepay_transactions`
--
ALTER TABLE `sepay_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32117965;

--
-- AUTO_INCREMENT cho bảng `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `shopping_carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Ràng buộc cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_applies_to_category_foreign` FOREIGN KEY (`applies_to_category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupons_applies_to_product_foreign` FOREIGN KEY (`applies_to_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  ADD CONSTRAINT `inventory_receipts_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_receipts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `inventory_receipt_items`
--
ALTER TABLE `inventory_receipt_items`
  ADD CONSTRAINT `inventory_receipt_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `inventory_receipt_items_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `inventory_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_receipt_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Ràng buộc cho bảng `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_status_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_primary_category_id_foreign` FOREIGN KEY (`primary_category_id`) REFERENCES `product_categories` (`id`);

--
-- Ràng buộc cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD CONSTRAINT `shopping_carts_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shopping_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `site_settings`
--
ALTER TABLE `site_settings`
  ADD CONSTRAINT `site_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
