<?php
// api/users.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/../config/app.php';

try {
    $stmt = $pdo->query("SELECT u.id, u.nom, u.prenom, u.email, u.actif, u.created_at, r.nom AS role
FROM utilisateurs u
JOIN roles r ON u.role_id = r.id;");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'users'    => $users
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}