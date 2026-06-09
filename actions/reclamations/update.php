<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . "/../../core/Auth.php";
require __DIR__ . "/../../core/CSRF.php";
Auth::requireRole('admin', 'client');
CSRF::verify();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (str_contains($contentType, 'application/json')) {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    $body = $_POST;
}

try {
    $id = intval($_GET['id'] ?? 0);

    if (!$id) {
        echo json_encode(['success' => false, 'error' => 'ID manquant']);
        exit;
    }

    $check = $pdo->prepare("
    SELECT r.id, s.code AS statut_code 
    FROM reclamations r 
    JOIN statuts s ON s.id = r.statut_id 
    WHERE r.id = ?
    ");
    $check->execute([$id]);
    $reclamation = $check->fetch();
    if (!$reclamation) {
        echo json_encode(['success' => false, 'message' => 'Reclamation introuvable']);
        exit;
    }
    if (in_array($reclamation['statut_code'], ['CLOTUREE', 'REJETEE'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Cette réclamation est clôturée et ne peut plus être modifiée.']);
        exit;
    }

    $objet = trim($body['objet']);
    $description = trim($body['description']);
    $categorie_id = trim($body['categorie_id']);
    $stmt = $pdo->prepare("
            UPDATE reclamations
            SET objet = :objet, description = :description, categorie_id = :categorie_id
            WHERE id = :id
        ");
    $stmt->execute([
        ':objet' => $objet,
        ':description' => $description,
        ':categorie_id' => $categorie_id,
        ':id' => $id,
    ]);
    echo json_encode(['success' => true, 'updated' => $stmt->rowCount()]);
} catch (Exception $e) {
    error_log('[API Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
