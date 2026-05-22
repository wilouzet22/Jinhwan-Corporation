    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 mt-auto">
        <div class="container mx-auto px-4 py-6 text-center text-sm">
            &copy; 2025 Jinnwhan Corporation. Todos los derechos reservados.
        </div>
    </footer>
    </div> <!-- Close Content Wrapper -->
</div> <!-- Close Body Wrapper -->

<script>
    // Sidebar Toggle Logic for Admin
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('admin-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    
    if (mobileBtn && sidebar && backdrop) {
        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scroll on body
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        mobileBtn.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);
    }
</script>
</body>
</html>
