<?php
/**
 * ============================================================
 * CONTROLADOR DEL DASHBOARD DEL ESTUDIANTE (DashboardController)
 * ============================================================
 * Muestra el panel de bienvenida y resumen personal del estudiante.
 *
 * Acceso: requiere sesión activa (cualquier rol autenticado).
 * Ruta: GET /estudiante/dashboard
 * Vista: estudiante/dashboard
 *
 * Datos que envía a la vista:
 *   - $estudiante → datos completos del miembro autenticado
 *                   (nombre, nivel, sede, teléfono, etc.)
 * ============================================================
 */
namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Config\Roles;

class DashboardController extends Controller {

    /**
     * Constructor: verifica que haya una sesión activa válida.
     * No verifica rol específico (cualquier usuario autenticado puede ver su dashboard).
     */
    public function __construct() {
        Security::verifySession();

        // Proteger la ruta: si el usuario no es estudiante, redirigir a su panel correcto
        $rol = $_SESSION['rol_id'] ?? null;
        if (Roles::esAdmin($rol)) {
            header('Location: ' . base_url('/admin/dashboard')); exit;
        }
        if (Roles::esMaestro($rol) || $rol === Roles::PROFESOR || $rol === Roles::MONITOR) {
            header('Location: ' . base_url('/maestro/dashboard')); exit;
        }
    }

    /**
     * Muestra el panel personal del estudiante.
     * Ruta: GET /estudiante/dashboard
     *
     * Obtiene los datos completos del miembro usando el ID guardado en la sesión.
     * La vista puede mostrar: nombre completo, nivel/cinturón, sede, correo, etc.
     */
    public function index() {
        $usuarioModel = new Usuario();

        // Obtener los datos del estudiante autenticado usando su ID de sesión
        $estudiante = $usuarioModel->getById($_SESSION['id']); // $_SESSION['id'] fue guardado en startSecureSession()

        $this->view('estudiante/dashboard', [
            'estudiante' => $estudiante,    // Datos personales del alumno
            'page_title' => 'Portal del Alumno'
        ]);
    }
}
