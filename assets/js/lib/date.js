export function formatDate(d) {
  if (!d) return "—";

  const [y, m, day] = d.split("-");

  const months = [
    "janvier",
    "février",
    "mars",
    "avril",
    "mai",
    "juin",
    "juillet",
    "août",
    "septembre",
    "octobre",
    "novembre",
    "décembre",
  ];

  return `${parseInt(day)} ${months[+m - 1]} ${y}`;
}
