import { initials, colorFor } from "../lib/string.js";
import { formatDate } from "../lib/date.js";
import { showToast } from "../lib/toast.js";
const clients = [];
let filtered = [];
let page = 1;
const PER = 10;
