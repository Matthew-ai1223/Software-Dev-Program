<?php
header('Content-Type: application/json');
require_once 'databass.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Sanitize and Validate
$fullname = trim($input['fullname'] ?? '');
$email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = trim($input['phone'] ?? '');
$track = trim($input['track'] ?? '');
$experience = trim($input['experience'] ?? '');

$errors = [];
if (empty($fullname)) $errors[] = "Full name is required";
if (!$email) $errors[] = "Valid email is required";
if (empty($phone)) $errors[] = "Phone number is required";
if (!in_array($track, ['frontend', 'backend', 'fullstack'])) $errors[] = "Invalid track selection";
if (!in_array($experience, ['beginner', 'intermediate', 'advanced'])) $errors[] = "Invalid experience level";

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    // Insert User
    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, phone, track, experience) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$fullname, $email, $phone, $track, $experience]);
    $userId = $pdo->lastInsertId();

    // Insert GitHub Link into Settings (if provided)
    $github = trim($input['github'] ?? '');
    if (!empty($github)) {
        // Basic URL validation
        if (filter_var($github, FILTER_VALIDATE_URL)) {
             $stmt = $pdo->prepare("INSERT INTO settings (user_id, github_link) VALUES (?, ?)");
             $stmt->execute([$userId, $github]);
        }
    } else {
        // Create empty settings entry just to be consistent? Or do nothing?
        // Let's create it so UPDATE doesn't have to check for existance as much (though update_user.php handles it)
        $stmt = $pdo->prepare("INSERT INTO settings (user_id) VALUES (?)");
        $stmt->execute([$userId]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful!',
        'userId' => $userId
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
