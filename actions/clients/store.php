<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Wrong method']);
    exit;
}

// Read JSON body
$body = json_decode(file_get_contents('php://input'), true);

try {
    $nom          = trim($body['nom']);
    $prenom       = trim($body['prenom']);
    $email        = trim($body['email']);
    $mot_de_passe = password_hash($body['mot_de_passe'], PASSWORD_BCRYPT);
    $telephone      = intval($body['telephone']);
    $adresse        = intval($body['adresse']);

    $check = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Email déjà utilisé']);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO clients (nom, prenom, email, mot_de_passe, telephone, adresse)
        VALUES (:nom, :prenom, :email, :mot_de_passe, :telephone, :adresse)
    ");

    $stmt->execute([
        ':nom'          => $nom,
        ':prenom'       => $prenom,
        ':email'        => $email,
        ':mot_de_passe' => $mot_de_passe,
        ':telephone'      => $telephone,
        ':adresse'        => $adresse,
    ]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;
