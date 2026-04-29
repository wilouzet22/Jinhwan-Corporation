<?php
/**
 * ============================================================
 * CONTROLADOR DE INSTRUCTORES – SITIO WEB PÚBLICO (InstructoresController)
 * ============================================================
 * Muestra la página de instructores del club de Taekwondo.
 * No requiere autenticación.
 *
 * Ruta: GET /instructores
 * Vista: web/instructores
 *
 * El contenido de instructores es actualmente estático (en la vista).
 * En una versión futura podría cargarse desde la base de datos
 * filtrando miembros con rol de Maestro (Roles::MAESTRO = 2).
 * ============================================================
 */
namespace App\Controllers\Web;

use App\Core\Controller;

class InstructoresController extends Controller {

    /**
     * Muestra la página de instructores del club.
     * Ruta: GET /instructores
     *
     * Actualmente el listado de instructores está definido directamente
     * en la vista (web/instructores.php) de forma estática.
     */
    public function index() {
        $this->view('web/instructores', ['page_title' => 'Nuestros Instructores']);
    }
}
