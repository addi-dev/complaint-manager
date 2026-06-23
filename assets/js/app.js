// 23 / June / 2026
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
    const fullName = [user.nom, user.prenom].filter(Boolean).join(" ");
    avatar.innerHTML = initials(fullName);
    avatar.style.background = colorFor(fullName);
    nameEl.textContent = fullName;
    roleEl.textContent = user.role;
    //! aficher les icons des mesage non lues
    const badge = document.getElementById("sidebarNotifBadge");
    console.log("badge:", badge, "role:", data.user?.role);
    if (badge && data.user?.role === "client") {
      try {
        const notifRes = await fetch(
          "../../api/client_notifications_api.php?action=list",
          {
            credentials: "include",
          },
        );
        const notifData = await notifRes.json();
        if (notifData.success && notifData.unread > 0) {
          badge.textContent = notifData.unread;
          badge.style.display = "inline-block";
        }
      } catch (err) {
        console.log("error not showing");
      }
    }
  } catch (err) {
    console.error("Auth API error:", err);
  }
});
