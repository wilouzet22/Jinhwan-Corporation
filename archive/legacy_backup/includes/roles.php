<?php
// Roles definidos en la base de datos 'tkd' - tabla 'roles'
define('ROL_SUPERUSUARIO', 1);
define('ROL_ADMINISTRADOR', 2);
define('ROL_CONTADOR', 3);
define('ROL_MAESTRO', 4);
define('ROL_ESTUDIANTE', 5);

function esAdmin($rol_id) {
    return in_array($rol_id, [ROL_SUPERUSUARIO, ROL_ADMINISTRADOR]);
}
?>
