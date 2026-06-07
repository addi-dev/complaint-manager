import { formatDate } from "../lib/date.js";
import { initials, colorFor } from "../lib/string.js";

// Date header
const now = new Date();
document.getElementById("dashDate").textContent = now.toLocaleDateString(
  "fr-FR",
  {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  },
);

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
    document.getElementById("statMonth").textContent =
      `+${data.this_month} ce mois`;
    document.getElementById("statNewClients").textContent =
      `+${data.new_clients} ce mois`;

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
  byStatut.forEach((row) => {
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
  legend.innerHTML = byStatut
    .filter((r) => r.total > 0)
    .map(
      (row) => `
        <div class="legend-item">
          <div class="legend-dot" style="background:${STATUT_COLORS[row.code] || "#8c93a8"}"></div>
          <span class="legend-label">${row.libelle}</span>
          <span class="legend-val">${row.total}</span>
        </div>
      `,
    )
    .join("");
}

function drawBars(byPriorite) {
  const max = Math.max(...byPriorite.map((r) => parseInt(r.total)), 1);
  document.getElementById("prioriteBars").innerHTML = byPriorite
    .map(
      (row) => `
        <div class="bar-row">
          <div class="bar-label">${row.libelle}</div>
          <div class="bar-track">
            <div class="bar-fill" style="width:${Math.round((row.total / max) * 100)}%;background:${PRIO_COLORS[row.libelle] || "#6c4ef8"}"></div>
          </div>
          <div class="bar-val">${row.total}</div>
        </div>
      `,
    )
    .join("");
}

function drawRecent(rows) {
  if (!rows || rows.length === 0) {
    document.getElementById("recentBody").innerHTML = `
          <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Aucune réclamation</td></tr>
        `;
    return;
  }
  document.getElementById("recentBody").innerHTML = rows
    .map(
      (r, i) => `
        <tr style="animation-delay:${i * 0.04}s">
          <td><span class="num-badge">${r.numero_unique}</span></td>
          <td>
            <div class="table-cell">
              <div class="table-avatar" style="background:${colorFor(r.client_nom + " " + r.client_prenom)}">${initials(r.client_nom + " " + r.client_prenom)}</div>
              ${r.client_nom} ${r.client_prenom}
            </div>
          </td>
          <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" class="table-objet">${r.objet}</td>
          <td><span class="prio-badge ${PRIO_CLASSES[r.priorite] || ""}">${r.priorite}</span></td>
          <td><span class="status-badge ${STATUT_BADGE[r.statut_code] || ""}">${r.statut}</span></td>
          <td style="color:var(--text-muted);font-size:13px">${formatDate(r.created_at)}</td>
        </tr>
      `,
    )
    .join("");
}

loadStats();
