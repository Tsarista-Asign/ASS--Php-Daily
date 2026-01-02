function openPopup(id) {
    document.getElementById(id).style.display = 'flex';
}
function closePopup(id) {
    document.getElementById(id).style.display = 'none';
}
function confirmDelete(url) {
    if (confirm("Bạn có chắc chắn muốn xóa bản ghi này?")) {
        window.location = url;
    }
}
function toggleTheme() {
  const root = document.documentElement;
  const theme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
  root.setAttribute('data-theme', theme);
  localStorage.setItem('theme', theme);
}
document.addEventListener('DOMContentLoaded', ()=>{
  document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'light');
});