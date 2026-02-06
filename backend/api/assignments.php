<?php
header('Content-Type: application/json');
require_once '../databass.php';

// GET - Fetch all assignments
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM assignments ORDER BY due_date DESC");
        $assignments = $stmt->fetchAll();
        echo json_encode(['success' => true, 'assignments' => $assignments]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// POST - Create or Update assignment
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input =json_decode(file_get_contents('php://input'), true);
    
    $title = $input['title'] ?? '';
    $description = $input['description'] ?? '';
    $due_date = $input['due_date'] ?? '';
    $status = $input['status'] ?? 'active';
    $grade = $input['grade'] ?? null;
    $id = $input['id'] ?? null;

    try {
        if ($id) {
            // Update existing
            $stmt = $pdo->prepare("UPDATE assignments SET title=?, description=?, due_date=?, status=?, grade=? WHERE id=?");
            $stmt->execute([$title, $description, $due_date, $status, $grade, $id]);
        } else {
            // Create new
            $stmt = $pdo->prepare("INSERT INTO assignments (title, description, due_date, status, grade) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $due_date, $status, $grade]);
        }
        echo json_encode(['success' => true, 'message' => 'Assignment saved']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// DELETE - Remove assignment
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM assignments WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Assignment deleted']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
