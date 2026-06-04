<?php
header('Content-Type: application/json');

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Validator.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];

if (empty($body)) {
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
    ->numeric('categorie_id', 'Catégorie');

if ($validator->fails()) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'errors' => $validator->errors()
    ]);
    exit;
}

// ── SESSION ─────────────────────────────
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$client_id = $_SESSION['user_id'];

// ── SANITIZE ─────────────────────────────
$objet = trim($body['objet']);
$description = trim($body['description']);

// ── AUTO DATA ─────────────────────────────
$numero_unique = "REC-" . date("Y") . rand(1000, 9999);

// ── INSERT ─────────────────────────────
try {
    $stmt = $pdo->prepare("
        INSERT INTO reclamations 
        (numero_unique, client_id, categorie_id, priorite_id, statut_id, objet, description)
        VALUES
        (:numero, :client, :categorie, 2, 1, :objet, :description)
    ");

    $stmt->execute([
        ':numero' => $numero_unique,
        ':client' => $client_id,
        ':categorie' => $body['categorie_id'],
        ':objet' => $objet,
        ':description' => $description,
    ]);

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'id' => $pdo->lastInsertId()
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}