<?php
// api/clients_api.php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin');
try {
    $stmt = $pdo->query("
        SELECT
            c.id,
            c.nom,
            c.prenom,
            c.email,
            c.telephone,
            c.adresse,
            c.created_at,
            c.updated_at,

            -- Réclamations du client
            COUNT(DISTINCT r.id)                                        AS total_reclamations,
            SUM(CASE WHEN s.code = 'NOUVELLE'            THEN 1 END)    AS rec_nouvelles,
            SUM(CASE WHEN s.code = 'ATTENTE_AFFECTATION' THEN 1 END)    AS rec_attente_affectation,
            SUM(CASE WHEN s.code = 'AFFECTEE'            THEN 1 END)    AS rec_affectees,
            SUM(CASE WHEN s.code = 'EN_COURS'            THEN 1 END)    AS rec_en_cours,
            SUM(CASE WHEN s.code = 'ATTENTE_INFO'        THEN 1 END)    AS rec_attente_info,
            SUM(CASE WHEN s.code = 'RESOLUE'             THEN 1 END)    AS rec_resolues,
            SUM(CASE WHEN s.code = 'CLOTUREE'            THEN 1 END)    AS rec_cloturees,
            SUM(CASE WHEN s.code = 'REJETEE'             THEN 1 END)    AS rec_rejetees

        FROM clients c
        LEFT JOIN reclamations r ON r.client_id = c.id
        LEFT JOIN statuts      s ON s.id         = r.statut_id

        GROUP BY
            c.id, c.nom, c.prenom, c.email,
            c.telephone, c.adresse, c.created_at, c.updated_at

        ORDER BY c.created_at DESC
    ");

    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast numeric strings to proper types
    foreach ($clients as &$client) {
        $client['total_reclamations'] = (int) $client['total_reclamations'];
        $client['rec_nouvelles'] = (int) ($client['rec_nouvelles'] ?? 0);
        $client['rec_attente_affectation'] = (int) ($client['rec_attente_affectation'] ?? 0);
        $client['rec_affectees'] = (int) ($client['rec_affectees'] ?? 0);
        $client['rec_en_cours'] = (int) ($client['rec_en_cours'] ?? 0);
        $client['rec_attente_info'] = (int) ($client['rec_attente_info'] ?? 0);
        $client['rec_resolues'] = (int) ($client['rec_resolues'] ?? 0);
        $client['rec_cloturees'] = (int) ($client['rec_cloturees'] ?? 0);
        $client['rec_rejetees'] = (int) ($client['rec_rejetees'] ?? 0);
    }
    unset($client);

    echo json_encode([
        'success' => true,
        'total' => count($clients),
        'clients' => $clients,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
