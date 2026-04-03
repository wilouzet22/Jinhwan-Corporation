<?php
namespace App\Config;

class Roles {
    const SUPERUSUARIO = 1;
    const ADMINISTRADOR = 2;
    const CONTADOR = 3;
    const MAESTRO = 4;
    const ESTUDIANTE = 5;

    public static function esAdmin($rol_id) {
        return in_array($rol_id, [self::SUPERUSUARIO, self::ADMINISTRADOR]);
    }
}
