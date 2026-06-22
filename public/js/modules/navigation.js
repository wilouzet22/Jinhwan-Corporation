// Navigation logic for public site, admin, maestro and estudiante panels
document.addEventListener("DOMContentLoaded", () => {

  // ── Mobile hamburger menu ──────────────────────────────────────────────────
  // Support multiple sidebar IDs used across different layouts
  const mobileMenuBtn = document.getElementById("mobile-menu-btn");
  const sidebar = (
    document.getElementById("sidebar") ||
    document.getElementById("admin-sidebar") ||
    document.getElementById("maestro-sidebar") ||
    document.getElementById("estudiante-sidebar")
  );
  const backdrop = document.getElementById("sidebar-backdrop");

  function openSidebar() {
    sidebar.classList.remove("-translate-x-full");
    backdrop.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
  }

  function closeSidebar() {
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

    // Close when touching the backdrop
    backdrop.addEventListener("click", closeSidebar);

    // Close when navigating to a sidebar link
    sidebar.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", closeSidebar);
    });
  }

  // ── Admin dropdown menu (public site only) ─────────────────────────────────
  const adminToggle   = document.getElementById("admin-menu-toggle");
  const adminDropdown = document.getElementById("admin-menu-dropdown");

  if (adminToggle && adminDropdown) {
    adminToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      adminDropdown.classList.toggle("hidden");
    });

    // Close when clicking outside
    document.addEventListener("click", (e) => {
      if (
        !adminDropdown.contains(e.target) &&
        !adminToggle.contains(e.target)
      ) {
        adminDropdown.classList.add("hidden");
      }
    });
  }
});
