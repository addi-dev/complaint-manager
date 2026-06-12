<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('admin', 'superviseur', 'agent', 'client');
CSRF::verify();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Wrong method']);
    exit;
}
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (str_contains($contentType, 'application/json')) {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    $body = $_POST;
}
$reclamation_id = intval($body['reclamation_id'] ?? 0);
$contenu        = trim($body['contenu'] ?? '');
$interne        = !empty($body['interne']) ? 1 : 0;
$user_role      = $_SESSION['user_role'];
$user_id        = $_SESSION['user_id'];
if (!$reclamation_id || $contenu === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Contenu requis.']);
    exit;
}
if ($user_role === 'client' && $interne) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit;
}
try {
    $stmt = $pdo->prepare("SELECT id, client_id FROM reclamations WHERE id = ?");
    $stmt->execute([$reclamation_id]);
    $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$reclamation) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Réclamation introuvable.']);
        exit;
    }
    if ($user_role === 'client' && (int)$reclamation['client_id'] !== (int)$user_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
        exit;
    }
    if ($user_role === 'client') {
        $auteur_id = null;
        $client_id = $user_id;
    } else {
        $auteur_id = $user_id;
        $client_id = null;
    }
    $stmt = $pdo->prepare("
        INSERT INTO commentaires (reclamation_id, auteur_id, client_id, contenu, interne)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$reclamation_id, $auteur_id, $client_id, $contenu, $interne]);
    $comment_id = $pdo->lastInsertId();
    if (!$interne) {
        if ($user_role === 'client') {
            $stmtAgent = $pdo->prepare("SELECT agent_id FROM reclamations WHERE id = ?");
            $stmtAgent->execute([$reclamation_id]);
            $agent_id = $stmtAgent->fetchColumn();
            if ($agent_id) {
                $stmtNotif = $pdo->prepare("
                INSERT INTO notifications (utilisateur_id, reclamation_id, type, message)
                VALUES (?, ?, 'INFO', ?)
            ");
                $stmtNotif->execute([
                    $agent_id,
                    $reclamation_id,
                    "Le client a ajouté un commentaire sur la réclamation #{$reclamation_id}."
                ]);
            }
        } else {
            $stmtClient = $pdo->prepare("SELECT client_id FROM reclamations WHERE id = ?");
            $stmtClient->execute([$reclamation_id]);
            $notif_client_id = $stmtClient->fetchColumn();
            if ($notif_client_id) {
                $stmtNotif = $pdo->prepare("
                INSERT INTO notifications (client_id, reclamation_id, type, message)
                VALUES (?, ?, 'INFO', ?)
            ");
                $stmtNotif->execute([
                    $notif_client_id,
                    $reclamation_id,
                    "Un agent a répondu à votre réclamation #{$reclamation_id}."
                ]);
            }
        }
    }
    echo json_encode(['success' => true, 'id' => $comment_id]);
} catch (Exception $e) {
    error_log('[Commentaire Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
}
