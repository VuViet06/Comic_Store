-- Script để fix lỗi MySQL tablespace
-- Chạy script này trong MySQL (phpMyAdmin, MySQL Workbench, hoặc MySQL CLI)

-- Bước 1: Xóa bảng migrations nếu tồn tại
DROP TABLE IF EXISTS `migrations`;

-- Bước 2: Nếu vẫn lỗi, thử discard tablespace
-- ALTER TABLE `migrations` DISCARD TABLESPACE;

-- Bước 3: Xóa và tạo lại database (CHỈ DÙNG KHI ĐANG PHÁT TRIỂN, SẼ MẤT HẾT DỮ LIỆU)
DROP DATABASE IF EXISTS `comic_store`;
CREATE DATABASE `comic_store` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sau khi chạy script này, quay lại terminal và chạy:
-- php artisan migrate:fresh
