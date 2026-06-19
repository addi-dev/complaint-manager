// assets/js/pages/agent/index.js
import { initials, colorFor } from "../../lib/string.js";
import { formatDate } from "../../lib/date.js";
import { showToast } from "../../lib/toast.js";

document.addEventListener("DOMContentLoaded", () => {
  const dashDate = document.getElementById("dashDate");
  if (dashDate) {
    const now = new Date();
    dashDate.textContent = now.toLocaleDateString("fr-FR", {
      weekday: "long",
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  }
  loadStats();
});

async function loadStats() {
  try {
    const res = await fetch("../../api/agent_stats_api.php");
    const data = await res.json();
    if (!data.success) return;
    const s = data.stats;
    document.getElementById("statTotal").textContent = s.total;
    document.getElementById("statEnCours").textContent = s.en_cours;
    document.getElementById("statResolues").textContent = s.resolues;
    document.getElementById("statDelai").textContent = s.avg_resolution_hours
      ? s.avg_resolution_hours + "h"
      : "—";
    renderActivity(data.recent_activity);
  } catch (err) {
    console.error(err);
  }
}

function renderActivity(rows) {
  const body = document.getElementById("activityBody");
  if (!rows || rows.length === 0) {
    body.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">Aucune activité récente</td></tr>`;
    return;
  }
  body.innerHTML = rows
    .map(
      (r) => `
        <tr>
            <td><span class="num-badge">${r.numero_unique}</span></td>
            <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${r.objet}</td>
            <td>${r.nouveau_statut ?? r.action}</td>
            <td style="color:var(--text-muted);font-size:13px">${formatDate(r.created_at)}</td>
        </tr>
    `,
    )
    .join("");
}
loadStats();