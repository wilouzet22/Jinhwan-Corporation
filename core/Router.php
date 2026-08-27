<?php

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

        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $scriptName = str_replace('\\', '/', $scriptName);

        if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
            $path = substr($path, strlen($scriptName));
        }

        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, strlen('/index.php'));
        }

        if ($path === '') {
            $path = '/';
        }

        if (array_key_exists($path, $this->routes[$method] ?? [])) {
            $callback = $this->routes[$method][$path];

            if (is_array($callback)) {
                $controllerClass = $callback[0];
                $methodName = $callback[1];
                $controller = new $controllerClass();
                return $controller->$methodName();
            }

            return call_user_func($callback);
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}

