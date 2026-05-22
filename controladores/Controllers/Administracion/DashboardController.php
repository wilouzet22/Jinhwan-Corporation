<?php
/**
 * ============================================================
 * CONTROLADOR DEL PANEL DE ADMINISTRACIÓN (DashboardController)
 * ============================================================
 * Muestra el panel de control principal del administrador con
 * estadísticas generales del club de Taekwondo.
 *
 * Acceso: requiere sesión activa + rol de Administrador.
 * Ruta: GET /admin/dashboard
 * Vista: administracion/dashboard
 *
 * Datos que recopila y envía a la vista:
 *   1. Stats generales: total miembros, activos, pendientes, sedes
 *   2. Distribución de miembros por grado/cinturón
 *   3. Distribución de miembros por sede
 *   4. Los 5 últimos miembros registrados
 * ============================================================
 */
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;

class DashboardController extends Controller {

    /**
     * Constructor: verifica sesión y rol de administrador antes de
     * permitir el acceso a cualquier método de este controlador.
     * Si la verificación falla, Security redirige automáticamente.
     */
    public function __construct() {
        Security::verifySession(); // Verifica sesión activa con todos los checks de seguridad
        Security::verifyAdmin();   // Verifica que el rol sea Administrador (rol_id = 1)
    }

    /**
     * Muestra el panel de control administrativo con estadísticas.
     * Ruta: GET /admin/dashboard
     *
     * Realiza 4 bloques de consultas SQL independientes:
     *
     *   [1] Estadísticas generales (4 conteos simples)
     *   [2] Distribución por grado → datos para gráfica de cinturones
     *   [3] Distribución por sede  → datos para gráfica por sede
     *   [4] Últimos 5 miembros     → actividad reciente
     */
    public function index() {
        $db = Database::getInstance()->getConnection();

        // ── [1] ESTADÍSTICAS GENERALES ──────────────────────────
        $stats = [];

        // Total de miembros registrados (activos + inactivos)
        $res = $db->query("SELECT COUNT(*) as total FROM miembros");
        $stats['total_miembros'] = $res->fetch_assoc()['total'];

        // Total de miembros activos (activo = 1, ya aprobados)
        $res = $db->query("SELECT COUNT(*) as total FROM miembros WHERE activo = 1");
        $stats['activos'] = $res->fetch_assoc()['total'];

        // Total de solicitudes pendientes de aprobación (activo = 0)
        $res = $db->query("SELECT COUNT(*) as total FROM miembros WHERE activo = 0");
        $stats['pendientes'] = $res->fetch_assoc()['total'];

        // Total de sedes registradas en el sistema
        $res = $db->query("SELECT COUNT(*) as total FROM sedes");
        $stats['total_sedes'] = $res->fetch_assoc()['total'];

        // ── [2] DISTRIBUCIÓN POR CINTURÓN/GRADO ─────────────────
        // Muestra cuántos miembros activos hay en cada grado.
        // LEFT JOIN desde 'grados' para incluir grados sin miembros (cantidad = 0).
        $sqlGrados = "SELECT g.nombre, COUNT(m.id_miembro) as cantidad
                      FROM grados g
                      LEFT JOIN miembros m ON g.id_grado = m.id_grado AND m.activo = 1
                      GROUP BY g.id_grado, g.nombre
                      ORDER BY g.id_grado ASC";
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados->fetch_all(MYSQLI_ASSOC);

        // ── [3] DISTRIBUCIÓN POR SEDE ────────────────────────────
        // Muestra cuántos miembros activos hay en cada sede.
        // LEFT JOIN desde 'sedes' para incluir sedes sin miembros (cantidad = 0).
        $sqlSedes = "SELECT s.nombre, COUNT(m.id_miembro) as cantidad
                     FROM sedes s
                     LEFT JOIN miembros m ON s.id_sede = m.id_sede AND m.activo = 1
                     GROUP BY s.id_sede, s.nombre";
        $resSedes = $db->query($sqlSedes);
        $distribucion_sedes = $resSedes->fetch_all(MYSQLI_ASSOC);

        // ── [4] ÚLTIMOS MIEMBROS REGISTRADOS ────────────────────
        // Los 5 miembros más recientes (por ID descendente = más nuevo primero).
        // Incluye su fecha de nacimiento y estado activo/inactivo.
        $sqlUltimos = "SELECT nombre, apellido, fecha_n as fecha, activo
                       FROM miembros
                       ORDER BY id_miembro DESC LIMIT 5";
        $resUltimos = $db->query($sqlUltimos);
        $ultimos_miembros = $resUltimos->fetch_all(MYSQLI_ASSOC);

        // ── RENDERIZAR VISTA ─────────────────────────────────────
        // Pasar todos los datos a la vista del dashboard administrativo
        $this->view('administracion/dashboard', [
            'stats'               => $stats,               // Contadores del resumen
            'distribucion_grados' => $distribucion_grados, // Datos para gráfica de grados
            'distribucion_sedes'  => $distribucion_sedes,  // Datos para gráfica de sedes
            'ultimos_miembros'    => $ultimos_miembros,    // Lista de últimos registros
            'page_title'          => 'Panel de Control',   // Título de la página (para <title> y breadcrumb)
            'current_page'        => 'dashboard'           // Indica cuál ítem del menú lateral está activo
        ]);
    }
}
