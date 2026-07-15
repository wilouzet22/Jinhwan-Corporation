<?php
/**
 * ============================================================
 * RUTEADOR (Router Dispatcher)
 * ============================================================
 * Extraído de index.php para seguir la guía proyectoMVC-master.
 * Gestiona todas las rutas de la aplicación.
 * ============================================================
 */

// ── CARGA DE CONTROLADORES ──────────────────────────────────
require_once __DIR__ . '/controladores/Web/InicioController.php';
require_once __DIR__ . '/controladores/Web/SedesController.php';
require_once __DIR__ . '/controladores/Web/PaginaController.php';
require_once __DIR__ . '/controladores/Web/MiembrosController.php';
require_once __DIR__ . '/controladores/Web/GaleriaController.php';

require_once __DIR__ . '/controladores/Administracion/AscensosController.php';
require_once __DIR__ . '/controladores/Administracion/SedesController.php';
require_once __DIR__ . '/controladores/Administracion/MiembrosController.php';
require_once __DIR__ . '/controladores/Administracion/PerfilesPublicosController.php';
require_once __DIR__ . '/controladores/Administracion/RegistrosController.php';
require_once __DIR__ . '/controladores/Administracion/DashboardController.php';
require_once __DIR__ . '/controladores/Administracion/CalendarioController.php';
require_once __DIR__ . '/controladores/Administracion/ReportesController.php';
require_once __DIR__ . '/controladores/Administracion/GaleriaController.php';

require_once __DIR__ . '/controladores/Autenticacion/AutenticacionController.php';

require_once __DIR__ . '/controladores/Usuario/CalendarioController.php';
require_once __DIR__ . '/controladores/Usuario/PerfilController.php';

require_once __DIR__ . '/controladores/Estudiante/DashboardController.php';
require_once __DIR__ . '/controladores/Estudiante/EstudioController.php';
require_once __DIR__ . '/controladores/Estudiante/HistorialController.php';

require_once __DIR__ . '/controladores/Maestro/DashboardController.php';
require_once __DIR__ . '/controladores/Maestro/AlumnosController.php';
require_once __DIR__ . '/controladores/Maestro/SolicitudesAscensoController.php';

// ── IMPORTACIÓN DE NAMESPACES ───────────────────────────────
use App\Core\Router;
use App\Controllers\Web\InicioController;
use App\Controllers\Web\PaginaController;
use App\Controllers\Web\MiembrosController as WebMiembrosController;
use App\Controllers\Web\GaleriaController;
use App\Controllers\Web\SedesController as WebSedesController;
use App\Controllers\Administracion\SedesController as AdminSedesController;
use App\Controllers\Administracion\AscensosController;
use App\Controllers\Administracion\MiembrosController;
use App\Controllers\Administracion\PerfilesPublicosController;
use App\Controllers\Administracion\RegistrosController;
use App\Controllers\Administracion\DashboardController;
use App\Controllers\Administracion\CalendarioController as AdminCalendarioController;
use App\Controllers\Administracion\ReportesController;
use App\Controllers\Administracion\GaleriaController as AdminGaleriaController;
use App\Controllers\Autenticacion\AutenticacionController;
use App\Controllers\Usuario\CalendarioController as UsuarioCalendarioController;
use App\Controllers\Usuario\PerfilController as SharedPerfilController;
use App\Controllers\Estudiante\DashboardController as StudentDashboardController;
use App\Controllers\Estudiante\EstudioController as StudentEstudioController;
use App\Controllers\Estudiante\HistorialController as StudentHistorialController;
use App\Controllers\Maestro\DashboardController as MaestroDashboardController;
use App\Controllers\Maestro\AlumnosController as MaestroAlumnosController;
use App\Controllers\Maestro\SolicitudesAscensoController as MaestroSolicitudesController;

// ── INSTANCIA Y RUTAS ───────────────────────────────────────
$router = new Router();

// SITIO PÚBLICO
$router->get('/', [InicioController::class, 'portal']);
$router->get('/portal', [InicioController::class, 'portal']);
$router->get('/inicio', [InicioController::class, 'index']);
$router->get('/nosotros', [PaginaController::class, 'nosotros']);
$router->get('/sedes', [WebSedesController::class, 'index']);
$router->get('/miembros', [WebMiembrosController::class, 'index']);
$router->get('/galeria', [GaleriaController::class, 'index']);

