<?php

include_once __DIR__ . '/../../modelos/Certificado.php';

class AdminRegistrosController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyPermission('registros');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        // Practicantes pendientes de activación
        $sql = "SELECT e.id_estudiante as id, e.nombre, e.apellido, e.num_doc, e.telefono, e.correo, e.fecha_nacimiento
                FROM estudiante e
                WHERE e.activo = 0
                ORDER BY e.id_estudiante DESC";

        $result = $db->query($sql);
        $solicitudes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // Historial de certificados de ascensos emitidos
        $sqlMovimientos = "SELECT c.id_certificado as id, 'aprobado' as estado, c.creado_en as fecha_solicitud, c.fecha_examen as fecha_resolucion,
                                  c.observaciones,
                                  e.nombre as nombre_alumno, e.apellido as apellido_alumno,
                                  c.grado_anterior as grado_actual, c.grado_nuevo as grado_solicitado,
                                  m.nombre as nombre_maestro, m.apellido as apellido_maestro,
                                  c.folio, c.id_certificado
                           FROM certificados_ascenso c
                           JOIN estudiante e ON c.id_estudiante = e.id_estudiante
                           LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
                           ORDER BY c.creado_en DESC
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

            $stmt = $db->prepare("UPDATE estudiante SET activo = 1 WHERE id_estudiante = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                Notificacion::registrar('sistema', 'Estudiante Aprobado', "Se aprobó y activó un nuevo estudiante en la academia.", '/admin/estudiantes');
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

            $stmt = $db->prepare("DELETE FROM estudiante WHERE id_estudiante = ?");
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
            $id_miembro          = intval($_POST['id_miembro'] ?? 0);
            $id_grado_solicitado = intval($_POST['id_grado_solicitado'] ?? 0);
            $observaciones_cert  = trim($_POST['observaciones_cert'] ?? '');
            $id_maestro          = intval($_POST['id_maestro'] ?? 150);

            $db = Database::getInstance()->getConnection();

            // Obtener datos actuales del estudiante
            $stmtEst = $db->prepare("SELECT e.id_grado, g.nombre as grado_actual, e.id_maestro FROM estudiante e LEFT JOIN grados g ON e.id_grado = g.id_grado WHERE e.id_estudiante = ? LIMIT 1");
            $stmtEst->bind_param("i", $id_miembro);
            $stmtEst->execute();
            $estData = $stmtEst->get_result()->fetch_assoc();
            $stmtEst->close();

            // Grado nuevo
            $stmtG = $db->prepare("SELECT nombre FROM grados WHERE id_grado = ? LIMIT 1");
            $stmtG->bind_param("i", $id_grado_solicitado);
            $stmtG->execute();
            $gRow = $stmtG->get_result()->fetch_assoc();
            $stmtG->close();

            if ($estData && $gRow) {
                $maestroFinal = !empty($estData['id_maestro']) ? (int)$estData['id_maestro'] : $id_maestro;
                $certModel = new Certificado();
                $ok = $certModel->create([
                    'id_estudiante'  => $id_miembro,
                    'id_maestro'     => $maestroFinal,
                    'grado_anterior' => $estData['grado_actual'] ?? 'Blanco',
                    'grado_nuevo'    => $gRow['nombre'],
                    'id_grado_nuevo' => $id_grado_solicitado,
                    'fecha_examen'   => date('Y-m-d'),
                    'observaciones'  => $observaciones_cert,
                    'folio'          => Certificado::generarFolio()
                ]);

                if ($ok) {
                    $this->redirect('/admin/registros?msg=promo_approved');
                    return;
                }
            }

            $this->redirect('/admin/registros?error=1');
        }
    }

    public function rechazarAscenso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->redirect('/admin/registros?msg=promo_rejected');
        }
    }

    /** Endpoint AJAX: devuelve el HTML del certificado para el modal del admin */
    public function certificadoPreview() {
        Security::verifySession();
        Security::verifyPermission('registros');

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo '<p class="text-center text-rose-500 py-8">Solicitud inválida.</p>';
            return;
        }

        $certModel = new Certificado();
        $cert = $certModel->getById($id);

        if (!$cert) {
            http_response_code(404);
            echo '<p class="text-center text-slate-500 py-8">Certificado no encontrado.</p>';
            return;
        }

        // Render HTML fragment (no layout)
        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }
}
