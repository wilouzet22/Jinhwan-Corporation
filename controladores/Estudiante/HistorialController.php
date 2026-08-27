<?php

class EstudianteHistorialController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_persona = (int)$_SESSION['id'];

        $sql = "SELECT s.id_solicitud as id, s.observaciones, s.estado, s.fecha_solicitud, s.fecha_resolucion,
                       g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado,
                       m.nombre as maestro_nombre, m.apellido as maestro_apellido
                FROM solicitudes_ascenso s
                JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                LEFT JOIN personas m ON s.id_persona_maestro = m.id_persona
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
}
