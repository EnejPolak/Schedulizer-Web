<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

$input = json_decode(file_get_contents("php://input"), true);
$ids = $input['ids'] ?? [];

if (!is_array($ids) || empty($ids)) {
    echo json_encode([]);
    exit;
}

$inClause = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("
    SELECT id, status, users_id, swap_date, swap_time 
    FROM swap 
    WHERE id IN ($inClause)
");
$stmt->execute($ids);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statusMap = [];
foreach ($results as $row) {
    $statusMap[$row['id']] = [
        'status' => $row['status'],
        'requester_id' => $row['users_id'],
        'date' => $row['swap_date'],
        'time' => $row['swap_time']
    ];
}

echo json_encode($statusMap);
