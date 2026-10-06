<?php
/**
 * Widget Asistente IA Maestro (NVIDIA NIM)
 * Botón flotante y panel lateral interactivo reutilizable
 */
$contextoIA = $contextoIA ?? 'cronogramas';
?>

<!-- Estilos dedicados para el Panel Lateral IA y su Backdrop -->
<style>
#ia-panel-lateral {
    position: fixed !important;
    top: 0 !important;
    right: 0 !important;
    height: 100vh !important;
    width: 440px !important;
    max-width: 95vw !important;
    z-index: 999999 !important;
    transform: translateX(100%) !important;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease !important;
    box-shadow: -10px 0 30px rgba(0, 0, 0, 0.25) !important;
    display: flex !important;
    flex-direction: column !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

#ia-panel-lateral.panel-ia-abierto {
    transform: translateX(0) !important;
    visibility: visible !important;
    pointer-events: auto !important;
}

#ia-panel-backdrop {
    position: fixed !important;
    inset: 0 !important;
    background-color: rgba(2, 6, 23, 0.6) !important;
    backdrop-filter: blur(4px) !important;
    -webkit-backdrop-filter: blur(4px) !important;
    z-index: 999990 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
    transition: opacity 0.3s ease, visibility 0.3s ease !important;
}

#ia-panel-backdrop.backdrop-ia-abierto {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
}
</style>

<!-- Botón Flotante IA (FAB) -->
<div id="ia-fab-container" class="fixed bottom-6 right-6 z-50">
    <button type="button" 
            id="ia-btn-toggle" 
            onclick="togglePanelIA()" 
            class="group relative inline-flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-purple-600 via-indigo-600 to-violet-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-purple-500/30 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
        <!-- Glow pulse ring -->
        <span class="absolute -inset-0.5 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 opacity-60 blur-xs group-hover:opacity-100 transition duration-300 animate-pulse"></span>
        <span class="relative flex items-center gap-2">
            <span class="material-icons-outlined text-lg animate-spin-slow">auto_awesome</span>
            <span class="hidden sm:inline">Asistente IA</span>
        </span>
    </button>
</div>

<!-- Backdrop Overlay -->
<div id="ia-panel-backdrop" 
     onclick="cerrarPanelIA()" 
     class="transition-all duration-300"></div>

