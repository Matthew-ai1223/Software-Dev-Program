<?php
header('Content-Type: application/json');
require_once 'databass.php';

// Check if user ID is provided (passed via POST body for security)
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['userId'] ?? null;
$fullname = trim($input['fullname'] ?? '');
$email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
$bio = trim($input['bio'] ?? '');
$techStack = $input['techStack'] ?? '';
$github = trim($input['github'] ?? '');

if (!$userId || !$fullname || !$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Update User Info
    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
    $stmt->execute([$fullname, $email, $userId]);

    // Update or Insert Settings
    $stmt = $pdo->prepare("SELECT user_id FROM settings WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("UPDATE settings SET bio = ?, tech_stack = ?, github_link = ? WHERE user_id = ?");
        $stmt->execute([$bio, $techStack, $github, $userId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO settings (user_id, bio, tech_stack, github_link) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $bio, $techStack, $github]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
