<?php

namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Models\Sede;
use App\Models\Nivel;

class ReportesController extends Controller {

    private $usuarioModel;

    private $sedeModel;

    private $nivelModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyPermission('reportes');

        $this->usuarioModel = new Usuario();
        $this->sedeModel    = new Sede();
        $this->nivelModel   = new Nivel();
    }

    public function index() {
        $miembros = $this->usuarioModel->getAllWithDetails();
        $sedes    = $this->sedeModel->getAll();
        $niveles  = $this->nivelModel->getAll();

        $this->view('administracion/reportes', [
            'miembros'     => $miembros,
            'sedes_list'   => $sedes,
            'niveles_list' => $niveles,
            'page_title'   => 'Reportes y Consultas',
            'current_page' => 'reportes'
        ]);
    }
}
