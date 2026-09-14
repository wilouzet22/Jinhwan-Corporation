<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Nivel.php';
include_once __DIR__ . '/../../modelos/Grupo.php';

class MaestroAlumnosController extends Controller {

    private $usuarioModel;
    private $sedeModel;
    private $nivelModel;
    private $grupoModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyMaestro();

        $this->usuarioModel = new Usuario();
        $this->sedeModel    = new Sede();
        $this->nivelModel   = new Nivel();
        $this->grupoModel   = new Grupo();
    }

    public function index() {
        $miembros = $this->usuarioModel->getAllWithDetails();

        $alumnos = array_filter($miembros, function($m) {
            return $m['rol_id'] === Roles::ESTUDIANTE;
        });

        $sedes  = $this->sedeModel->getAll();
        $grados = $this->nivelModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $this->view('maestro/alumnos', [
            'alumnos'      => $alumnos,
            'sedes_list'   => $sedes,
            'grados_list'  => $grados,
            'grupos_list'  => $grupos,
            'page_title'   => 'Alumnos de Jinhwan',
            'current_page' => 'alumnos'
        ]);
    }

    public function show($id) {
        $id = (int)$id;
        $alumno = $this->usuarioModel->getById($id);

        if (!$alumno || $alumno['rol_id'] !== Roles::ESTUDIANTE) {
            http_response_code(404);
            echo "Alumno no encontrado";
            return;
        }

        $this->view('maestro/alumno_detalle', [
            'alumno'       => $alumno,
            'page_title'   => 'Detalle del Alumno',
            'current_page' => 'alumnos'
        ]);
    }
}
