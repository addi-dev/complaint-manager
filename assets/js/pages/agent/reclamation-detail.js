import { formatDate } from "../../lib/date.js";
import { initials, colorFor } from "../../lib/string.js";
import { showToast } from "../../lib/toast.js";

const params = new URLSearchParams(window.location.search);
const id = params.get("id");
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

function getReclamationDetails() {
  if (!id) return;
  fetch(`../../../api/reclamation_details_api.php?id=${id}`)
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) return;
      renderDetails(data.reclamation);
      renderCommentaires(data.commentaires);
      renderAttachements(data.pieces_jointes);
      renderHistorique(data.historique);
      const statutSelect = document.getElementById("statut-select");
      if (statutSelect) statutSelect.value = data.reclamation.statut_code;
    });
}

getReclamationDetails();

function renderDetails(reclamation) {
  document.getElementById("rec-ref").textContent = reclamation.numero_unique;
  document.getElementById("rec-objet").textContent = reclamation.objet;
  document.getElementById("rec-status").textContent =
    reclamation.statut_libelle;
  document
    .getElementById("rec-status")
    .classList.add(`status-${reclamation.statut_code.toLowerCase()}`);
  document.getElementById("rec-priority").textContent =
    reclamation.priorite_libelle;
  document
    .getElementById("rec-priority")
    .classList.add(reclamation.priorite_libelle.toLowerCase());
  document.getElementById("rec-category").textContent =
    reclamation.categorie_libelle;
  document.getElementById("rec-created").textContent = formatDate(
    reclamation.created_at,
  );
  document.getElementById("rec-updated").textContent = formatDate(
    reclamation.updated_at,
  );
  document.getElementById("red-closed").textContent = reclamation.closed_at
    ? formatDate(reclamation.closed_at)
    : "—";
  document.getElementById("rec-description").textContent =
    reclamation.description;
}

function renderCommentaires(commentaires) {
  const count = commentaires.length;
  document.getElementById("commentsList").innerHTML = commentaires
    .map((cmt) => {
      const isAgent = cmt.utilisateur_id !== null;
      const nom = isAgent
        ? `${cmt.utilisateur_nom} ${cmt.utilisateur_prenom}`
        : `${cmt.client_auteur_nom} ${cmt.client_auteur_prenom}`;
      const role = isAgent ? cmt.utilisateur_role : "client";
      return `
        <div class="comment ${isAgent ? "comment-agent" : "comment-client"}">
            <div class="cmt-avatar" style="background:${colorFor(nom)}">${initials(nom)}</div>
            <div class="cmt-right">
                <div class="cmt-meta">
                    <div><span class="cmt-author">${nom}</span><span class="cmt-role">${role}</span></div>
                    <span class="cmt-time">${cmt.created_at}</span>
                </div>
                <div class="cmt-text">${cmt.contenu}</div>
            </div>
        </div>`;
    })
    .join("");
  document.getElementById("nombre_commentaires").innerText =
    `${count} message${count > 1 ? "s" : ""}`;
}

function renderAttachements(pieces_jointes) {
  const count = pieces_jointes.length;
  const getIconClass = (mime) =>
    mime.includes("pdf")
      ? "pdf"
      : mime.includes("image")
        ? "img"
        : mime.includes("word")
          ? "doc"
          : "other";
  const getIcon = (mime) =>
    mime.includes("pdf")
      ? "fa-regular fa-file-pdf"
      : mime.includes("image")
        ? "fa-regular fa-image"
        : "fa-regular fa-file";
  const formatSize = (bytes) =>
    bytes >= 1048576
      ? (bytes / 1048576).toFixed(1) + " Mo"
      : Math.round(bytes / 1024) + " Ko";

  document.getElementById("attachements-container").innerHTML = pieces_jointes
    .map(
      (pj) => `
      <div class="file-item">
          <div class="file-icon ${getIconClass(pj.type_mime)}"><i class="${getIcon(pj.type_mime)}"></i></div>
          <div>
              <div class="file-name">${pj.nom_fichier}</div>
              <div class="file-size">${formatSize(pj.taille)}</div>
          </div>
          <div style="flex:1"></div>
          <a href="../../../${pj.chemin}" download="${pj.nom_fichier}" class="file-dl"><i class="fa-solid fa-download"></i></a>
      </div>`,
    )
    .join("");
  document.getElementById("nombre_pieces").innerText =
    `${count} fichier${count > 1 ? "s" : ""}`;
}

function renderHistorique(historique) {
  const el = document.getElementById("historique-container");
  if (!historique.length) {
    el.innerHTML =
      '<p style="color:var(--text-muted);font-size:14px">Aucune action</p>';
    return;
  }
  el.innerHTML = historique
    .map(
      (h) => `
      <div style="padding:12px 0;border-bottom:1px solid var(--border);font-size:14px">
          <strong>${h.utilisateur_nom} ${h.utilisateur_prenom}</strong> — ${h.action}
          ${h.ancien_statut ? `<span style="color:var(--text-muted)"> : ${h.ancien_statut} → ${h.nouveau_statut}</span>` : ""}
          <span style="color:var(--text-muted);margin-left:8px">${formatDate(h.created_at)}</span>
          ${h.details ? `<div style="color:var(--text-muted);margin-top:4px">${h.details}</div>` : ""}
      </div>`,
    )
    .join("");
}

window.updateStatut = async function () {
  const statut_code = document.getElementById("statut-select").value;
  const details = document.getElementById("statut-details").value;
  if (!statut_code) {
    showToast("Sélectionner un statut");
    return;
  }

  const res = await fetch(
    "/complaint-manager/actions/affectations/mise_a_jour_statut.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-Token": csrfToken,
      },
      body: JSON.stringify({
        reclamation_id: parseInt(id),
        statut_code,
        details,
      }),
    },
  );
  const data = await res.json();
  if (data.success) {
    showToast("Statut mis à jour");
    getReclamationDetails();
  } else {
    showToast(data.message || "Échec de la mise à jour");
  }
};

window.addComment = async function () {
  const contenu = document.getElementById("newComment").value.trim();
  if (!contenu) return;
  const res = await fetch(
    "/complaint-manager/actions/commentaires/ajouter.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-Token": csrfToken,
      },
      body: JSON.stringify({
        reclamation_id: parseInt(id),
        contenu,
        interne: false,
      }),
    },
  );
  const data = await res.json();
  if (data.success) {
    document.getElementById("newComment").value = "";
    getReclamationDetails();
  } else {
    showToast(data.message || "Échec");
  }
};
