<?php

include_once __DIR__ . '/../../modelos/Certificado.php';

class EstudianteHistorialController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_persona = (int)$_SESSION['id'];

        $sql = "SELECT s.id_solicitud as id, s.observaciones, s.estado, s.fecha_solicitud, s.fecha_resolucion,
                       g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado,
                       m.nombre as maestro_nombre, m.apellido as maestro_apellido,
                       c.id_certificado, c.folio, c.fecha_examen, c.grado_anterior, c.grado_nuevo
                FROM solicitudes_ascenso s
                JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                LEFT JOIN personas m ON s.id_persona_maestro = m.id_persona
                LEFT JOIN certificados_ascenso c ON c.id_solicitud = s.id_solicitud
                WHERE s.id_persona_estudiante = ?
                ORDER BY s.id_solicitud DESC";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_persona);
        $stmt->execute();
        $ascensos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $this->view('estudiante/historial', [
            'ascensos'     => $ascensos,
            'page_title'   => 'Mi Historial de Ascensos',
            'current_page' => 'historial'
        ]);
    }

    /** Endpoint AJAX: devuelve el HTML del certificado para el modal */
    public function certificado() {
        Security::verifySession();

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

        // Sólo puede ver su propio certificado
        if ((int)$cert['id_persona'] !== (int)$_SESSION['id']) {
            http_response_code(403);
            echo '<p class="text-center text-rose-500 py-8">Acceso denegado.</p>';
            return;
        }

        // Render HTML fragment (no layout)
        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }
}
