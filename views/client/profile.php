<?php
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('client');

$client_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, nom, prenom, email, telephone, adresse, numero_cin, date_naissance FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php echo CSRF::metaTag(); ?>
    <title>Mon profil | <?php echo APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/app.css" />
    <style>
        .profile-card {
            max-width: 600px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow-sm);
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="<?php echo APP_LOGO ?>"></i>
            </div>
            <?php echo APP_NAME ?>
        </div>
        <div class="sidebar-section-label">Menu</div>
        <a class="nav-item" href="index.php"><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Mes réclamations</a>
        <a class="nav-item" href="notifications.php">
            <i class="fa-solid fa-bell"></i>Notifications
            <span class="badge-notif" id="sidebarNotifBadge" style="display:none"></span>
        </a>
        <div class="sidebar-section-label">Other</div>
        <a class="nav-item active" href="profile.php"><i class="fa-solid fa-user"></i>Mon profil</a>
        <a class="nav-item" href="../../actions/auth/deconnexion.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Déconnexion</a>
    </aside>
    <div class="main">
        <header class="topbar">
            <div class="topbar-actions">
                <div class="user-chip">
                    <div class="avatar" id="avatar"></div>
                    <div class="user-info">
                        <div class="name"></div>
                        <div class="role"></div>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="page-header">
                <div>
                    <h1>Mon profil</h1>
                    <div class="sub">Vos informations personnelles</div>
                </div>
            </div>
            <div class="profile-card">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($client['prenom'], 0, 1) . substr($client['nom'], 0, 1)); ?>
                </div>
                <form id="profileForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" id="f-prenom" value="<?= htmlspecialchars($client['prenom']) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" id="f-nom" value="<?= htmlspecialchars($client['nom']) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="f-email" value="<?= htmlspecialchars($client['email']) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="text" id="f-telephone" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>" />
                        </div>
                        <div class="form-group full">
                            <label>Adresse</label>
                            <textarea id="f-adresse"><?= htmlspecialchars($client['adresse'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="modal-actions" style="margin-top:20px">
                        <button type="submit" class="btn-submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    <script type="module" src="../../assets/js/app.js"></script>
    <script type="module">
        import {
            showToast
        } from "../../assets/js/lib/toast.js";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const res = await fetch('../../actions/clients/mise_a_jour_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({
                    nom: document.getElementById('f-nom').value,
                    prenom: document.getElementById('f-prenom').value,
                    email: document.getElementById('f-email').value,
                    telephone: document.getElementById('f-telephone').value,
                    adresse: document.getElementById('f-adresse').value,
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast('Profil mis à jour avec succès');
            } else {
                showToast(data.message || 'Échec de la mise à jour');
            }
        });
    </script>
</body>

</html>