<?php
session_start();
require 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT s.id, s.swap_date, s.swap_time, s.reason
    FROM swap s
    WHERE s.users_id != ?
      AND s.is_active = 1
      AND NOT EXISTS (
          SELECT 1 FROM swap_responses r
          WHERE r.swap_id = s.id AND r.user_id = ?
      )
");
$stmt->execute([$user_id, $user_id]);
$swapRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($swapRequests);
