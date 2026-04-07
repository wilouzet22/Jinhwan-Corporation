<?php
namespace App\Config;

class Roles {
    const ADMINISTRADOR = 1;
    const MAESTRO = 2;
    const ESTUDIANTE = 3;

    public static function esAdmin($rol_id) {
        return $rol_id == self::ADMINISTRADOR;
    }
}
