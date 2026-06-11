<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('client');
CSRF::verify();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Wrong method']);
    exit;
}
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$client_id = $_SESSION['user_id'];
$nom       = trim($body['nom'] ?? '');
$prenom    = trim($body['prenom'] ?? '');
$email     = trim($body['email'] ?? '');
$telephone = trim($body['telephone'] ?? '');
$adresse   = trim($body['adresse'] ?? '');
if (!$nom || !$prenom || !$email) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nom, prénom et email requis.']);
    exit;
}
try {
    $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ? AND id != ?");
    $stmt->execute([$email, $client_id]);
    if ($stmt->fetch()) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
        exit;
    }
    $stmt = $pdo->prepare("
        UPDATE clients SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ? WHERE id = ?
    ");
    $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $client_id]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('[UpdateProfile Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
}
