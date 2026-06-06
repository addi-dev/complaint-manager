<?php

require __DIR__ . '/../../config/app.php';

// fetch caregories for filter dropdown
$stmt = $pdo->query("SELECT * FROM categories_reclamation ORDER BY libelle");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// fetch priorities for filter dropdown
$stmt = $pdo->query("SELECT * FROM priorites ORDER BY libelle");
$priorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
// fetch status for filter dropdown
$stmt = $pdo->query("SELECT * FROM statuts ORDER BY libelle");
$statuts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Réclamations | <?php echo APP_NAME ?></title>
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
        <a class="nav-item" href="users.php"><i class="fa-solid fa-users"></i>Utilisateurs</a>
        <a class="nav-item" href="reclamations.php"><i
                class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
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
                    <h1>Reclamations</h1>
                    <div class="sub" id="enrollCount"></div>
                </div>
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

                    <select class="filter-select" id="sortSelect">
                        <option value="">Par défaut</option>
                        <option value="name">Nom A→Z</option>
                        <option value="name_desc">Nom Z→A</option>
                        <option value="date_desc">Plus récents</option>
                        <option value="date_asc">Plus anciens</option>
                    </select>

                    <select class="filter-select" id="categoryFilter">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select class="filter-select" id="priorityFilter">
                        <option value="">Toutes les priorités</option>
                        <?php foreach ($priorites as $priority): ?>
                            <option value="<?= $priority['id'] ?>">
                                <?= htmlspecialchars($priority['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select class="filter-select" id="statusFilter">
                        <option value="">Tous les statuts</option>

                        <?php foreach ($statuts as $s): ?>
                            <option value="<?= strtolower($s['code']) ?>">
                                <?= htmlspecialchars($s['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Client</th>
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
    <script type="module" src="../../assets/js/pages/agent/reclamations.js"></script>
</body>

</html>