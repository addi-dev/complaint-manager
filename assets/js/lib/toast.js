let toastTimer;
export function showToast(message, timer = 3200){
  const t=document.getElementById('toast');
  document.getElementById('toastMsg').textContent=message;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer=setTimeout(()=>t.classList.remove('show'),timer);
}