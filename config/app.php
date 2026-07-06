<?php
// config/app.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/constants.php';
require __DIR__ . '/../core/env.php';

loadEnv(__DIR__ . '/../.env');

$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Une erreur est survenue. Veuillez réessayer plus tard.");
}
