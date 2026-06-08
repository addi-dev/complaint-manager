<?php
header('Content-Type: application/json');

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('admin', 'superviseur', 'agent');
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

$reclamation_id  = intval($body['reclamation_id'] ?? 0);
$nouveau_statut_code = strtoupper(trim($body['statut_code'] ?? ''));
$details         = trim($body['details'] ?? '');
$user_id         = $_SESSION['user_id'];
$user_role       = $_SESSION['user_role'];

if (!$reclamation_id || !$nouveau_statut_code) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Réclamation et statut requis.']);
    exit;
}

// Transition rules per role
$transitions = [
    'admin'       => ['NOUVELLE', 'ATTENTE_AFFECTATION', 'AFFECTEE', 'EN_COURS', 'ATTENTE_INFO', 'RESOLUE', 'CLOTUREE', 'REJETEE'],
    'superviseur' => ['NOUVELLE', 'ATTENTE_AFFECTATION', 'AFFECTEE', 'EN_COURS', 'ATTENTE_INFO', 'RESOLUE', 'CLOTUREE', 'REJETEE'],
    'agent'       => ['EN_COURS', 'ATTENTE_INFO', 'RESOLUE'],
];

if (!in_array($nouveau_statut_code, $transitions[$user_role] ?? [])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Transition de statut non autorisée.']);
    exit;
}

try {
    // Get current reclamation
    $stmt = $pdo->prepare("SELECT id, statut_id, agent_id FROM reclamations WHERE id = ?");
    $stmt->execute([$reclamation_id]);
    $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$reclamation) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Réclamation introuvable.']);
        exit;
    }

    // Agent can only update their own assigned reclamations
    if ($user_role === 'agent' && (int)$reclamation['agent_id'] !== (int)$user_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
        exit;
    }

    // Get new statut id
    $stmt = $pdo->prepare("SELECT id FROM statuts WHERE code = ?");
    $stmt->execute([$nouveau_statut_code]);
    $nouveau_statut_id = $stmt->fetchColumn();
    if (!$nouveau_statut_id) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Statut invalide.']);
        exit;
    }

    $ancien_statut_id = $reclamation['statut_id'];

    $pdo->beginTransaction();

    // Update statut, set closed_at if closing
    $closed_at = in_array($nouveau_statut_code, ['CLOTUREE', 'REJETEE']) ? date('Y-m-d H:i:s') : null;
    $stmt = $pdo->prepare("
        UPDATE reclamations SET statut_id = ?, closed_at = ? WHERE id = ?
    ");
    $stmt->execute([$nouveau_statut_id, $closed_at, $reclamation_id]);

    // Log to historique_actions
    $stmt = $pdo->prepare("
        INSERT INTO historique_actions (reclamation_id, utilisateur_id, ancien_statut_id, nouveau_statut_id, action, details)
        VALUES (?, ?, ?, ?, 'CHANGEMENT_STATUT', ?)
    ");
    $stmt->execute([
        $reclamation_id,
        $user_id,
        $ancien_statut_id,
        $nouveau_statut_id,
        $details ?: 'Statut mis à jour.'
    ]);

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log('[UpdateStatut Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
}