<!-- Panel Lateral Deslizante (Slideover) -->
<aside id="ia-panel-lateral" 
       class="bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 shadow-2xl flex flex-col overflow-hidden text-slate-800 dark:text-slate-100">
    
    <!-- Header del Panel -->
    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-md flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-purple-500/20">
                <span class="material-icons-outlined text-lg">auto_awesome</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-tight">Asistente IA Maestro</h3>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">NVIDIA NIM</span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Planificador pedagógico y biblioteca</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5">
            <button type="button" 
                    id="ia-btn-refresh"
                    onclick="limpiarChatIA()" 
                    class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer flex items-center justify-center" 
                    title="Reiniciar conversación">
                <span class="material-icons-outlined text-lg pointer-events-none">refresh</span>
            </button>
            <button type="button" 
                    id="ia-btn-cerrar"
                    onclick="cerrarPanelIA()" 
                    class="p-2 text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition cursor-pointer flex items-center justify-center" 
                    title="Cerrar panel (Esc)">
                <span class="material-icons-outlined text-xl pointer-events-none">close</span>
            </button>
        </div>
    </div>

    <!-- Sugerencias Rápidas (Chips de atajo) -->
    <div class="px-4 py-2.5 bg-slate-100/60 dark:bg-slate-950/40 border-b border-slate-200/80 dark:border-slate-800/80 overflow-x-auto flex items-center gap-1.5 shrink-0 no-scrollbar">
        <button type="button" 
                onclick="enviarPromptRapido('Genera un cronograma de clase completo con un objetivo que tú escojas para nivel intermedio')" 
                class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-purple-400 dark:hover:border-purple-600 hover:text-purple-600 dark:hover:text-purple-400 whitespace-nowrap shadow-2xs transition">
            🎯 Objetivo libre
        </button>
        <button type="button" 
                onclick="enviarPromptRapido('Genera un cronograma enfocado en velocidad y técnica de Bandal Chagui y Dollyo Chagui')" 
                class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-purple-400 dark:hover:border-purple-600 hover:text-purple-600 dark:hover:text-purple-400 whitespace-nowrap shadow-2xs transition">
            ⚡ Velocidad de pateo
        </button>
        <button type="button" 
                onclick="enviarPromptRapido('Planifica una sesión de combate táctico, contraataques y desplazamientos de esquiva')" 
                class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-purple-400 dark:hover:border-purple-600 hover:text-purple-600 dark:hover:text-purple-400 whitespace-nowrap shadow-2xs transition">
            🛡️ Combate táctico
        </button>
        <button type="button" 
                onclick="enviarPromptRapido('Sugiere 3 ejercicios nuevos de pliometría y fuerza explosiva para añadir a la biblioteca')" 
                class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-purple-400 dark:hover:border-purple-600 hover:text-purple-600 dark:hover:text-purple-400 whitespace-nowrap shadow-2xs transition">
            💡 Nuevos ejercicios
        </button>
    </div>

    <!-- Contenedor del Chat (Historial con scroll) -->
    <div id="ia-chat-mensajes" class="flex-1 min-h-0 overflow-y-auto p-4 space-y-3.5 text-xs panel-scroll-custom bg-slate-50/50 dark:bg-slate-900/50">
        <!-- Mensaje de bienvenida inicial -->
        <div class="flex items-start gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white shrink-0 shadow-xs">
                <span class="material-icons-outlined text-sm">smart_toy</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-xs border border-slate-200 dark:border-slate-700/80 shadow-xs max-w-[88%] space-y-2">
                <p class="text-slate-800 dark:text-slate-200 leading-relaxed">
                    ¡Saludos, Sabomnim! 👋 Soy tu asistente de IA especializado en Taekwondo.
                </p>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                    Puedo generar <strong class="text-purple-600 dark:text-purple-400">cronogramas de clase completos</strong> con fases (inicial, central y final) aprovechando los ejercicios de tu biblioteca o proponer <strong class="text-indigo-600 dark:text-indigo-400">ejercicios nuevos</strong>.
                </p>
                <div class="p-2.5 rounded-xl bg-purple-50/70 dark:bg-purple-950/40 border border-purple-200/60 dark:border-purple-900/40 text-[11px] text-purple-900 dark:text-purple-300">
                    💡 <em>Prueba diciendo: "Genera un cronograma con un objetivo que tú escojas" o usa los botones rápidos arriba.</em>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de "Escribiendo..." animado -->
    <div id="ia-chat-loading" class="hidden px-4 py-2 flex items-center gap-2 text-xs text-purple-600 dark:text-purple-400 bg-white/80 dark:bg-slate-900/80 border-t border-slate-100 dark:border-slate-800/80 shrink-0">
        <span class="material-icons-outlined text-sm animate-spin">refresh</span>
        <span class="font-medium animate-pulse">Generando respuesta con NVIDIA NIM...</span>
    </div>

    <!-- Área de Entrada de Mensaje (Footer) -->
    <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
        <form id="ia-form-chat" onsubmit="enviarMensajeIA(event)" class="flex items-center gap-2">
            <input type="text" 
                   id="ia-input-mensaje" 
                   placeholder="Escribe aquí tu petición o duda..." 
                   autocomplete="off" 
                   class="flex-1 px-3.5 py-2.5 text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
            <button type="submit" 
                    id="ia-btn-enviar" 
                    class="p-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-xs hover:shadow-purple-500/20 transition-all shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-icons-outlined text-base block">send</span>
            </button>
        </form>
        <div class="flex items-center justify-between mt-2 px-1 text-[10px] text-slate-400">
            <span>Presiona Enter para enviar</span>
            <span class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>NVIDIA NIM Conectado</span>
            </span>
        </div>
    </div>

