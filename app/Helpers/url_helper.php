<?php

if (!function_exists('base_url')) {
    function base_url($path = '') {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $base = dirname($scriptName);
        
        // Normalize slashes
        $base = str_replace('\\', '/', $base);
        
        // Remove trailing slash if exists (root dir case)
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
