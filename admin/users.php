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
            <input type="text" id="searchInput" placeholder="Search students…" oninput="filterTable()" />
          </div>
          <select class="filter-select" id="classFilter" onchange="filterTable()">
            {{-- INFO options are fetched from DB --}}
            <option value="">Select class</option>
            @foreach($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
          </select>
          <select class="filter-select" id="statusFilter" onchange="filterTable()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="withdrawn">Withdrawn</option>
          </select>
          <select class="filter-select" id="genderFilter" onchange="filterTable()">
            <option value="">All Genders</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
          <select class="filter-select" id="sortBy" onchange="sortTable()">
            <option value="newest" selected>Newest first</option>
            <option value="oldest">Oldest first</option>
            <option value="name_asc">Name (A → Z)</option>
            <option value="name_desc">Name (Z → A)</option>
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
  <script type="module" src="../assets/js/app.js"></script>
  <script type="module" src="../assets/js/users.js"></script>
  <script>
    function openModal() {}

    function FilterTable() {}

    function openModal() {}
  </script>
</body>

</html>