<?php
/**
 * ============================================================
 * CONTROLADOR DE CALENDARIO – ADMINISTRACIÓN (CalendarioController)
 * ============================================================
 * Gestiona el CRUD completo de eventos del calendario
 * desde el panel de administración.
 * ============================================================
 */
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Evento;

class CalendarioController extends Controller {

    private $eventoModel;

    public function __construct() {
        Security::verifySession(); 
        Security::verifyAdmin();   
        $this->eventoModel = new Evento();
    }

    /**
     * Muestra la vista del calendario.
     */
    public function index() {
        $this->view('administracion/calendario', [
            'page_title'   => 'Calendario de Eventos',
            'current_page' => 'calendario'
        ]);
    }

    /**
     * Devuelve los eventos en formato JSON para FullCalendar.
     */
    public function getEventos() {
        header('Content-Type: application/json');
        $eventos = $this->eventoModel->getAll();
        echo json_encode($eventos);
        exit;
    }

    /**
     * Crea un nuevo evento.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title'       => $_POST['titulo'],
                'description' => $_POST['descripcion'] ?? '',
                'start'       => $_POST['fecha'],
                'end'         => null,
                'id_miembro'  => $_SESSION['id']
            ];

            $this->eventoModel->create($data);
            $this->redirect('/admin/calendario');
        }
    }

    /**
     * Actualiza un evento.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'title'       => $_POST['titulo'],
                'description' => $_POST['descripcion'] ?? '',
                'start'       => $_POST['fecha'],
                'end'         => null
            ];

            $this->eventoModel->update($id, $data);
            $this->redirect('/admin/calendario');
        }
    }

    /**
     * Elimina un evento.
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->eventoModel->delete($id);
            $this->redirect('/admin/calendario');
        }
    }
}
