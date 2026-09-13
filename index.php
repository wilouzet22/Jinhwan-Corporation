<?php

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/helpers/url_helper.php';

require_once __DIR__ . '/core/Roles.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

require_once __DIR__ . '/modelos/Sede.php';
require_once __DIR__ . '/modelos/Nivel.php';
require_once __DIR__ . '/modelos/Teoria.php';
require_once __DIR__ . '/modelos/Usuario.php';
require_once __DIR__ . '/modelos/MultimediaGaleria.php';
require_once __DIR__ . '/modelos/Evento.php';
require_once __DIR__ . '/modelos/Categoria.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/ruteador.php';