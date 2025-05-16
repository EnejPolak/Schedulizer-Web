<?php
session_start();
require 'db_connect.php';

// Nastavimo časovno območje
date_default_timezone_set('Europe/Ljubljana');
$pdo->exec("SET time_zone = '+02:00'");

header('Content-Type: application/json');

// Preverimo prijavo
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Preberemo poslane podatke
$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || empty($data)) {
    echo json_encode(['success' => false, 'error' => 'Invalid or empty data']);
    exit;
}

// Izračunamo razpon datumov (pon–ned)
$dates = array_unique(array_column($data, 'date'));
sort($dates);
$start_date = reset($dates);    // prvi datum v tednu
$end_date   = end($dates);      // zadnji datum v tednu

try {
    // Za varnost v transakciji
    $pdo->beginTransaction();

    // 1) Izbrišemo vse vnose tega uporabnika za ta teden
    $del = $pdo->prepare("
        DELETE FROM calendar
         WHERE users_id = ?
           AND date BETWEEN ? AND ?
    ");
    $del->execute([
        $user_id,
        $start_date,
        $end_date
    ]);

    // 2) Pripravimo INSERT stavek
    $ins = $pdo->prepare("
        INSERT INTO calendar
            (users_id, available, not_available, date, time)
        VALUES
            (?, ?, ?, ?, ?)
    ");

    // 3) Vstavimo vse poslane celice (pon–ned)
    foreach ($data as $entry) {
        // Standardiziramo format datuma in ure
        $rawDate = $entry['date'];
        $rawTime = $entry['time'];
        $dt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $rawDate . ' ' . $rawTime,
            new DateTimeZone('Europe/Ljubljana')
        );
        if ($dt) {
            $date = $dt->format('Y-m-d');
            $time = $dt->format('H:i:s');
        } else {
            $date = $rawDate;
            $time = $rawTime;
        }

        // Nastavimo vrednosti
        $avail     = ($entry['availability'] === 'can')  ? 1 : 0;
        $not_avail = ($entry['availability'] === 'cant') ? 1 : 0;

        // Vstavimo vrstico
        $ins->execute([
            $user_id,
            $avail,
            $not_avail,
            $date,
            $time
        ]);
    }

    // Potrdimo transakcijo
    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Ob napaki rollback
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
