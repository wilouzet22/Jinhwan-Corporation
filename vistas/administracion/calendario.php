<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8 animate-fade-in-up">
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="p-4 rounded-2xl <?= $_SESSION['tipo_mensaje'] === 'success' ? 'bg-emerald-50 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-300 border border-emerald-500/30' : 'bg-red-50 text-red-900 dark:bg-red-500/10 dark:text-red-300 border border-red-500/30' ?> flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <span class="material-icons-outlined text-xl"><?= $_SESSION['tipo_mensaje'] === 'success' ? 'check_circle' : 'error' ?></span>
                <p class="font-bold text-sm"><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="p-1 hover:bg-black/5 dark:hover:bg-white/5 rounded-lg transition-colors">
                <span class="material-icons-outlined text-sm">close</span>
            </button>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <!-- Calendar Hero Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950/80 p-6 md:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs font-bold tracking-wider uppercase border border-blue-500/30">
                    <span class="pulse-dot"></span>
                    <span>Gestión de Eventos</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-white uppercase">Calendario de Eventos</h1>
                <p class="text-slate-300 text-sm max-w-xl">Selecciona cualquier día del calendario para agendar eventos o haz clic en un evento existente para administrarlo.</p>
            </div>

            <button onclick="openEventModal('add')" class="px-6 py-3.5 bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold rounded-2xl flex items-center justify-center space-x-2 transition-all shadow-lg hover:shadow-blue-500/30 hover-lift shrink-0">
                <span class="material-icons-outlined text-xl">add_circle</span>
                <span class="text-sm uppercase tracking-wider">Nuevo Evento</span>
            </button>
        </div>
    </div>
    
    <!-- Glassmorphic Calendar Wrapper -->
    <div class="bg-white dark:bg-slate-900/90 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 lg:p-8 relative z-10 shadow-xl backdrop-blur-md">
        <div id='calendar'></div>
    </div>
</main>

<!-- Event Modal -->
<div id="evento-modal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden transition-colors duration-300 relative animate-fade-in-up">
        <div class="h-1.5 w-full bg-gradient-to-r from-tkd-blue via-blue-500 to-tkd-red"></div>
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider"></h2>
            <button onclick="closeEventModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition-colors focus:outline-none">
                <span class="material-icons-outlined text-lg">close</span>
            </button>
        </div>
        <form id="evento-form" action="" method="POST" class="p-6">
            <input type="hidden" name="id" id="id">
            <div class="space-y-5">
                <div>
                    <label for="titulo" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Título del Evento</label>
                    <input type="text" name="titulo" id="titulo" required placeholder="Ej. Examen de Grado - Sede Central" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3.5 focus:border-tkd-blue focus:ring-2 focus:ring-tkd-blue/20 transition-all focus:outline-none text-sm font-medium">
                </div>
                <div>
                    <label for="fecha" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha" id="fecha" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3.5 focus:border-tkd-blue focus:ring-2 focus:ring-tkd-blue/20 transition-all focus:outline-none text-sm font-medium">
                </div>
                <div>
                    <label for="descripcion" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" placeholder="Detalles adicionales del evento..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3.5 focus:border-tkd-blue focus:ring-2 focus:ring-tkd-blue/20 transition-all focus:outline-none text-sm font-medium"></textarea>
                </div>
            </div>
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <button type="button" id="btn-delete" onclick="deleteEvent()" class="hidden px-5 py-3 bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 font-bold uppercase text-xs rounded-xl transition-all focus:outline-none border border-red-200 dark:border-red-800/40">Borrar</button>
                </div>
                <div class="space-x-3 flex items-center">
                    <button type="button" onclick="closeEventModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-all focus:outline-none">Cancelar</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold uppercase text-xs rounded-xl transition-all shadow-md hover:shadow-blue-500/30 focus:outline-none">Guardar</button>
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
            events: '<?= base_url('/admin/calendario/get-eventos') ?>',
            dateClick: function(info) {
                let today = new Date();
                today.setHours(0, 0, 0, 0);

                let parts = info.dateStr.split('-');
                let year = parseInt(parts[0], 10);
                let month = parseInt(parts[1], 10) - 1;
                let day = parseInt(parts[2], 10);
                let selectedDate = new Date(year, month, day, 0, 0, 0);

                if (selectedDate < today) {
                    alert('No se pueden programar eventos en fechas pasadas.');
                    return;
                }

                let now = new Date();
                let hours = '09';
                let minutes = '00';
                
                if (selectedDate.getTime() === today.getTime()) {
                    hours = String(now.getHours()).padStart(2, '0');
                    minutes = String(now.getMinutes()).padStart(2, '0');
                }

                let defaultDateTime = `${info.dateStr}T${hours}:${minutes}`;
                openEventModal('add', { start: defaultDateTime });
            },
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
                let dateStr = info.event.start;
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

        document.getElementById('evento-form').addEventListener('submit', function(e) {
            let fechaInput = document.getElementById('fecha').value;
            if (fechaInput) {
                let selectedDate = new Date(fechaInput);
                let today = new Date();
                today.setHours(0, 0, 0, 0);
                if (selectedDate < today) {
                    e.preventDefault();
                    alert('No puedes guardar un evento para una fecha anterior a hoy.');
                    return false;
                }
            }
        });
    });

    function getTodayMinDateTime() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}T00:00`;
    }

    function openEventModal(action, data = null) {
        const modal = document.getElementById('evento-modal');
        const form = document.getElementById('evento-form');
        const title = document.getElementById('modal-title');
        const btnDelete = document.getElementById('btn-delete');
        const fechaInput = document.getElementById('fecha');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        form.reset();
        
        fechaInput.min = getTodayMinDateTime();
        
        if (action === 'add') {
            title.textContent = 'Nuevo Evento';
            form.action = '<?= base_url('/admin/calendario/create') ?>';
            document.getElementById('id').value = '';
            btnDelete.classList.add('hidden');

            if (data && data.start) {
                fechaInput.value = data.start;
            } else {
                let now = new Date();
                let tzoffset = now.getTimezoneOffset() * 60000;
                let localISOTime = (new Date(now.getTime() - tzoffset)).toISOString().slice(0, 16);
                fechaInput.value = localISOTime;
            }
        } else if (action === 'edit') {
            title.textContent = 'Editar Evento';
            form.action = '<?= base_url('/admin/calendario/update') ?>';
            
            document.getElementById('id').value = data.id;
            document.getElementById('titulo').value = data.title;
            document.getElementById('descripcion').value = data.description;
            fechaInput.value = data.start;
            
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

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>


