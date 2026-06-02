<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../config/app.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT
    r.id,
    r.numero_unique,
    r.objet,
    r.description,
    r.created_at,
    r.updated_at,
    r.closed_at,

    CONCAT(c.prenom, ' ', c.nom) AS client,
    c.email AS client_email,
    c.telephone AS client_telephone,

    cat.libelle AS categorie,

    p.libelle AS priorite,
    p.niveau AS priorite_niveau,

    s.libelle AS statut,
    s.code AS statut_code,

    CONCAT(a.prenom, ' ', a.nom) AS agent

FROM reclamations r
JOIN clients c ON c.id = r.client_id
JOIN categories_reclamation cat ON cat.id = r.categorie_id
JOIN priorites p ON p.id = r.priorite_id
JOIN statuts s ON s.id = r.statut_id
LEFT JOIN utilisateurs a ON a.id = r.agent_id

WHERE r.client_id = ?

ORDER BY p.niveau DESC, r.created_at ASC");
$stmt->execute([$userId]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));