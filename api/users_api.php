<?php
// api/users_api.php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin', 'superviseur');
try {
    $stmt = $pdo->query("SELECT u.id, u.nom, u.prenom, u.date_naissance, u.numero_cin, u.email, u.actif, u.created_at, u.role_id, r.nom AS role
    FROM utilisateurs u
    JOIN roles r ON u.role_id = r.id
    WHERE u.deleted_at IS NULL
    ORDER BY u.created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (PDOException $e) {
    error_log('message', $e->getMessage());
    Response::error('Error serveur', 500);
}
