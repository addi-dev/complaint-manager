<?php
session_start();

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../core/Validator.php';
require_once __DIR__ . '/../../core/CSRF.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::methodNotAllowed();
}

CSRF::verify();

$body = json_decode(file_get_contents('php://input'), true) ?? [];

$step = (int)($body['step'] ?? 0);

// ── Step 1: Verify identity ───────────────────────────────────────────────────
if ($step === 1) {
    $_SESSION['reset_attempts']      = $_SESSION['reset_attempts']      ?? 0;
    $_SESSION['reset_lockout_until'] = $_SESSION['reset_lockout_until'] ?? 0;

    if (time() < $_SESSION['reset_lockout_until']) {
        $wait = ceil(($_SESSION['reset_lockout_until'] - time()) / 60);
        Response::error("Trop de tentatives. Réessayez dans $wait minute(s).");
    }
    
    $v = Validator::make($body)
        ->required('email', 'Adresse e-mail')
        ->email('email', 'Adresse e-mail')
        ->required('date_naissance', 'Date de naissance')
        ->required('numero_cin', 'Numéro CIN');

    if ($v->fails()) {
        Response::error($v->firstError());
    }

    $email         = trim($body['email']);
    $dateNaissance = trim($body['date_naissance']);
    $numeroCin     = trim($body['numero_cin']);

    try {
        $stmt = $pdo->prepare(
            'SELECT id FROM utilisateurs
             WHERE email = ? AND date_naissance = ? AND numero_cin = ? AND actif = 1
             LIMIT 1'
        );
        $stmt->execute([$email, $dateNaissance, $numeroCin]);
        $row = $stmt->fetch();

        if ($row) {
            Response::success('Identité vérifiée.', [
                'verified' => true,
                'id'       => $row['id'],
                'table'    => 'utilisateurs',
            ]);
        }

        $stmt = $pdo->prepare(
            'SELECT id FROM clients
             WHERE email = ? AND date_naissance = ? AND numero_cin = ?
             LIMIT 1'
        );
        $stmt->execute([$email, $dateNaissance, $numeroCin]);
        $row = $stmt->fetch();

        if ($row) {
            Response::success('Identité vérifiée.', [
                'verified' => true,
                'id'       => $row['id'],
                'table'    => 'clients',
            ]);
        }

        $_SESSION['reset_attempts']++;
        if ($_SESSION['reset_attempts'] >= 3) {
            $_SESSION['reset_lockout_until'] = time() + (15 * 60);
            $_SESSION['reset_attempts']      = 0;
            Response::error('Trop de tentatives. Réessayez dans 15 minutes.');
        }
        Response::error('Aucun compte ne correspond à ces informations.');
    } catch (PDOException $e) {
        error_log('[reset-password] step1: ' . $e->getMessage());
        Response::error('Une erreur est survenue. Veuillez réessayer.', 500);
    }
}

// ── Step 2: Update password ───────────────────────────────────────────────────
if ($step === 2) {

    $v = Validator::make($body)
        ->required('password', 'Mot de passe')
        ->minLength('password', 8, 'Mot de passe')
        ->required('confirm_password', 'Confirmation du mot de passe')
        ->required('id', 'Identifiant')
        ->in('table', ['utilisateurs', 'clients'], 'Table');

    if ($v->fails()) {
        Response::error($v->firstError());
    }

    $password        = $body['password'];
    $confirmPassword = $body['confirm_password'];
    $id              = (int)$body['id'];
    $table           = $body['table']; // whitelisted by Validator::in()

    if ($password !== $confirmPassword) {
        Response::error('Les mots de passe ne correspondent pas.');
    }

    if ($id <= 0) {
        Response::error('Identifiant invalide.');
    }

    try {
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("UPDATE {$table} SET mot_de_passe = ? WHERE id = ?");
        $stmt->execute([$hashed, $id]);

        if ($stmt->rowCount() === 0) {
            Response::error('Utilisateur introuvable.', 404);
        }

        Response::success('Mot de passe réinitialisé avec succès.', [
            'redirect' => '/complaint-manager/views/auth/login.php',
        ]);
    } catch (PDOException $e) {
        error_log('[reset-password] step2: ' . $e->getMessage());
        Response::error('Une erreur est survenue. Veuillez réessayer.', 500);
    }
}

Response::error('Étape invalide.', 400);
