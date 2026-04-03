<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove project folder from path if exists (e.g. /jinwha)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
            $path = substr($path, strlen($scriptName));
        }

        if (array_key_exists($path, $this->routes[$method] ?? [])) {
            $callback = $this->routes[$method][$path];
            
            if (is_array($callback)) {
                $controller = new $callback[0]();
                $method = $callback[1];
                return $controller->$method();
            }
            
            return call_user_func($callback);
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
