<?php

namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;
use App\Models\Evento;

class DashboardController extends Controller {

    public function __construct() {
        Security::verifySession(); 
        Security::verifyAdmin();   
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        $stats = [];

        $res = $db->query("SELECT COUNT(*) as total FROM personas");
        $stats['total_miembros'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        $res = $db->query("SELECT COUNT(*) as total FROM personas WHERE activo = 1");
        $stats['activos'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        $res = $db->query("SELECT COUNT(*) as total FROM personas WHERE activo = 0");
        $stats['pendientes'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        $res = $db->query("SELECT COUNT(*) as total FROM solicitudes_ascenso WHERE estado = 'pendiente'");
        $stats['pendientes_ascenso'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        $res = $db->query("SELECT COUNT(*) as total FROM sedes");
        $stats['total_sedes'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Distribución de grados de deportistas activos
        $sqlGrados = "SELECT g.nombre, COUNT(pd.id_persona) as cantidad
                      FROM grados g
                      LEFT JOIN perfil_deportistas pd ON g.id_grado = pd.id_grado
                      LEFT JOIN personas p ON pd.id_persona = p.id_persona AND p.activo = 1
                      GROUP BY g.id_grado, g.nombre
                      ORDER BY g.id_grado ASC";
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados ? $resGrados->fetch_all(MYSQLI_ASSOC) : [];

        // Distribución por sedes
        $sqlSedes = "SELECT s.nombre, COUNT(p.id_persona) as cantidad
                     FROM sedes s
                     LEFT JOIN personas p ON s.id_sede = p.id_sede AND p.activo = 1
                     GROUP BY s.id_sede, s.nombre
                     ORDER BY s.id_sede ASC";
        $resSedes = $db->query($sqlSedes);
        $distribucion_sedes = $resSedes ? $resSedes->fetch_all(MYSQLI_ASSOC) : [];

        // Últimos miembros registrados
        $sqlUltimos = "SELECT p.nombre, p.apellido, pd.fecha_n as fecha, p.activo
                       FROM personas p
                       LEFT JOIN perfil_deportistas pd ON p.id_persona = pd.id_persona
                       ORDER BY p.id_persona DESC LIMIT 5";
        $resUltimos = $db->query($sqlUltimos);
        $ultimos_miembros = $resUltimos ? $resUltimos->fetch_all(MYSQLI_ASSOC) : [];

        $eventoModel = new Evento();
        $proximos_eventos = $eventoModel->getUpcoming(3);

        $this->view('administracion/dashboard', [
            'stats'               => $stats,               
            'distribucion_grados' => $distribucion_grados, 
            'distribucion_sedes'  => $distribucion_sedes,  
            'ultimos_miembros'    => $ultimos_miembros,    
            'proximos_eventos'    => $proximos_eventos,    
            'page_title'          => 'Panel de Control',   
            'current_page'        => 'dashboard'           
        ]);
    }
}
