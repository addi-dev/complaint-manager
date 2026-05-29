<?php
header('Content-Type: application/json');
require __DIR__ . '/../../config/app.php';

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
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $check->execute([$id]);
    if (!$check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Utilisateur introuvable']);
        exit;
    }

    // Check email not taken by another user
    $email = trim($body['email']);
    $emailCheck = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
    $emailCheck->execute([$email, $id]);
    if ($emailCheck->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Email déjà utilisé']);
        exit;
    }

    $nom     = trim($body['nom']);
    $prenom  = trim($body['prenom']);
    $role_id = intval($body['role_id']);
    $actif   = intval($body['actif']);

    // Build query — only update password if provided
    if (!empty($body['mot_de_passe'])) {
        $mot_de_passe = password_hash($body['mot_de_passe'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            UPDATE utilisateurs
            SET nom = :nom, prenom = :prenom, email = :email,
                mot_de_passe = :mot_de_passe, role_id = :role_id, actif = :actif
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom'          => $nom,
            ':prenom'       => $prenom,
            ':email'        => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':role_id'      => $role_id,
            ':actif'        => $actif,
            ':id'           => $id,
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE utilisateurs
            SET nom = :nom, prenom = :prenom, email = :email,
                role_id = :role_id, actif = :actif
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom'     => $nom,
            ':prenom'  => $prenom,
            ':email'   => $email,
            ':role_id' => $role_id,
            ':actif'   => $actif,
            ':id'      => $id,
        ]);
    }

    echo json_encode(['success' => true, 'updated' => $stmt->rowCount()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;
