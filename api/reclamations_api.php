<?php
// api/reclamations_api.php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin', 'superviseur');
try {
    $stmt = $pdo->query("
        SELECT
            r.id,
            r.numero_unique,
            r.objet,
            r.description,
            r.created_at,
            r.updated_at,
            r.closed_at,
            CONCAT(c.prenom, ' ', c.nom)    AS client,
            c.email                          AS client_email,
            c.telephone                      AS client_telephone,
            cat.libelle                      AS categorie,
            cat.id                          AS categorie_id,
            p.libelle                        AS priorite,
            p.niveau                         AS priorite_niveau,
            s.libelle                        AS statut,
            s.code                           AS statut_code,
            CONCAT(a.prenom, ' ', a.nom)    AS agent
        FROM reclamations r
        JOIN clients                 c   ON c.id   = r.client_id
        JOIN categories_reclamation  cat ON cat.id  = r.categorie_id
        JOIN priorites               p   ON p.id    = r.priorite_id
        JOIN statuts                 s   ON s.id    = r.statut_id
        LEFT JOIN utilisateurs       a   ON a.id    = r.agent_id
        WHERE r.deleted_at IS NULL
        ORDER BY priorite_niveau DESC, created_at ASC
    ");
    $reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($reclamations as &$rec) {
        $rec['priorite_niveau'] = (int) $rec['priorite_niveau'];
    }
    unset($rec);
    echo json_encode([
        'success' => true,
        'reclamations' => $reclamations,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    error_log('message', $e->getMessage());
    Response::error('Error serveur', 500);
}
