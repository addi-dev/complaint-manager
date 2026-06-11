<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin', 'superviseur', 'agent');
$user_id   = $_SESSION['user_id'];
$action    = $_GET['action'] ?? 'list';
try {
    if ($action === 'list') {
        $stmt = $pdo->prepare("
            SELECT
                n.id, n.type, n.message, n.lu, n.created_at,
                r.numero_unique, r.objet
            FROM notifications n
            LEFT JOIN reclamations r ON r.id = n.reclamation_id
            WHERE n.utilisateur_id = ?
            ORDER BY n.created_at DESC
            LIMIT 50
        ");
        $stmt->execute([$user_id]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE utilisateur_id = ? AND lu = FALSE");
        $stmt->execute([$user_id]);
        $unread = (int) $stmt->fetchColumn();
        echo json_encode([
            'success'       => true,
            'notifications' => $notifications,
            'unread'        => $unread,
        ]);
    } elseif ($action === 'mark_read') {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            $stmt = $pdo->prepare("UPDATE notifications SET lu = TRUE WHERE id = ? AND utilisateur_id = ?");
            $stmt->execute([$id, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE notifications SET lu = TRUE WHERE utilisateur_id = ?");
            $stmt->execute([$user_id]);
        }
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action invalide.']);
    }
} catch (PDOException $e) {
    error_log('message', $e->getMessage());
    Response::error('Error serveur', 500);
}
