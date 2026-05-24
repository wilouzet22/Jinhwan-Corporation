<?php
/**
 * ============================================================
 * CONTROLADOR BASE (Controller)
 * ============================================================
 * Todos los controladores de la aplicación extienden esta clase.
 * Proporciona dos métodos utilitarios esenciales:
 *   - view()     → cargar y renderizar una vista PHP
 *   - redirect() → redirigir al navegador a otra URL
 * ============================================================
 */
namespace App\Core;

class Controller {

    /**
     * Carga y renderiza una vista PHP.
     *
     * Busca el archivo en: app/Views/{$view}.php
     * Si el archivo existe, lo incluye con require (lo ejecuta).
     * Las variables del array $data se extraen al scope local
     * con extract(), quedando disponibles en la vista.
     *
     * Ejemplo de uso en un controlador:
     *   $this->view('web/inicio', ['page_title' => 'Inicio']);
     *
     * @param string $view Ruta relativa de la vista, sin extensión .php
     *                     (p. ej. 'web/inicio', 'administracion/dashboard')
     * @param array  $data Variables que se pasarán a la vista
     */
    protected function view($view, $data = []) {
        // extract() convierte cada clave del array en una variable local
        // Ejemplo: ['page_title' => 'Inicio'] → variable $page_title disponible en la vista
        extract($data);

        // Construir la ruta absoluta al archivo de vista
        $viewPath = __DIR__ . "/../vistas/$view.php";

        if (file_exists($viewPath)) {
            // Incluir/renderizar la vista
            require $viewPath;
        } else {
            // La vista solicitada no existe: detener la ejecución con mensaje de error
            die("View $view not found");
        }
    }

    /**
     * Redirige al navegador a otra URL dentro de la aplicación.
     *
     * Calcula automáticamente el prefijo base del proyecto
     * (útil cuando la app no está en la raíz del servidor, p. ej.
     * http://localhost/Jinhwan-Corporation-main/).
     *
     * Ejemplo de uso:
     *   $this->redirect('/login');
     *   $this->redirect('/admin/dashboard');
     *
     * @param string $url Ruta relativa destino (siempre empieza con '/')
     */
    protected function redirect($url) {
        // Detectar la subcarpeta del proyecto (si existe)
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base === '/') $base = '';  // En la raíz del servidor, no hay prefijo

        // Enviar cabecera de redirección HTTP 302
        header("Location: " . $base . $url);
        exit; // Detener ejecución para evitar que se envíe más contenido
    }
}
