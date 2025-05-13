<?php
session_start();
require 'db_connect.php';

date_default_timezone_set('Europe/Ljubljana');
$pdo->exec("SET time_zone = '+02:00'");

header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}
$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

// pripravimo stavke
$stmt_check  = $pdo->prepare("SELECT id FROM calendar WHERE users_id = ? AND date = ? AND time = ?");
$stmt_insert = $pdo->prepare("INSERT INTO calendar (users_id, available, not_available, date, time) VALUES (?, ?, ?, ?, ?)");
$stmt_update = $pdo->prepare("UPDATE calendar SET available = ?, not_available = ? WHERE users_id = ? AND date = ? AND time = ?");

try {
    foreach ($data as $entry) {
        // 1) združi v lokalni coni
        $dt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $entry['date'] . ' ' . $entry['time'],
            new DateTimeZone('Europe/Ljubljana')
        );
        if ($dt) {
            $date = $dt->format('Y-m-d');
            $time = $dt->format('H:i:s');
        } else {
            $date = $entry['date'];
            $time = $entry['time'];
        }

        // 2) razčleni availability
        $available     = ($entry['availability'] === 'can')  ? 1 : 0;
        $not_available = ($entry['availability'] === 'cant') ? 1 : 0;
        if (!$available && !$not_available) continue;

        // 3) vstavi ali posodobi
        $stmt_check->execute([$user_id, $date, $time]);
        if ($stmt_check->fetch()) {
            $stmt_update->execute([
                $available,
                $not_available,
                $user_id,
                $date,
                $time
            ]);
        } else {
            $stmt_insert->execute([
                $user_id,
                $available,
                $not_available,
                $date,
                $time
            ]);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
