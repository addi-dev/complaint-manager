<?php
session_start();

require __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../core/CSRF.php';

Auth::requireRole('admin', 'superviseur');

// fetch roles for dropdown
$stmt = $pdo->query("SELECT id, nom FROM roles ORDER BY nom");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php echo CSRF::metaTag(); ?>
    <title>Clients | <?php echo APP_NAME ?></title>
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
        <a class="nav-item" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
        <a class="nav-item active" href="clients.php"><i class="fa-solid fa-user"></i>Clients</a>
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
                    <h1>Clients</h1>
                    <div class="sub" id="enrollCount"></div>
                </div>
                <button class="enroll-btn" onclick="openModal()">
                    <i class="fa-solid fa-plus"></i>
                    Inscrire un client
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
                                <th>Nom Complét</th>
                                <th>Téléphone</th>
                                <th>Total réclamations</th>
                                <th>Adresse</th>
                                <th>Ajouter le</th>
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
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" id="f-nom" placeholder="ex: Fadil" name="nom" />
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" id="f-prenom" placeholder="ex: Ibtissam" name="prenom" />
                    </div>
                    <div class="form-group">
                        <label>Date de naissance</label>
                        <input type="date" id="f-date_naissance" name="date_naissance" />
                    </div>
                    <div class="form-group">
                        <label>CIN</label>
                        <input type="text" id="f-numero_cin" placeholder="ex: A123456" name="numero_cin" />
                    </div>
                    <div class="form-group full">
                        <label>Email</label>
                        <input type="email" id="f-email" placeholder="client@gmail.com" name="email" />
                    </div>
                    <div class="form-group full">
                        <label>Téléphone</label>
                        <input type="text" id="f-telephone" placeholder="ex: 0612345678" name="telephone" />
                    </div>
                    <div class="form-group full">
                        <label>Address</label>
                        <input type="text" id="f-adresse" placeholder="ex: Tanger, Maroc" name="adresse" />
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
    <script type="module" src="../../assets/js/pages/clients.js"></script>
    <script>
        function openModal() {
            document.getElementById("modalTitle").textContent = "Inscrire un nouvel client";
            document.getElementById("formModal").action = "../../actions/clients/store.php";
            document.getElementById("formMethod").value = "POST";
            document.getElementById("submitBtn").textContent = "Inscrire le client";

            ["f-nom", "f-prenom", "f-email", "f-date_naissance", "f-numero_cin", 'f-telephone', 'f-adresse'].forEach(
                (id) => (document.getElementById(id).value = "")
            );

            document.getElementById("overlay").classList.add("open");
        }

        function openEditModal(id) {
            const user = clients.find(u => u.id == id);
            if (!user) return;
            document.getElementById('modalTitle').textContent = 'Modifier le client';
            document.getElementById('submitBtn').textContent = 'Enregistrer';
            document.getElementById('formModal').action = `../../actions/clients/update.php`;
            document.getElementById('formModal').dataset.mode = 'edit';
            document.getElementById('formModal').dataset.editId = id;

            // fill fields
            document.getElementById('f-nom').value = user.nom || '';
            document.getElementById('f-prenom').value = user.prenom || '';
            document.getElementById('f-email').value = user.email || '';
            document.getElementById('f-telephone').value = user.telephone || '';
            document.getElementById('f-adresse').value = user.adresse;
            document.getElementById('overlay').classList.add('open');
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