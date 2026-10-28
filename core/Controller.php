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
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $target = $url;
        } else {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                       || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                       || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
            $protocol = $isHttps ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $target = $protocol . $host . base_url($url);
        }
        header("Location: " . $target);
        exit;
    }
}

