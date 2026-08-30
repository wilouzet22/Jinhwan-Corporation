<?php

include_once __DIR__ . '/../../modelos/Certificado.php';

class AdminRegistrosController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyPermission('registros');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT p.id_persona as id, p.nombre, p.apellido, p.num_doc, p.telefono, c.correo, pd.fecha_n
                FROM personas p
                JOIN credenciales c ON p.id_persona = c.id_persona
                LEFT JOIN perfil_deportistas pd ON p.id_persona = pd.id_persona
                WHERE p.activo = 0
                ORDER BY p.id_persona DESC";

        $result = $db->query($sql);
        $solicitudes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // Historial completo de movimientos de ascensos (todos los estados)
        $sqlMovimientos = "SELECT s.id_solicitud as id, s.estado, s.fecha_solicitud, s.fecha_resolucion, s.observaciones,
                                p.nombre as nombre_alumno, p.apellido as apellido_alumno,
                                g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado,
                                maest.nombre as nombre_maestro, maest.apellido as apellido_maestro,
                                c.folio, c.id_certificado
                         FROM solicitudes_ascenso s
                         JOIN personas p ON s.id_persona_estudiante = p.id_persona
                         JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                         JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                         JOIN personas maest ON s.id_persona_maestro = maest.id_persona
                         LEFT JOIN certificados_ascenso c ON c.id_solicitud = s.id_solicitud
                         ORDER BY s.fecha_solicitud DESC
                         LIMIT 50";
        $resultMovimientos = $db->query($sqlMovimientos);
        $movimientos_ascenso = $resultMovimientos ? $resultMovimientos->fetch_all(MYSQLI_ASSOC) : [];

        $this->view('administracion/registros', [
            'solicitudes'          => $solicitudes,
            'movimientos_ascenso'  => $movimientos_ascenso,
            'page_title'           => 'Solicitudes Pendientes',
            'current_page'         => 'registros'
        ]);
    }

    public function aprobar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("UPDATE personas SET activo = 1 WHERE id_persona = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=approved');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
            $stmt->close();
        }
    }

    public function rechazar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("DELETE FROM personas WHERE id_persona = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=rejected');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
            $stmt->close();
        }
    }

    public function aprobarAscenso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud        = intval($_POST['id']);
            $id_miembro          = intval($_POST['id_miembro']);
            $id_grado_solicitado = intval($_POST['id_grado_solicitado']);

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
                        'observaciones' => $_POST['observaciones_cert'] ?? '',
                        'folio'         => Certificado::generarFolio()
                    ]);
                }

                $db->commit();
                $this->redirect('/admin/registros?msg=promo_approved');
            } catch (Exception $e) {
                $db->rollback();
                $this->redirect('/admin/registros?error=1');
            }
        }
    }

    public function rechazarAscenso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = intval($_POST['id']);
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("UPDATE solicitudes_ascenso SET estado = 'rechazado', fecha_resolucion = CURRENT_TIMESTAMP WHERE id_solicitud = ?");
            $stmt->bind_param("i", $id_solicitud);

            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=promo_rejected');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
            $stmt->close();
        }
    }

    /** Endpoint AJAX: devuelve el HTML del certificado para el modal del admin */
    public function certificadoPreview() {
        Security::verifySession();
        Security::verifyPermission('registros');

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

        // Render HTML fragment (no layout)
        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }
}
