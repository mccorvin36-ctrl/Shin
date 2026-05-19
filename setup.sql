-- Run this SQL to set up the database
-- You can run it via phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS dxd_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE dxd_app;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
