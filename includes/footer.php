</main>
</div>
<script>
// Simple sidebar toggle for mobile
const toggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
if (toggle && sidebar) toggle.addEventListener('click', () => sidebar.classList.toggle('open'));

// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(el => {
  setTimeout(() => el.style.opacity = '0', 4000);
});
</script>
</body>
</html>
