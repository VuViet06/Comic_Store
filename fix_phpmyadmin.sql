-- Script SQL để chạy trong phpMyAdmin
-- Copy toàn bộ nội dung này và paste vào tab SQL trong phpMyAdmin

-- Bước 1: Xóa database cũ (nếu có)
DROP DATABASE IF EXISTS `comic_store`;

-- Bước 2: Tạo lại database mới
CREATE DATABASE `comic_store` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Bước 3: Chọn database vừa tạo
USE `comic_store`;

-- Sau khi chạy xong, quay lại terminal và chạy: php artisan migrate:fresh
