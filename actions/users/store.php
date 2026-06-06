<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Validator.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/Response.php';
require __DIR__ . "/../../core/CSRF.php";
Auth::requireRole('admin');
CSRF::verify();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];

// ── Validation ────────────────────────────────────────────────────────────────
$validator = Validator::make($body)
    ->required('nom', 'Nom')
    ->minLength('nom', 2, 'Nom')
    ->maxLength('nom', 100, 'Nom')
    ->required('prenom', 'Prénom')
    ->minLength('prenom', 2, 'Prénom')
    ->maxLength('prenom', 100, 'Prénom')
    ->required('email', 'Email')
    ->email('email', 'Email')
    ->required('mot_de_passe', 'Mot de passe')
    ->minLength('mot_de_passe', 8, 'Mot de passe')
    ->required('role_id', 'Rôle')
    ->numeric('role_id', 'Rôle')
    ->required('actif', 'Actif')
    ->in('actif', ['0', '1', 0, 1], 'Actif');

if ($validator->fails()) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
}

// ── Sanitise (safe after validation) ─────────────────────────────────────────
try {
    $nom = trim($body['nom']);
    $prenom = trim($body['prenom']);
    $email = trim($body['email']);
    $mot_de_passe = password_hash($body['mot_de_passe'], PASSWORD_BCRYPT);
    $role_id = intval($body['role_id']);
    $actif = intval($body['actif']);

    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'errors' => ['email' => 'Email déjà utilisé']]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id, actif)
        VALUES (:nom, :prenom, :email, :mot_de_passe, :role_id, :actif)
    ");
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':mot_de_passe' => $mot_de_passe,
        ':role_id' => $role_id,
        ':actif' => $actif,
    ]);

    http_response_code(201);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    error_log('[API Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur interne du serveur est survenue.']);
}
exit;
