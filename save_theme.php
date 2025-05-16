<?php
session_start();
require 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$light_mode = $data['light_mode'];
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id !== null && ($light_mode === 0 || $light_mode === 1)) {
    $stmt = $pdo->prepare("UPDATE users SET light_mode = ? WHERE id = ?");
    $stmt->execute([$light_mode, $user_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
