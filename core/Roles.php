<?php

class Roles {
    const ADMINISTRADOR = 'Administracion';
    const MAESTRO = 'Maestros';
    const PROFESOR = 'Profesores';
    const MONITOR = 'Monitores';
    const ESTUDIANTE = 'Deportistas';

    public static function esAdmin($rol_id) {
        return $rol_id == self::ADMINISTRADOR;
    }

    public static function esMaestro($rol_id) {
        return $rol_id == self::MAESTRO;
    }
}

