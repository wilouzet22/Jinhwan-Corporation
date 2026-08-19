    </div> 
</div> 

<script>
    
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    if (themeToggleBtn) {
        
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            darkIcon.classList.toggle('hidden');
            lightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }

            window.dispatchEvent(new Event('themeChanged'));
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('estudiante-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        if (mobileBtn && sidebar && backdrop) {
            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            mobileBtn.addEventListener('click', (e) => { e.stopPropagation(); const isOpen = !sidebar.classList.contains('-translate-x-full'); isOpen ? closeSidebar() : openSidebar(); });
            backdrop.addEventListener('click', closeSidebar);
            sidebar.querySelectorAll('a').forEach(link => link.addEventListener('click', closeSidebar));
        }
    });
</script>
</body>
</html>
