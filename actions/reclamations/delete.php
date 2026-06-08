<?php

header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . "/../../core/CSRF.php";

Auth::requireRole('admin', 'superviseur');
CSRF::verify();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

$id = intval($_GET['id']);

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE reclamations SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'error' => 'Reclamation not found']);
        exit;
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('[API Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
}
exit;
