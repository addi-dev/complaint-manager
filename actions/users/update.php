<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Validator.php';
require __DIR__ . "/../../core/CSRF.php";
require __DIR__ . "/../../core/Auth.php";
CSRF::verify();
Auth::requireRole('admin');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];

// ── Validate ID ───────────────────────────────────────────────────────────────
$id = intval($_GET['id'] ?? $body['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID manquant']);
    exit;
}

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
    ->required('role_id', 'Rôle')
    ->numeric('role_id', 'Rôle')
    ->required('actif', 'Actif')
    ->in('actif', ['0', '1', 0, 1], 'Actif');

// Password only validated if provided (optional on update)
if (!empty($body['mot_de_passe'])) {
    $validator->minLength('mot_de_passe', 8, 'Mot de passe');
}

if ($validator->fails()) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
}

// ── DB ────────────────────────────────────────────────────────────────────────
try {
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $check->execute([$id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Utilisateur introuvable']);
        exit;
    }

    $email = trim($body['email']);
    $emailCheck = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
    $emailCheck->execute([$email, $id]);
    if ($emailCheck->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'errors' => ['email' => 'Email déjà utilisé']]);
        exit;
    }

    $nom = trim($body['nom']);
    $prenom = trim($body['prenom']);
    $role_id = intval($body['role_id']);
    $actif = intval($body['actif']);

    if (!empty($body['mot_de_passe'])) {
        $mot_de_passe = password_hash($body['mot_de_passe'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            UPDATE utilisateurs
            SET nom = :nom, prenom = :prenom, email = :email,
                mot_de_passe = :mot_de_passe, role_id = :role_id, actif = :actif
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':role_id' => $role_id,
            ':actif' => $actif,
            ':id' => $id,
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE utilisateurs
            SET nom = :nom, prenom = :prenom, email = :email,
                role_id = :role_id, actif = :actif
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':role_id' => $role_id,
            ':actif' => $actif,
            ':id' => $id,
        ]);
    }

    echo json_encode(['success' => true, 'updated' => $stmt->rowCount()]);
} catch (Exception $e) {
    error_log('[API Error] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
}
exit;
