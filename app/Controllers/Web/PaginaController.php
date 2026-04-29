<?php
/**
 * ============================================================
 * CONTROLADOR DE PÁGINAS ESTÁTICAS – SITIO WEB PÚBLICO (PaginaController)
 * ============================================================
 * Maneja páginas informativas estáticas del sitio que no necesitan
 * datos dinámicos de la base de datos.
 * No requiere autenticación.
 *
 * Rutas:
 *   GET /nosotros → página institucional "Sobre Nosotros"
 *
 * (Extendible: agregar más páginas estáticas como /contacto, /mision, etc.)
 * ============================================================
 */
namespace App\Controllers\Web;

use App\Core\Controller;

class PaginaController extends Controller {

    /**
     * Muestra la página "Nosotros" con información institucional del club.
     * Ruta: GET /nosotros
     *
     * Contiene la misión, visión, historia y valores del club de Taekwondo.
     * El contenido es estático (en la vista HTML, no en la BD).
     */
    public function nosotros() {
        $this->view('web/nosotros', ['page_title' => 'Nosotros']);
    }
}
