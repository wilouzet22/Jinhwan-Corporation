<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<!-- FullCalendar CSS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden transition-colors duration-300">
    <div class="flex justify-between items-center mb-8 relative z-10">
        <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight">Calendario de Eventos</h1>
        <button onclick="openEventModal('add')" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl flex items-center space-x-2 transition-colors">
            <span class="material-icons-outlined">add</span>
            <span>Nuevo Evento</span>
        </button>
    </div>
    
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 relative z-10 shadow-sm">
        <div id='calendar'></div>
    </div>
</main>

<!-- Modal -->
<div id="evento-modal" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white"></h2>
            <button onclick="closeEventModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form id="evento-form" action="" method="POST" class="p-6">
            <input type="hidden" name="id" id="id">
            <div class="space-y-6">
                <div>
                    <label for="titulo" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Título del Evento</label>
                    <input type="text" name="titulo" id="titulo" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="fecha" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha" id="fecha" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="descripcion" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-between pt-6 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <button type="button" id="btn-delete" onclick="deleteEvent()" class="hidden px-5 py-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Borrar</button>
                </div>
                <div class="space-x-3">
                    <button type="button" onclick="closeEventModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Cancelar</button>
                    <button type="submit" class="px-5 py-3 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-lg focus:outline-none">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="delete-form" action="<?= base_url('/admin/calendario/delete') ?>" method="POST" class="hidden">
    <input type="hidden" name="id" id="delete-id">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '<?= base_url('/admin/calendario/get-eventos') ?>',
            eventClick: function(info) {
                // Formatting date for datetime-local input
                let dateStr = info.event.start;
                // Add timezone offset to keep correct local time in datetime-local input
                let tzoffset = (new Date()).getTimezoneOffset() * 60000;
                let localISOTime = (new Date(dateStr - tzoffset)).toISOString().slice(0, 16);

                let eventData = {
                    id: info.event.id,
                    title: info.event.title,
                    description: info.event.extendedProps.description || '',
                    start: localISOTime
                };
                openEventModal('edit', eventData);
            }
        });
        calendar.render();
    });

    function openEventModal(action, data = null) {
        const modal = document.getElementById('evento-modal');
        const form = document.getElementById('evento-form');
        const title = document.getElementById('modal-title');
        const btnDelete = document.getElementById('btn-delete');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        form.reset();
        
        if (action === 'add') {
            title.textContent = 'Nuevo Evento';
            form.action = '<?= base_url('/admin/calendario/create') ?>';
            document.getElementById('id').value = '';
            btnDelete.classList.add('hidden');
        } else if (action === 'edit') {
            title.textContent = 'Editar Evento';
            form.action = '<?= base_url('/admin/calendario/update') ?>';
            
            document.getElementById('id').value = data.id;
            document.getElementById('titulo').value = data.title;
            document.getElementById('descripcion').value = data.description;
            document.getElementById('fecha').value = data.start;
            
            btnDelete.classList.remove('hidden');
            document.getElementById('delete-id').value = data.id;
        }
    }
    
    function closeEventModal() {
        const modal = document.getElementById('evento-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function deleteEvent() {
        if(confirm('¿Borrar este evento?')) {
            document.getElementById('delete-form').submit();
        }
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

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>
