<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($pdo)) {
    require 'db_connect.php';
}

$lightMode = true;

if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT light_mode FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $lightMode = $stmt->fetchColumn() == 1 ? true : false;
}
