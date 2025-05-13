<?php
require 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['user_id'] ?? null;
$date = $data['date'] ?? null;
$time = $data['time'] ?? null;

if (!$userId || !$date || !$time) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$stmt = $pdo->prepare("UPDATE calendar SET available = 0, not_available = 1 WHERE users_id = ? AND date = ? AND time = ?");
if ($stmt->execute([$userId, $date, $time])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database update failed']);
}
