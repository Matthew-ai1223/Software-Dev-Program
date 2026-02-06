<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'databass.php';

//Allow DELETE method
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Create assignments table
    $sql_assignments = "CREATE TABLE IF NOT EXISTS `assignments` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        `due_date` DATETIME NOT NULL,
        `status` ENUM('active', 'graded') DEFAULT 'active',
        `grade` INT(3),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_assignments);

    // Curriculum table
    $sql_curriculum = "CREATE TABLE IF NOT EXISTS `curriculum` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `module_title` VARCHAR(255) NOT NULL,
        `week_title` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        `status` ENUM('completed', 'in_progress', 'locked') DEFAULT 'locked',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_curriculum);

    // Community posts table
    $sql_community = "CREATE TABLE IF NOT EXISTS `community_posts` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `type` ENUM('announcement', 'discussion') DEFAULT 'discussion',
        `author` VARCHAR(100) NOT NULL,
        `replies` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_community);

    // Schedule table
    $sql_schedule = "CREATE TABLE IF NOT EXISTS `schedule` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `event_date` DATETIME NOT NULL,
        `time_range` VARCHAR(50) NOT NULL,
        `description` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_schedule);

    echo json_encode(['success' => true, 'message' => 'All tables created successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
