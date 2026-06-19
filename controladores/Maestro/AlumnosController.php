<?php
/**
 * ============================================================
 * CONTROLADOR DE ALUMNOS – MAESTRO (AlumnosController)
 * ============================================================
 * Permite al Maestro ver la lista de todos los estudiantes (Deportistas)
 * y proponer ascensos de grado.
 *
 * Acceso: requiere sesión activa + rol de Maestro.
 * Rutas:
 *   GET  /maestro/alumnos  → listar alumnos de todas las sedes
 * ============================================================
 */
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

    /**
     * Muestra la lista de deportistas (alumnos) con sus detalles.
     */
    public function index() {
        $miembros = $this->usuarioModel->getAllWithDetails();
        
        // Filtrar para mostrar solo deportistas (estudiantes)
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
