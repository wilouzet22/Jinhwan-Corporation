<?php

include_once __DIR__ . '/controladores/Web/InicioController.php';
include_once __DIR__ . '/controladores/Web/SedesController.php';
include_once __DIR__ . '/controladores/Web/GruposController.php';
include_once __DIR__ . '/controladores/Web/PaginaController.php';
include_once __DIR__ . '/controladores/Web/MiembrosController.php';
include_once __DIR__ . '/controladores/Web/GaleriaController.php';
            
include_once __DIR__ . '/controladores/Administracion/AscensosController.php';
include_once __DIR__ . '/controladores/Administracion/SedesController.php';
include_once __DIR__ . '/controladores/Administracion/MiembrosController.php';
include_once __DIR__ . '/controladores/Administracion/EstudiantesController.php';
include_once __DIR__ . '/controladores/Administracion/MaestrosController.php';
include_once __DIR__ . '/controladores/Administracion/GruposController.php';
include_once __DIR__ . '/controladores/Administracion/PerfilesPublicosController.php';
include_once __DIR__ . '/controladores/Administracion/RegistrosController.php';
include_once __DIR__ . '/controladores/Administracion/DashboardController.php';
include_once __DIR__ . '/controladores/Administracion/CalendarioController.php';
include_once __DIR__ . '/controladores/Administracion/GaleriaController.php';

include_once __DIR__ . '/controladores/Autenticacion/AutenticacionController.php';

include_once __DIR__ . '/controladores/Usuario/CalendarioController.php';
include_once __DIR__ . '/controladores/Usuario/PerfilController.php';

include_once __DIR__ . '/controladores/Estudiante/DashboardController.php';
include_once __DIR__ . '/controladores/Estudiante/EstudioController.php';
include_once __DIR__ . '/controladores/Estudiante/HistorialController.php';

include_once __DIR__ . '/controladores/Maestro/DashboardController.php';
include_once __DIR__ . '/controladores/Maestro/AlumnosController.php';
include_once __DIR__ . '/controladores/Maestro/SolicitudesAscensoController.php';

$router = new Router();

// Rutas Públicas (Web)
$router->get('/', [WebInicioController::class, 'portal']);
$router->get('/portal', [WebInicioController::class, 'portal']);
$router->get('/inicio', [WebInicioController::class, 'index']);
$router->get('/nosotros', [WebPaginaController::class, 'nosotros']);
$router->get('/sedes', [WebSedesController::class, 'index']);
$router->get('/grupos', [WebGruposController::class, 'index']);
$router->get('/miembros', [WebMiembrosController::class, 'index']);
$router->get('/galeria', [WebGaleriaController::class, 'index']);

// Rutas Estudiante
$router->get('/estudiante/dashboard', [EstudianteDashboardController::class, 'index']);
$router->get('/estudiante/estudio', [EstudianteEstudioController::class, 'index']);
$router->post('/ascensos/toggle', [EstudianteEstudioController::class, 'toggle']);
$router->get('/estudiante/historial', [EstudianteHistorialController::class, 'index']);
$router->get('/estudiante/historial/certificado', [EstudianteHistorialController::class, 'certificado']);
$router->get('/estudiante/perfil', [UsuarioPerfilController::class, 'index']);
$router->post('/estudiante/perfil/update', [UsuarioPerfilController::class, 'update']);

// Rutas Usuario General / Perfil
$router->get('/usuario/perfil', [UsuarioPerfilController::class, 'index']);
$router->post('/usuario/perfil/update', [UsuarioPerfilController::class, 'update']);
$router->get('/perfil', [UsuarioPerfilController::class, 'index']);
$router->post('/perfil/update', [UsuarioPerfilController::class, 'update']);
$router->get('/usuario/calendario', [UsuarioCalendarioController::class, 'index']);
$router->get('/usuario/calendario/get-eventos', [UsuarioCalendarioController::class, 'getEventos']);

// Autenticación y Registro
$router->get('/login', [AutenticacionController::class, 'loginForm']);
$router->post('/login/process', [AutenticacionController::class, 'login']);
$router->get('/logout', [AutenticacionController::class, 'logout']);
$router->get('/registro', [AutenticacionController::class, 'registroForm']);
$router->post('/registro/process', [AutenticacionController::class, 'processRegistro']);
$router->get('/registro/completar', [AutenticacionController::class, 'completarRegistroForm']);
$router->post('/registro/completar/process', [AutenticacionController::class, 'processCompletarRegistro']);

// Administración
$router->get('/admin/dashboard', [AdminDashboardController::class, 'index']);
$router->get('/admin/ascensos', [AdminAscensosController::class, 'index']);
$router->post('/admin/ascensos/create', [AdminAscensosController::class, 'store']);
$router->post('/admin/ascensos/update', [AdminAscensosController::class, 'update']);
$router->post('/admin/ascensos/delete', [AdminAscensosController::class, 'delete']);

