<?php

namespace App\Controllers\Maestro;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Models\Sede;
use App\Models\Nivel;
use App\Config\Roles;

class AlumnosController extends Controller {

    private $usuarioModel;
    private $sedeModel;
    private $nivelModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyMaestro();

        $this->usuarioModel = new Usuario();
        $this->sedeModel    = new Sede();
        $this->nivelModel   = new Nivel();
    }

    public function index() {
        $miembros = $this->usuarioModel->getAllWithDetails();

        $alumnos = array_filter($miembros, function($m) {
            return $m['rol_id'] === Roles::ESTUDIANTE;
        });

        $sedes  = $this->sedeModel->getAll();
        $grados = $this->nivelModel->getAll();

        $this->view('maestro/alumnos', [
            'alumnos'     => $alumnos,
            'sedes_list'  => $sedes,
            'grados_list' => $grados,
            'page_title'  => 'Mis Alumnos',
            'current_page' => 'alumnos'
        ]);
    }
}
