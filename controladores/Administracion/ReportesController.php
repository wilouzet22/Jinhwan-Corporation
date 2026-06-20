<?php
/**
 * ============================================================
 * CONTROLADOR DE REPORTES – ADMINISTRACIÓN (ReportesController)
 * ============================================================
 * Gestiona el módulo de consultas y reportes analíticos del panel
 * de administración. Permite filtrar miembros y visualizar
 * gráficas interactivas en tiempo real.
 *
 * Acceso: requiere sesión activa + rol de Administrador.
 * Rutas:
 *   GET  /admin/reportes → Vista principal de reportes y consultas
 *
 * Vista: administracion/reportes
 * Modelos usados: Usuario, Sede, Nivel
 * ============================================================
 */
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Models\Sede;
use App\Models\Nivel;

class ReportesController extends Controller {

    /** @var Usuario Modelo de usuarios/miembros */
    private $usuarioModel;

    /** @var Sede Modelo de sedes */
    private $sedeModel;

    /** @var Nivel Modelo de niveles/cinturones */
    private $nivelModel;

    /**
     * Constructor: verifica seguridad e instancia los modelos necesarios.
     */
    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();

        $this->usuarioModel = new Usuario();
        $this->sedeModel    = new Sede();
        $this->nivelModel   = new Nivel();
    }

    /**
     * Vista principal del módulo de reportes y consultas.
     * Ruta: GET /admin/reportes
     *
     * Pasa a la vista todos los miembros con detalles completos
     * para que el JavaScript los procese y filtre dinámicamente
     * sin recargar la página.
     */
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
