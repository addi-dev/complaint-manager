<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('agent');
$agent_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("
        SELECT
            COUNT(*)                                                        AS total,
            SUM(CASE WHEN s.code = 'AFFECTEE'    THEN 1 ELSE 0 END)       AS affectees,
            SUM(CASE WHEN s.code = 'EN_COURS'    THEN 1 ELSE 0 END)       AS en_cours,
            SUM(CASE WHEN s.code = 'ATTENTE_INFO' THEN 1 ELSE 0 END)      AS attente_info,
            SUM(CASE WHEN s.code = 'RESOLUE'     THEN 1 ELSE 0 END)       AS resolues,
            SUM(CASE WHEN s.code = 'CLOTUREE'    THEN 1 ELSE 0 END)       AS cloturees,
            SUM(CASE WHEN s.code = 'REJETEE'     THEN 1 ELSE 0 END)       AS rejetees
        FROM reclamations r
        JOIN statuts s ON s.id = r.statut_id
        WHERE r.agent_id = ? AND r.deleted_at IS NULL
    ");
    $stmt->execute([$agent_id]);
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("
        SELECT ROUND(AVG(TIMESTAMPDIFF(HOUR, r.created_at, r.closed_at)), 1) AS avg_resolution_hours
        FROM reclamations r
        JOIN statuts s ON s.id = r.statut_id
        WHERE r.agent_id = ? AND r.closed_at IS NOT NULL AND r.deleted_at IS NULL
    ");
    $stmt->execute([$agent_id]);
    $avg = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("
        SELECT
            h.action, h.details, h.created_at,
            r.numero_unique, r.objet,
            s_new.libelle AS nouveau_statut
        FROM historique_actions h
        JOIN reclamations r ON r.id = h.reclamation_id
        LEFT JOIN statuts s_new ON s_new.id = h.nouveau_statut_id
        WHERE h.utilisateur_id = ?
        ORDER BY h.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$agent_id]);
    $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE utilisateur_id = ? AND lu = FALSE");
    $stmt->execute([$agent_id]);
    $unread_notifications = (int) $stmt->fetchColumn();
    echo json_encode([
        'success' => true,
        'stats'   => [
            'total'                => (int) $counts['total'],
            'affectees'            => (int) $counts['affectees'],
            'en_cours'             => (int) $counts['en_cours'],
            'attente_info'         => (int) $counts['attente_info'],
            'resolues'             => (int) $counts['resolues'],
            'cloturees'            => (int) $counts['cloturees'],
            'rejetees'             => (int) $counts['rejetees'],
            'avg_resolution_hours' => (float) ($avg['avg_resolution_hours'] ?? 0),
            'unread_notifications' => $unread_notifications,
        ],
        'recent_activity' => $recent,
    ]);
} catch (PDOException $e) {
    error_log('message', $e->getMessage());
    Response::error('Error serveur', 500);
}
