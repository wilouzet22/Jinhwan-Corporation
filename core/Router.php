<?php
/**
 * ============================================================
 * ENRUTADOR (Router)
 * ============================================================
 * Responsabilidad: recibir todas las peticiones HTTP, compararlas
 * con las rutas registradas y despachar la ejecución al controlador
 * y método correctos.
 *
 * Implementa un enrutador de tipo "Front Controller" muy sencillo:
 *   - Soporta métodos GET y POST.
 *   - Cada ruta se registra como [método HTTP][path] => callback.
 *   - Resuelve la subcarpeta del proyecto automáticamente.
 * ============================================================
 */
namespace App\Core;

class Router {

    /**
     * Almacén de rutas registradas.
     * Estructura: ['GET' => ['/ruta' => callback], 'POST' => [...]]
     */
    private $routes = [];

    /**
     * Registra una ruta que responde al método HTTP GET.
     *
     * @param string $path     Ruta relativa, p. ej. '/admin/dashboard'
     * @param mixed  $callback Array [Clase::class, 'método'] o callable
     */
    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * Registra una ruta que responde al método HTTP POST.
     *
     * @param string $path     Ruta relativa, p. ej. '/login/process'
     * @param mixed  $callback Array [Clase::class, 'método'] o callable
     */
    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * Despacha la petición actual al controlador registrado.
     *
     * 1. Lee el método HTTP y la ruta solicitada.
     * 2. Elimina el prefijo de la subcarpeta del proyecto (p. ej. /Jinhwan-Corporation-main).
     * 3. Busca coincidencia exacta en las rutas registradas.
     * 4. Si la ruta existe, instancia el controlador e invoca el método.
     * 5. Si no existe, devuelve HTTP 404.
     */
    public function dispatch() {
        // Obtener el método HTTP (GET, POST, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtener la ruta de la URL (sin query string)
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Quitar el prefijo de la subcarpeta si la app no está en la raíz del servidor
        // Ejemplo: si la app está en http://localhost/Jinhwan-Corporation-main/
        //          la ruta /Jinhwan-Corporation-main/sedes se convierte en /sedes
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        // Normalizar barras invertidas (Windows)
        $scriptName = str_replace('\\', '/', $scriptName);

        if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
            $path = substr($path, strlen($scriptName));
        }

        // Si la URL explícitamente tiene /index.php, también quitarlo
        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, strlen('/index.php'));
        }

        // Si la ruta queda vacía tras limpiar prefijos, apuntar a la raíz
        if ($path === '') {
            $path = '/';
        }

        // Buscar la ruta dentro del método HTTP correspondiente
        if (array_key_exists($path, $this->routes[$method] ?? [])) {
            $callback = $this->routes[$method][$path];

            // Si el callback es un array [ControllerClass, 'metodo'],
            // instanciar el controlador e invocar el método
            if (is_array($callback)) {
                $controller = new $callback[0]();
                $method = $callback[1];
                return $controller->$method();
            }

            // Si el callback es una función anónima, ejecutarla directamente
            return call_user_func($callback);
        }

        // Ruta no encontrada → respuesta 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