// ESTUDIANTE
$router->get('/estudiante/dashboard', [StudentDashboardController::class, 'index']);
$router->get('/estudiante/estudio', [StudentEstudioController::class, 'index']);
$router->post('/ascensos/toggle', [StudentEstudioController::class, 'toggle']);
$router->get('/estudiante/historial', [StudentHistorialController::class, 'index']);
$router->get('/estudiante/perfil', [SharedPerfilController::class, 'index']);
$router->post('/estudiante/perfil/update', [SharedPerfilController::class, 'update']);
$router->get('/usuario/perfil', [SharedPerfilController::class, 'index']);
$router->post('/usuario/perfil/update', [SharedPerfilController::class, 'update']);
$router->get('/perfil', [SharedPerfilController::class, 'index']);
$router->post('/perfil/update', [SharedPerfilController::class, 'update']);

// AUTENTICACIÓN
$router->get('/login', [AutenticacionController::class, 'loginForm']);
$router->post('/login/process', [AutenticacionController::class, 'login']);
$router->get('/logout', [AutenticacionController::class, 'logout']);
$router->get('/registro', [AutenticacionController::class, 'registroForm']);
$router->post('/registro/process', [AutenticacionController::class, 'processRegistro']);
$router->get('/registro/completar', [AutenticacionController::class, 'completarRegistroForm']);
$router->post('/registro/completar/process', [AutenticacionController::class, 'processCompletarRegistro']);

// ADMINISTRADOR
$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->get('/admin/ascensos', [AscensosController::class, 'index']);
$router->post('/admin/ascensos/create', [AscensosController::class, 'store']);
$router->post('/admin/ascensos/update', [AscensosController::class, 'update']);
$router->post('/admin/ascensos/delete', [AscensosController::class, 'delete']);

$router->get('/admin/sedes', [AdminSedesController::class, 'index']);
$router->post('/admin/sedes/create', [AdminSedesController::class, 'store']);
$router->post('/admin/sedes/update', [AdminSedesController::class, 'update']);
$router->post('/admin/sedes/delete', [AdminSedesController::class, 'delete']);

$router->get('/admin/miembros', [MiembrosController::class, 'index']);
$router->post('/admin/miembros/create', [MiembrosController::class, 'store']);
$router->post('/admin/miembros/update', [MiembrosController::class, 'update']);
$router->post('/admin/miembros/delete', [MiembrosController::class, 'delete']);
$router->post('/admin/miembros/delete-bulk', [MiembrosController::class, 'deleteBulk']);

$router->get('/admin/perfiles-publicos', [PerfilesPublicosController::class, 'index']);
$router->post('/admin/perfiles-publicos/update', [PerfilesPublicosController::class, 'update']);

$router->get('/admin/registros', [RegistrosController::class, 'index']);
$router->post('/admin/registros/aprobar', [RegistrosController::class, 'aprobar']);
$router->post('/admin/registros/rechazar', [RegistrosController::class, 'rechazar']);
$router->post('/admin/registros/aprobar-ascenso', [RegistrosController::class, 'aprobarAscenso']);
$router->post('/admin/registros/rechazar-ascenso', [RegistrosController::class, 'rechazarAscenso']);

$router->get('/admin/calendario', [AdminCalendarioController::class, 'index']);
$router->get('/admin/calendario/get-eventos', [AdminCalendarioController::class, 'getEventos']);
$router->post('/admin/calendario/create', [AdminCalendarioController::class, 'store']);
$router->post('/admin/calendario/update', [AdminCalendarioController::class, 'update']);
$router->post('/admin/calendario/delete', [AdminCalendarioController::class, 'delete']);

$router->get('/admin/reportes', [ReportesController::class, 'index']);

$router->get('/admin/galeria', [AdminGaleriaController::class, 'index']);
$router->get('/admin/galeria/crear', [AdminGaleriaController::class, 'crear']);
$router->post('/admin/galeria/store', [AdminGaleriaController::class, 'store']);
$router->post('/admin/galeria/delete', [AdminGaleriaController::class, 'delete']);

// USUARIO (Estudiantes y Maestros)
$router->get('/usuario/calendario', [UsuarioCalendarioController::class, 'index']);
$router->get('/usuario/calendario/get-eventos', [UsuarioCalendarioController::class, 'getEventos']);

// MAESTRO
$router->get('/maestro/dashboard', [MaestroDashboardController::class, 'index']);
$router->get('/maestro/alumnos', [MaestroAlumnosController::class, 'index']);
$router->get('/maestro/solicitudes-ascenso', [MaestroSolicitudesController::class, 'index']);
$router->post('/maestro/solicitudes-ascenso/create', [MaestroSolicitudesController::class, 'store']);

// DESPACHO
$router->dispatch();
