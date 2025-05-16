<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

// 1) Preverimo, ali je uporabnik prijavljen
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}
$adminId = $_SESSION['user_id'];

// 2) Preberemo začetek tedna iz zahteve
$payload = json_decode(file_get_contents('php://input'), true);
if (empty($payload['weekStartDate'])) {
    echo json_encode(['success' => false, 'error' => 'Missing weekStartDate']);
    exit;
}
$weekStart = DateTime::createFromFormat('Y-m-d', $payload['weekStartDate']);
if (!$weekStart) {
    echo json_encode(['success' => false, 'error' => 'Invalid date format']);
    exit;
}
$weekEnd = clone $weekStart;
$weekEnd->modify('+6 days');

$startStr = $weekStart->format('Y-m-d');
$endStr   = $weekEnd->format('Y-m-d');

// 3) Preverimo, ali ima admin privilegij
$stmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
$stmt->execute([$adminId]);
$role = $stmt->fetchColumn();
if (!in_array($role, ['admin', 'moderator'], true)) {
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 4) Pridobimo company_id-je, kjer je admin
    $stmt = $pdo->prepare("SELECT company_id FROM user_companies WHERE user_id = ?");
    $stmt->execute([$adminId]);
    $companyIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (empty($companyIds)) {
        throw new Exception('Admin is not in any company');
    }

    // 5) Pridobimo vse user_id-je v teh podjetjih
    $in = implode(',', array_fill(0, count($companyIds), '?'));
    $stmt = $pdo->prepare("
        SELECT DISTINCT user_id
        FROM user_companies
        WHERE company_id IN ($in)
    ");
    $stmt->execute($companyIds);
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (empty($userIds)) {
        throw new Exception('No users found in these companies');
    }

    // 6) Vstavimo lock za vsakega, ki še nima vnosa za ta teden
    $ins = $pdo->prepare("
        INSERT INTO schedule_locks
            (user_id, week_start_date, week_end_date, locked, locked_at)
        SELECT ?, ?, ?, 1, NOW()
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1
            FROM schedule_locks
            WHERE user_id = ?
              AND week_start_date = ?
        )
    ");

    foreach ($userIds as $uid) {
        $ins->execute([
            $uid,
            $startStr,
            $endStr,
            $uid,
            $startStr
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
