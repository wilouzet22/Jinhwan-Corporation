<?php

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

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = (int)$_SESSION['id'];

        $sql = "SELECT s.id_solicitud as id, s.observaciones, s.estado, s.fecha_solicitud, s.fecha_resolucion,
                       p.nombre as nombre_alumno, p.apellido as apellido_alumno,
                       g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado
                FROM solicitudes_ascenso s
                JOIN personas p ON s.id_persona_estudiante = p.id_persona
                JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                WHERE s.id_persona_maestro = ?
                ORDER BY s.id_solicitud DESC";

        $stmt = $db->prepare($sql);
        $solicitudes = [];
        if ($stmt) {
            $stmt->bind_param("i", $id_maestro);
            $stmt->execute();
            $solicitudes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

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

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance()->getConnection();
            $id_maestro = (int)$_SESSION['id'];

            $alumnos_seleccionados = $_POST['alumnos_seleccionados'] ?? [];
            $grados_actuales       = $_POST['grados_actuales'] ?? [];
            $grados_solicitados    = $_POST['grados_solicitados'] ?? [];
            $observaciones         = trim($_POST['observaciones'] ?? '');

            if (empty($alumnos_seleccionados)) {
                $this->redirect('/maestro/solicitudes-ascenso?error=no_selection');
                return;
            }

            $sql = "INSERT INTO solicitudes_ascenso (id_persona_estudiante, id_grado_actual, id_grado_solicitado, id_persona_maestro, observaciones, estado)
                    VALUES (?, ?, ?, ?, ?, 'pendiente')";
            $stmt = $db->prepare($sql);

            if ($stmt) {
                foreach ($alumnos_seleccionados as $id_alumno) {
                    $id_alumno = intval($id_alumno);
                    $id_grado_actual     = intval($grados_actuales[$id_alumno] ?? 0);
                    $id_grado_solicitado = intval($grados_solicitados[$id_alumno] ?? 0);

                    if ($id_alumno > 0 && $id_grado_actual > 0 && $id_grado_solicitado > 0) {
                        $stmt->bind_param("iiiis", $id_alumno, $id_grado_actual, $id_grado_solicitado, $id_maestro, $observaciones);
                        $stmt->execute();
                    }
                }
                $stmt->close();
            }
            
            $this->redirect('/maestro/solicitudes-ascenso?success=1');
        }
    }
}
