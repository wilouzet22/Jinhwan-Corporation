<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Nivel.php';

class MaestroSolicitudesAscensoController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyMaestro();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = (int)$_SESSION['id'];

        $sql = "SELECT s.id_solicitud as id, s.observaciones, s.estado, s.fecha_solicitud, s.fecha_resolucion,
                       p.nombre as nombre_alumno, p.apellido as apellido_alumno,
                       COALESCE(g_act.nombre, 'Sin Grado') as grado_actual,
                       COALESCE(g_sol.nombre, 'Sin Grado') as grado_solicitado
                FROM solicitudes_ascenso s
                JOIN personas p ON s.id_persona_estudiante = p.id_persona
                LEFT JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                LEFT JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
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
            return ($m['rol_id'] === Roles::ESTUDIANTE || $m['rol_id'] === 'Alumno') && ($m['activo'] == 1);
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
                $insertados = 0;
                foreach ($alumnos_seleccionados as $id_alumno) {
                    $id_alumno = intval($id_alumno);
                    $id_grado_actual     = intval($grados_actuales[$id_alumno] ?? 1);
                    if ($id_grado_actual <= 0) $id_grado_actual = 1;
                    $id_grado_solicitado = intval($grados_solicitados[$id_alumno] ?? 0);

                    if ($id_alumno > 0 && $id_grado_solicitado > 0) {
                        $stmt->bind_param("iiiis", $id_alumno, $id_grado_actual, $id_grado_solicitado, $id_maestro, $observaciones);
                        $stmt->execute();
                        $insertados++;
                    }
                }
                $stmt->close();

                if ($insertados > 0) {
                    $this->redirect('/maestro/solicitudes-ascenso?success=1');
                    return;
                }
            }
            
            $this->redirect('/maestro/solicitudes-ascenso?error=invalid_data');
        }
    }
}
