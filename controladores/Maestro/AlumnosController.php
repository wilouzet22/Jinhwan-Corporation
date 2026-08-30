<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Nivel.php';

class MaestroAlumnosController extends Controller {

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
            'alumnos'      => $alumnos,
            'sedes_list'   => $sedes,
            'grados_list'  => $grados,
            'page_title'   => 'Alumnos de Jinhwan',
            'current_page' => 'alumnos'
        ]);
    }
}