</aside>

<script>
/**
 * Lógica del Asistente IA Maestro
 */
const IA_CONTEXTO_ACTUAL = '<?php echo htmlspecialchars($contextoIA); ?>';
const IA_ENDPOINT_CHAT = '<?php echo base_url("maestro/ia/chat"); ?>';
const IA_ENDPOINT_GUARDAR = '<?php echo base_url("maestro/ia/ejercicio/guardar"); ?>';

let iaHistorial = [];
let iaProcesando = false;

function togglePanelIA() {
    const panel = document.getElementById('ia-panel-lateral');
    if (!panel) return;

    if (panel.classList.contains('panel-ia-abierto')) {
        cerrarPanelIA();
    } else {
        abrirPanelIA();
    }
}

function abrirPanelIA() {
    const panel = document.getElementById('ia-panel-lateral');
    const backdrop = document.getElementById('ia-panel-backdrop');
    if (!panel) return;

    panel.classList.add('panel-ia-abierto');
    panel.style.transform = 'translateX(0)';
    panel.style.visibility = 'visible';
    panel.style.pointerEvents = 'auto';

    if (backdrop) {
        backdrop.classList.add('backdrop-ia-abierto');
        backdrop.style.opacity = '1';
        backdrop.style.visibility = 'visible';
        backdrop.style.pointerEvents = 'auto';
    }

    setTimeout(() => {
        const inp = document.getElementById('ia-input-mensaje');
        if (inp) inp.focus();
    }, 150);
}

function cerrarPanelIA() {
    const panel = document.getElementById('ia-panel-lateral');
    const backdrop = document.getElementById('ia-panel-backdrop');

    if (panel) {
        panel.classList.remove('panel-ia-abierto');
        panel.style.transform = 'translateX(100%)';
        panel.style.visibility = 'hidden';
        panel.style.pointerEvents = 'none';
    }

    if (backdrop) {
        backdrop.classList.remove('backdrop-ia-abierto');
        backdrop.style.opacity = '0';
        backdrop.style.visibility = 'hidden';
        backdrop.style.pointerEvents = 'none';
    }
}

// Listeners adicionales de respaldo (Click directo, Escape, Backdrop)
function inicializarEventosPanelIA() {
    const btnCerrar = document.getElementById('ia-btn-cerrar');
    if (btnCerrar) {
        btnCerrar.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            cerrarPanelIA();
        };
    }

    const backdrop = document.getElementById('ia-panel-backdrop');
    if (backdrop) {
        backdrop.onclick = function(e) {
            e.preventDefault();
            cerrarPanelIA();
        };
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const panel = document.getElementById('ia-panel-lateral');
            if (panel && panel.classList.contains('panel-ia-abierto')) {
                cerrarPanelIA();
            }
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarEventosPanelIA);
} else {
    inicializarEventosPanelIA();
}

function limpiarChatIA() {
    iaHistorial = [];
    const container = document.getElementById('ia-chat-mensajes');
    if (container) {
        container.innerHTML = `
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white shrink-0 shadow-xs">
                    <span class="material-icons-outlined text-sm">smart_toy</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-xs border border-slate-200 dark:border-slate-700/80 shadow-xs max-w-[88%] space-y-2">
                    <p class="text-slate-800 dark:text-slate-200 leading-relaxed">
                        Conversación reiniciada. ¿En qué te ayudo ahora, Sabomnim?
                    </p>
                </div>
            </div>
        `;
    }
}

function enviarPromptRapido(texto) {
    const inp = document.getElementById('ia-input-mensaje');
    if (inp) inp.value = texto;
    abrirPanelIA();
    enviarMensajeIA();
}

