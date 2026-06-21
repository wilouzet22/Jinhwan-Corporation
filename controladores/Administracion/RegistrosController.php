<?php
/**
 * ============================================================
 * CONTROLADOR DE REGISTROS/SOLICITUDES – ADMINISTRACIÓN (RegistrosController)
 * ============================================================
 * Gestiona las solicitudes de registro de nuevos estudiantes.
 * Cuando un usuario se registra públicamente (/registro), su cuenta
 * queda con activo = 0 (pendiente). Este controlador permite al
 * administrador aprobar o rechazar esas solicitudes.
 *
 * Acceso: requiere sesión activa + rol de Administrador.
 * Rutas:
 *   GET  /admin/registros           → listar solicitudes pendientes
 *   POST /admin/registros/aprobar   → aprobar una solicitud (activo = 1)
 *   POST /admin/registros/rechazar  → rechazar y eliminar la solicitud
 *
 * Vista: administracion/registros
 * ============================================================
 */
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;

class RegistrosController extends Controller {

    /**
     * Constructor: verifica sesión activa y rol de administrador.
     */
    public function __construct() {
        Security::verifySession();
        Security::verifyPermission('registros');
    }

    /**
     * Lista todas las solicitudes de registro pendientes (activo = 0).
     * Ruta: GET /admin/registros
     *
     * Consulta miembros con activo = 0 uniendo con userlog para mostrar
     * el correo electrónico del solicitante.
     * Ordenado por id_miembro DESC (más reciente primero).
     */
    public function index() {
        $db = Database::getInstance()->getConnection();

        // Obtener miembros pendientes con su correo de acceso
        // JOIN con userlog para incluir el correo registrado
        $sql = "SELECT m.id_miembro as id, m.nombre, m.apellido, m.num_doc, m.telefono, u.correo, m.fecha_n
                FROM miembros m
                JOIN userlog u ON m.id_miembro = u.id_miembro
                WHERE m.activo = 0
                ORDER BY m.id_miembro DESC";

        $result      = $db->query($sql);
        $solicitudes = $result->fetch_all(MYSQLI_ASSOC);

        // Obtener solicitudes de ascenso pendientes
        $sqlAscensos = "SELECT s.id, s.id_miembro, s.id_grado_solicitado, s.observaciones, s.fecha_solicitud,
                               m.nombre as nombre_alumno, m.apellido as apellido_alumno,
                               g_act.nombre as grado_actual, g_sol.nombre as grado_solicitado,
                               maest.nombre as nombre_maestro, maest.apellido as apellido_maestro
                        FROM solicitudes_ascenso s
                        JOIN miembros m ON s.id_miembro = m.id_miembro
                        JOIN grados g_act ON s.id_grado_actual = g_act.id_grado
                        JOIN grados g_sol ON s.id_grado_solicitado = g_sol.id_grado
                        JOIN miembros maest ON s.id_maestro = maest.id_miembro
                        WHERE s.estado = 'pendiente'
                        ORDER BY s.id DESC";
        $resultAscensos = $db->query($sqlAscensos);
        $solicitudes_ascenso = $resultAscensos->fetch_all(MYSQLI_ASSOC);

        $this->view('administracion/registros', [
            'solicitudes'          => $solicitudes,
            'solicitudes_ascenso'  => $solicitudes_ascenso,
            'page_title'           => 'Solicitudes Pendientes',
            'current_page'         => 'registros'
        ]);
    }

    /**
     * Aprueba una solicitud de registro.
     * Ruta: POST /admin/registros/aprobar
     *
     * Actualiza el campo 'activo' a 1, permitiendo al estudiante iniciar sesión.
     * Redirige con ?msg=approved al completar.
     *
     * Campo POST esperado: id (ID del miembro a aprobar)
     */
    public function aprobar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $db = Database::getInstance()->getConnection();

            // Activar el miembro: cambiar activo de 0 a 1
            $stmt = $db->prepare("UPDATE miembros SET activo = 1 WHERE id_miembro = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=approved');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
        }
    }

    /**
     * Rechaza y elimina una solicitud de registro.
     * Ruta: POST /admin/registros/rechazar
     *
     * El rechazo elimina permanentemente los datos del solicitante
     * en dos pasos para respetar integridad referencial:
     *   1. Eliminar de 'userlog' (credenciales)
     *   2. Eliminar de 'miembros' (datos personales)
     *
     * Campo POST esperado: id (ID del miembro a rechazar)
     */
    public function rechazar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $db = Database::getInstance()->getConnection();

            // Paso 1: Eliminar primero de userlog (evitar FK violation si hay restricción)
            $stmt_log = $db->prepare("DELETE FROM userlog WHERE id_miembro = ?");
            $stmt_log->bind_param("i", $id);
            $stmt_log->execute();

            // Paso 2: Eliminar el miembro de la tabla principal
            $stmt = $db->prepare("DELETE FROM miembros WHERE id_miembro = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=rejected');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
        }
    }

    /**
     * Aprueba una propuesta de ascenso de grado.
     * Ruta: POST /admin/registros/aprobar-ascenso
     */
    public function aprobarAscenso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud        = intval($_POST['id']);
            $id_miembro          = intval($_POST['id_miembro']);
            $id_grado_solicitado = intval($_POST['id_grado_solicitado']);

            $db = Database::getInstance()->getConnection();

            // 1. Iniciar transacción
            $db->begin_transaction();

            try {
                // Actualizar solicitud a aprobado
                $stmt1 = $db->prepare("UPDATE solicitudes_ascenso SET estado = 'aprobado', fecha_resolucion = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt1->bind_param("i", $id_solicitud);
                $stmt1->execute();
                $stmt1->close();

                // Actualizar miembro al nuevo grado
                $stmt2 = $db->prepare("UPDATE miembros SET id_grado = ? WHERE id_miembro = ?");
                $stmt2->bind_param("ii", $id_grado_solicitado, $id_miembro);
                $stmt2->execute();
                $stmt2->close();

                $db->commit();
                $this->redirect('/admin/registros?msg=promo_approved');
            } catch (\Exception $e) {
                $db->rollback();
                $this->redirect('/admin/registros?error=1');
            }
        }
    }

    /**
     * Rechaza una propuesta de ascenso de grado.
     * Ruta: POST /admin/registros/rechazar-ascenso
     */
    public function rechazarAscenso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = intval($_POST['id']);
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("UPDATE solicitudes_ascenso SET estado = 'rechazado', fecha_resolucion = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("i", $id_solicitud);

            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=promo_rejected');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
            $stmt->close();
        }
    }
}
