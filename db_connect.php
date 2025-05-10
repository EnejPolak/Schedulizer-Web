<?php
$host = 'localhost';              // Usually localhost on cPanel
$db   = 'scheduli_primaryDatabase';     // Replace with your actual DB name
$user = 'scheduli_primaryDB';       // Replace with your cPanel DB username
$pass = 'RosercicPolak1209!';       // Replace with your cPanel DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // good for debugging
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
