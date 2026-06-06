<?php
// config/app.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/constants.php';
$host = 'localhost';
$db   = 'reclamations';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
