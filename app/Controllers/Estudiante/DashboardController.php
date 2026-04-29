<?php
namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;

class DashboardController extends Controller {
    
    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $usuarioModel = new Usuario();
        $estudiante = $usuarioModel->getById($_SESSION['id']);
        
        $this->view('estudiante/dashboard', [
            'estudiante' => $estudiante,
            'page_title' => 'Portal del Alumno'
        ]);
    }
}
