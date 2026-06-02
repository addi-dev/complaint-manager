<?php
// api/stats_api.php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin');
try {
    $totalClients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();

    $totalReclamations = $pdo->query("SELECT COUNT(*) FROM reclamations")->fetchColumn();

    $byStatut = $pdo->query("
        SELECT s.libelle, s.code, COUNT(r.id) AS total
        FROM statuts s
        LEFT JOIN reclamations r ON r.statut_id = s.id
        GROUP BY s.id, s.libelle, s.code
    ")->fetchAll(PDO::FETCH_ASSOC);

    $byPriorite = $pdo->query("
        SELECT p.libelle, p.niveau, COUNT(r.id) AS total
        FROM priorites p
        LEFT JOIN reclamations r ON r.priorite_id = p.id
        GROUP BY p.id, p.libelle, p.niveau
        ORDER BY p.niveau ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    $thisMonth = $pdo->query("
        SELECT COUNT(*) FROM reclamations
        WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
    ")->fetchColumn();

    $newClients = $pdo->query("
        SELECT COUNT(*) FROM clients
        WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
    ")->fetchColumn();

    $totalAgents = $pdo->query("
        SELECT COUNT(*) FROM utilisateurs u
        JOIN roles r ON u.role_id = r.id
        WHERE r.nom = 'agent' AND u.actif = 1
    ")->fetchColumn();

    $unresolved = $pdo->query("
        SELECT COUNT(*) FROM reclamations r
        JOIN statuts s ON r.statut_id = s.id
        WHERE s.code NOT IN ('RESOLUE', 'CLOTUREE', 'REJETEE')
    ")->fetchColumn();

    $recent = $pdo->query("
        SELECT
            r.id,
            r.numero_unique,
            r.objet,
            r.created_at,
            c.nom  AS client_nom,
            c.prenom AS client_prenom,
            p.libelle AS priorite,
            s.libelle AS statut,
            s.code    AS statut_code
        FROM reclamations r
        JOIN clients  c ON r.client_id   = c.id
        JOIN priorites p ON r.priorite_id = p.id
        JOIN statuts  s ON r.statut_id   = s.id
        ORDER BY r.created_at DESC
        LIMIT 8
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'total_clients' => (int) $totalClients,
        'total_reclamations' => (int) $totalReclamations,
        'this_month' => (int) $thisMonth,
        'new_clients' => (int) $newClients,
        'total_agents' => (int) $totalAgents,
        'unresolved' => (int) $unresolved,
        'by_statut' => $byStatut,
        'by_priorite' => $byPriorite,
        'recent' => $recent,
    ]);
} catch (PDOException $e) {
    error_log('[API Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
}