    </div> <!-- Cierra el flex-1 main wrapper de cabecera -->
</div> <!-- Cierra el flex min-h-screen principal de cabecera -->

<!-- Script para el Sidebar Móvil -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle Logic for Student
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('estudiante-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (mobileBtn && sidebar && backdrop) {
        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }
        }

        mobileBtn.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);
    }
});
</script>
</body>
</html>
