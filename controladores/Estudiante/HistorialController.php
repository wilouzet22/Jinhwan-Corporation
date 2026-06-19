<?php
/**
 * ============================================================
 * CONTROLADOR DE HISTORIAL - ESTUDIANTE
 * ============================================================
 * Muestra el historial de ascensos del estudiante.
 * ============================================================
 */
namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;

class HistorialController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_miembro = $_SESSION['id'];

        $sql = "SELECT s.*, 
                       g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado,
                       m.nombre as maestro_nombre, m.apellido as maestro_apellido
                FROM solicitudes_ascenso s
                JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                LEFT JOIN miembros m ON s.id_maestro = m.id_miembro
                WHERE s.id_miembro = ? 
                ORDER BY s.id DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();
        $ascensos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $this->view('estudiante/historial', [
            'ascensos'     => $ascensos,
            'page_title'   => 'Mi Historial de Ascensos',
            'current_page' => 'historial'
        ]);
    }
}
