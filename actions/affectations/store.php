<?php
header('Content-Type: application/json');

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('admin', 'superviseur');
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
$agent_id       = intval($body['agent_id'] ?? 0);
$note           = trim($body['note'] ?? '');
$affecte_par    = $_SESSION['user_id'];

if (!$reclamation_id || !$agent_id) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Réclamation et agent requis.']);
    exit;
}

try {
    // Verify reclamation exists and get current statut
    $stmt = $pdo->prepare("SELECT id, statut_id FROM reclamations WHERE id = ?");
    $stmt->execute([$reclamation_id]);
    $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$reclamation) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Réclamation introuvable.']);
        exit;
    }

    // Verify agent exists
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ? AND actif = TRUE");
    $stmt->execute([$agent_id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Agent introuvable.']);
        exit;
    }

    // Get AFFECTEE statut id
    $stmt = $pdo->prepare("SELECT id FROM statuts WHERE code = 'AFFECTEE'");
    $stmt->execute();
    $statut_affectee = $stmt->fetchColumn();

    $ancien_statut_id = $reclamation['statut_id'];

    $pdo->beginTransaction();

    // Insert affectation record
    $stmt = $pdo->prepare("
        INSERT INTO affectations (reclamation_id, utilisateur_id, affecte_par, note)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$reclamation_id, $agent_id, $affecte_par, $note]);

    // Update reclamation: assign agent and update statut
    $stmt = $pdo->prepare("
        UPDATE reclamations SET agent_id = ?, statut_id = ? WHERE id = ?
    ");
    $stmt->execute([$agent_id, $statut_affectee, $reclamation_id]);

    // Log to historique_actions
    $stmt = $pdo->prepare("
        INSERT INTO historique_actions (reclamation_id, utilisateur_id, ancien_statut_id, nouveau_statut_id, action, details)
        VALUES (?, ?, ?, ?, 'AFFECTATION', ?)
    ");
    $stmt->execute([
        $reclamation_id,
        $affecte_par,
        $ancien_statut_id,
        $statut_affectee,
        $note ?: 'Réclamation affectée à un agent.'
    ]);

    // Notify the assigned agent
    $stmt = $pdo->prepare("
        INSERT INTO notifications (utilisateur_id, reclamation_id, type, message)
        VALUES (?, ?, 'AFFECTATION', ?)
    ");
    $stmt->execute([
        $agent_id,
        $reclamation_id,
        'Une nouvelle réclamation vous a été affectée.'
    ]);

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log('[Affectation Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
}
