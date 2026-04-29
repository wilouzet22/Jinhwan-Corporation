<?php
/**
 * ============================================================
 * CONTROLADOR DE GALERÍA – SITIO WEB PÚBLICO (GaleriaController)
 * ============================================================
 * Muestra la galería multimedia del club de Taekwondo.
 * No requiere autenticación (acceso libre).
 *
 * Ruta: GET /galeria
 * Vista: web/galeria
 *
 * Lee las imágenes desde el sistema de archivos usando glob()
 * para evitar tener que registrarlas en una base de datos.
 * Las imágenes deben estar en: public/img/galeria/*.jpg
 * ============================================================
 */
namespace App\Controllers\Web;

use App\Core\Controller;

class GaleriaController extends Controller {

    /**
     * Carga y muestra la galería de imágenes del club.
     * Ruta: GET /galeria
     *
     * Busca archivos .jpg en la carpeta galeria/ del proyecto.
     * Tiene un fallback de ruta para compatibilidad con distintas
     * configuraciones del servidor (con o sin subcarpeta 'public/').
     *
     * La variable $imagenes es un array con las rutas de los archivos
     * que la vista usará para construir los tags <img>.
     */
    public function index() {
        // Determinar la ruta correcta de la galería según la estructura del servidor
        if (is_dir('public/img/galeria')) {
            $path = 'public/img/galeria/*.jpg'; // Ruta estándar con carpeta public/
        } else {
            $path = 'img/galeria/*.jpg';        // Ruta alternativa sin subcarpeta public/
        }

        // glob() retorna un array de rutas de archivos que coinciden con el patrón
        // Si no hay imágenes, retorna un array vacío []
        $imagenes = glob($path);

        $this->view('web/galeria', [
            'page_title' => 'Galería Multimedia',
            'imagenes'   => $imagenes  // Array de rutas de imágenes para mostrar en la vista
        ]);
    }
}
