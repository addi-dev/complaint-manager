<?php
session_start();
header('Content-Type: application/json');

if (!empty($_SESSION['logged_in'])) {
    echo json_encode([
        'logged_in' => true,
        'user' => [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role'  => $_SESSION['user_role'],   // 'admin'|'supervisor'|'agent'|'client'
            'from'  => $_SESSION['user_table'],  // 'utilisateurs' or 'clients'
        ]
    ]);
} else {
    echo json_encode(['logged_in' => false, 'user' => null]);
}
