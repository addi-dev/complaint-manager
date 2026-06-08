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

$body = json_decode(file_get_contents('php://input'), true);

try {
    $id = intval($_GET['id'] ?? $body['id'] ?? 0);

    if (!$id) {
        echo json_encode(['success' => false, 'error' => 'ID manquant']);
        exit;
    }

    // Check user exists
    $check = $pdo->prepare("SELECT id FROM reclamations WHERE id = ?");
    $check->execute([$id]);
    if (!$check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Reclamation introuvable']);
        exit;
    }

    $objet = trim($body['objet']);
    $description = trim($body['description']);
    $categorie_id = trim($body['categorie_id']);
    $stmt = $pdo->prepare("
            UPDATE reclamations
            SET objet = :objet, description = :description, categorie_id = :categorie_id,
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
