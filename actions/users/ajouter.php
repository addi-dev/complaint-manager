<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Validator.php';
require __DIR__ . '/../../core/Auth.php';
require_once __DIR__ . '/../../core/Response.php';
require __DIR__ . "/../../core/CSRF.php";
require __DIR__ . "/../../core/Helpers.php";
Auth::requireRole('admin');
CSRF::verify();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$validator = Validator::make($body)
    ->required('nom', 'Nom')
    ->minLength('nom', 2, 'Nom')
    ->maxLength('nom', 100, 'Nom')
    ->required('prenom', 'Prénom')
    ->minLength('prenom', 2, 'Prénom')
    ->maxLength('prenom', 100, 'Prénom')
    ->required('date_naissance', 'Date de naissance')
    ->minAge('date_naissance', 18, "Date de naissance")
    ->required('numero_cin', 'CIN')
    ->required('email', 'Email')
    ->email('email', 'Email')
    ->required('role_id', 'Rôle')
    ->numeric('role_id', 'Rôle')
    ->required('actif', 'Actif')
    ->in('actif', ['0', '1', 0, 1], 'Actif');
if ($validator->fails()) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
}
try {
    $nom = trim($body['nom']);
    $prenom = trim($body['prenom']);
    $date_naissance = trim($body['date_naissance']);
    $numero_cin = trim($body['numero_cin']);
    $email = trim($body['email']);
    $mot_de_passe_brut = Helpers::generer_mot_de_passe();
    $mot_de_passe = password_hash($mot_de_passe_brut, PASSWORD_BCRYPT);
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
        INSERT INTO utilisateurs (nom, prenom, date_naissance, numero_cin, email, mot_de_passe, role_id, actif)
        VALUES (:nom, :prenom, :date_naissance, :numero_cin, :email, :mot_de_passe, :role_id, :actif)
    ");
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':date_naissance' => $date_naissance,
        ':numero_cin' => $numero_cin,
        ':email' => $email,
        ':mot_de_passe' => $mot_de_passe,
        ':role_id' => $role_id,
        ':actif' => $actif,
    ]);
    http_response_code(201);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'mot_de_passe' => $mot_de_passe_brut]);
} catch (Exception $e) {
    error_log('[API Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur interne du serveur est survenue.']);
}
exit;
