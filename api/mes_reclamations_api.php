<?php
// api/mes_reclamations_api.php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/../config/app.php';

try {
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
        cat.id AS categorie_id,

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
    $mes_reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'mes_reclamations' => $mes_reclamations
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
