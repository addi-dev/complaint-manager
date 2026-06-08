// assets/js/reclamtions.js
import { initials, colorFor } from "../lib/string.js";
import { formatDate } from "../lib/date.js";
import { showToast } from "../lib/toast.js";
const reclamations = [];
let filtered = [];
let page = 1;
const PER = 10;

function applyFilters() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const statusFilter = document.getElementById("statusFilter").value;
  const categoryFilter = document.getElementById("categoryFilter").value;
  const priorityFilter = document.getElementById("priorityFilter").value;
  const sortBy = document.getElementById("sortSelect").value;

  filtered = reclamations.filter((r) => {
    const matchSearch =
      r.objet.toLowerCase().includes(search) ||
      r.numero_unique.toLowerCase().includes(search) ||
      r.description.toLowerCase().includes(search) ||
      r.client.toLowerCase().includes(search);
    const matchStatus =
      !statusFilter || r.statut_code.toLowerCase() === statusFilter;
    const matchPriority =
      !priorityFilter || r.priorite_niveau == priorityFilter;
    const matchCategory = !categoryFilter || r.categorie_id == categoryFilter;

    return matchSearch && matchStatus && matchPriority && matchCategory;
  });

  filtered.sort((a, b) => {
    if (sortBy === "name")
      return (a.nom + a.prenom).localeCompare(b.nom + b.prenom);
    if (sortBy === "name_desc")
      return (b.nom + b.prenom).localeCompare(a.nom + a.prenom);
    if (sortBy === "date_asc")
      return new Date(a.created_at) - new Date(b.created_at);
    if (sortBy === "date_desc")
      return new Date(b.created_at) - new Date(a.created_at);
    return 0;
  });

  page = 1;
  renderReclamations();
}

function getReclamations() {
  fetch("../../api/reclamations_api.php")
    .then((res) => res.json())
    .then((data) => {
      reclamations.length = 0;
      reclamations.push(...data.reclamations);
      applyFilters();
    })
    .catch((err) => console.error(err));
}

function renderReclamations() {
  const total = filtered.length;
  const pages = Math.max(1, Math.ceil(total / PER));
  if (page > pages) page = pages;
  const start = (page - 1) * PER;
  const slice = filtered.slice(start, start + PER); // ← defined here, used below
  document.getElementById("tableBody").innerHTML = slice
    .map(
      (data, i) => `
        <tr style="animation-delay:${i * 0.03}s" data-id='${data.id}'>
          <td>
            <span class="ref-badge">${data.numero_unique}</span>
          </td>
          <td>${data.client}</td>
          <td class="table-objet">${data.objet}</td>
          <td><span class="category-badge">${data.categorie}</span></td>
          <td><span class="priority-badge ${data.priorite.toLowerCase()}">${data.priorite}</span></td>
          <td><span class="r-status-badge status-${data.statut_code.toLowerCase()}">${data.statut}</span></td>
          <td>${data.agent}</td>
          <td>${formatDate(data.created_at)}</td>
          <td>
            <div class="action-btns">
              <button class="action-btn action-btn-view" title="Voir les détails" onclick="window.location.href='reclamation-details.php?id=${data.id}'">
                <i class="fa-regular fa-eye"></i>
              </button>
              <button class="action-btn action-btn-edit" title="Modifier" onclick="openEditModal(${data.id})">
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
              <button class="action-btn action-btn-delete" title="Supprimer" onclick="deleteRow('${data.id}')">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </div>
          </td>
        </tr>
      `,
    )
    .join("");
  const end = Math.min(start + PER, total);
  document.getElementById("tfInfo").innerHTML =
    total === 0
      ? "Aucune réclamation trouvée"
      : `${start + 1}–${end} sur ${total} clients inscrits`;

  const pg = document.getElementById("pagination");
  pg.innerHTML = "";
  const btn = (label, p, active = false) => {
    const b = document.createElement("button");
    b.className = "pg-btn" + (active ? " active" : "");
    b.textContent = label;
    b.onclick = () => {
      page = p;
      renderReclamations();
    };
    return b;
  };
  if (page > 1) pg.appendChild(btn("‹", page - 1));
  for (let p2 = 1; p2 <= pages; p2++) {
    if (pages <= 6 || p2 === 1 || p2 === pages || Math.abs(p2 - page) <= 1)
      pg.appendChild(btn(p2, p2, p2 === page));
    else if (p2 === 2 || p2 === pages - 1) {
      const s = document.createElement("span");
      s.className = "pg-btn";
      s.textContent = "…";
      s.style.pointerEvents = "none";
      pg.appendChild(s);
    }
  }
  if (page < pages) pg.appendChild(btn("›", page + 1));

  //! Show Reclamations count
  document.getElementById("enrollCount").innerHTML =
    `${reclamations.length} réclamation${reclamations.length > 1 ? "s" : ""}`;
}

getReclamations();

// Apply Filters

document.getElementById("searchInput").addEventListener("input", applyFilters);
document
  .getElementById("priorityFilter")
  .addEventListener("change", applyFilters);
document
  .getElementById("statusFilter")
  .addEventListener("change", applyFilters);
document
  .getElementById("categoryFilter")
  .addEventListener("change", applyFilters);
document.getElementById("sortSelect").addEventListener("change", applyFilters);
