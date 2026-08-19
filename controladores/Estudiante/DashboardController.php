<?php

namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Config\Roles;

class DashboardController extends Controller {

    public function __construct() {
        Security::verifySession();

        $rol = $_SESSION['rol_id'] ?? null;
        if (Roles::esAdmin($rol)) {
            header('Location: ' . base_url('/admin/dashboard')); exit;
        }
        if (Roles::esMaestro($rol) || $rol === Roles::PROFESOR || $rol === Roles::MONITOR) {
            header('Location: ' . base_url('/maestro/dashboard')); exit;
        }
    }

    public function index() {
        $usuarioModel = new Usuario();
        $eventoModel = new \App\Models\Evento();

        $estudiante = $usuarioModel->getById($_SESSION['id']); 

        $proximos_eventos = $eventoModel->getUpcoming(3);

        $this->view('estudiante/dashboard', [
            'estudiante' => $estudiante,    
            'proximos_eventos' => $proximos_eventos, 
            'page_title' => 'Portal del Alumno'
        ]);
    }
}
