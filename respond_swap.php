<?php
session_start();
require 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$swap_id = $data['swap_id'] ?? null;
$action = $data['action'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$swap_id || !$action || !$user_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

// Če že obstaja odgovor tega userja, preskoči
$stmt = $pdo->prepare("SELECT COUNT(*) FROM swap_responses WHERE swap_id = ? AND user_id = ?");
$stmt->execute([$swap_id, $user_id]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'error' => 'Already responded']);
    exit;
}

// Zapiši odgovor
$stmt = $pdo->prepare("INSERT INTO swap_responses (swap_id, user_id, response) VALUES (?, ?, ?)");
$stmt->execute([$swap_id, $user_id, $action]);

// Pridobi originalni swap
$stmt = $pdo->prepare("SELECT * FROM swap WHERE id = ?");
$stmt->execute([$swap_id]);
$swap = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$swap) {
    echo json_encode(['success' => false, 'error' => 'Swap not found']);
    exit;
}

// Če je akcija ACCEPT
if ($action === 'accept') {
    // 1. Označi zmagovalca (acceptor) kot AVAILABLE
    $stmt = $pdo->prepare("UPDATE calendar SET available = true, not_available = false 
                           WHERE users_id = ? AND date = ? AND time = ?");
    $stmt->execute([$user_id, $swap['swap_date'], $swap['swap_time']]);

    // 2. Označi tistega, ki je oddal swap kot NOT_AVAILABLE
    $stmt = $pdo->prepare("UPDATE calendar SET available = false, not_available = true 
                           WHERE users_id = ? AND date = ? AND time = ?");
    $stmt->execute([$swap['users_id'], $swap['swap_date'], $swap['swap_time']]);

    // 3. Posodobi status swapa v 'accepted'
    $stmt = $pdo->prepare("UPDATE swap SET status = 'accepted', is_active = 0 WHERE id = ?");
    $stmt->execute([$swap_id]);

    echo json_encode(['success' => true, 'accepted' => true]);
    exit;
}

// Če je akcija DECLINE
if ($action === 'decline') {
    // Preštej, če so VSI zavrnili
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM swap_responses WHERE swap_id = ?");
    $stmt->execute([$swap_id]);
    $total = $stmt->fetchColumn();

    // Štej skupne uporabnike brez tistega, ki je swap dal
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id != ?");
    $stmt->execute([$swap['users_id']]);
    $allUsers = $stmt->fetchColumn();

    $final = ($total >= $allUsers);

    if ($final) {
        $stmt = $pdo->prepare("UPDATE swap SET status = 'declined', is_active = 0 WHERE id = ?");
        $stmt->execute([$swap_id]);
    }

    echo json_encode(['success' => true, 'final' => $final]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Unknown action']);
