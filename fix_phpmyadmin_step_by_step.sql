-- Script SQL để chạy trong phpMyAdmin - XÓA TỪNG BƯỚC
-- Copy và chạy từng đoạn một trong tab SQL của phpMyAdmin

-- BƯỚC 1: Xóa tất cả bảng trong database comic_store
-- (Chạy đoạn này khi đã chọn database comic_store)

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `comic_store`.`order_items`;
DROP TABLE IF EXISTS `comic_store`.`orders`;
DROP TABLE IF EXISTS `comic_store`.`comics`;
DROP TABLE IF EXISTS `comic_store`.`categories`;
DROP TABLE IF EXISTS `comic_store`.`publishers`;
DROP TABLE IF EXISTS `comic_store`.`sessions`;
DROP TABLE IF EXISTS `comic_store`.`password_reset_tokens`;
DROP TABLE IF EXISTS `comic_store`.`users`;
DROP TABLE IF EXISTS `comic_store`.`migrations`;
DROP TABLE IF EXISTS `comic_store`.`cache`;
DROP TABLE IF EXISTS `comic_store`.`cache_locks`;
DROP TABLE IF EXISTS `comic_store`.`jobs`;
DROP TABLE IF EXISTS `comic_store`.`job_batches`;
DROP TABLE IF EXISTS `comic_store`.`failed_jobs`;

SET FOREIGN_KEY_CHECKS = 1;

-- BƯỚC 2: Sau khi xóa hết bảng, mới drop database
-- (Chạy đoạn này sau khi đã chạy BƯỚC 1)

DROP DATABASE IF EXISTS `comic_store`;

-- BƯỚC 3: Tạo lại database mới
CREATE DATABASE `comic_store` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sau khi chạy xong, quay lại terminal và chạy: php artisan migrate:fresh
