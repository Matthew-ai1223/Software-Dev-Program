<?php
header('Content-Type: application/json');
require_once '../databass.php';

// GET - Fetch all schedule
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM schedule ORDER BY event_date ASC");
        $events = $stmt->fetchAll();
        echo json_encode(['success' => true, 'events' => $events]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// POST - Create event
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $title = $input['title'] ?? '';
    $event_date = $input['event_date'] ?? '';
    $time_range = $input['time_range'] ?? '';
    $description = $input['description'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO schedule (title, event_date, time_range, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $event_date, $time_range, $description]);
        echo json_encode(['success' => true, 'message' => 'Event saved']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// DELETE - Remove event
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM schedule WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Event deleted']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
