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

        // Total alumnos activos (perfil_deportistas con persona activa)
        $res = $db->query("SELECT COUNT(*) as total 
                           FROM perfil_deportistas pd
                           JOIN personas p ON pd.id_persona = p.id_persona
                           WHERE p.activo = 1");
        $stats['total_alumnos'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Solicitudes pendientes de este maestro
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM solicitudes_ascenso WHERE id_persona_maestro = ? AND estado = 'pendiente'");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $stats['solicitudes_pendientes'] = (int)$stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // Solicitudes aprobadas de este maestro
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM solicitudes_ascenso WHERE id_persona_maestro = ? AND estado = 'aprobado'");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $stats['solicitudes_aprobadas'] = (int)$stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // Distribución de grados de alumnos
        $sqlGrados = "SELECT g.nombre, COUNT(pd.id_persona) as cantidad
                      FROM grados g
                      LEFT JOIN perfil_deportistas pd ON g.id_grado = pd.id_grado
                      LEFT JOIN personas p ON pd.id_persona = p.id_persona AND p.activo = 1
                      GROUP BY g.id_grado, g.nombre
                      ORDER BY g.id_grado ASC";
                      
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados ? $resGrados->fetch_all(MYSQLI_ASSOC) : [];

        // Últimos 5 alumnos registrados
        $sqlUltimos = "SELECT p.nombre, p.apellido, pd.fecha_n as fecha, p.activo
                       FROM personas p
                       JOIN perfil_deportistas pd ON p.id_persona = pd.id_persona
                       ORDER BY p.id_persona DESC LIMIT 5";
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
