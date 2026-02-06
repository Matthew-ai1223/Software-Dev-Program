<?php
header('Content-Type: application/json');
require_once 'databass.php';

// Check if user ID is provided
$userId = $_GET['id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT u.id, u.fullname, u.email, u.track, u.experience, s.bio, s.tech_stack, s.github_link 
        FROM users u 
        LEFT JOIN settings s ON u.id = s.user_id 
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
