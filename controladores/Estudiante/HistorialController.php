<?php

include_once __DIR__ . '/../../modelos/Certificado.php';

class EstudianteHistorialController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_estudiante = (int)$_SESSION['id'];

        $sql = "SELECT c.id_certificado as id, c.observaciones, 'aprobado' as estado,
                       c.creado_en as fecha_solicitud, c.fecha_examen as fecha_resolucion,
                       c.grado_anterior as grado_actual, c.grado_nuevo as grado_solicitado,
                       m.nombre as maestro_nombre, m.apellido as maestro_apellido,
                       c.id_certificado, c.folio, c.fecha_examen, c.grado_anterior, c.grado_nuevo
                FROM certificados_ascenso c
                LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
                WHERE c.id_estudiante = ?
                ORDER BY c.creado_en DESC";

        $stmt = $db->prepare($sql);
        $ascensos = [];
        if ($stmt) {
            $stmt->bind_param("i", $id_estudiante);
            $stmt->execute();
            $ascensos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        $this->view('estudiante/historial', [
            'ascensos'     => $ascensos,
            'page_title'   => 'Mi Historial de Ascensos',
            'current_page' => 'historial'
        ]);
    }

    /** Endpoint AJAX: devuelve el HTML del certificado para el modal */
    public function certificado() {
        Security::verifySession();

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

        // Sólo puede ver su propio certificado
        if ((int)$cert['id_estudiante'] !== (int)$_SESSION['id']) {
            http_response_code(403);
            echo '<p class="text-center text-rose-500 py-8">Acceso denegado.</p>';
            return;
        }

        // Render HTML fragment (no layout)
        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }

    public function descargar() {
        Security::verifyRole(Roles::ESTUDIANTE);

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            exit('Solicitud inválida.');
        }

        $certModel = new Certificado();
        $cert = $certModel->getById($id);

        if (!$cert) {
            http_response_code(404);
            exit('Certificado no encontrado.');
        }

        // Sólo puede descargar su propio certificado
        if ((int)$cert['id_estudiante'] !== (int)$_SESSION['id']) {
            http_response_code(403);
            exit('Acceso denegado.');
        }

        require_once __DIR__ . '/../../helpers/DiplomaPdf.php';
        DiplomaPdf::descargar($cert);
    }
}

