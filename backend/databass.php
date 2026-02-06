<?php
// Database Connection Configuration
$host = 'localhost';
$dbname = 'devprogram_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Attempt to create database if it doesn't exist
    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $pdo->exec("USE `$dbname`");
    } catch (PDOException $e2) {
        die("Database connection failed: " . $e2->getMessage());
    }
}

// Automatic Table Creation
try {
    // 1. Users Table
    $sql_users = "CREATE TABLE IF NOT EXISTS `users` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `fullname` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `phone` VARCHAR(20) NOT NULL,
        `track` ENUM('frontend', 'backend', 'fullstack') NOT NULL,
        `experience` ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_users);

    // 2. Settings Table (For Bio, etc.)
    $sql_settings = "CREATE TABLE IF NOT EXISTS `settings` (
        `user_id` INT(11) UNSIGNED NOT NULL,
        `bio` TEXT,
        `tech_stack` TEXT,
        PRIMARY KEY (`user_id`),
        CONSTRAINT `fk_user_settings` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    )";
    $pdo->exec($sql_settings);

} catch (PDOException $e) {
    die("Table creation failed: " . $e->getMessage());
}
?>