$router->get('/admin/sedes', [AdminSedesController::class, 'index']);
$router->post('/admin/sedes/create', [AdminSedesController::class, 'store']);
$router->post('/admin/sedes/update', [AdminSedesController::class, 'update']);
$router->post('/admin/sedes/delete', [AdminSedesController::class, 'delete']);

$router->get('/admin/miembros', [AdminMiembrosController::class, 'index']);
$router->post('/admin/miembros/create', [AdminMiembrosController::class, 'store']);
$router->post('/admin/miembros/update', [AdminMiembrosController::class, 'update']);
$router->post('/admin/miembros/delete', [AdminMiembrosController::class, 'delete']);
$router->post('/admin/miembros/delete-bulk', [AdminMiembrosController::class, 'deleteBulk']);

$router->get('/admin/estudiantes', [AdminEstudiantesController::class, 'index']);
$router->post('/admin/estudiantes/create', [AdminEstudiantesController::class, 'store']);
$router->post('/admin/estudiantes/update', [AdminEstudiantesController::class, 'update']);
$router->post('/admin/estudiantes/delete', [AdminEstudiantesController::class, 'delete']);
$router->post('/admin/estudiantes/delete-bulk', [AdminEstudiantesController::class, 'deleteBulk']);

$router->get('/admin/maestros', [AdminMaestrosController::class, 'index']);
$router->post('/admin/maestros/create', [AdminMaestrosController::class, 'store']);
$router->post('/admin/maestros/update', [AdminMaestrosController::class, 'update']);
$router->post('/admin/maestros/delete', [AdminMaestrosController::class, 'delete']);

$router->get('/admin/grupos', [AdminGruposController::class, 'index']);
$router->post('/admin/grupos/create', [AdminGruposController::class, 'store']);
$router->post('/admin/grupos/update', [AdminGruposController::class, 'update']);
$router->post('/admin/grupos/delete', [AdminGruposController::class, 'delete']);

$router->get('/admin/perfiles-publicos', [AdminPerfilesPublicosController::class, 'index']);
$router->post('/admin/perfiles-publicos/update', [AdminPerfilesPublicosController::class, 'update']);
$router->post('/admin/perfiles-publicos/toggle', [AdminPerfilesPublicosController::class, 'toggleVisibility']);
$router->post('/admin/perfiles-publicos/bulk', [AdminPerfilesPublicosController::class, 'bulkVisibility']);

$router->get('/admin/registros', [AdminRegistrosController::class, 'index']);
$router->post('/admin/registros/aprobar', [AdminRegistrosController::class, 'aprobar']);
$router->post('/admin/registros/rechazar', [AdminRegistrosController::class, 'rechazar']);
$router->get('/admin/certificado-preview', [AdminRegistrosController::class, 'certificadoPreview']);

$router->get('/admin/calendario', [AdminCalendarioController::class, 'index']);
$router->get('/admin/calendario/get-eventos', [AdminCalendarioController::class, 'getEventos']);
$router->post('/admin/calendario/create', [AdminCalendarioController::class, 'store']);
$router->post('/admin/calendario/update', [AdminCalendarioController::class, 'update']);
$router->post('/admin/calendario/delete', [AdminCalendarioController::class, 'delete']);

$router->get('/admin/galeria', [AdminGaleriaController::class, 'index']);
$router->get('/admin/galeria/crear', [AdminGaleriaController::class, 'crear']);
$router->post('/admin/galeria/store', [AdminGaleriaController::class, 'store']);
$router->post('/admin/galeria/delete', [AdminGaleriaController::class, 'delete']);

// Maestro / Instructores
$router->get('/maestro/dashboard', [MaestroDashboardController::class, 'index']);
$router->get('/maestro/alumnos', [MaestroAlumnosController::class, 'index']);
$router->get('/maestro/alumnos/{id}', [MaestroAlumnosController::class, 'show']);
$router->get('/maestro/solicitudes-ascenso', [MaestroSolicitudesAscensoController::class, 'index']);
$router->post('/maestro/solicitudes-ascenso/create', [MaestroSolicitudesAscensoController::class, 'store']);
$router->post('/maestro/solicitudes-ascenso/aprobar', [MaestroSolicitudesAscensoController::class, 'aprobar']);
$router->post('/maestro/solicitudes-ascenso/rechazar', [MaestroSolicitudesAscensoController::class, 'rechazar']);
$router->get('/maestro/solicitudes-ascenso/certificado', [MaestroSolicitudesAscensoController::class, 'certificado']);

$router->dispatch();
