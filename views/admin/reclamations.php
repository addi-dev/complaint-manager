<?php
session_start();

require_once __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../config/app.php';

Auth::requireRole('admin');

// fetch roles for dropdown
$stmt = $pdo->query("SELECT id, nom FROM roles ORDER BY nom");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reclamations | <?php echo APP_NAME ?></title>
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
        <a class="nav-item" href=""><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item" href="users.php"><i class="fa-solid fa-users"></i>Utilisateurs</a>
        <a class="nav-item active" href="reclamations.php"><i
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
                    <select class="filter-select" id="roleFilter">

                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="">Tous</option>
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
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
                                <th>Client</th>
                                <th>Objet</th>
                                <th>Catégorie</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Agent</th>
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
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    <script type="module" src="../../assets/js/app.js"></script>
    <script type="module" src="../../assets/js/pages/reclamations.js"></script>
</body>

</html>