async function enviarMensajeIA(e) {
    if (e && e.preventDefault) e.preventDefault();
    if (iaProcesando) return;

    const inp = document.getElementById('ia-input-mensaje');
    const btn = document.getElementById('ia-btn-enviar');
    const loader = document.getElementById('ia-chat-loading');
    const msg = (inp?.value || '').trim();

    if (!msg) return;

    // Agregar mensaje del usuario a la interfaz
    renderizarMensajeUsuario(msg);
    iaHistorial.push({ role: 'user', content: msg });
    if (inp) inp.value = '';

    iaProcesando = true;
    if (btn) btn.disabled = true;
    if (loader) loader.classList.remove('hidden');
    scrollChatAlFondo();

    try {
        const resp = await fetch(IA_ENDPOINT_CHAT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                mensaje: msg,
                contexto: IA_CONTEXTO_ACTUAL,
                historial: iaHistorial
            })
        });

        const resData = await resp.json();

        if (resData.success && resData.data) {
            renderizarRespuestaIA(resData.data);
            iaHistorial.push({
                role: 'assistant',
                content: typeof resData.data === 'string' ? resData.data : JSON.stringify(resData.data)
            });
        } else {
            renderizarErrorIA(resData.error || 'Ocurrió un error al procesar tu solicitud');
        }
    } catch (err) {
        console.error(err);
        renderizarErrorIA('No se pudo establecer conexión con el servidor. Verifica tu red.');
    } finally {
        iaProcesando = false;
        if (btn) btn.disabled = false;
        if (loader) loader.classList.add('hidden');
        scrollChatAlFondo();
    }
}

function renderizarMensajeUsuario(texto) {
    const cont = document.getElementById('ia-chat-mensajes');
    if (!cont) return;

    const div = document.createElement('div');
    div.className = 'flex justify-end';
    div.innerHTML = `
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-3 rounded-2xl rounded-tr-xs shadow-xs max-w-[85%] leading-relaxed">
            ${escaparHTML(texto)}
        </div>
    `;
    cont.appendChild(div);
}

function renderizarErrorIA(error) {
    const cont = document.getElementById('ia-chat-mensajes');
    if (!cont) return;

    const div = document.createElement('div');
    div.className = 'flex items-start gap-2.5';
    div.innerHTML = `
        <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
            <span class="material-icons-outlined text-sm">error_outline</span>
        </div>
        <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/40 text-red-700 dark:text-red-300 p-3 rounded-2xl rounded-tl-xs max-w-[88%] leading-relaxed">
            <strong>Atención:</strong> ${escaparHTML(error)}
        </div>
    `;
    cont.appendChild(div);
}

