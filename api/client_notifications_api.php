<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('client');
$client_id = $_SESSION['user_id'];
$action    = $_GET['action'] ?? 'list';
try {
    if ($action === 'list') {
        $stmt = $pdo->prepare("
            SELECT
                n.id, n.type, n.message, n.lu, n.created_at,
                r.numero_unique, r.objet
            FROM notifications n
            LEFT JOIN reclamations r ON r.id = n.reclamation_id
            WHERE n.client_id = ?
            ORDER BY n.created_at DESC
            LIMIT 50
        ");
        $stmt->execute([$client_id]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE client_id = ? AND lu = FALSE");
        $stmt->execute([$client_id]);
        $unread = (int) $stmt->fetchColumn();
        echo json_encode([
            'success'       => true,
            'notifications' => $notifications,
            'unread'        => $unread,
        ]);
    } elseif ($action === 'mark_read') {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            $stmt = $pdo->prepare("UPDATE notifications SET lu = TRUE WHERE id = ? AND client_id = ?");
            $stmt->execute([$id, $client_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE notifications SET lu = TRUE WHERE client_id = ?");
            $stmt->execute([$client_id]);
        }
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action invalide.']);
    }
} catch (PDOException $e) {
    error_log('[Client Notifications Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
}
