<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Evento.php';

class EstudianteDashboardController extends Controller {

    public function __construct() {
        Security::verifySession();

        $rol = $_SESSION['rol_id'] ?? null;
        if (Roles::esAdmin($rol)) {
            $this->redirect('/admin/dashboard');
        }
        if (Roles::esMaestro($rol) || $rol === Roles::PROFESOR || $rol === Roles::MONITOR) {
            $this->redirect('/maestro/dashboard');
        }
    }

    public function index() {
        $usuarioModel = new Usuario();
        $eventoModel  = new Evento();

        $estudiante = $usuarioModel->getById($_SESSION['id']); 

        $proximos_eventos = $eventoModel->getUpcoming(3);

        $this->view('estudiante/dashboard', [
            'estudiante'       => $estudiante,    
            'proximos_eventos' => $proximos_eventos, 
            'page_title'       => 'Portal del Alumno'
        ]);
    }
}
