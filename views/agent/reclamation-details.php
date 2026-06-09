<?php
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('agent');
$statuts_agent = $pdo->query("SELECT id, code, libelle FROM statuts WHERE code IN ('EN_COURS','ATTENTE_INFO','RESOLUE') ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Réclamation | <?php echo APP_NAME ?></title>
    <?php echo CSRF::metaTag(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/app.css" />
    <link rel="stylesheet" href="../../assets/css/pages/reclamation-details.css" />
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">LG</div>ReclamationOS
        </div>
        <div class="sidebar-section-label">Menu</div>
        <a class="nav-item" href="index.php"><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item active" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
        <a class="nav-item" href="notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a>
        <a class="nav-item" href="historiques.php"><i class="fa-solid fa-clock-rotate-left"></i>Historiques</a>
        <div class="sidebar-section-label">Other</div>
        <a class="nav-item" href="../../actions/auth/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Déconnexion</a>
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
            <div class="header-card">
                <div class="header-top">
                    <div class="header-left">
                        <div class="rec-ref" id="rec-ref"></div>
                        <div class="rec-objet" id="rec-objet"></div>
                    </div>
                    <div class="header-badges">
                        <span class="r-status-badge" id="rec-status"></span>
                        <span class="priority-badge" id="rec-priority"></span>
                    </div>
                </div>
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-label">Catégorie</div>
                        <div class="meta-val" id="rec-category"></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Créée le</div>
                        <div class="meta-val" id="rec-created"></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Mise à jour</div>
                        <div class="meta-val" id="rec-updated"></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Clôturée le</div>
                        <div class="meta-val muted" id="red-closed"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-regular fa-file-lines"></i> Description</div>
                </div>
                <div class="card-body">
                    <p class="desc-text" id="rec-description"></p>
                </div>
            </div>
            <!-- STATUS UPDATE (agent only transitions) -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-rotate"></i> Changer le statut</div>
                </div>
                <div class="card-body">
                    <div style="display:flex;gap:10px;align-items:center">
                        <select id="statut-select" class="filter-select" style="flex:1">
                            <option value="">Sélectionner un statut...</option>
                            <?php foreach ($statuts_agent as $s): ?>
                                <option value="<?= $s['code'] ?>"><?= $s['libelle'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" id="statut-details" class="filter-select" placeholder="Détails (facultatif)" style="flex:1" />
                        <button class="enroll-btn" onclick="updateStatut()"><i class="fa-solid fa-check"></i> Mettre à jour</button>
                    </div>
                </div>
            </div>
            <!-- COMMENTS -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-regular fa-comment"></i> Commentaires</div>
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600" id="nombre_commentaires"></span>
                </div>
                <div class="card-body">
                    <div id="commentsList"></div>
                    <div class="comment-input">
                        <input id="newComment" placeholder="Ajouter un commentaire ou une précision…" />
                        <button class="send-btn" onclick="addComment()"><i class="fa-regular fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <!-- ATTACHMENTS -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-paperclip"></i> Pièces jointes</div>
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600" id="nombre_pieces"></span>
                </div>
                <div class="card-body" style="padding-top:12px;padding-bottom:12px" id="attachements-container"></div>
            </div>
            <!-- AUDIT TRAIL -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-list-check"></i> Historique des actions</div>
                </div>
                <div class="card-body" id="historique-container"></div>
            </div>
        </div>
    </div>
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    <script type="module" src="../../assets/js/app.js"></script>
    <script type="module" src="../../assets/js/pages/agent/reclamation-detail.js"></script>
</body>

</html>