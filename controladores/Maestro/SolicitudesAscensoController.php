<?php
/**
 * ============================================================
 * CONTROLADOR DE SOLICITUDES DE ASCENSO – MAESTRO (SolicitudesAscensoController)
 * ============================================================
 * Permite al Maestro proponer ascensos de grado para deportistas.
 * Las solicitudes se guardan en la tabla `solicitudes_ascenso` con estado 'pendiente'.
 * El administrador podrá verlas y aprobarlas/rechazarlas.
 *
 * Acceso: requiere sesión activa + rol de Maestro.
 * Rutas:
 *   GET  /maestro/solicitudes-ascenso         → listar solicitudes del maestro
 *   POST /maestro/solicitudes-ascenso/create  → guardar una nueva propuesta de ascenso
 * ============================================================
 */
namespace App\Controllers\Maestro;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;
use App\Models\Usuario;
use App\Models\Nivel;
use App\Config\Roles;

class SolicitudesAscensoController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyMaestro();
    }

    /**
     * Muestra la lista de solicitudes enviadas por el maestro logueado.
     */
    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = $_SESSION['id'];

        // Obtener las solicitudes de este maestro
        $sql = "SELECT s.id, s.observaciones, s.estado, s.fecha_solicitud, s.fecha_resolucion,
                       m.nombre as nombre_alumno, m.apellido as apellido_alumno,
                       g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado
                FROM solicitudes_ascenso s
                JOIN miembros m ON s.id_miembro = m.id_miembro
                JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                WHERE s.id_maestro = ?
                ORDER BY s.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $solicitudes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Cargar alumnos y grados para la propuesta múltiple
        $usuarioModel = new Usuario();
        $nivelModel   = new Nivel();
        
        $miembros = $usuarioModel->getAllWithDetails();
        $alumnos = array_filter($miembros, function($m) {
            return $m['rol_id'] === Roles::ESTUDIANTE;
        });
        $grados_list = $nivelModel->getAll();

        $this->view('maestro/solicitudes_ascenso', [
            'solicitudes'  => $solicitudes,
            'alumnos'      => $alumnos,
            'grados_list'  => $grados_list,
            'page_title'   => 'Solicitudes de Ascenso',
            'current_page' => 'solicitudes_ascenso'
        ]);
    }

    /**
     * Registra una nueva solicitud de ascenso de grado.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance()->getConnection();
            $id_maestro = $_SESSION['id'];

            $alumnos_seleccionados = $_POST['alumnos_seleccionados'] ?? [];
            $grados_actuales       = $_POST['grados_actuales'] ?? [];
            $grados_solicitados    = $_POST['grados_solicitados'] ?? [];
            $observaciones         = trim($_POST['observaciones'] ?? '');

            if (empty($alumnos_seleccionados)) {
                $this->redirect('/maestro/solicitudes-ascenso?error=no_selection');
                return;
            }

            $sql = "INSERT INTO solicitudes_ascenso (id_miembro, id_grado_actual, id_grado_solicitado, id_maestro, observaciones, estado)
                    VALUES (?, ?, ?, ?, ?, 'pendiente')";
            $stmt = $db->prepare($sql);

            foreach ($alumnos_seleccionados as $id_miembro) {
                $id_miembro = intval($id_miembro);
                $id_grado_actual = intval($grados_actuales[$id_miembro] ?? 0);
                $id_grado_solicitado = intval($grados_solicitados[$id_miembro] ?? 0);

                // Solo insertar si los datos son válidos
                if ($id_miembro > 0 && $id_grado_actual > 0 && $id_grado_solicitado > 0) {
                    $stmt->bind_param("iiiis", $id_miembro, $id_grado_actual, $id_grado_solicitado, $id_maestro, $observaciones);
                    $stmt->execute();
                }
            }
            
            $stmt->close();
            $this->redirect('/maestro/solicitudes-ascenso?success=1');
        }
    }
}
