<?php
header('Content-Type: application/json');
require_once 'databass.php';

// Get Input
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['userId'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

try {
    // Delete User (Cascading delete will handle settings if foreign keys are set up, otherwise we delete manually)
    // Based on databass.php, FK with ON DELETE CASCADE is set for settings table.
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
