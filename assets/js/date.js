export function formatDate(d) {
  if (!d) return "—";
  const [y, m, day] = d.split("-");
  return (
    [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ][+m - 1] +
    " " +
    parseInt(day) +
    ", " +
    y
  );
}
