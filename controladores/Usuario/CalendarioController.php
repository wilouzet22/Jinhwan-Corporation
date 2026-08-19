<?php

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

    public function index() {
        
        $rol = $_SESSION['user_role'] ?? '';
        
        $this->view('usuario/calendario', [
            'page_title'   => 'Calendario de Eventos',
            'current_page' => 'calendario',
            'rol'          => $rol
        ]);
    }

    public function getEventos() {
        header('Content-Type: application/json');
        $eventos = $this->eventoModel->getAll();
        echo json_encode($eventos);
        exit;
    }
}
