<?php
header('Content-Type: application/json');
require_once '../databass.php';

// GET - Fetch all curriculum
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM curriculum ORDER BY id DESC");
        $curriculum = $stmt->fetchAll();
        echo json_encode(['success' => true, 'curriculum' => $curriculum]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// POST - Create curriculum
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $module_title = $input['module_title'] ?? '';
    $week_title = $input['week_title'] ?? '';
    $description = $input['description'] ?? '';
    $status = $input['status'] ?? 'locked';

    try {
        $stmt = $pdo->prepare("INSERT INTO curriculum (module_title, week_title, description, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$module_title, $week_title, $description, $status]);
        echo json_encode(['success' => true, 'message' => 'Curriculum saved']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// DELETE - Remove curriculum
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM curriculum WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Curriculum deleted']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
