<?php

if (!function_exists('base_url')) {
    function base_url($path = '') {
        
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $base = dirname($scriptName);

        $base = str_replace('\\', '/', $base);

        if ($base !== '/') {
            $base = rtrim($base, '/');
        } else {
            
            $base = '';
        }

        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return base_url('public/' . ltrim($path, '/'));
    }
}
