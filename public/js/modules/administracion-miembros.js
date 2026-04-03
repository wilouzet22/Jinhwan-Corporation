// Admin Miembros Logic
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('member-modal');
    const modalTitle = document.getElementById('modal-title');
    const memberForm = document.getElementById('member-form');
    const actionInput = document.getElementById('action');
    const idInput = document.getElementById('id');

    window.openModal = function(action, data = {}) {
        if (!modal || !modalTitle || !memberForm || !actionInput || !idInput) return;

        actionInput.value = action;
        if (action === 'add') {
            modalTitle.innerText = 'Añadir Nuevo Miembro';
            memberForm.reset();
        } else if (action === 'edit') {
            modalTitle.innerText = 'Editar Miembro';
            idInput.value = data.id;
            
            // Populate fields
            const fields = ['nombre', 'apellido', 'tipo_documento', 'numero_documento', 'fecha_nacimiento', 'telefono', 'correo', 'nivel_id', 'sede_id', 'rol_id'];
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (input && data[field] !== undefined) {
                    input.value = data[field];
                }
            });
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
    
    // Close modal on outside click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    // Auto open modal if requested via URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('open') === 'new') {
        openModal('add');
    }
});
