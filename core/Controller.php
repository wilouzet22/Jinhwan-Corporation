<?php

class Controller {

    protected function view($view, $data = []) {
        extract($data);

        $viewPath = __DIR__ . "/../vistas/$view.php";

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View $view not found");
        }
    }

    protected function redirect($url) {
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base === '/' || $base === '\\') $base = '';

        header("Location: " . $base . $url);
        exit;
    }
}

