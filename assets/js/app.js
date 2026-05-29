import { initials, colorFor } from "../js/lib/string.js";

const avatar = document.getElementById("avatar");
const name = "Ibtisam Fadil";
avatar.innerHTML = initials(name);
avatar.style.background = colorFor(name);