<?php
session_start();
require 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
$weekStartDate = $input['weekStartDate'] ?? null;

$stmt = $pdo->prepare("
  SELECT locked, week_end_date
  FROM schedule_locks
  WHERE user_id = ? AND week_start_date = ?
");
$stmt->execute([$user_id, $weekStartDate]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $locked      = (bool) $row['locked'];
    $weekEndDate = $row['week_end_date'];           // YYYY-MM-DD format
} else {
    // Če še ni bilo vnosa, predvidevamo, da ni zaklenjeno
    $locked      = false;
    // Izračunamo končni datum sami, čeprav ga v bazi še ni
    $weekEndDate = date('Y-m-d', strtotime("$weekStartDate +6 days"));
}

echo json_encode([
    'locked'      => $locked,
    'weekEndDate' => $weekEndDate
]);
