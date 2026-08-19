

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

function openAscensoModal(data) {
  const modal = document.getElementById("ascenso-modal");
  const modalContent = document.getElementById("modal-content");

  document.getElementById("modal-title").textContent = data.titulo;
  document.getElementById("modal-category").textContent =
    data.categoria_nombre || "General";

  const safeDescription = escapeHtml(data.descripcion).replace(/\n/g, "<br>");
  document.getElementById("modal-description").innerHTML = safeDescription;

  const resourcesContainer = document.getElementById("modal-resources");
  resourcesContainer.innerHTML = "";

  if (!data.recursos) data.recursos = [];
  
  if (data.url_video) {
      
      const hasMainVideo = data.recursos.some(r => r.url === data.url_video);
      if (!hasMainVideo) {
          data.recursos.unshift({
              tipo: 'Video',
              titulo: 'Video Demostrativo',
              url: data.url_video
          });
      }
  }

  if (data.recursos && data.recursos.length > 0) {
    data.recursos.forEach((recurso) => {
      const resourceCard = document.createElement("div");
      resourceCard.className =
        "glass-card rounded-2xl p-6 border border-slate-800/80 shadow-lg";

      let contentHtml = "";

      if (recurso.tipo === "Video") {
        if (recurso.url.includes("uploads/")) {
          contentHtml = `
                        <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden bg-black mb-4 shadow-lg border border-slate-800/50">
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
                                <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden bg-black mb-4 shadow-lg border border-slate-800/50">
                                    <iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                                </div>
                             `;
            } else {
              contentHtml = `<a href="${recurso.url}" target="_blank" class="inline-flex items-center gap-2 text-tkd-blue hover:text-blue-400 hover:underline mb-3 transition-colors"><span class="material-icons-outlined">open_in_new</span> Ver Video Externo</a>`;
            }
          } else {
            contentHtml = `<a href="${recurso.url}" target="_blank" class="inline-flex items-center gap-2 text-tkd-blue hover:text-blue-400 hover:underline mb-3 transition-colors"><span class="material-icons-outlined">open_in_new</span> Ver Video Externo</a>`;
          }
        }
      } else if (recurso.tipo === "Imagen") {
        contentHtml = `<img src="${recurso.url}" alt="${recurso.titulo}" class="w-full h-auto rounded-xl shadow-md border border-slate-800/50 mb-4">`;
      }

      resourceCard.innerHTML = `
                <h4 class="font-display font-bold text-lg mb-3 text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-slate-400">${recurso.tipo === "Video" ? "movie" : "description"}</span>
                    ${recurso.titulo}
                </h4>
                ${contentHtml}
                ${recurso.tipo !== "Video" && recurso.tipo !== "Imagen" ? `<a href="${recurso.url}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white rounded-xl transition-all text-sm font-bold uppercase tracking-wider shadow-md hover:shadow-[0_0_15px_rgba(37,99,235,0.3)]">Ver Recurso</a>` : ""}
            `;

      resourcesContainer.appendChild(resourceCard);
    });
  } else {
    resourcesContainer.innerHTML =
      '<p class="text-slate-500 italic">No hay recursos multimedia disponibles para este tema.</p>';
  }

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
    allBtn.classList.add("bg-tkd-red", "text-white", "shadow-[0_0_15px_rgba(220,38,38,0.4)]");
    allBtn.classList.remove("text-slate-400", "hover:text-white");
    mineBtn.classList.remove("bg-tkd-red", "text-white", "shadow-[0_0_15px_rgba(220,38,38,0.4)]");
    mineBtn.classList.add("text-slate-400", "hover:text-white");

    cards.forEach((card) => {
      card.classList.remove("hidden");
      visibleCount++;
    });
    emptyState.classList.add("hidden");
  } else {
    mineBtn.classList.add("bg-tkd-red", "text-white", "shadow-[0_0_15px_rgba(220,38,38,0.4)]");
    mineBtn.classList.remove("text-slate-400", "hover:text-white");
    allBtn.classList.remove("bg-tkd-red", "text-white", "shadow-[0_0_15px_rgba(220,38,38,0.4)]");
    allBtn.classList.add("text-slate-400", "hover:text-white");

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
        
        if (data.action === "added") {
          miTeoriaIds.push(teoriaId);
          btn.classList.add("text-tkd-gold");
          btn.classList.remove("text-slate-400");
          icon.textContent = "star";
        } else {
          const index = miTeoriaIds.indexOf(teoriaId);
          if (index > -1) miTeoriaIds.splice(index, 1);
          btn.classList.remove("text-tkd-gold");
          btn.classList.add("text-slate-400");
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
