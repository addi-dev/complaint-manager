import { initials, colorFor } from "../lib/string.js";
import { formatDate } from "../lib/date.js";
import { showToast } from "../lib/toast.js";

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const mes_reclamations = [];
let filtered = [];
let page = 1;
const PER = 10;

function applyFilters() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const sortBy = document.getElementById("sortSelect").value;
  const statusFilter = document.getElementById("statusFilter").value;
  const priorityFilter = document.getElementById("priorityFilter").value;
  const categoryFilter = document.getElementById("categoryFilter").value;

  filtered = mes_reclamations.filter((r) => {
    const matchSearch =
      r.objet.toLowerCase().includes(search) ||
      r.numero_unique.toLowerCase().includes(search) ||
      r.description.toLowerCase().includes(search);
    const matchStatus =
      !statusFilter || r.statut_code.toLowerCase() === statusFilter;
    const matchPriority =
      !priorityFilter || r.priorite_niveau == priorityFilter;
    const matchCategory = !categoryFilter || r.categorie_id == categoryFilter;

    return matchSearch && matchStatus && matchPriority && matchCategory;
  });

  filtered.sort((a, b) => {
    if (sortBy === "objet_asc") return a.objet.localeCompare(b.objet);
    if (sortBy === "objet_desc") return b.objet.localeCompare(a.objet);
    if (sortBy === "date_asc")
      return new Date(a.created_at) - new Date(b.created_at);
    if (sortBy === "date_desc")
      return new Date(b.created_at) - new Date(a.created_at);
    return 0;
  });

  page = 1;
  renderMesReclamations();
}

function getMesReclamations() {
  fetch("../../api/mes_reclamations_api.php")
    .then((res) => res.json())
    .then((data) => {
      mes_reclamations.length = 0;
      console.log(data);
      mes_reclamations.push(...data.mes_reclamations);
      applyFilters();
    })
    .catch((err) => console.error(err));
}

function renderMesReclamations() {
  const total = filtered.length;
  const pages = Math.max(1, Math.ceil(total / PER));
  if (page > pages) page = pages;
  const start = (page - 1) * PER;
  const slice = filtered.slice(start, start + PER);

  document.getElementById("tableBody").innerHTML = slice
    .map(
      (r, i) => `
        <tr style="animation-delay:${i * 0.03}s" data-id="${r.id}">
          <td><span class='ref-badge'>${r.numero_unique}</span></td>
          <td class="table-objet">${r.objet}</td>
          <td><span class="category-badge">${r.categorie}</span></td>
          <td><span class="priority-badge ${r.priorite.toLowerCase()}">${r.priorite}</span></td>
          <td><span class="status-badge status-${r.statut.toLowerCase()}">${r.statut}</span></td>
          <td>
            <div class="action-btns">
              <button class="action-btn action-btn-view" title="Voir le dossier" onclick="openEditModal(${r.id})">
                <i class="fa-regular fa-eye"></i>
              </button>
              <button class="action-btn action-btn-edit" title="Modifier" onclick="openEditModal(${r.id})">
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
              <button class="action-btn action-btn-delete" title="Supprimer" onclick="deleteRow('${r.id}')">
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
      : `${start + 1}–${end} sur ${total} réclamations`;

  const pg = document.getElementById("pagination");
  pg.innerHTML = "";
  const btn = (label, p, active = false) => {
    const b = document.createElement("button");
    b.className = "pg-btn" + (active ? " active" : "");
    b.textContent = label;
    b.onclick = () => {
      page = p;
      renderMesReclamations();
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
  //! Show reclamations count
  document.getElementById("enrollCount").innerHTML =
    `${mes_reclamations.length} réclamation${mes_reclamations.length > 1 ? "s" : ""}`;
}

getMesReclamations();

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

window.mes_reclamations = mes_reclamations;
window.closeDeleteModal = function () {
  document.getElementById("deleteOverlay").classList.remove("open");
};

window.deleteRow = function (id) {
  const reclamation = mes_reclamations.find((u) => u.id == id);
  document.getElementById("deleteRowName").textContent =
    reclamation.objet || "cette réclamation";
  document.getElementById("deleteOverlay").classList.add("open");

  document.getElementById("confirmDelete").onclick = async function () {
    try {
      const res = await fetch(
        `/complaint-manager/actions/reclamations/delete.php?id=${id}`,
        {
          method: "POST",
          headers: { "X-CSRF-Token": csrfToken },
        },
      );

      const data = await res.json();

      if (data.success) {
        closeDeleteModal();
        showToast("Réclamation supprimée avec succès");
        getMesReclamations();
      } else {
        console.error(data.error);
        showToast("Échec de la suppression");
      }
    } catch (err) {
      console.error(err);
      showToast("Une erreur est survenue");
    }
  };
};

//! Handle Store.php (insertion using api)

document
  .getElementById("formModal")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const body = {
      objet: document.getElementById("f-objet").value,
      description: document.getElementById("f-description").value,
      categorie_id: document.getElementById("f-category").value,
    };

    const mode = this.dataset.mode;
    const url =
      mode === "edit"
        ? `/complaint-manager/actions/reclamations/update.php?id=${this.dataset.editId}`
        : `/complaint-manager/actions/reclamations/store.php`;

    try {
      const formData = new FormData(this);

      const res = await fetch(url, {
        method: "POST",
        headers: { "X-CSRF-Token": csrfToken },
        body: formData,
      });

      const data = await res.json();

      if (data.success) {
        closeModal();
        showToast(
          mode === "edit"
            ? "Réclamation mis à jour avec succès"
            : "Réclamation ajouté avec succès",
        );
        getMesReclamations();
      } else if (data.errors) {
        Object.values(data.errors).forEach((msg) => showToast(msg));
      } else {
        console.error(data.message);
        showToast("Échec de l'opération");
      }
    } catch (err) {
      console.log(err);
      showToast("Une erreur est survenue");
    }
  });
