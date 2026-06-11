<?php

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';
Auth::requireRole('admin', 'superviseur');

$statuts = $pdo->query("SELECT id, code, libelle FROM statuts ORDER BY id")->fetchAll();
$categories = $pdo->query("SELECT id, libelle FROM categories_reclamation")->fetchAll();
$priorites = $pdo->query("SELECT id, libelle FROM priorites")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de bord | <?php echo APP_NAME ?></title>
    <?php echo CSRF::metaTag(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/app.css" />
    <link rel="stylesheet" href="../../assets/css/pages/reclamation-details.css" />
    <style>
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">LG</div>
            ReclamationOS
        </div>
        <div class="sidebar-section-label">Menu</div>
        <a class="nav-item active" href="index.php"><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item" href="users.php"><i class="fa-solid fa-users"></i>Utilisateurs</a>
        <a class="nav-item" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
        <a class="nav-item" href="clients.php"><i class="fa-solid fa-user"></i>Clients</a>
        <div class="sidebar-section-label">Other</div>
        <a class="nav-item" href="../../actions/auth/deconnexion.php"><i
                class="fa-solid fa-arrow-right-from-bracket"></i>Déconnexion</a>
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
            <!-- HEADER CARD -->
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
            <!-- DESCRIPTION -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-regular fa-file-lines"></i> Description
                    </div>
                </div>
                <div class="card-body">
                    <p class="desc-text" id="rec-description"></p>
                </div>
            </div>
            <!-- AFFECTATION -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-user-check"></i> Affectation</div>
                </div>
                <div class="card-body">
                    <div id="current-agent" style="margin-bottom:16px;font-size:14px;color:var(--text-label)">Aucun agent assigné</div>
                    <div style="display:flex;gap:10px;align-items:center">
                        <select id="agent-select" class="filter-select" style="flex:1">
                            <option value="">Sélectionner un agent...</option>
                        </select>
                        <input type="text" id="affectation-note" class="filter-select" placeholder="Note (facultatif)" style="flex:1" />
                        <button class="enroll-btn" onclick="assignAgent()"><i class="fa-solid fa-user-plus"></i> Affecter</button>
                    </div>
                </div>
            </div>

            <!-- STATUS UPDATE -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-rotate"></i> Changer le statut</div>
                </div>
                <div class="card-body">
                    <div style="display:flex;gap:10px;align-items:center">
                        <select id="statut-select" class="filter-select" style="flex:1">
                            <option value="">Sélectionner un statut...</option>
                            <?php foreach ($statuts as $s): ?>
                                <option value="<?= $s['code'] ?>"><?= $s['libelle'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" id="statut-details" class="filter-select" placeholder="Détails (facultatif)" style="flex:1" />
                        <button class="enroll-btn" onclick="updateStatut()"><i class="fa-solid fa-check"></i> Mettre à jour</button>
                    </div>
                </div>
            </div>

            <!-- AFFECTATION HISTORY -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> Historique des affectations</div>
                </div>
                <div class="card-body" id="affectations-container"></div>
            </div>

            <!-- AUDIT TRAIL -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-list-check"></i> Historique des actions</div>
                </div>
                <div class="card-body" id="historique-container"></div>
            </div>
            <!-- COMMENTS -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-regular fa-comment"></i>
                        Commentaires
                    </div>
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600" id="nombre_commentaires"></span>
                </div>
                <div class="card-body">
                    <div id="commentsList"></div>
                    <div class="comment-input">
                        <input id="newComment" placeholder="Ajouter un commentaire ou une précision…" />
                        <button class="send-btn" onclick="addComment()">
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- ATTACHMENTS -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-paperclip"></i>
                        Pièces jointes
                    </div>
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600" id="nombre_pieces"></span>
                </div>
                <div class="card-body" style="padding-top:12px;padding-bottom:12px" id="attachements-container"></div>
            </div>
        </div>
    </div>
    <!-- ENROLL MODAL -->
    <div class="overlay" id="overlay" onclick="closeOnOverlay(event)">
        <div class="modal">
            <form id="formModal" action="" method="POST">
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-header">
                    <h2 id="modalTitle"></h2>
                    <button type="button" class="close-btn" onclick="closeModal()">✕</button>
                </div>
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Objet</label>
                        <input type="text" id="f-objet" placeholder="Ex: Problème de connexion" name="objet" />
                    </div>
                    <div class="form-group full">
                        <label>Description</label>
                        <textarea id="f-description" placeholder="Décrivez votre problème ici..."
                            name="description"></textarea>
                    </div>
                    <div class="form-group full">
                        <label>Catégorie</label>
                        <select name="categorie_id" id="f-category">
                            <option value="">Sélectionner une categorie</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= $cat['libelle'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Pièces jointes (facultatif)</label>
                        <input type="file" name="pieces_jointes[]" id="" multiple>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button id="submitBtn" class="btn-submit" type="submit"></button>
                </div>
            </form>
        </div>
    </div>
    <!-- DELETE MODAL -->
    <div class="overlay" id="deleteOverlay" onclick="if(event.target===this)closeDeleteModal()">
        <form class="modal" id="deleteForm" method="POST" style="max-width:380px">
            <div class="modal-header">
                <h2>Delete </h2>
                <button type="button" class="close-btn" onclick="closeDeleteModal()">✕</button>
            </div>
            <p style="color:var(--text-label);font-size:14px;margin-bottom:22px;line-height:1.6">
                Are you sure you want to delete <strong id="deleteRowName"></strong>? This action cannot be undone.
            </p>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" id="confirmDelete" class="btn-submit" style="background:#dc2626">Delete</button>
            </div>
        </form>
    </div>
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    <script type="module" src="../../assets/js/app.js"></script>
    <script type="module" src="../../assets/js/pages/reclamation-detail.js"></script>
    <script>
        function openModal() {
            document.getElementById("modalTitle").textContent = "Insérer une nouvelle réclamation";
            document.getElementById("formModal").action = "../../actions/reclamations/ajouter.php";
            document.getElementById("formMethod").value = "POST";
            document.getElementById("submitBtn").textContent = "Insérer la réclamation";

            ["f-objet", "f-description", "f-category"].forEach(
                (id) => (document.getElementById(id).value = "")
            );

            document.getElementById("overlay").classList.add("open");
        }

        function closeModal() {
            document.getElementById('overlay').classList.remove('open');
        }

        function closeOnOverlay(e) {
            if (e.target === e.currentTarget) closeModal();
        }
    </script>
</body>

</html>