<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$light_mode = 1; // default light mode

if ($user_id) {
    $stmt = $pdo->prepare("SELECT light_mode FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $light_mode = (int)$stmt->fetchColumn();
}

echo json_encode(['light_mode' => $light_mode]);
