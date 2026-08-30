<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Nivel.php';
include_once __DIR__ . '/../../modelos/Certificado.php';

class MaestroSolicitudesAscensoController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyMaestro();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = (int)$_SESSION['id'];

        $sql = "SELECT s.id_solicitud as id, s.id_persona_estudiante, s.id_grado_solicitado, s.observaciones, s.estado, s.fecha_solicitud, s.fecha_resolucion,
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

    /** Aprobar solicitud de ascenso y generar certificado */
    public function aprobar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud        = intval($_POST['id']);
            $id_miembro          = intval($_POST['id_miembro']);
            $id_grado_solicitado = intval($_POST['id_grado_solicitado']);
            $observaciones_cert  = trim($_POST['observaciones_cert'] ?? '');

            $db = Database::getInstance()->getConnection();
            $db->begin_transaction();

            try {
                // 1. Marcar solicitud como aprobada
                $stmt1 = $db->prepare("UPDATE solicitudes_ascenso SET estado = 'aprobado', fecha_resolucion = CURRENT_TIMESTAMP WHERE id_solicitud = ?");
                $stmt1->bind_param("i", $id_solicitud);
                $stmt1->execute();
                $stmt1->close();

                // 2. Actualizar grado del estudiante
                $stmt2 = $db->prepare("UPDATE perfil_deportistas SET id_grado = ? WHERE id_persona = ?");
                $stmt2->bind_param("ii", $id_grado_solicitado, $id_miembro);
                $stmt2->execute();
                $stmt2->close();

                // 3. Obtener datos de la solicitud para el certificado
                $stmtInfo = $db->prepare(
                    "SELECT s.id_persona_maestro, g_act.nombre AS grado_anterior, g_sol.nombre AS grado_nuevo"
                  . " FROM solicitudes_ascenso s"
                  . " JOIN grados g_act ON s.id_grado_actual = g_act.id_grado"
                  . " JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado"
                  . " WHERE s.id_solicitud = ? LIMIT 1"
                );
                $stmtInfo->bind_param("i", $id_solicitud);
                $stmtInfo->execute();
                $infoRow = $stmtInfo->get_result()->fetch_assoc();
                $stmtInfo->close();

                // 4. Crear certificado de ascenso
                if ($infoRow) {
                    $certModel = new Certificado();
                    $certModel->create([
                        'id_solicitud'  => $id_solicitud,
                        'id_persona'    => $id_miembro,
                        'id_maestro'    => $infoRow['id_persona_maestro'],
                        'grado_anterior'=> $infoRow['grado_anterior'],
                        'grado_nuevo'   => $infoRow['grado_nuevo'],
                        'fecha_examen'  => date('Y-m-d'),
                        'observaciones' => $observaciones_cert,
                        'folio'         => Certificado::generarFolio()
                    ]);
                }

                $db->commit();
                $this->redirect('/maestro/solicitudes-ascenso?msg=promo_approved');
            } catch (Exception $e) {
                $db->rollback();
                $this->redirect('/maestro/solicitudes-ascenso?error=1');
            }
        }
    }

    /** Rechazar solicitud de ascenso */
    public function rechazar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = intval($_POST['id']);
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("UPDATE solicitudes_ascenso SET estado = 'rechazado', fecha_resolucion = CURRENT_TIMESTAMP WHERE id_solicitud = ?");
            $stmt->bind_param("i", $id_solicitud);

            if ($stmt->execute()) {
                $this->redirect('/maestro/solicitudes-ascenso?msg=promo_rejected');
            } else {
                $this->redirect('/maestro/solicitudes-ascenso?error=1');
            }
            $stmt->close();
        }
    }

    /** Endpoint AJAX: devuelve el HTML del certificado para el modal */
    public function certificado() {
        Security::verifySession();
        Security::verifyMaestro();

        $id_solicitud = intval($_GET['id'] ?? 0);
        if ($id_solicitud <= 0) {
            http_response_code(400);
            echo '<p class="text-center text-rose-500 py-8">Solicitud inválida.</p>';
            return;
        }

        $certModel = new Certificado();
        $cert = $certModel->getBySolicitud($id_solicitud);

        if (!$cert) {
            http_response_code(404);
            echo '<p class="text-center text-slate-500 py-8">Certificado no encontrado.</p>';
            return;
        }

        // El maestro solo puede ver certificados de sus propias solicitudes
        if ((int)$cert['id_maestro'] !== (int)$_SESSION['id']) {
            http_response_code(403);
            echo '<p class="text-center text-rose-500 py-8">Acceso denegado.</p>';
            return;
        }

        // Render HTML fragment (no layout)
        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }
}
