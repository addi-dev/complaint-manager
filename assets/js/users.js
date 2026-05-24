import { initials, colorFor } from "./string.js";
import { formatDate } from "./date.js";

const users = [];
let filtered = [];
let page = 1;
const PER = 10;

function applyFilters() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const roleFilter = document.getElementById("roleFilter").value;
  const statusFilter = document.getElementById("statusFilter").value;
  const sortBy = document.getElementById("sortSelect").value;

  filtered = users.filter((u) => {
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
  renderUsers();
}

function getUsers() {
  fetch("http://localhost/complaint-manager/api/users_api.php")
    .then((res) => res.json())
    .then((data) => {
      users.push(...data.users);
      applyFilters();
    })
    .catch((err) => console.error(err));
}

function renderUsers() {
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
            <div class="table-cell">
              <div class="table-avatar" style="background:${colorFor(data.nom + " " + data.prenom)}">${initials(data.nom + " " + data.prenom)}</div>
              <div>
                <div class="table-fullname">${data.nom + " " + data.prenom}</div>
                <div class="table-email">${data.email}</div>
              </div>
            </div>
          </td>
          <td><span class="role-badge role-${data.role.toLowerCase()}">${data.role}</span></td>
          <td>
            <span class="status-badge status-${data.actif == 1 ? "active" : "inactive"}">
              ${data.actif == 1 ? "Actif" : "Inactif"}
            </span>
          </td>
          <td>${formatDate(data.created_at)}</td>
          <td>
            <div class="action-btns">
              <button class="action-btn action-btn-edit" title="Modifier" onclick="openEditModal()">
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
              <button class="action-btn action-btn-delete" title="Supprimer">
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
      : `${start + 1}–${end} sur ${total} utilisateurs inscrits`;

  const pg = document.getElementById("pagination");
  pg.innerHTML = "";
  const btn = (label, p, active = false) => {
    const b = document.createElement("button");
    b.className = "pg-btn" + (active ? " active" : "");
    b.textContent = label;
    b.onclick = () => {
      page = p;
      renderUsers();
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

  //! Show users count
  document.getElementById("enrollCount").innerHTML =
    users.length + " " + "utilisateurs inscrits";
}

getUsers();

// Apply Filters

document.getElementById("searchInput").addEventListener("input", applyFilters);
document.getElementById("roleFilter").addEventListener("change", applyFilters);
document
  .getElementById("statusFilter")
  .addEventListener("change", applyFilters);
document.getElementById("sortSelect").addEventListener("change", applyFilters);
