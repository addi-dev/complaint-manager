<?php
session_start();

require __DIR__ . '/../../config/app.php';
$categories = $pdo->query("SELECT id, libelle FROM categories_reclamation")->fetchAll();
$priorites = $pdo->query("SELECT id, libelle FROM priorites")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de bord | <?php echo APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/app.css" />
    <style>
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                LG
            </div>
            ReclamationOS
        </div>
        <div class="sidebar-section-label">Menu</div>
        <a class="nav-item" href="index.php"><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item active" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Mes
            réclamations</a>
        <a class="nav-item" href="clients.php"><i class="fa-solid fa-user"></i>Clients</a>
        <div class="sidebar-section-label">Other</div>
        <a class="nav-item" href="../../actions/auth/logout.php"><i
                class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
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
                    <h1>Mes Réclamations</h1>
                    <div class="sub" id="enrollCount"></div>
                </div>
                <button class="enroll-btn" onclick="openModal()">
                    <i class="fa-solid fa-plus"></i>
                    Ajouter une réclamation
                </button>
            </div>
            <div class="card">
                <div class="table-toolbar">
                    <div class="tb-search">
                        <svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Rechercher..." />
                    </div>
                    <select class="filter-select" id="roleFilter">

                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="nouvelle">Nouvelle</option>
                        <option value="attente_affectation">En attente d'affectation</option>
                        <option value="affectee">Affectée</option>
                        <option value="en_cours">En cours de traitement</option>
                        <option value="attente_info">En attente d'informations</option>
                        <option value="resolue">Résolue</option>
                        <option value="cloturee">Clôturée</option>
                        <option value="rejetee">Rejetée</option>
                    </select>
                    <select class="filter-select" id="priorityFilter">
                        <option value="">Toutes les priorités</option>
                        <option value="1">Faible</option>
                        <option value="2">Normale</option>
                        <option value="3">Haute</option>
                        <option value="4">Critique</option>
                    </select>
                    <select class="filter-select" id="categoryFilter">
                        <option value="">Toutes les catégories</option>
                        <option value="1">Facturation</option>
                        <option value="2">Livraison</option>
                        <option value="3">Qualité produit</option>
                        <option value="4">Service après-vente</option>
                        <option value="5">Remboursement</option>
                        <option value="6">Délai de livraison</option>
                        <option value="7">Produit manquant</option>
                        <option value="8">Produit endommagé</option>
                        <option value="9">Erreur de commande</option>
                        <option value="10">Annulation commande</option>
                        <option value="11">Compte client</option>
                        <option value="12">Paiement en ligne</option>
                        <option value="13">Offre promotionnelle</option>
                        <option value="14">Communication commerciale</option>
                        <option value="15">Service client</option>
                        <option value="16">Garantie produit</option>
                        <option value="17">Retour marchandise</option>
                        <option value="18">Transport / Transporteur</option>
                        <option value="19">Conformité réglementaire</option>
                        <option value="20">Confidentialité des données</option>
                        <option value="21">Application mobile</option>
                        <option value="22">Site web</option>
                        <option value="23">Abonnement</option>
                        <option value="24">Résiliation</option>
                        <option value="25">Devis / Estimation</option>
                    </select>
                    <select class="filter-select" id="sortSelect">
                        <option value="">Par défaut</option>
                        <option value="name">Nom A→Z</option>
                        <option value="name_desc">Nom Z→A</option>
                        <option value="date_desc">Plus récents</option>
                        <option value="date_asc">Plus anciens</option>
                    </select>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Objet</th>
                                <th>Catégorie</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Créée le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <div class="tf-info" id="tfInfo"></div>
                    <div class="pagination" id="pagination"></div>
                </div>
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
                    <div class="form-group">
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
                    <div class="form-group">
                        <label>Priorité</label>
                        <select name="priorite_id" id="f-priority">
                            <option value="">Sélectionner une priorité</option>
                            <?php foreach ($priorites as $priority): ?>
                                <option value="<?= $priority['id'] ?>">
                                    <?= $priority['libelle'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Pièces jointes</label>
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
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    <script type="module" src="../../assets/js/app.js"></script>
    <script type="module" src="../../assets/js/pages/mes-reclamations.js"></script>
    <script>
        function openModal() {
            document.getElementById("modalTitle").textContent = "Insérer une nouvelle réclamation";
            document.getElementById("formModal").action = "../../actions/reclamations/store.php";
            document.getElementById("formMethod").value = "POST";
            document.getElementById("submitBtn").textContent = "Insérer la réclamation";

            ["f-objet", "f-description", "f-category", "f-priority"].forEach(
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