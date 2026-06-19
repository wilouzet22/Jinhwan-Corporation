<?php
/**
 * ============================================================
 * CONTROLADOR DEL DASHBOARD DE MAESTRO (DashboardController)
 * ============================================================
 * Muestra el panel de control principal del Maestro/Instructor
 * con estadísticas de los deportistas y sus solicitudes de ascenso.
 *
 * Acceso: requiere sesión activa + rol de Maestro.
 * Ruta: GET /maestro/dashboard
 * Vista: maestro/dashboard
 * ============================================================
 */
namespace App\Controllers\Maestro;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;

class DashboardController extends Controller {

    /**
     * Constructor: verifica sesión y rol de maestro antes de
     * permitir el acceso a este controlador.
     */
    public function __construct() {
        Security::verifySession(); // Verifica sesión activa
        Security::verifyMaestro(); // Verifica rol de Maestro
    }

    /**
     * Muestra el panel de control del maestro.
     */
    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = $_SESSION['id'];

        $stats = [];

        // ── [1] ESTADÍSTICAS GENERALES ──────────────────────────
        // Total de alumnos registrados (rol = 'Deportistas' y activos)
        $res = $db->query("SELECT COUNT(*) as total FROM miembros WHERE rol = 'Deportistas' AND activo = 1");
        $stats['total_alumnos'] = $res->fetch_assoc()['total'];

        // Solicitudes de ascenso enviadas por este maestro que están pendientes
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM solicitudes_ascenso WHERE id_maestro = ? AND estado = 'pendiente'");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $stats['solicitudes_pendientes'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // Solicitudes de ascenso enviadas por este maestro que fueron aprobadas
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM solicitudes_ascenso WHERE id_maestro = ? AND estado = 'aprobado'");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $stats['solicitudes_aprobadas'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // ── [2] DISTRIBUCIÓN POR CINTURÓN/GRADO ─────────────────
        // Muestra cuántos deportistas hay en cada grado.
        $sqlGrados = "SELECT g.nombre, COUNT(m.id_miembro) as cantidad
                      FROM grados g
                      LEFT JOIN miembros m ON g.id_grado = m.id_grado AND m.activo = 1 AND m.rol = 'Deportistas'
                      GROUP BY g.id_grado, g.nombre
                      ORDER BY g.id_grado ASC";
                      
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados->fetch_all(MYSQLI_ASSOC);

        // ── [3] ÚLTIMOS MIEMBROS DEPORTISTAS REGISTRADOS ────────
        $sqlUltimos = "SELECT nombre, apellido, fecha_n as fecha, activo
                       FROM miembros
                       WHERE rol = 'Deportistas'
                       ORDER BY id_miembro DESC LIMIT 5";
        $resUltimos = $db->query($sqlUltimos);
        $ultimos_alumnos = $resUltimos->fetch_all(MYSQLI_ASSOC);

        // ── RENDERIZAR VISTA ─────────────────────────────────────
        $this->view('maestro/dashboard', [
            'stats'               => $stats,
            'distribucion_grados' => $distribucion_grados,
            'ultimos_alumnos'     => $ultimos_alumnos,
            'page_title'          => 'Dashboard Instructor',
            'current_page'        => 'dashboard'
        ]);
    }
}
