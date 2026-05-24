// Fetch all users
import { initials, colorFor } from "./string.js";
import { formatDate } from "./date.js";
const users = [];
let filtered = [];
let page = 1;
const PER = 10; // users per page
function getUsers() {
  fetch("http://localhost/complaint-manager/api/users_api.php")
    .then((res) => res.json())
    .then((data) => {
      users.push(...data.users);
      console.log(users);
      renderUsers();
    })
    .catch((err) => console.error(err));
}
getUsers();
function renderUsers() {
  const slice = users;
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
              <button class="action-btn action-btn-edit" title="Modifier">
                <i class="fa-solid fa-pencil"></i>
              </button>
              <button class="action-btn action-btn-delete" title="Supprimer">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
        </td>
        </tr>
  `,
    )
    .join("");
  //! Show users count
  document.getElementById("enrollCount").innerHTML =
    users.length + " " + "utilisateurs inscrits";
}
