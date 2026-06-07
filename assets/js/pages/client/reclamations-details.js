import { formatDate } from "../../lib/date.js";
import { initials, colorFor } from "../../lib/string.js";
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

function getReclamationDetails() {
  if (!id) {
    console.error("No id in URL");
    return;
  }

  fetch(`../../api/reclamation_details_api.php?id=${id}`)
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        console.error(data.message);
        return;
      }
      console.log(data.reclamation); // core fields
      renderDetails(data.reclamation);
      renderCommentaires(data.commentaires); // comments
      renderAttachements(data.pieces_jointes); // attachments
      console.log(data.affectations); // assignment history
      console.log(data.historique); // audit trail
    })
    .catch((err) => console.error(err));
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
  const count = commentaires.length
  document.getElementById("commentsList").innerHTML = commentaires
    .map((cmt, i) => {
      const isAgent = cmt.utilisateur_id !== null; // agent wrote it
      const nom = isAgent
        ? `${cmt.utilisateur_nom} ${cmt.utilisateur_prenom}`
        : `${cmt.client_auteur_nom} ${cmt.client_auteur_prenom}`;
      const role = isAgent ? cmt.utilisateur_role : "client";
      const color = isAgent ? "#3b6ef8" : "#16a34a"; // blue = agent, green = client

      return `
                    <div class="comment ${isAgent ? "comment-agent" : "comment-client"}">
                        <div class="cmt-avatar" style="background:${colorFor(nom)}">${initials(nom)}</div>
                        <div class="cmt-right">
                            <div class="cmt-meta">
                                <div><span class="cmt-author">${nom}</span><span class="cmt-role">${role}</span></div>
                                <span class="cmt-time">${cmt.created_at}</span>
                            </div>
                            <div class="cmt-text">Bonjour, nous avons bien reçu votre réclamation. Pourriez-vous nous transmettre le numéro de contrat associé à cette facture ?</div>
                        </div>
                    </div>
    `;
    })
    .join("");
  document.getElementById("nombre_commentaires").innerText =
    `${count} message${count > 1 ? "s" : ""}`;
}
function renderAttachements(pieces_jointes) {
  const count = pieces_jointes.length;
  const getIconClass = (mime) => {
    if (mime.includes("pdf")) return "pdf";
    if (mime.includes("image")) return "img";
    if (mime.includes("word")) return "doc";
    return "other";
  };

  const getIcon = (mime) => {
    if (mime.includes("pdf")) return "fa-regular fa-file-pdf";
    if (mime.includes("image")) return "fa-regular fa-image";
    return "fa-regular fa-file";
  };

  const formatSize = (bytes) => {
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + " Mo";
    return Math.round(bytes / 1024) + " Ko";
  };
  document.getElementById("attachements-container").innerHTML = pieces_jointes
    .map(
      (pj, i) => `<div class="file-item">
                        <div class="file-icon ${getIconClass(pj.type_mime)}">
                            <i class="${getIcon(pj.type_mime)}"></i>
                        </div>
                        <div>
                            <div class="file-name">${pj.nom_fichier}</div>
                            <div class="file-size">${formatSize(pj.taille)}</div>
                        </div>
                        <div style="flex:1"></div>
                        <a href="../../${pj.chemin}" download="${pj.nom_fichier}" class="file-dl">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>`,
    )
    .join("");
  document.getElementById("nombre_pieces").innerText =
    `${count} fichier${count > 1 ? "s" : ""}
`;
}
