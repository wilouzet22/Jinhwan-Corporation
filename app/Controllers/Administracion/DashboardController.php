<?php
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;

class DashboardController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // 1. Estadísticas Generales
        $stats = [];
        
        // Total Miembros
        $res = $db->query("SELECT COUNT(*) as total FROM miembros");
        $stats['total_miembros'] = $res->fetch_assoc()['total'];
        
        // Miembros Activos
        $res = $db->query("SELECT COUNT(*) as total FROM miembros WHERE activo = 1");
        $stats['activos'] = $res->fetch_assoc()['total'];
        
        // Solicitudes Pendientes
        $res = $db->query("SELECT COUNT(*) as total FROM miembros WHERE activo = 0");
        $stats['pendientes'] = $res->fetch_assoc()['total'];
        
        // Sedes
        $res = $db->query("SELECT COUNT(*) as total FROM sedes");
        $stats['total_sedes'] = $res->fetch_assoc()['total'];

        // 2. Distribución por Cinturón (Grados)
        $sqlGrados = "SELECT g.nombre, COUNT(m.id_miembro) as cantidad 
                      FROM grados g 
                      LEFT JOIN miembros m ON g.id_grado = m.id_grado AND m.activo = 1
                      GROUP BY g.id_grado, g.nombre 
                      ORDER BY g.id_grado ASC";
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados->fetch_all(MYSQLI_ASSOC);

        // 3. Distribución por Sede
        $sqlSedes = "SELECT s.nombre, COUNT(m.id_miembro) as cantidad 
                     FROM sedes s 
                     LEFT JOIN miembros m ON s.id_sede = m.id_sede AND m.activo = 1
                     GROUP BY s.id_sede, s.nombre";
        $resSedes = $db->query($sqlSedes);
        $distribucion_sedes = $resSedes->fetch_all(MYSQLI_ASSOC);

        // 4. Últimos Miembros Registrados
        $sqlUltimos = "SELECT nombre, apellido, fecha_n as fecha, activo 
                       FROM miembros 
                       ORDER BY id_miembro DESC LIMIT 5";
        $resUltimos = $db->query($sqlUltimos);
        $ultimos_miembros = $resUltimos->fetch_all(MYSQLI_ASSOC);

        $this->view('administracion/dashboard', [
            'stats' => $stats,
            'distribucion_grados' => $distribucion_grados,
            'distribucion_sedes' => $distribucion_sedes,
            'ultimos_miembros' => $ultimos_miembros,
            'page_title' => 'Panel de Control',
            'current_page' => 'dashboard'
        ]);
    }
}
