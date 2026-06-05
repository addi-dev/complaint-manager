<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../core/Auth.php';
Auth::requireRole('admin', 'superviseur');
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
    $check = $pdo->prepare("SELECT id FROM clients WHERE id = ?");
    $check->execute([$id]);
    if (!$check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Client introuvable']);
        exit;
    }

    // Check email not taken by another user
    $email = trim($body['email']);
    $emailCheck = $pdo->prepare("SELECT id FROM clients WHERE email = ? AND id != ?");
    $emailCheck->execute([$email, $id]);
    if ($emailCheck->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Email déjà utilisé']);
        exit;
    }

    $nom = trim($body['nom']);
    $prenom = trim($body['prenom']);
    $telephone = trim($body['telephone']);
    $adresse = trim($body['adresse']);

    // Build query — only update password if provided
    if (!empty($body['mot_de_passe'])) {
        $mot_de_passe = password_hash($body['mot_de_passe'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            UPDATE clients
            SET nom = :nom, prenom = :prenom, email = :email,
                mot_de_passe = :mot_de_passe, telephone = :telephone, adresse = :adresse
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
            ':id' => $id,
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE clients
            SET nom = :nom, prenom = :prenom, email = :email,
                telephone = :telephone, adresse = :adresse
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
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
