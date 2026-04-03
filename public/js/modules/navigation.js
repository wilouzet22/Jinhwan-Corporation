// Admin Menu Toggle logic for both public and admin headers
document.addEventListener("DOMContentLoaded", () => {
  const adminToggle = document.getElementById("admin-menu-toggle");
  const adminDropdown = document.getElementById("admin-menu-dropdown");

  if (adminToggle && adminDropdown) {
    adminToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      adminDropdown.classList.toggle("hidden");
    });

    // Close on click outside
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
