// Admin sedes Logic
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('sede-modal');
    const modalTitle = document.getElementById('modal-title');
    const sedeForm = document.getElementById('sede-form');
    const actionInput = document.getElementById('action');
    const idInput = document.getElementById('id');

    window.openModal = function(action, data = {}) {
        if (!modal || !modalTitle || !sedeForm || !actionInput || !idInput) return;

        actionInput.value = action;
        if (action === 'add') {
            modalTitle.innerText = 'Añadir Nueva Sede';
            sedeForm.reset();
        } else if (action === 'edit') {
            modalTitle.innerText = 'Editar Sede';
            idInput.value = data.id;
            // Map fields manually or ensure input IDs match data keys
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('direccion').value = data.direccion;
            document.getElementById('telefono').value = data.telefono || '';
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeModal = function() {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    // Close on click outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});