function renderizarRespuestaIA(data) {
    const cont = document.getElementById('ia-chat-mensajes');
    if (!cont) return;

    const accion = data.accion || 'texto';
    const mensajeTexto = data.mensaje || '';
    const cronograma = data.cronograma || null;
    const ejerciciosSugeridos = data.ejercicios_sugeridos || [];

    const div = document.createElement('div');
    div.className = 'flex items-start gap-2.5';

    let cuerpoHtml = '';

    // Si trae un cronograma estructurado
    if (accion === 'llenar_cronograma' && cronograma) {
        const totalInicial = (cronograma.parte_inicial || []).length;
        const totalCentral = (cronograma.parte_central || []).length;
        const totalFinal   = (cronograma.parte_final || []).length;

        // Guardar cronograma en memoria global para poder aplicarlo al hacer clic
        const idCronogramaMemoria = 'crono_' + Date.now();
        window[idCronogramaMemoria] = cronograma;

        cuerpoHtml = `
            <div class="space-y-2.5">
                ${mensajeTexto ? `<p class="text-slate-800 dark:text-slate-200 leading-relaxed">${escaparHTML(mensajeTexto)}</p>` : ''}
                
                <!-- Tarjeta del Cronograma -->
                <div class="p-3 bg-slate-50 dark:bg-slate-950/70 border border-purple-200 dark:border-purple-900/50 rounded-xl space-y-2">
                    <!-- Badges de Grupo y Fecha si fueron especificados -->
                    ${(cronograma.grupo_nombre || cronograma.fecha_texto || cronograma.fecha) ? `
                        <div class="flex flex-wrap items-center gap-1.5 pb-1">
                            ${cronograma.grupo_nombre ? `
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800/40">
                                    <span class="material-icons-outlined text-xs">groups</span>
                                    <span>${escaparHTML(cronograma.grupo_nombre)}</span>
                                </span>
                            ` : ''}
                            ${(cronograma.fecha_texto || cronograma.fecha) ? `
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/40">
                                    <span class="material-icons-outlined text-xs">event</span>
                                    <span>${escaparHTML(cronograma.fecha_texto || cronograma.fecha)}</span>
                                </span>
                            ` : ''}
                        </div>
                    ` : ''}

                    <div class="flex items-center gap-1.5 text-purple-700 dark:text-purple-300 font-bold text-[11px] uppercase tracking-wide">
                        <span class="material-icons-outlined text-sm">flag</span>
                        <span>Objetivo de la sesión</span>
                    </div>
                    <p class="text-slate-900 dark:text-white font-semibold text-xs leading-snug">
                        "${escaparHTML(cronograma.objetivo || 'Sin objetivo')}"
                    </p>

                    <!-- Fases Desglosadas -->
                    <div class="grid grid-cols-3 gap-1.5 pt-1 text-[10px]">
                        <div class="p-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/40 text-center">
                            <span class="block text-amber-800 dark:text-amber-300 font-bold">Inicial</span>
                            <span class="text-amber-600 dark:text-amber-400 font-medium">${totalInicial} ejercicio${totalInicial === 1 ? '' : 's'}</span>
                        </div>
                        <div class="p-1.5 rounded-lg bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-900/40 text-center">
                            <span class="block text-purple-800 dark:text-purple-300 font-bold">Central</span>
                            <span class="text-purple-600 dark:text-purple-400 font-medium">${totalCentral} ejercicio${totalCentral === 1 ? '' : 's'}</span>
                        </div>
                        <div class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/40 text-center">
                            <span class="block text-emerald-800 dark:text-emerald-300 font-bold">Final</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">${totalFinal} ejercicio${totalFinal === 1 ? '' : 's'}</span>
                        </div>
                    </div>

                    <!-- Botón Aplicar al Formulario -->
                    <button type="button" 
                            onclick="aplicarCronogramaAlFormulario('${idCronogramaMemoria}')"
                            class="w-full mt-2 py-2 px-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-sm transition">
                        <span class="material-icons-outlined text-sm">assignment_turned_in</span>
                        <span>Aplicar a la Clase</span>
                    </button>
                </div>
            </div>
        `;
    } 
    // Si sugiere ejercicios para la biblioteca
    else if (accion === 'agregar_ejercicio' && ejerciciosSugeridos.length > 0) {
        let listaCards = '';
        ejerciciosSugeridos.forEach((ej, idx) => {
            const idEjMem = 'sug_' + Date.now() + '_' + idx;
            window[idEjMem] = ej;

            listaCards += `
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-1.5" id="card-${idEjMem}">
                    <div class="flex items-start justify-between gap-1">
                        <h6 class="font-bold text-slate-900 dark:text-white text-xs">${escaparHTML(ej.nombre)}</h6>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/40 shrink-0">
                            ${escaparHTML(ej.tipo)}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-snug">
                        ${escaparHTML(ej.explicacion || '')}
                    </p>
                    <div class="flex items-center gap-1.5 pt-1">
                        <button type="button" 
                                onclick="guardarEjercicioDirecto('${idEjMem}')"
                                class="btn-guardar-${idEjMem} px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 transition">
                            <span class="material-icons-outlined text-xs">add</span>
                            <span>Guardar en Biblioteca</span>
                        </button>
                    </div>
                </div>
            `;
        });

        cuerpoHtml = `
            <div class="space-y-2.5">
                ${mensajeTexto ? `<p class="text-slate-800 dark:text-slate-200 leading-relaxed">${escaparHTML(mensajeTexto)}</p>` : ''}
                <div class="space-y-2">
                    ${listaCards}
                </div>
            </div>
        `;
    } 
    // Respuesta de texto general
    else {
        cuerpoHtml = `
            <p class="text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">
                ${escaparHTML(mensajeTexto || JSON.stringify(data))}
            </p>
        `;
    }

    div.innerHTML = `
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white shrink-0 shadow-xs">
            <span class="material-icons-outlined text-sm">smart_toy</span>
        </div>
        <div class="bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-xs border border-slate-200 dark:border-slate-700/80 shadow-xs max-w-[88%]">
            ${cuerpoHtml}
        </div>
    `;

    cont.appendChild(div);
}

