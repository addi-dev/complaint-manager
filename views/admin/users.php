<?php
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/../config/app.php';

// fetch roles for the dropdown
$stmt = $pdo->query("SELECT id, nom FROM roles ORDER BY nom");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/app.css" />
  <link rel="stylesheet" href="../assets/css/modal.css" />
  <link rel="stylesheet" href="../assets/css/sidebar.css" />
  <link rel="stylesheet" href="../assets/css/topbar.css" />
  <link rel="stylesheet" href="../assets/css/table.css" />
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
    <a class="nav-item" href=""><i class="fa-solid fa-house"></i>Dashboard</a>
    <a class="nav-item active" href=""><i class="fa-solid fa-users"></i>Utilisateurs</a>
    <a class="nav-item" href=""><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
    <a class="nav-item" href=""><i class="fa-solid fa-user"></i>Clients</a>
    <div class="sidebar-section-label">Other</div>
    <a class="nav-item" href=""><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="topbar-actions">
        <div class="user-chip">
          <div class="avatar" id="avatar"></div>
          <div class="user-info">
            <div class="name">Ibtissam Fadil</div>
            <div class="role">Admin</div>
          </div>
        </div>
      </div>
    </header>
    <div class="content">
      <div class="page-header">
        <div>
          <h1>Utilisateurs</h1>
          <div class="sub" id="enrollCount"></div>
        </div>
        <button class="enroll-btn" onclick="openModal()">
          <i class="fa-solid fa-plus"></i>
          Inscrire un utilisateur
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
            <option value="">Tous les rôles</option>
            <option value="admin">Admin</option>
            <option value="agent">Agent</option>
            <option value="superviseur">Superviseur</option>
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
                <th>Role</th>
                <th>Status</th>
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
            <input type="text" id="f-nom" placeholder="e.g. Fadil" name="nom" required />
          </div>
          <div class="form-group">
            <label>Prénom</label>
            <input type="text" id="f-prenom" placeholder="e.g. Ibtisam" name="prenom" required />
          </div>
          <div class="form-group full">
            <label>Email</label>
            <input type="email" id="f-email" placeholder="utilisateur@gmail.com" name="email" required />
          </div>
          <div class="form-group full">
            <label>Mot de passe</label>
            <input type="password" id="f-mot_de_passe" placeholder="••••••••" name="mot_de_passe" required />
          </div>
          <div class="form-group">
            <label>Rôle</label>
            <select id="f-role_id" name="role_id" required>
              <option value="">Sélectionner un rôle</option>
              <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Actif</label>
            <select id="f-actif" name="actif">
              <option value="1">Oui</option>
              <option value="0">Non</option>
            </select>
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
        <button type="submit" class="btn-submit" style="background:#dc2626">Delete</button>
      </div>
    </form>
  </div>
  <div class="toast" id="toast"><span id="toastMsg"></span></div>
  <script type="module" src="../assets/js/app.js"></script>
  <script type="module" src="../assets/js/users.js"></script>
  <script>
    function FilterTable() {}

    function openModal() {
      document.getElementById("modalTitle").textContent = "Inscrire un nouvel utilisateur";
      document.getElementById("formModal").action = "store.php";
      document.getElementById("formMethod").value = "POST";
      document.getElementById("submitBtn").textContent = "Inscrire l'utilisateur";

      ["f-nom", "f-prenom", "f-email", "f-mot_de_passe"].forEach(
        (id) => (document.getElementById(id).value = "")
      );

      document.getElementById("f-role_id").value = "";
      document.getElementById("f-actif").value = "1"; // default: actif
      document.getElementById("overlay").classList.add("open");
    }

    function openEditModal(id) {
      const user = users.find(u => u.id == id);
      if (!user) return;
      document.getElementById('modalTitle').textContent = 'Modifier l\'utilisateur';
      document.getElementById('submitBtn').textContent = 'Enregistrer';
      document.getElementById('formModal').action = `update.php`;
      document.getElementById('formModal').dataset.mode = 'edit';
      document.getElementById('formModal').dataset.editId = id;

      // fill fields
      document.getElementById('f-nom').value = user.nom || '';
      document.getElementById('f-prenom').value = user.prenom || '';
      document.getElementById('f-email').value = user.email || '';
      document.getElementById('f-mot_de_passe').value = ''; // leave empty
      document.getElementById('f-role_id').value = user.role_id || '';
      document.getElementById('f-actif').value = user.actif ?? '1';

      document.getElementById('overlay').classList.add('open');
    }

    function closeModal() {
      document.getElementById('overlay').classList.remove('open');
    }

    function closeOnOverlay(e) {
      if (e.target === e.currentTarget) closeModal();
    }

    function closeDeleteModal() {
      document.getElementById('deleteOverlay').classList.remove('open');
    }
  </script>
</body>

</html>