<?php
require_once 'databass.php';

try {
    // Attempt to add the column. If it exists, this might throw an error or warning, which we catch.
    $sql = "ALTER TABLE settings ADD COLUMN tech_stack TEXT";
    $pdo->exec($sql);
    echo json_encode(['success' => true, 'message' => 'Column tech_stack added successfully.']);
} catch (PDOException $e) {
    // Check if the error is because the column already exists (Error code 42S21 in MySQL usually)
    // or just generally catch it.
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo json_encode(['success' => true, 'message' => 'Column tech_stack already exists.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
