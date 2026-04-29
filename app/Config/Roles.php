<?php
/**
 * ============================================================
 * CONFIGURACIÓN DE ROLES (Roles)
 * ============================================================
 * Define las constantes de roles de usuario del sistema y
 * provee métodos de comprobación de rol.
 *
 * Roles existentes en la tabla 'miembros' (columna 'rol'):
 *   1 → Administrador: acceso completo al panel de control
 *   2 → Maestro:       rol intermedio (funcionalidad futura)
 *   3 → Estudiante:    acceso al portal del alumno
 * ============================================================
 */
namespace App\Config;

class Roles {

    /** Rol de Administrador: acceso total al panel de administración */
    const ADMINISTRADOR = 1;

    /** Rol de Maestro/Instructor (uso futuro o especial) */
    const MAESTRO = 2;

    /** Rol de Estudiante: acceso al dashboard y módulo de estudio */
    const ESTUDIANTE = 3;

    /**
     * Comprueba si un rol_id corresponde al Administrador.
     *
     * Uso típico:
     *   Security::verifyAdmin() llama a Roles::esAdmin($_SESSION['rol_id'])
     *
     * @param  int|null $rol_id ID de rol obtenido de la sesión o BD
     * @return bool     true si es Administrador, false en cualquier otro caso
     */
    public static function esAdmin($rol_id) {
        return $rol_id == self::ADMINISTRADOR;
    }
}
