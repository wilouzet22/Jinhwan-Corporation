<?php
/**
 * ============================================================
 * CONTROLADOR DE CALENDARIO – USUARIOS (CalendarioController)
 * ============================================================
 * Gestiona la visualización del calendario para estudiantes
 * y maestros.
 * ============================================================
 */
namespace App\Controllers\Usuario;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Evento;

class CalendarioController extends Controller {

    private $eventoModel;

    public function __construct() {
        Security::verifySession(); 
        $this->eventoModel = new Evento();
    }

    /**
     * Muestra la vista del calendario en modo lectura.
     */
    public function index() {
        // Obtenemos el rol del usuario para el layout
        $rol = $_SESSION['user_role'] ?? '';
        
        $this->view('usuario/calendario', [
            'page_title'   => 'Calendario de Eventos',
            'current_page' => 'calendario',
            'rol'          => $rol
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
}
