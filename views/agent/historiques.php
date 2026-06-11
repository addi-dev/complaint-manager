<?php
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/Auth.php';
Auth::requireRole('agent', 'admin', 'superviseur');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Historiques | <?php echo APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/app.css" />
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">LG</div>ReclamationOS
        </div>
        <div class="sidebar-section-label">Menu</div>
        <a class="nav-item" href="index.php"><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
        <a class="nav-item" href="notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a>
        <a class="nav-item active" href="historiques.php"><i class="fa-solid fa-clock-rotate-left"></i>Historiques</a>
        <div class="sidebar-section-label">Other</div>
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
                    <h1>Historiques</h1>
                    <div class="sub">Vos dernières actions</div>
                </div>
            </div>
            <div class="card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Réclamation</th>
                                <th>Action</th>
                                <th>Ancien statut</th>
                                <th>Nouveau statut</th>
                                <th>Détails</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="histBody">
                            <tr>
                                <td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Chargement...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    <script type="module" src="../../assets/js/app.js"></script>
    <script type="module">
        import {
            formatDate
        } from "../../assets/js/lib/date.js";

        async function loadHistorique() {
            const res = await fetch('../../api/agent_stats_api.php');
            const data = await res.json();
            if (!data.success) return;

            const tbody = document.getElementById('histBody');
            if (!data.recent_activity || data.recent_activity.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Aucune action enregistrée</td></tr>`;
                return;
            }

            tbody.innerHTML = data.recent_activity.map((h, i) => `
                <tr style="animation-delay:${i * 0.03}s">
                    <td><span class="ref-badge">${h.numero_unique}</span></td>
                    <td><strong>${h.action}</strong></td>
                    <td><span class="status-badge">—</span></td>
                    <td><span class="status-badge status-${(h.nouveau_statut || '').toLowerCase().replace(/ /g, '_')}">${h.nouveau_statut || '—'}</span></td>
                    <td style="color:var(--text-muted);font-size:13px">${h.details || '—'}</td>
                    <td style="color:var(--text-muted);font-size:13px">${formatDate(h.created_at)}</td>
                </tr>
            `).join('');
        }

        loadHistorique();
    </script>
</body>

</html>