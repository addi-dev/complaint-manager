<?php
session_start();

require_once __DIR__ . '/core/Auth.php';
var_dump($_SESSION); // show what's in the session

// If not logged in → go to login page
if (!Auth::check()) {
    header('Location: /complaint-manager/views/auth/login.php');
    exit;
}

// If logged in → redirect by role
$redirects = [
    'admin'      => '/complaint-manager/views/admin',
    'supervisor' => '/complaint-manager/views/admin',
    'agent'      => '/complaint-manager/views/agent',
    'client'     => '/complaint-manager/views/client',
];

$role = $_SESSION['user_role'];

header('Location: ' . ($redirects[$role] ?? '/complaint-manager/views/auth/login.php'));
exit;