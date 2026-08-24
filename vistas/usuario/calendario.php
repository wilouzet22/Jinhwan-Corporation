<?php 

$cabecera = __DIR__ . '/../layout/estudiante_cabecera.php';
$pie = __DIR__ . '/../layout/estudiante_pie.php';

if (isset($rol) && strtolower($rol) === 'maestro') {
    $cabecera = __DIR__ . '/../layout/maestro_cabecera.php';
    $pie = __DIR__ . '/../layout/maestro_pie.php';
}

include $cabecera; 
?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8 animate-fade-in-up">

    <!-- Calendar Hero Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950/80 p-6 md:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-cyan-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs font-bold tracking-wider uppercase border border-blue-500/30">
                    <span class="pulse-dot"></span>
                    <span>Calendario Académico</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-white uppercase">Calendario de Eventos</h1>
                <p class="text-slate-300 text-sm max-w-xl">Consulta los próximos exámenes de grado, torneos, seminarios y actividades programadas por la academia.</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl text-sm font-semibold text-slate-200 flex items-center gap-2.5 border border-white/15 shrink-0 justify-center">
                <span class="material-icons-outlined text-blue-400 text-xl">event_available</span>
                <span><?= date('d M, Y') ?></span>
            </div>
        </div>
    </div>
    
    <!-- Glassmorphic Calendar Wrapper -->
    <div class="bg-white dark:bg-slate-900/90 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 lg:p-8 relative z-10 shadow-xl backdrop-blur-md">
        <div id='calendar'></div>
    </div>
</main>

