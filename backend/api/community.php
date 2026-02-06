<?php
header('Content-Type: application/json');
require_once '../databass.php';

// GET - Fetch all posts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM community_posts ORDER BY created_at DESC");
        $posts = $stmt->fetchAll();
        echo json_encode(['success' => true, 'posts' => $posts]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// POST - Create post
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $title = $input['title'] ?? '';
    $content = $input['content'] ?? '';
    $type = $input['type'] ?? 'discussion';
    $author = $input['author'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO community_posts (title, content, type, author) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $type, $author]);
        echo json_encode(['success' => true, 'message' => 'Post saved']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// DELETE - Remove post
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Post deleted']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
