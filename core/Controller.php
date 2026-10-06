<?php

require_once __DIR__ . '/../helpers/url_helper.php';

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
        header("Location: " . base_url($url));
        exit;
    }
}

