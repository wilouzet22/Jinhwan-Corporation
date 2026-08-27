<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Nivel.php';

class AdminReportesController extends Controller {

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
