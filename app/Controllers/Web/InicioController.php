<?php
/**
 * ============================================================
 * CONTROLADOR DE INICIO – SITIO WEB PÚBLICO (InicioController)
 * ============================================================
 * Maneja las páginas de inicio y portal de bienvenida del
 * sitio público (sin autenticación requerida).
 *
 * Rutas:
 *   GET /        → portal de bienvenida (landing page)
 *   GET /portal  → misma vista de portal
 *   GET /inicio  → página de inicio del sitio web completo
 * ============================================================
 */
namespace App\Controllers\Web;

use App\Core\Controller;

class InicioController extends Controller {

    /**
     * Muestra la página de inicio principal del sitio web.
     * Ruta: GET /inicio  |  GET /index.php
     *
     * Renderiza la vista completa del sitio web con toda la información
     * del club: información, secciones, etc.
     */
    public function index() {
        $this->view('web/inicio', [
            'page_title' => 'Jinnwhan Organization - Taekwondo'
        ]);
    }

    /**
     * Muestra el portal de bienvenida (landing page principal).
     * Ruta: GET /  |  GET /portal
     *
     * Esta es la primera página que ve un visitante al entrar al sitio.
     * Presenta la propuesta de valor del club y accesos directos a login/registro.
     */
    public function portal() {
        $this->view('web/portal', [
            'page_title' => 'Bienvenido - Jinhwa Corporation'
        ]);
    }
}
