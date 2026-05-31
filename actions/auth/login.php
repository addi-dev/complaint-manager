<?php
// Catch everything before any output
ini_set('display_errors', 0);
error_reporting(0);

ob_start(); // buffer any accidental output

session_start();

// Convert any PHP error into JSON
set_error_handler(function ($errno, $errstr) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'PHP Error: ' . $errstr]);
    exit;
});

set_exception_handler(function ($e) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
    exit;
});

header('Content-Type: application/json');

// Test paths before including
$configPath  = __DIR__ . '/../../config/app.php';
$responsePath = __DIR__ . '/../../core/Response.php';

if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'message' => 'config/app.php not found at: ' . $configPath]);
    exit;
}

if (!file_exists($responsePath)) {
    echo json_encode(['success' => false, 'message' => 'core/Response.php not found at: ' . $responsePath]);
    exit;
}

require_once $configPath;   // gives $pdo
require_once $responsePath; // gives Response::

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::methodNotAllowed();
}

$body     = json_decode(file_get_contents('php://input'), true);
$email    = trim($body['email']    ?? '');
$password = trim($body['password'] ?? '');

if (!$email || !$password) {
    Response::error('Email and password are required.');
}

// ── 1. Try utilisateurs ───────────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT id, nom, prenom, email, mot_de_passe, role_id FROM utilisateurs WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['mot_de_passe'])) {
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['nom'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role_id'];
    $_SESSION['user_table'] = 'utilisateurs';
    $_SESSION['logged_in']  = true;

    $redirects = [
        'admin'      => '/views/admin/dashboard.php',
        'supervisor' => '/views/admin/dashboard.php',
        'agent'      => '/views/agent/dashboard.php',
    ];

    Response::success('Login successful.', [
        'redirect' => $redirects[$user['role_id']] ?? 'complaint-manager/views/auth/login.php'
    ]);
}

// ── 2. Try clients ────────────────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT id, nom, prenom, email, mot_de_passe FROM clients WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$client = $stmt->fetch();

if ($client && password_verify($password, $client['mot_de_passe'])) {
    $_SESSION['user_id']    = $client['id'];
    $_SESSION['user_name']  = $client['nom'];
    $_SESSION['user_email'] = $client['email'];
    $_SESSION['user_role']  = 'client';
    $_SESSION['user_table'] = 'clients';
    $_SESSION['logged_in']  = true;

    Response::success('Login successful.', [
        'redirect' => '/views/client/dashboard.php'
    ]);
}

// ── 3. No match ───────────────────────────────────────────────────────────────
Response::error('Invalid email or password.');
