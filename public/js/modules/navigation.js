// Admin Menu Toggle logic for both public and admin headers
document.addEventListener("DOMContentLoaded", () => {

  // ── Mobile hamburger menu ──────────────────────────────────────────────────
  const mobileMenuBtn  = document.getElementById("mobile-menu-btn");
  const sidebar        = document.getElementById("sidebar");
  const backdrop       = document.getElementById("sidebar-backdrop");

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

    // Cerrar al tocar el backdrop
    backdrop.addEventListener("click", closeSidebar);

    // Cerrar al navegar a un enlace del sidebar
    sidebar.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", closeSidebar);
    });
  }

  // ── Admin dropdown menu ────────────────────────────────────────────────────
  const adminToggle   = document.getElementById("admin-menu-toggle");
  const adminDropdown = document.getElementById("admin-menu-dropdown");

  if (adminToggle && adminDropdown) {
    adminToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      adminDropdown.classList.toggle("hidden");
    });

    // Cerrar al hacer clic fuera
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
