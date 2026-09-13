<?php

include_once __DIR__ . '/../../modelos/Evento.php';

class AdminDashboardController extends Controller {

    public function __construct() {
        Security::verifySession(); 
        Security::verifyAdmin();   
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        $stats = [];

        // Total miembros (alumnos + maestros)
        $res = $db->query("SELECT (SELECT COUNT(*) FROM estudiante) + (SELECT COUNT(*) FROM maestro) as total");
        $stats['total_miembros'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Miembros activos
        $res = $db->query("SELECT (SELECT COUNT(*) FROM estudiante WHERE activo = 1) + (SELECT COUNT(*) FROM maestro WHERE activo = 1) as total");
        $stats['activos'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Alumnos pendientes de aprobación
        $res = $db->query("SELECT COUNT(*) as total FROM estudiante WHERE activo = 0");
        $stats['pendientes'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Certificados emitidos
        $res = $db->query("SELECT COUNT(*) as total FROM certificados_ascenso");
        $stats['pendientes_ascenso'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Total sedes
        $res = $db->query("SELECT COUNT(*) as total FROM sedes");
        $stats['total_sedes'] = $res ? (int)$res->fetch_assoc()['total'] : 0;

        // Distribución de grados de deportistas activos
        $sqlGrados = "SELECT g.nombre, COUNT(e.id_estudiante) as cantidad
                      FROM grados g
                      LEFT JOIN estudiante e ON g.id_grado = e.id_grado AND e.activo = 1
                      GROUP BY g.id_grado, g.nombre
                      ORDER BY g.id_grado ASC";
        $resGrados = $db->query($sqlGrados);
        $distribucion_grados = $resGrados ? $resGrados->fetch_all(MYSQLI_ASSOC) : [];

        // Distribución por sedes (a través de grupos)
        $sqlSedes = "SELECT s.nombre, COUNT(e.id_estudiante) as cantidad
                     FROM sedes s
                     LEFT JOIN grupos gr ON s.id_sede = gr.id_sede
                     LEFT JOIN estudiante e ON gr.id_grupo = e.id_grupo AND e.activo = 1
                     GROUP BY s.id_sede, s.nombre
                     ORDER BY s.id_sede ASC";
        $resSedes = $db->query($sqlSedes);
        $distribucion_sedes = $resSedes ? $resSedes->fetch_all(MYSQLI_ASSOC) : [];

        // Últimos miembros registrados
        $sqlUltimos = "SELECT e.nombre, e.apellido, e.created_at as fecha, e.activo
                       FROM estudiante e
                       ORDER BY e.id_estudiante DESC LIMIT 5";
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
