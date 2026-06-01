import { initials, colorFor } from "../lib/string.js";
import { formatDate } from "../lib/date.js";
import { showToast } from "../lib/toast.js";
const reclamations = [];
let filtered = [];
let page = 1;
const PER = 10;

function applyFilters() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const roleFilter = document.getElementById("roleFilter").value;
  const statusFilter = document.getElementById("statusFilter").value;
  const sortBy = document.getElementById("sortSelect").value;

  filtered = reclamations.filter((u) => {
    const fullname = (u.nom + " " + u.prenom).toLowerCase();
    const matchSearch =
      fullname.includes(search) || u.email.toLowerCase().includes(search);
    const matchRole = !roleFilter || u.role.toLowerCase() === roleFilter;
    const matchStatus = statusFilter === "" ? true : u.actif == statusFilter;
    return matchSearch && matchRole && matchStatus;
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
  fetch("http://localhost/complaint-manager/api/reclamations_api.php")
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
            <span>${data.numero_unique}</span>
          </td>
          <td>${data.client}</td>
          <td>${data.objet}</td>
          <td>${data.categorie}</td>
          <td>${data.priorite}</td>
          <td>${data.statut}</td>
          <td>${data.agent}</td>
          <td>${formatDate(data.created_at)}</td>
          <td>
            <div class="action-btns">
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
      ? "Aucun utilisateur trouvé"
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
    `${reclamations.length} réclamation${reclamations.length > 1 ? 's' : ''}`
}

getReclamations();
