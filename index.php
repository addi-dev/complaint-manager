<?php
session_start();
require_once __DIR__ . '/core/Auth.php';
if (!Auth::check()) {
    header('Location: /complaint-manager/views/auth/connexion.php');
    exit;
}
$redirects = [
    'admin'      => '/complaint-manager/views/admin',
    'superviseur' => '/complaint-manager/views/admin',
    'agent'      => '/complaint-manager/views/agent',
    'client'     => '/complaint-manager/views/client',
];
$role = $_SESSION['user_role'];
header('Location: ' . ($redirects[$role] ?? '/complaint-manager/views/auth/connexion.php'));
exit;
