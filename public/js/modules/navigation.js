
document.addEventListener("DOMContentLoaded", () => {

  // ── Mobile sidebar drawer ──────────────────────────────────────────────
  const mobileMenuBtn = document.getElementById("mobile-menu-btn");
  const sidebar = (
    document.getElementById("sidebar") ||
    document.getElementById("admin-sidebar") ||
    document.getElementById("maestro-sidebar") ||
    document.getElementById("estudiante-sidebar")
  );
  const backdrop = document.getElementById("sidebar-backdrop");

  function openSidebar() {
    if (!sidebar || !backdrop) return;
    sidebar.classList.remove("-translate-x-full");
    backdrop.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
  }

  function closeSidebar() {
    if (!sidebar || !backdrop) return;
    sidebar.classList.add("-translate-x-full");
    backdrop.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
  }

  if (mobileMenuBtn && sidebar && backdrop) {
    mobileMenuBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = !sidebar.classList.contains("-translate-x-full");
      isOpen ? closeSidebar() : openSidebar();
    });

    backdrop.addEventListener("click", closeSidebar);

    sidebar.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", closeSidebar);
    });
  }

  // ── User/account dropdown (≡ inside sidebar) ──────────────────────────
  const adminToggle   = document.getElementById("admin-menu-toggle");
  const adminDropdown = document.getElementById("admin-menu-dropdown");

  if (adminToggle && adminDropdown) {
    adminToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      adminDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", (e) => {
      if (
        !adminDropdown.contains(e.target) &&
        !adminToggle.contains(e.target)
      ) {
        adminDropdown.classList.add("hidden");
      }
    });
  }

  // ── Theme toggle ───────────────────────────────────────────────────────
  const themeBtn  = document.getElementById("theme-toggle");
  const darkIcon  = document.getElementById("theme-toggle-dark-icon");
  const lightIcon = document.getElementById("theme-toggle-light-icon");

  function applyThemeIcons(isDark) {
    if (!darkIcon || !lightIcon) return;
    if (isDark) {
      darkIcon.classList.remove("hidden");   // show sun icon (click → light)
      lightIcon.classList.add("hidden");
    } else {
      lightIcon.classList.remove("hidden");  // show moon icon (click → dark)
      darkIcon.classList.add("hidden");
    }
  }

  if (themeBtn) {
    // Sync icons with current theme on load
    applyThemeIcons(document.documentElement.classList.contains("dark"));

    themeBtn.addEventListener("click", () => {
      const isDark = document.documentElement.classList.toggle("dark");
      localStorage.setItem("color-theme", isDark ? "dark" : "light");
      applyThemeIcons(isDark);
    });
  }

});
