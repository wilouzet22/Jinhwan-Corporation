<?php
/**
 * ============================================================
 * HELPER DE URLS (url_helper)
 * ============================================================
 * Proporciona funciones globales para construir URLs correctas
 * independientemente de si la aplicación está instalada en la
 * raíz del servidor (http://localhost/) o en una subcarpeta
 * (http://localhost/Jinhwan-Corporation-main/).
 *
 * Funciones disponibles:
 *   base_url($path) → URL completa al recurso dado
 *   asset($path)    → URL a un archivo en public/
 * ============================================================
 */

/**
 * Construye la URL base de la aplicación con la ruta dada.
 *
 * Detecta automáticamente si la app está en la raíz o en una
 * subcarpeta del servidor web y ajusta el prefijo.
 *
 * Ejemplo en raíz:      base_url('login') → '/login'
 * Ejemplo en subcarpeta: base_url('login') → '/Jinhwan-Corporation-main/login'
 *
 * @param  string $path Ruta relativa a la raíz del proyecto (sin '/' inicial)
 * @return string URL completa desde la raíz del servidor
 */
if (!function_exists('base_url')) {
    function base_url($path = '') {
        // Obtener la carpeta donde está el script principal (index.php)
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $base = dirname($scriptName);

        // Normalizar separadores de ruta (Windows usa '\')
        $base = str_replace('\\', '/', $base);

        // Quitar barra final excepto cuando es la raíz del servidor '/'
        if ($base !== '/') {
            $base = rtrim($base, '/');
        } else {
            // Si está en la raíz, no agregar prefijo
            $base = '';
        }

        // Combinar base + ruta (asegurando un solo '/' entre ambos)
        return $base . '/' . ltrim($path, '/');
    }
}

/**
 * Construye la URL a un archivo estático dentro de la carpeta public/.
 *
 * Simplifica el acceso a CSS, JS, imágenes y otros assets.
 *
 * Ejemplo: asset('styles/main.css') → '/public/styles/main.css'
 *          asset('js/app.js')       → '/public/js/app.js'
 *
 * @param  string $path Ruta relativa dentro de public/ (sin '/' inicial)
 * @return string URL completa al asset
 */
if (!function_exists('asset')) {
    function asset($path) {
        return base_url('public/' . ltrim($path, '/'));
    }
}
