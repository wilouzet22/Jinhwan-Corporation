// Student Ascensos Logic
// Assumes global 'miTeoriaIds' array is defined in the HTML

// Helper to escape HTML and prevent XSS
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

function openAscensoModal(data) {
  const modal = document.getElementById("ascenso-modal");
  const modalContent = document.getElementById("modal-content");

  // Populate Data
  document.getElementById("modal-title").textContent = data.titulo;
  document.getElementById("modal-category").textContent =
    data.categoria_nombre || "General";

  // SECURE: Escape HTML to prevent XSS, then convert newlines to <br>
  const safeDescription = escapeHtml(data.descripcion).replace(/\n/g, "<br>");
  document.getElementById("modal-description").innerHTML = safeDescription;

  // Populate Resources
  const resourcesContainer = document.getElementById("modal-resources");
  resourcesContainer.innerHTML = "";

  if (data.recursos && data.recursos.length > 0) {
    data.recursos.forEach((recurso) => {
      const resourceCard = document.createElement("div");
      resourceCard.className =
        "bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700";

      let contentHtml = "";

      if (recurso.tipo === "Video") {
        if (recurso.url.includes("uploads/")) {
          contentHtml = `
                        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-black mb-3 shadow-lg">
                            <video controls class="w-full h-full object-contain">
                                <source src="${recurso.url}" type="video/mp4">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        </div>
                    `;
        } else {
          if (
            recurso.url.includes("youtube.com") ||
            recurso.url.includes("youtu.be")
          ) {
            let videoId = "";
            if (recurso.url.includes("v="))
              videoId = recurso.url.split("v=")[1].split("&")[0];
            else if (recurso.url.includes("youtu.be/"))
              videoId = recurso.url.split("youtu.be/")[1];

            if (videoId) {
              contentHtml = `
                                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-black mb-3 shadow-lg">
                                    <iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                                </div>
                             `;
            } else {
              contentHtml = `<a href="${recurso.url}" target="_blank" class="flex items-center gap-2 text-tkd-blue hover:underline mb-2"><span class="material-icons-outlined">open_in_new</span> Ver Video Externo</a>`;
            }
          } else {
            contentHtml = `<a href="${recurso.url}" target="_blank" class="flex items-center gap-2 text-tkd-blue hover:underline mb-2"><span class="material-icons-outlined">open_in_new</span> Ver Video Externo</a>`;
          }
        }
      } else if (recurso.tipo === "Imagen") {
        contentHtml = `<img src="${recurso.url}" alt="${recurso.titulo}" class="w-full h-auto rounded-lg shadow-md mb-3">`;
      }

      resourceCard.innerHTML = `
                <h4 class="font-bold text-lg mb-2 text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-slate-400">${recurso.tipo === "Video" ? "movie" : "description"}</span>
                    ${recurso.titulo}
                </h4>
                ${contentHtml}
                ${recurso.tipo !== "Video" && recurso.tipo !== "Imagen" ? `<a href="${recurso.url}" target="_blank" class="inline-flex items-center px-4 py-2 bg-tkd-blue text-white rounded hover:bg-blue-700 transition-colors text-sm font-bold uppercase tracking-wide">Ver Recurso</a>` : ""}
            `;

      resourcesContainer.appendChild(resourceCard);
    });
  } else {
    resourcesContainer.innerHTML =
      '<p class="text-slate-500 italic">No hay recursos multimedia disponibles para este tema.</p>';
  }

  // Show Modal
  modal.classList.remove("hidden");
  setTimeout(() => {
    modal.classList.remove("opacity-0");
    modalContent.classList.remove("scale-95");
    modalContent.classList.add("scale-100");
  }, 10);
  document.body.style.overflow = "hidden";
}

function closeAscensoModal() {
  const modal = document.getElementById("ascenso-modal");
  const modalContent = document.getElementById("modal-content");

  modal.classList.add("opacity-0");
  modalContent.classList.remove("scale-100");
  modalContent.classList.add("scale-95");

  setTimeout(() => {
    modal.classList.add("hidden");
    const videos = modal.querySelectorAll("video");
    videos.forEach((v) => v.pause());
    const iframes = modal.querySelectorAll("iframe");
    iframes.forEach((i) => {
      i.src = i.src;
    });
  }, 300);
  document.body.style.overflow = "";
}

let currentTab = "all";

function switchTab(tab) {
  currentTab = tab;
  const allBtn = document.getElementById("tab-all-btn");
  const mineBtn = document.getElementById("tab-mine-btn");
  const cards = document.querySelectorAll(".teoria-card");
  const emptyState = document.getElementById("mi-teoria-empty");
  let visibleCount = 0;

  if (tab === "all") {
    allBtn.classList.add("bg-tkd-red", "text-white", "shadow-md");
    allBtn.classList.remove("text-slate-500");
    mineBtn.classList.remove("bg-tkd-red", "text-white", "shadow-md");
    mineBtn.classList.add("text-slate-500");

    cards.forEach((card) => {
      card.classList.remove("hidden");
      visibleCount++;
    });
    emptyState.classList.add("hidden");
  } else {
    mineBtn.classList.add("bg-tkd-red", "text-white", "shadow-md");
    mineBtn.classList.remove("text-slate-500");
    allBtn.classList.remove("bg-tkd-red", "text-white", "shadow-md");
    allBtn.classList.add("text-slate-500");

    cards.forEach((card) => {
      const id = parseInt(card.dataset.teoriaId);
      if (miTeoriaIds.includes(id)) {
        card.classList.remove("hidden");
        visibleCount++;
      } else {
        card.classList.add("hidden");
      }
    });

    if (visibleCount === 0) {
      emptyState.classList.remove("hidden");
    } else {
      emptyState.classList.add("hidden");
    }
  }
}

function toggleMiTeoria(teoriaId) {
  const btn = document.querySelector(
    `.teoria-card[data-teoria-id="${teoriaId}"] .mi-teoria-toggle`,
  );
  const icon = btn.querySelector(".material-icons-outlined");

  fetch("/jinwha/ascensos/toggle", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ teoriaId: teoriaId }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Controller returns 'status' not 'success' boolean
        if (data.action === "added") {
          miTeoriaIds.push(teoriaId);
          btn.classList.add("text-tkd-gold");
          btn.classList.remove("text-slate-300");
          icon.textContent = "star";
        } else {
          const index = miTeoriaIds.indexOf(teoriaId);
          if (index > -1) miTeoriaIds.splice(index, 1);
          btn.classList.remove("text-tkd-gold");
          btn.classList.add("text-slate-300");
          icon.textContent = "star_border";
        }

        if (currentTab === "mine") {
          switchTab("mine");
        }
      }
    });
}

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("ascenso-modal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        closeAscensoModal();
      }
    });
  }
});
