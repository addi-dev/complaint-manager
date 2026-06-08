<?php
session_start();

require_once __DIR__ . '/../../core/Auth.php';
require __DIR__ . '/../../config/app.php';

Auth::requireRole('admin', 'superviseur', 'agent');
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-icon.purple {
            background: #eef2ff;
            color: var(--brand);
        }

        .stat-icon.green {
            background: #dcfce7;
            color: var(--active-green);
        }

        .stat-icon.amber {
            background: #fef9c3;
            color: var(--leave-amber);
        }

        .stat-icon.red {
            background: #fef2f2;
            color: var(--inactive-red);
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .stat-sub {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .chart-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .chart-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .donut-wrap {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .legend {
            display: flex;
            flex-direction: column;
            gap: 9px;
            flex: 1;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-label {
            color: var(--text-label);
            flex: 1;
        }

        .legend-val {
            font-weight: 700;
            color: var(--text-primary);
        }

        .bars {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bar-label {
            font-size: 12px;
            color: var(--text-label);
            width: 70px;
            flex-shrink: 0;
        }

        .bar-track {
            flex: 1;
            height: 8px;
            background: var(--border);
            border-radius: 99px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }

        .bar-val {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-primary);
            width: 28px;
            text-align: right;
            flex-shrink: 0;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h2 {
            font-size: 14px;
            font-weight: 700;
        }

        .view-all {
            font-size: 12.5px;
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        .num-badge {
            font-size: 12px;
            font-weight: 700;
            color: var(--brand);
            background: var(--brand-light);
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .prio-badge {
            display: inline-flex;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .prio-critique {
            background: #fef2f2;
            color: var(--inactive-red);
        }

        .prio-haute {
            background: #fef9c3;
            color: var(--leave-amber);
        }

        .prio-normale {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .prio-faible {
            background: #f0fdf4;
            color: #15803d;
        }

        #donutCanvas {
            flex-shrink: 0;
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 6px;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
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
        <a class="nav-item" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
        <a class="nav-item" href="notifications.php"><i class="fa-solid fa-users"></i>Notifications</a>
        <a class="nav-item" href="historiques.php"><i class="fa-solid fa-user"></i>Historiques</a>
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
                    <h1>Tableau de bord</h1>
                    <div class="sub" id="dashDate"></div>
                </div>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fa-solid fa-file-circle-exclamation"></i></div>
                    <div>
                        <div class="stat-label">Réclamations</div>
                        <div class="stat-value" id="statTotal">—</div>
                        <div class="stat-sub" id="statMonth">ce mois</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fa-solid fa-user"></i></div>
                    <div>
                        <div class="stat-label">Clients</div>
                        <div class="stat-value" id="statClients">—</div>
                        <div class="stat-sub" id="statNewClients">ce mois</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="fa-solid fa-hourglass-half"></i></div>
                    <div>
                        <div class="stat-label">Non résolues</div>
                        <div class="stat-value" id="statUnresolved">—</div>
                        <div class="stat-sub">En attente</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fa-solid fa-users-gear"></i></div>
                    <div>
                        <div class="stat-label">Agents actifs</div>
                        <div class="stat-value" id="statAgents">—</div>
                        <div class="stat-sub">En service</div>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-title">Réclamations par statut</div>
                    <div class="donut-wrap">
                        <canvas id="donutCanvas" width="130" height="130"></canvas>
                        <div class="legend" id="donutLegend">
                            <div class="skeleton" style="height:14px;width:80%"></div>
                            <div class="skeleton" style="height:14px;width:70%"></div>
                            <div class="skeleton" style="height:14px;width:75%"></div>
                        </div>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-title">Réclamations par priorité</div>
                    <div class="bars" id="prioriteBars">
                        <div class="skeleton" style="height:14px"></div>
                        <div class="skeleton" style="height:14px"></div>
                        <div class="skeleton" style="height:14px"></div>
                        <div class="skeleton" style="height:14px"></div>
                    </div>
                </div>
            </div>

            <!-- RECENT TABLE -->
            <div class="card">
                <div class="card-header">
                    <h2>Réclamations récentes</h2>
                    <a class="view-all" href="reclamations.php">Voir tout →</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Client</th>
                                <th>Objet</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Créée le</th>
                            </tr>
                        </thead>
                        <tbody id="recentBody">
                            <tr>
                                <td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">
                                    Chargement...</td>
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
        import {
            initials,
            colorFor
        } from "../../assets/js/lib/string.js";

        // Date header
        const now = new Date();
        document.getElementById("dashDate").textContent = now.toLocaleDateString("fr-FR", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        const STATUT_COLORS = {
            NOUVELLE: "#6c4ef8",
            ATTENTE_AFFECTATION: "#3b82f6",
            AFFECTEE: "#0ea5e9",
            EN_COURS: "#d97706",
            ATTENTE_INFO: "#7c3aed",
            RESOLUE: "#16a34a",
            CLOTUREE: "#8c93a8",
            REJETEE: "#dc2626",
        };

        const PRIO_COLORS = {
            Critique: "#dc2626",
            Haute: "#d97706",
            Normale: "#6c4ef8",
            Faible: "#16a34a",
        };

        const PRIO_CLASSES = {
            Critique: "prio-critique",
            Haute: "prio-haute",
            Normale: "prio-normale",
            Faible: "prio-faible",
        };

        const STATUT_BADGE = {
            NOUVELLE: "s-nouvelle",
            ATTENTE_AFFECTATION: "s-attente",
            AFFECTEE: "s-attente",
            EN_COURS: "s-encours",
            ATTENTE_INFO: "s-attente",
            RESOLUE: "s-resolue",
            CLOTUREE: "s-cloturee",
            REJETEE: "s-rejetee",
        };

        async function loadStats() {
            try {
                const res = await fetch("../../api/stats_api.php");
                const data = await res.json();
                if (!data.success) return;

                document.getElementById("statTotal").textContent = data.total_reclamations;
                document.getElementById("statClients").textContent = data.total_clients;
                document.getElementById("statUnresolved").textContent = data.unresolved;
                document.getElementById("statAgents").textContent = data.total_agents;
                document.getElementById("statMonth").textContent = `+${data.this_month} ce mois`;
                document.getElementById("statNewClients").textContent = `+${data.new_clients} ce mois`;

                drawDonut(data.by_statut);
                drawBars(data.by_priorite);
                drawRecent(data.recent);
            } catch (err) {
                console.error(err);
            }
        }

        function drawDonut(byStatut) {
            const canvas = document.getElementById("donutCanvas");
            const ctx = canvas.getContext("2d");
            const cx = 65,
                cy = 65,
                ro = 55,
                ri = 36;
            const total = byStatut.reduce((s, r) => s + parseInt(r.total), 0);

            let start = -Math.PI / 2;
            byStatut.forEach(row => {
                const v = parseInt(row.total);
                const angle = total > 0 ? (v / total) * Math.PI * 2 : 0;
                const color = STATUT_COLORS[row.code] || "#8c93a8";
                ctx.beginPath();
                ctx.moveTo(cx + ro * Math.cos(start), cy + ro * Math.sin(start));
                ctx.arc(cx, cy, ro, start, start + angle);
                ctx.arc(cx, cy, ri, start + angle, start, true);
                ctx.closePath();
                ctx.fillStyle = color;
                ctx.fill();
                start += angle;
            });

            ctx.fillStyle = "#fff";
            ctx.beginPath();
            ctx.arc(cx, cy, ri - 2, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = "#1a1d2e";
            ctx.font = "bold 18px Plus Jakarta Sans, sans-serif";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillText(total, cx, cy - 8);
            ctx.font = "11px Plus Jakarta Sans, sans-serif";
            ctx.fillStyle = "#8c93a8";
            ctx.fillText("total", cx, cy + 10);

            const legend = document.getElementById("donutLegend");
            legend.innerHTML = byStatut.filter(r => r.total > 0).map(row => `
        <div class="legend-item">
          <div class="legend-dot" style="background:${STATUT_COLORS[row.code] || '#8c93a8'}"></div>
          <span class="legend-label">${row.libelle}</span>
          <span class="legend-val">${row.total}</span>
        </div>
      `).join("");
        }

        function drawBars(byPriorite) {
            const max = Math.max(...byPriorite.map(r => parseInt(r.total)), 1);
            document.getElementById("prioriteBars").innerHTML = byPriorite.map(row => `
        <div class="bar-row">
          <div class="bar-label">${row.libelle}</div>
          <div class="bar-track">
            <div class="bar-fill" style="width:${Math.round((row.total / max) * 100)}%;background:${PRIO_COLORS[row.libelle] || '#6c4ef8'}"></div>
          </div>
          <div class="bar-val">${row.total}</div>
        </div>
      `).join("");
        }

        function drawRecent(rows) {
            if (!rows || rows.length === 0) {
                document.getElementById("recentBody").innerHTML = `
          <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Aucune réclamation</td></tr>
        `;
                return;
            }
            document.getElementById("recentBody").innerHTML = rows.map((r, i) => `
        <tr style="animation-delay:${i * 0.04}s">
          <td><span class="num-badge">${r.numero_unique}</span></td>
          <td>
            <div class="table-cell">
              <div class="table-avatar" style="background:${colorFor(r.client_nom + ' ' + r.client_prenom)}">${initials(r.client_nom + ' ' + r.client_prenom)}</div>
              ${r.client_nom} ${r.client_prenom}
            </div>
          </td>
          <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${r.objet}</td>
          <td><span class="prio-badge ${PRIO_CLASSES[r.priorite] || ''}">${r.priorite}</span></td>
          <td><span class="status-badge ${STATUT_BADGE[r.statut_code] || ''}">${r.statut}</span></td>
          <td style="color:var(--text-muted);font-size:13px">${formatDate(r.created_at)}</td>
        </tr>
      `).join("");
        }

        loadStats();
    </script>
</body>

</html>