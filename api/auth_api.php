<?php
// api/auth_api.php
session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../core/Auth.php';
header('Content-Type: application/json');

if (!empty($_SESSION['logged_in'])) {
    echo json_encode([
        'logged_in' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'nom' => $_SESSION['user_nom'],
            'prenom' => $_SESSION['user_prenom'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'from' => $_SESSION['user_table'],
        ]
    ]);
} else {
    echo json_encode(['logged_in' => false, 'user' => null]);
}
