<?php
require_once 'databass.php';

try {
    // Attempt to add the column.
    $sql = "ALTER TABLE settings ADD COLUMN github_link VARCHAR(255) DEFAULT NULL";
    $pdo->exec($sql);
    echo json_encode(['success' => true, 'message' => 'Column github_link added successfully.']);
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo json_encode(['success' => true, 'message' => 'Column github_link already exists.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
