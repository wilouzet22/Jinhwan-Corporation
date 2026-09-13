<?php

include_once __DIR__ . '/../../modelos/Evento.php';

class MaestroDashboardController extends Controller {

    public function __construct() {
        Security::verifySession(); 
        Security::verifyMaestro(); 
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = (int)$_SESSION['id'];

        $stats = [];

        // Total alumnos activos
        $res = $db->query("SELECT COUNT(*) as total FROM estudiante WHERE activo = 1");
        $stats['total_alumnos'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Certificados emitidos por este maestro
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM certificados_ascenso WHERE id_maestro = ?");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $stats['solicitudes_aprobadas'] = (int)$stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // Solicitudes pendientes de activación en el sistema
        $resPend = $db->query("SELECT COUNT(*) as total FROM estudiante WHERE activo = 0");
        $stats['solicitudes_pendientes'] = $resPend ? (int)$resPend->fetch_assoc()['total'] : 0;

        // Distribución de grados de alumnos
        $sqlGrados = "SELECT g.nombre, COUNT(e.id_estudiante) as cantidad
                      FROM grados g
                      LEFT JOIN estudiante e ON g.id_grado = e.id_grado AND e.activo = 1
                      GROUP BY g.id_grado, g.nombre
                      ORDER BY g.id_grado ASC";
                      
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados ? $resGrados->fetch_all(MYSQLI_ASSOC) : [];

        // Últimos 5 alumnos registrados
        $sqlUltimos = "SELECT e.nombre, e.apellido, e.created_at as fecha, e.activo
                       FROM estudiante e
                       ORDER BY e.id_estudiante DESC LIMIT 5";
        $resUltimos = $db->query($sqlUltimos);
        $ultimos_alumnos = $resUltimos ? $resUltimos->fetch_all(MYSQLI_ASSOC) : [];

        $eventoModel = new Evento();
        $proximos_eventos = $eventoModel->getUpcoming(3);

        $this->view('maestro/dashboard', [
            'stats'               => $stats,
            'distribucion_grados' => $distribucion_grados,
            'ultimos_alumnos'     => $ultimos_alumnos,
            'proximos_eventos'    => $proximos_eventos,
            'page_title'          => 'Dashboard Instructor',
            'current_page'        => 'dashboard'
        ]);
    }
}
