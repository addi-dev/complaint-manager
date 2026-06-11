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
    <title>Notifications | <?php echo APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/app.css" />
    <style>
        .notif-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--card-bg);
            transition: background 0.2s;
        }

        .notif-item.unread {
            border-left: 3px solid var(--brand);
            background: var(--brand-light);
        }

        .notif-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            background: #eef2ff;
            color: var(--brand);
        }

        .notif-message {
            font-size: 13.5px;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .notif-meta {
            font-size: 12px;
            color: var(--text-muted);
        }

        .notif-ref {
            font-size: 12px;
            font-weight: 700;
            color: var(--brand);
        }

        .mark-all-btn {
            font-size: 13px;
            font-weight: 600;
            color: var(--brand);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .mark-all-btn:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
            display: block;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">LG</div>ReclamationOS
        </div>
        <div class="sidebar-section-label">Menu</div>
        <a class="nav-item" href="index.php"><i class="fa-solid fa-house"></i>Tableau de bord</a>
        <a class="nav-item" href="reclamations.php"><i class="fa-solid fa-file-circle-exclamation"></i>Réclamations</a>
        <a class="nav-item active" href="notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a>
        <a class="nav-item" href="historiques.php"><i class="fa-solid fa-clock-rotate-left"></i>Historiques</a>
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
                    <h1>Notifications</h1>
                    <div class="sub" id="unreadCount"></div>
                </div>
                <button class="mark-all-btn" onclick="markAllRead()">Tout marquer comme lu</button>
            </div>
            <div class="card">
                <div id="notifList">
                    <div class="empty-state"><i class="fa-regular fa-bell"></i>Chargement...</div>
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
            showToast
        } from "../../assets/js/lib/toast.js";

        const TYPE_ICONS = {
            AFFECTATION: 'fa-solid fa-user-check',
            STATUT: 'fa-solid fa-rotate',
            INFO: 'fa-solid fa-circle-info',
            RESOLUTION: 'fa-solid fa-circle-check',
        };

        async function loadNotifications() {
            const res = await fetch('../../api/notifications_api.php?action=list');
            const data = await res.json();
            if (!data.success) return;

            document.getElementById('unreadCount').textContent =
                data.unread > 0 ? `${data.unread} non lue(s)` : 'Tout est lu';

            const list = document.getElementById('notifList');
            if (data.notifications.length === 0) {
                list.innerHTML = `<div class="empty-state"><i class="fa-regular fa-bell"></i>Aucune notification</div>`;
                return;
            }

            list.innerHTML = `<div class="notif-list">${data.notifications.map(n => `
                <div class="notif-item ${n.lu == 0 ? 'unread' : ''}" id="notif-${n.id}">
                    <div class="notif-icon"><i class="${TYPE_ICONS[n.type] || 'fa-solid fa-bell'}"></i></div>
                    <div style="flex:1">
                        <div class="notif-message">${n.message}</div>
                        ${n.numero_unique ? `<div class="notif-ref">${n.numero_unique} — ${n.objet}</div>` : ''}
                        <div class="notif-meta">${formatDate(n.created_at)}</div>
                    </div>
                    ${n.lu == 0 ? `<button onclick="markRead(${n.id})" style="font-size:11px;color:var(--brand);background:none;border:none;cursor:pointer;white-space:nowrap">Marquer lu</button>` : ''}
                </div>
            `).join('')}</div>`;
        }

        window.markRead = async function(id) {
            await fetch(`../../api/notifications_api.php?action=mark_read&id=${id}`);
            loadNotifications();
        };

        window.markAllRead = async function() {
            await fetch('../../api/notifications_api.php?action=mark_read');
            loadNotifications();
            showToast('Toutes les notifications marquées comme lues');
        };

        loadNotifications();
    </script>
</body>

</html>