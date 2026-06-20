<?php 
// Determine which layout to include based on the role
$cabecera = __DIR__ . '/../layout/estudiante_cabecera.php';
$pie = __DIR__ . '/../layout/estudiante_pie.php';

if (isset($rol) && strtolower($rol) === 'maestro') {
    $cabecera = __DIR__ . '/../layout/maestro_cabecera.php';
    $pie = __DIR__ . '/../layout/maestro_pie.php';
}

include $cabecera; 
?>

<!-- FullCalendar CSS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden transition-colors duration-300 max-w-5xl">
    <div class="flex justify-between items-center mb-8 relative z-10">
        <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight">Calendario de Eventos</h1>
    </div>
    
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 relative z-10 shadow-sm">
        <div id='calendar'></div>
    </div>
</main>

<!-- Modal de Lectura -->
<div id="evento-modal-lectura" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white">Detalle del Evento</h2>
            <button onclick="closeEventModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Título</p>
                <p id="view-titulo" class="text-lg font-semibold text-slate-900 dark:text-white"></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Fecha y Hora</p>
                <p id="view-fecha" class="text-slate-800 dark:text-slate-200"></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Descripción</p>
                <p id="view-descripcion" class="text-slate-800 dark:text-slate-200 whitespace-pre-wrap"></p>
            </div>
        </div>
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
            <button onclick="closeEventModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Cerrar</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 650,
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '<?= base_url('/usuario/calendario/get-eventos') ?>',
            eventClick: function(info) {
                // Formatear la fecha para mostrar
                let dateObj = new Date(info.event.start);
                let formattedDate = dateObj.toLocaleString('es-ES', { 
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                document.getElementById('view-titulo').textContent = info.event.title;
                document.getElementById('view-descripcion').textContent = info.event.extendedProps.description || 'Sin descripción.';
                document.getElementById('view-fecha').textContent = formattedDate;
                
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
/* Estilos para adaptar FullCalendar al dark mode */
.dark .fc-theme-standard .fc-scrollgrid { border-color: #1e293b; }
.dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #1e293b; }
.dark .fc-col-header-cell-cushion, .dark .fc-daygrid-day-number { color: #f8fafc; }
.dark .fc-button-primary { background-color: #2563eb; border-color: #2563eb; }
.dark .fc-button-primary:hover { background-color: #1d4ed8; border-color: #1d4ed8; }
.dark .fc-button-primary:not(:disabled).fc-button-active, .dark .fc-button-primary:not(:disabled):active { background-color: #1e40af; border-color: #1e40af; }
.dark .fc-day-today { background-color: #0f172a !important; }
</style>

<?php include $pie; ?>
