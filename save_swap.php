<?php
session_start();
require 'db_connect.php';

// Preveri ali je uporabnik prijavljen
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'] ?? null;
$swap_date = $data['date'] ?? null;
$swap_time = $data['time'] ?? null;
$reason = $data['reason'] ?? null;

if (!$user_id || !$swap_date || !$swap_time || !$reason) {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO swap (users_id, swap_date, swap_time, reason) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $swap_date, $swap_time, $reason]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
