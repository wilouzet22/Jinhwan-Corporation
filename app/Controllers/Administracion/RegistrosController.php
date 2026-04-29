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
        Security::verifyAdmin();
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

        $this->view('administracion/registros', [
            'solicitudes'  => $solicitudes,
            'page_title'   => 'Solicitudes de Registro',
            'current_page' => 'registros'
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
}
