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
            grid-template-columns: repeat(3, 1fr);
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
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                    <div>
                        <div class="stat-label">Taux de résolution</div>
                        <div class="stat-value" id="statTaux">—</div>
                        <div class="stat-sub">Résolues + Clôturées</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <div class="stat-label">Délai moyen</div>
                        <div class="stat-value" id="statDelai">—</div>
                        <div class="stat-sub">Heures de traitement</div>
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

            <!-- CHARTS ROW 2 -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-title">Réclamations par catégorie</div>
                    <div class="bars" id="categorieBars">
                        <div class="skeleton" style="height:14px"></div>
                        <div class="skeleton" style="height:14px"></div>
                        <div class="skeleton" style="height:14px"></div>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-title">Réclamations par agent</div>
                    <div class="bars" id="agentBars">
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
    <script type="module" src="../../assets/js/pages/dashboard.js"></script>
</body>

</html>