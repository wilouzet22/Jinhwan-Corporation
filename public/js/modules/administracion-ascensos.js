
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('theory-modal');
    const modalTitle = document.getElementById('modal-title');
    const theoryForm = document.getElementById('theory-form');
    const actionInput = document.getElementById('action');
    const idInput = document.getElementById('id');

    window.openModal = function(action, data = {}) {
        if (!modal || !modalTitle || !theoryForm || !actionInput || !idInput) return;
        
        actionInput.value = action;
        if (action === 'add') {
            modalTitle.innerText = 'Añadir Nueva Teoría';
            theoryForm.reset();
            idInput.value = '';
        } else if (action === 'edit') {
            modalTitle.innerText = 'Editar Teoría';
            idInput.value = data.id;
            document.getElementById('titulo').value = data.titulo;
            document.getElementById('descripcion').value = data.descripcion;
            document.getElementById('nivel_id').value = data.nivel_id;
            document.getElementById('url_video').value = data.url_video || '';
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

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('open') === 'new') {
        openModal('add');
    }
});
