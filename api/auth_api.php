<?php
// api/auth_api.php
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';

Auth::init();

$body = json_decode(file_get_contents('php://input'), true);

$stmt = $pdo->prepare("
    SELECT u.*, r.nom AS role
    FROM utilisateurs u
    JOIN roles r ON r.id = u.role_id
    WHERE u.email = ?
");
$stmt->execute([$body['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !Auth::attempt($user, $body['password'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Identifiants invalides.']);
    exit;
}

echo json_encode(['success' => true, 'user' => Auth::user()]);