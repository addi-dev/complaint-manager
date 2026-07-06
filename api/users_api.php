<?php
// api/users_api.php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin', 'superviseur');

$sort = $_GET['sort'] ?? 'name';

$allowed = [
    'name',
    'name_desc',
    'date_asc',
    'date_desc'
];

if (!in_array($sort, $allowed)) {
    $sort = 'name';
}

switch ($sort) {
    case 'name':
        $orderBy = "nom ASC, prenom ASC";
        break;

    case 'name_desc':
        $orderBy = "nom DESC, prenom DESC";
        break;

    case 'date_asc':
        $orderBy = "created_at ASC";
        break;

    case 'date_desc':
        $orderBy = "created_at DESC";
        break;

    default:
        $orderBy = "nom ASC, prenom ASC";
}

try {
    $stmt = $pdo->query("SELECT u.id, u.nom, u.prenom, u.date_naissance, u.numero_cin, u.email, u.actif, u.created_at, u.role_id, r.nom AS role
    FROM utilisateurs u
    JOIN roles r ON u.role_id = r.id
    WHERE u.deleted_at IS NULL
    ORDER BY $orderBy");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    Response::error('Error serveur', 500);
}
