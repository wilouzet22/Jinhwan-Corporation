<?php

include_once __DIR__ . '/../../modelos/Evento.php';

class UsuarioCalendarioController extends Controller {

    private $eventoModel;

    public function __construct() {
        Security::verifySession(); 
        $this->eventoModel = new Evento();
    }

    public function index() {
        $rol = $_SESSION['rol_id'] ?? '';
                    ADMIN
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
