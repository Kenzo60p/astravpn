-- AstraVPN Enterprise schema

CREATE DATABASE IF NOT EXISTS `astravpn` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `astravpn`;

CREATE TABLE `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `role` ENUM('super','manager') NOT NULL DEFAULT 'manager',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `status` ENUM('active','suspended','pending') NOT NULL DEFAULT 'pending',
  `subscription_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`email`),
  FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `subscriptions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `duration_days` INT UNSIGNED NOT NULL DEFAULT 30,
  `bandwidth_gb` INT UNSIGNED NOT NULL DEFAULT 100,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `vpn_servers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `host` VARCHAR(150) NOT NULL,
  `type` ENUM('openvpn','wireguard') NOT NULL DEFAULT 'openvpn',
  `region` VARCHAR(100) NOT NULL,
  `status` ENUM('online','offline','maintenance') NOT NULL DEFAULT 'online',
  `ping_ms` INT UNSIGNED DEFAULT NULL,
  `max_users` INT UNSIGNED DEFAULT 500,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`host`),
  INDEX (`type`)
) ENGINE=InnoDB;

CREATE TABLE `vpn_configs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `server_id` INT UNSIGNED NOT NULL,
  `protocol` ENUM('udp','tcp','wireguard') NOT NULL DEFAULT 'udp',
  `config_data` TEXT NOT NULL,
  `qr_code` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`server_id`) REFERENCES `vpn_servers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `devices` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `device_name` VARCHAR(150) NOT NULL,
  `device_type` VARCHAR(100) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `last_seen` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `sessions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `token` VARCHAR(512) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX (`token`)
) ENGINE=InnoDB;

CREATE TABLE `payments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `subscription_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'USD',
  `status` ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `reference` VARCHAR(120) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `admin_id` INT UNSIGNED NULL,
  `type` VARCHAR(80) NOT NULL,
  `message` TEXT NOT NULL,
  `meta` JSON DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL,
  INDEX (`type`)
) ENGINE=InnoDB;

INSERT INTO `subscriptions` (`name`, `price`, `duration_days`, `bandwidth_gb`) VALUES
('Starter', 4.99, 30, 100),
('Pro', 12.99, 30, 500),
('Elite', 24.99, 30, 2000);

INSERT INTO `admins` (`email`, `password`, `name`, `role`) VALUES
('admin@astravpn.local', '$2y$12$VH0nqflp1G4pC7f3VtZBee9qoYOEqln0WzN0ZXJwWTFuHM42kMbS2', 'Astra Admin', 'super');

INSERT INTO `users` (`name`, `email`, `password`, `status`) VALUES
('Demo User', 'demo@astravpn.local', '$2y$12$VH0nqflp1G4pC7f3VtZBee9qoYOEqln0WzN0ZXJwWTFuHM42kMbS2', 'active');