<!-- Read-Only Event Modal -->
<div id="evento-modal-lectura" class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden transition-colors duration-300 relative animate-fade-in-up">
        <div class="h-1.5 w-full bg-gradient-to-r from-tkd-blue via-cyan-500 to-tkd-red"></div>
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider">Detalle del Evento</h2>
            <button onclick="closeEventModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition-colors focus:outline-none">
                <span class="material-icons-outlined text-lg">close</span>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">Título del Evento</p>
                <p id="view-titulo" class="text-lg font-bold text-slate-900 dark:text-white"></p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">Fecha y Hora</p>
                <p id="view-fecha" class="text-sm font-semibold text-tkd-blue dark:text-blue-400 flex items-center gap-2">
                    <span class="material-icons-outlined text-base">schedule</span>
                    <span></span>
                </p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">Descripción</p>
                <p id="view-descripcion" class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed"></p>
            </div>
        </div>
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
            <button onclick="closeEventModal()" class="px-6 py-3 bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold uppercase text-xs rounded-xl transition-all shadow-md hover:shadow-blue-500/30 focus:outline-none">Cerrar</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 680,
            locale: 'es',
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Agenda'
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '<?= base_url('/usuario/calendario/get-eventos') ?>',
            eventDidMount: function(info) {
                let now = new Date();
                if (info.event.start < now) {
                    info.el.classList.add('evento-pasado');
                    info.el.setAttribute('title', 'Evento pasado: ' + info.event.title);
                } else {
                    info.el.classList.add('evento-futuro');
                    info.el.setAttribute('title', 'Evento próximo: ' + info.event.title);
                }
            },
            eventClick: function(info) {
                let dateObj = new Date(info.event.start);
                let formattedDate = dateObj.toLocaleString('es-ES', { 
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                document.getElementById('view-titulo').textContent = info.event.title;
                document.getElementById('view-descripcion').textContent = info.event.extendedProps.description || 'Sin descripción adicional.';
                
                const fechaContainer = document.getElementById('view-fecha');
                fechaContainer.querySelector('span:last-child').textContent = formattedDate;
                
                openEventModal();
            }
        });
        calendar.render();
    });

    function openEventModal() {
        const modal = document.getElementById('evento-modal-lectura');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeEventModal() {
        const modal = document.getElementById('evento-modal-lectura');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
/* FullCalendar Custom Premium Styling */
.fc {
    font-family: 'Inter', sans-serif;
}
.fc-header-toolbar {
    margin-bottom: 1.5rem !important;
    gap: 1rem;
    flex-wrap: wrap;
}
.fc-toolbar-title {
    font-family: 'Oswald', sans-serif !important;
    font-size: 1.6rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.05em;
    text-transform: capitalize;
    color: #0f172a;
}
.dark .fc-toolbar-title {
    color: #ffffff !important;
}

/* Custom Navigation & View Buttons */
.fc-button {
    border-radius: 0.75rem !important;
    font-weight: 700 !important;
    font-size: 0.825rem !important;
    text-transform: capitalize !important;
    padding: 0.5rem 1rem !important;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

.fc-button-primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    color: #ffffff !important;
}

.fc-button-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(37,99,235,0.35) !important;
}

.fc-button-primary:not(:disabled).fc-button-active, 
.fc-button-primary:not(:disabled):active {
    background: linear-gradient(135deg, #1e40af, #1e3a8a) !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 12px rgba(37,99,235,0.5) !important;
}

/* Day grid headers & cells */
.fc-col-header-cell {
    padding: 10px 0 !important;
    background-color: rgba(241, 245, 249, 0.5);
    border-color: #e2e8f0 !important;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.dark .fc-col-header-cell {
    background-color: rgba(15, 23, 42, 0.6);
    border-color: #1e293b !important;
    color: #94a3b8 !important;
}

.fc-theme-standard td, .fc-theme-standard th {
    border-color: #e2e8f0 !important;
}
.dark .fc-theme-standard td, .dark .fc-theme-standard th {
    border-color: #1e293b !important;
}

.fc-daygrid-day {
    transition: background-color 0.2s ease;
    cursor: pointer;
}
.fc-daygrid-day:hover {
    background-color: rgba(37, 99, 235, 0.04) !important;
}
.dark .fc-daygrid-day:hover {
    background-color: rgba(37, 99, 235, 0.08) !important;
}

.fc-day-today {
    background: radial-gradient(circle at center, rgba(37, 99, 235, 0.12) 0%, rgba(37, 99, 235, 0.03) 100%) !important;
    border: 2px solid rgba(37, 99, 235, 0.4) !important;
}
.dark .fc-day-today {
    background: radial-gradient(circle at center, rgba(37, 99, 235, 0.2) 0%, rgba(15, 23, 42, 0.8) 100%) !important;
    border: 2px solid rgba(37, 99, 235, 0.6) !important;
}

.fc-daygrid-day-number {
    font-weight: 700;
    font-size: 0.875rem;
    padding: 6px 10px !important;
    color: #334155;
    transition: color 0.2s ease, transform 0.2s ease;
}
.dark .fc-daygrid-day-number {
    color: #f8fafc;
}
.fc-daygrid-day:hover .fc-daygrid-day-number {
    color: #2563eb;
    transform: scale(1.1);
}

.fc-day-past {
    background-color: rgba(0, 0, 0, 0.02) !important;
    cursor: not-allowed;
}
.dark .fc-day-past {
    background-color: rgba(255, 255, 255, 0.015) !important;
    cursor: not-allowed;
}

/* Event Styling: Premium Badges */
.fc-event {
    border-radius: 0.5rem !important;
    padding: 3px 6px !important;
    margin-top: 2px !important;
    font-size: 0.775rem !important;
    font-weight: 600 !important;
    transition: all 0.25s ease !important;
}

.evento-pasado {
    opacity: 0.55 !important;
    filter: grayscale(60%);
    border-left: 4px solid #64748b !important;
    background: rgba(100, 116, 139, 0.15) !important;
}
.evento-pasado .fc-event-title {
    text-decoration: line-through;
    color: #64748b !important;
}

.evento-futuro {
    opacity: 1 !important;
    background: linear-gradient(135deg, rgba(37,99,235,0.9), rgba(29,78,216,0.95)) !important;
    border-left: 4px solid #60a5fa !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25) !important;
}
.evento-futuro:hover {
    transform: translateY(-2px) scale(1.02) !important;
    box-shadow: 0 6px 18px rgba(37,99,235,0.4) !important;
}
</style>

<?php include $pie; ?>

