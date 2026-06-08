<?php
// api/users_api.php
// session_start();
header('Content-Type: application/json');

require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin', 'superviseur');
try {
    $stmt = $pdo->query("SELECT u.id, u.nom, u.prenom, u.date_naissance, u.numero_cin, u.email, u.actif, u.created_at, u.role_id, r.nom AS role
FROM utilisateurs u
JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC;");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