/**
 * Aplica un cronograma devuelto por la IA al formulario de la vista cronogramas.php
 */
function aplicarCronogramaAlFormulario(idMemoria) {
    const cronograma = window[idMemoria];
    if (!cronograma) return;

    // 1. Abrir el modal de nuevo cronograma si está cerrado
    if (typeof openModal === 'function') {
        openModal('modal-nuevo-cronograma');
    } else {
        const modal = document.getElementById('modal-nuevo-cronograma');
        if (modal) modal.classList.remove('hidden');
    }

    const form = document.getElementById('form-nuevo-cronograma');
    if (form) {
        // 2. Asignar Grupo si fue determinado por la IA
        if (cronograma.id_grupo) {
            const selectGrupo = form.querySelector('select[name="id_grupo"]');
            if (selectGrupo) {
                selectGrupo.value = cronograma.id_grupo;
                selectGrupo.classList.add('ring-2', 'ring-purple-500');
                setTimeout(() => selectGrupo.classList.remove('ring-2', 'ring-purple-500'), 1500);
            }
        }

        // 3. Asignar Fecha seleccionada
        if (cronograma.fecha) {
            if (typeof seleccionarDia === 'function') {
                seleccionarDia(cronograma.fecha);
            } else {
                const inpFecha = document.getElementById('input-fecha-modal');
                if (inpFecha) inpFecha.value = cronograma.fecha;
            }
        }

        // 4. Rellenar el objetivo de la sesión
        const txtObjetivo = form.querySelector('textarea[name="objetivo"]');
        if (txtObjetivo) {
            txtObjetivo.value = cronograma.objetivo || '';
            txtObjetivo.classList.add('ring-2', 'ring-purple-500');
            setTimeout(() => txtObjetivo.classList.remove('ring-2', 'ring-purple-500'), 1500);
        }
    }

    // 5. Si fasesState existe (en cronogramas.php), vaciar fases previas e insertar las de la IA
    if (typeof fasesState !== 'undefined' && typeof renderizarFase === 'function') {
        fasesState.inicial = [];
        fasesState.central = [];
        fasesState.final = [];

        ['parte_inicial', 'parte_central', 'parte_final'].forEach(faseKey => {
            const faseNombre = faseKey.replace('parte_', ''); // 'inicial', 'central', 'final'
            const items = cronograma[faseKey] || [];

            items.forEach(it => {
                const idx = indexEjercicioGlobal++;
                fasesState[faseNombre].push({
                    idx: idx,
                    id_ejercicio: it.id_ejercicio || 0,
                    nombre: it.nombre || 'Ejercicio propuesto',
                    tipo: it.tipo || 'General',
                    series_o_tiempo: it.series_o_tiempo || '',
                    observaciones_especificas: it.observaciones || it.observaciones_especificas || ''
                });
            });

            renderizarFase(faseNombre);
        });

        // Feedback tipo toast
        if (typeof mostrarToastFeedback === 'function') {
            mostrarToastFeedback('Cronograma completo con sus 3 fases', 'central');
        }
    }

    // Mensaje de éxito en el chat
    const cont = document.getElementById('ia-chat-mensajes');
    if (cont) {
        const notif = document.createElement('div');
        notif.className = 'p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-[11px] font-semibold text-center';
        notif.innerHTML = '✅ ¡Cronograma incorporado al formulario de la clase con éxito!';
        cont.appendChild(notif);
        scrollChatAlFondo();
    }
}

