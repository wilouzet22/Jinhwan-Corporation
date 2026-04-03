<?php
namespace App\Core;

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        // Check for file in both web and admin directories or specific path
        $viewPath = __DIR__ . "/../Views/$view.php";
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View $view not found");
        }
    }

    protected function redirect($url) {
        // Handle project base path
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base === '/') $base = '';
        
        header("Location: " . $base . $url);
        exit;
    }
}
