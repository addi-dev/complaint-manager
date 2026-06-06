import { initials, colorFor } from "../js/lib/string.js";

document.addEventListener("DOMContentLoaded", async () => {
  const avatar = document.getElementById("avatar");
  const nameEl = document.querySelector(".user-info .name");
  const roleEl = document.querySelector(".user-info .role");

  try {
    const res = await fetch("../../api/auth_api.php", {
      credentials: "include",
    });

    const data = await res.json();

    if (!data.logged_in || !data.user) return;

    const user = data.user;

    // build full name from API fields
    const fullName = [user.nom, user.prenom].filter(Boolean).join(" ");

    // avatar
    avatar.innerHTML = initials(fullName);
    avatar.style.background = colorFor(fullName);

    // text fields
    nameEl.textContent = fullName;
    roleEl.textContent = user.role;
  } catch (err) {
    console.error("Auth API error:", err);
  }
});
