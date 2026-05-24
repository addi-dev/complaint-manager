<?php

require __DIR__ . '/../config/app.php';

$role_id      = intval($_POST['role_id']);
$nom          = trim($_POST['nom']);
$prenom       = trim($_POST['prenom']);
$email        = trim($_POST['email']);
$mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
$actif        = intval($_POST['actif']);

$stmt = $pdo->prepare("
    INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe, actif)
    VALUES (:role_id, :nom, :prenom, :email, :mot_de_passe, :actif)
");

$stmt->execute([
    ':role_id'      => $role_id,
    ':nom'          => $nom,
    ':prenom'       => $prenom,
    ':email'        => $email,
    ':mot_de_passe' => $mot_de_passe,
    ':actif'        => $actif
]);

header('Location: users.php');
exit;
