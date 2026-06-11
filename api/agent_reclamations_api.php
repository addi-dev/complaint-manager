<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('agent');
try {
    $agentId = (int) $_SESSION['user_id'];
    $stmt = $pdo->prepare("
        SELECT
            r.*,
            CONCAT(c.nom, ' ', c.prenom) AS client,
            c.email AS client_email,
            c.telephone AS client_telephone,
            c.adresse AS client_adresse,
            cat.libelle AS categorie,
            p.libelle AS priorite,
            p.niveau AS priorite_niveau,
            s.libelle AS statut,
            s.code AS statut_code,
            a.id AS agent_id,
            a.nom AS agent_nom,
            a.prenom AS agent_prenom,
            a.email AS agent_email,
            (
                SELECT COUNT(*)
                FROM commentaires com
                WHERE com.reclamation_id = r.id
            ) AS commentaires_count,
            (
                SELECT COUNT(*)
                FROM commentaires com
                WHERE com.reclamation_id = r.id
                AND com.interne = 1
            ) AS notes_internes_count,
            (
                SELECT COUNT(*)
                FROM pieces_jointes pj
                WHERE pj.reclamation_id = r.id
            ) AS pieces_jointes_count,
            (
                SELECT ha.action
                FROM historique_actions ha
                WHERE ha.reclamation_id = r.id
                ORDER BY ha.created_at DESC
                LIMIT 1
            ) AS derniere_action,
            TIMESTAMPDIFF(
                DAY,
                r.created_at,
                NOW()
            ) AS age_jours
        FROM reclamations r
        JOIN clients c
            ON c.id = r.client_id
        JOIN categories_reclamation cat
            ON cat.id = r.categorie_id
        JOIN priorites p
            ON p.id = r.priorite_id
        JOIN statuts s
            ON s.id = r.statut_id
        LEFT JOIN utilisateurs a
            ON a.id = r.agent_id
        WHERE r.agent_id = ? AND r.deleted_at IS NULL
        ORDER BY
            p.niveau DESC,
            r.created_at ASC
    ");
    $stmt->execute([$agentId]);
    $agent_reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'count' => count($agent_reclamations),
        'agent_reclamations' => $agent_reclamations
    ]);
} catch (PDOException $e) {
    error_log('message', $e->getMessage());
    Response::error('Error serveur', 500);
}
