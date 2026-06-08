<?php
header('Content-Type: application/json');

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Validator.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . "/../../core/CSRF.php";
Auth::requireRole('client', 'agent');
CSRF::verify();

// ── METHOD CHECK ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

// ── SESSION ─────────────────────────────
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$client_id = $_SESSION['user_id'];

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (str_contains($contentType, 'application/json')) {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    $body = $_POST;
}

// ── Validation ─────────────────────────────
$validator = Validator::make($body)
    ->required('objet', 'Objet')
    ->minLength('objet', 5, 'Objet')
    ->maxLength('objet', 255, 'Objet')

    ->required('description', 'Description')
    ->minLength('description', 10, 'Description')

    ->required('categorie_id', 'Catégorie')
    ->numeric('categorie_id', 'Catégorie')

    ->fileTypes('pieces_jointes', ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'], 'JPEG, PNG, GIF, PDF')
    ->fileMaxSize('pieces_jointes', 5 * 1024 * 1024, '5');

if ($validator->fails()) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'errors' => $validator->errors()
    ]);
    exit;
}

// ── SANITIZE ─────────────────────────────
$objet       = trim($body['objet']);
$description = trim($body['description']);

// ── FILE CONFIG ─────────────────────────────
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
$max_size      = 5 * 1024 * 1024;
$upload_dir    = __DIR__ . '/../../storage/pieces_jointes/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// ── INSERT ─────────────────────────────
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO reclamations 
        (numero_unique, client_id, categorie_id, priorite_id, statut_id, objet, description)
        VALUES
        (:numero, :client, :categorie, 2, 1, :objet, :description)
    ");

    $stmt->execute([
        ':numero'    => '',
        ':client'    => $client_id,
        ':categorie' => $body['categorie_id'],
        ':objet'     => $objet,
        ':description' => $description,
    ]);

    $reclamation_id = $pdo->lastInsertId();

    // ── BUILD & UPDATE numero_unique ─────────────────────────────
    $numero_unique = "REC-" . date("Y") . str_pad($reclamation_id, 6, '0', STR_PAD_LEFT);
    $pdo->prepare("UPDATE reclamations SET numero_unique = ? WHERE id = ?")
        ->execute([$numero_unique, $reclamation_id]);

    // ── FILE UPLOADS ─────────────────────────────
    if (!empty($_FILES['pieces_jointes']['name'][0])) {
        $files = $_FILES['pieces_jointes'];
        $count = count($files['name']);

        $stmtFile = $pdo->prepare("
            INSERT INTO pieces_jointes (reclamation_id, nom_fichier, chemin, type_mime, taille)
            VALUES (:reclamation_id, :nom_fichier, :chemin, :type_mime, :taille)
        ");

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (!in_array($files['type'][$i], $allowed_types)) continue;
            if ($files['size'][$i] > $max_size) continue;

            $ext         = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $safe_name   = uniqid('pj_', true) . '.' . $ext;
            $destination = $upload_dir . $safe_name;

            if (!move_uploaded_file($files['tmp_name'][$i], $destination)) continue;

            $stmtFile->execute([
                ':reclamation_id' => $reclamation_id,
                ':nom_fichier'    => $files['name'][$i],
                ':chemin'         => 'storage/pieces_jointes/' . $safe_name,
                ':type_mime'      => $files['type'][$i],
                ':taille'         => $files['size'][$i],
            ]);
        }
    }

    $pdo->commit();

    http_response_code(201);
    echo json_encode(['success' => true, 'id' => $reclamation_id, 'numero' => $numero_unique]);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
