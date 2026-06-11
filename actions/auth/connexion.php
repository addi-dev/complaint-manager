<?php
require_once __DIR__ . '/../../core/CSRF.php';
require_once __DIR__ . '/../../core/Auth.php';
ini_set('display_errors', 0);
error_reporting(0);
ob_start();
session_start();
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
require_once $configPath;
require_once $responsePath;
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::methodNotAllowed();
}
CSRF::verify();
$_SESSION['login_attempts']    = $_SESSION['login_attempts']    ?? 0;
$_SESSION['login_lockout_until'] = $_SESSION['login_lockout_until'] ?? 0;
if (time() < $_SESSION['login_lockout_until']) {
    $wait = ceil(($_SESSION['login_lockout_until'] - time()) / 60);
    Response::error("Trop de tentatives. Réessayez dans $wait minute(s).");
}
$body     = json_decode(file_get_contents('php://input'), true);
$email    = trim($body['email']    ?? '');
$password = trim($body['password'] ?? '');

if (!$email || !$password) {
    Response::error('Email and password are required.');
}
$stmt = $pdo->prepare("SELECT u.id, u.nom, u.prenom, u.email, u.mot_de_passe, r.nom AS role_nom
    FROM utilisateurs u
    JOIN roles r ON r.id = u.role_id
    WHERE u.email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['mot_de_passe'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_nom']  = $user['nom'];
    $_SESSION['user_prenom']  = $user['prenom'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role_nom'];
    $_SESSION['user_table'] = 'utilisateurs';
    $_SESSION['logged_in']  = true;
    $redirects = [
        'admin'      => '/complaint-manager/views/admin',
        'superviseur' => '/complaint-manager/views/admin',
        'agent'      => '/complaint-manager/views/agent',
    ];
    $_SESSION['login_attempts']     = 0;
    $_SESSION['login_lockout_until'] = 0;
    Response::success('Login successful.', [
        'redirect' => $redirects[$user['role_nom']] ?? '/complaint-manager/views/auth/connexion.php'
    ]);
}
$stmt = $pdo->prepare("SELECT id, nom, prenom, email, mot_de_passe FROM clients WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$client = $stmt->fetch();
if ($client && password_verify($password, $client['mot_de_passe'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']    = $client['id'];
    $_SESSION['user_nom']  = $client['nom'];
    $_SESSION['user_prenom']  = $client['prenom'];
    $_SESSION['user_email'] = $client['email'];
    $_SESSION['user_role']  = 'client';
    $_SESSION['user_table'] = 'clients';
    $_SESSION['logged_in']  = true;
    $_SESSION['login_attempts']     = 0;
    $_SESSION['login_lockout_until'] = 0;
    Response::success('Login successful.', [
        'redirect' => '/complaint-manager/views/client'
    ]);
}
$_SESSION['login_attempts']++;
if ($_SESSION['login_attempts'] >= 5) {
    $_SESSION['login_lockout_until'] = time() + (15 * 60);
    $_SESSION['login_attempts']      = 0;
    Response::error('Trop de tentatives. Compte bloqué pendant 15 minutes.');
}
Response::error('Adresse e-mail ou mot de passe invalide.');
