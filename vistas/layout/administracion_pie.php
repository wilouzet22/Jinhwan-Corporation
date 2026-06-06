    <footer class="bg-slate-950/40 backdrop-blur-sm border-t border-slate-900 text-slate-500 mt-auto">
        <div class="container mx-auto px-4 py-6 text-center text-sm">
            &copy; 2025 Jinhwan Corporation. Todos los derechos reservados.
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