/**
 * Guarda un ejercicio sugerido por la IA directo a la base de datos
 */
async function guardarEjercicioDirecto(idMemoria) {
    const ej = window[idMemoria];
    if (!ej) return;

    const btn = document.querySelector(`.btn-guardar-${idMemoria}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<span class="material-icons-outlined text-xs animate-spin">refresh</span> Guardando...`;
    }

    try {
        const resp = await fetch(IA_ENDPOINT_GUARDAR, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nombre: ej.nombre,
                tipo: ej.tipo,
                explicacion: ej.explicacion || ''
            })
        });

        const res = await resp.json();
        if (res.success) {
            if (btn) {
                btn.className = 'px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 cursor-default';
                btn.innerHTML = `<span class="material-icons-outlined text-xs text-emerald-500">check_circle</span> Guardado`;
            }

            // Si estamos en cronogramas.php, podemos añadirlo a la biblioteca visible en la columna izquierda
            const contBib = document.getElementById('contenedor-biblioteca-ejercicios');
            if (contBib) {
                const card = document.createElement('div');
                card.className = 'card-ejercicio-biblioteca p-3 bg-purple-50/50 dark:bg-purple-950/20 rounded-xl border border-purple-200 dark:border-purple-800/50 shadow-2xs space-y-1';
                card.setAttribute('data-nombre', (res.nombre + ' ' + (res.explicacion || '')).toLowerCase());
                card.setAttribute('data-tipo', res.tipo);
                card.innerHTML = `
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h6 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">${escaparHTML(res.nombre)}</h6>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300 shrink-0">${escaparHTML(res.tipo)}</span>
                    </div>
                    <div class="flex items-center gap-1 pt-1.5 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-400 mr-auto">Agregar:</span>
                        <button type="button" onclick="agregarEjercicioAFase(${res.id_ejercicio}, '${escaparHTML(res.nombre)}', '${escaparHTML(res.tipo)}', 'inicial')" class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">Inicial</button>
                        <button type="button" onclick="agregarEjercicioAFase(${res.id_ejercicio}, '${escaparHTML(res.nombre)}', '${escaparHTML(res.tipo)}', 'central')" class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300">Central</button>
                        <button type="button" onclick="agregarEjercicioAFase(${res.id_ejercicio}, '${escaparHTML(res.nombre)}', '${escaparHTML(res.tipo)}', 'final')" class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Final</button>
                    </div>
                `;
                contBib.prepend(card);
            }

            // Si estamos en ejercicios.php, recargar o avisar
            if (typeof filtrar === 'function') {
                setTimeout(() => location.reload(), 1200);
            }
        } else {
            alert(res.error || 'No se pudo guardar el ejercicio');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<span class="material-icons-outlined text-xs">add</span> Reintentar`;
            }
        }
    } catch (e) {
        console.error(e);
        alert('Error al conectar con el servidor');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<span class="material-icons-outlined text-xs">add</span> Reintentar`;
        }
    }
}

function scrollChatAlFondo() {
    const cont = document.getElementById('ia-chat-mensajes');
    if (cont) cont.scrollTop = cont.scrollHeight;
}

function escaparHTML(texto) {
    if (!texto) return '';
    const div = document.createElement('div');
    div.innerText = texto;
    return div.innerHTML;
}
</script>
