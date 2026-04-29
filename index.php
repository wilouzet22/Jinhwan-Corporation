<?php
/**
 * ============================================================
 * PUNTO DE ENTRADA PRINCIPAL (Front Controller)
 * ============================================================
 * Este archivo es el único punto de acceso a toda la aplicación.
 * Todas las peticiones HTTP son redirigidas aquí gracias al
 * archivo .htaccess. Aquí se cargan las dependencias, se
 * registran las rutas y se despacha la petición al controlador
 * correspondiente.
 * ============================================================
 */

// ── CARGA DE ARCHIVOS NÚCLEO ──────────────────────────────────
// Configuración de la base de datos (conexión MySQL)
require_once __DIR__ . '/app/Config/Database.php';

// Helper de URLs: funciones base_url() y asset()
require_once __DIR__ . '/app/Helpers/url_helper.php';

// Enrutador: resuelve qué controlador/método atender según la URL
require_once __DIR__ . '/app/Core/Router.php';

// Controlador base: método view() y redirect() para todos los controladores
require_once __DIR__ . '/app/Core/Controller.php';

// Modelo base: proporciona la conexión $db a todos los modelos
require_once __DIR__ . '/app/Core/Model.php';

// Clase de Seguridad: manejo de sesiones, timeouts, User-Agent, regeneración
require_once __DIR__ . '/app/Core/Security.php';

// Constantes de roles (ADMINISTRADOR=1, MAESTRO=2, ESTUDIANTE=3)
require_once __DIR__ . '/app/Config/Roles.php';

// ── CARGA DE MODELOS ──────────────────────────────────────────
// Modelo de Sedes: CRUD sobre la tabla 'sedes'
require_once __DIR__ . '/app/Models/Sede.php';

// Modelo de Niveles/Grados: obtiene la lista de cinturones de 'grados'
require_once __DIR__ . '/app/Models/Nivel.php';

// Modelo de Teoría: gestiona el contenido teórico de ascensos
require_once __DIR__ . '/app/Models/Teoria.php';

// Modelo de Usuario: CRUD de miembros + credenciales en 'userlog'
require_once __DIR__ . '/app/Models/Usuario.php';

// ── CARGA DE CONTROLADORES WEB (sitio público) ────────────────
require_once __DIR__ . '/app/Controllers/Web/InicioController.php';       // Página de inicio y portal
require_once __DIR__ . '/app/Controllers/Web/SedesController.php';        // Vista pública de sedes
require_once __DIR__ . '/app/Controllers/Web/PaginaController.php';       // Página "Nosotros"
require_once __DIR__ . '/app/Controllers/Web/InstructoresController.php'; // Página de instructores
require_once __DIR__ . '/app/Controllers/Web/GaleriaController.php';      // Galería de imágenes

// ── CARGA DE CONTROLADORES DE ADMINISTRACIÓN ─────────────────
require_once __DIR__ . '/app/Controllers/Administracion/AscensosController.php';  // CRUD de teorías/ascensos
require_once __DIR__ . '/app/Controllers/Administracion/SedesController.php';      // CRUD de sedes (admin)
require_once __DIR__ . '/app/Controllers/Administracion/MiembrosController.php';  // CRUD de miembros
require_once __DIR__ . '/app/Controllers/Administracion/RegistrosController.php'; // Aprobación de solicitudes
require_once __DIR__ . '/app/Controllers/Administracion/DashboardController.php'; // Panel estadístico admin

// ── CARGA DE CONTROLADORES DE AUTENTICACIÓN ──────────────────
require_once __DIR__ . '/app/Controllers/Autenticacion/AutenticacionController.php'; // Login, registro, logout

// ── CARGA DE CONTROLADORES DE ESTUDIANTE ─────────────────────
require_once __DIR__ . '/app/Controllers/Estudiante/DashboardController.php'; // Dashboard del alumno
require_once __DIR__ . '/app/Controllers/Estudiante/EstudioController.php';   // Módulo de estudio teórico

// ── IMPORTACIÓN DE NAMESPACES (alias) ────────────────────────
use App\Core\Router;
use App\Models\Teoria;
use App\Models\Nivel;
use App\Models\Sede;
use App\Models\Usuario;
use App\Controllers\Web\InicioController;
use App\Controllers\Administracion\AscensosController;

// Alias para evitar conflicto de nombres entre Web\SedesController y Admin\SedesController
use App\Controllers\Web\SedesController as WebSedesController;
use App\Controllers\Administracion\SedesController as AdminSedesController;

use App\Controllers\Administracion\MiembrosController;
use App\Controllers\Administracion\RegistrosController;
use App\Controllers\Administracion\DashboardController;
use App\Controllers\Autenticacion\AutenticacionController;
use App\Controllers\Web\PaginaController;
use App\Controllers\Web\InstructoresController;
use App\Controllers\Web\GaleriaController;

// Alias para los controladores de Estudiante (evitar conflicto con DashboardController de Admin)
use App\Controllers\Estudiante\DashboardController as StudentDashboardController;
use App\Controllers\Estudiante\EstudioController as StudentEstudioController;

// ── INICIO DE SESIÓN ─────────────────────────────────────────
// Se inicia la sesión PHP solo si aún no está activa.
// La clase Security también puede iniciarla, pero se garantiza aquí
// para que esté disponible antes de que cualquier controlador la use.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── INSTANCIA DEL ENRUTADOR ──────────────────────────────────
$router = new Router();

// ────────────────────────────────────────────────────────────
// DEFINICIÓN DE RUTAS – SITIO PÚBLICO (Web)
// Accesibles sin autenticación
// ────────────────────────────────────────────────────────────

// Ruta raíz → muestra el portal de bienvenida
$router->get('/', [InicioController::class, 'portal']);

// /portal → misma vista del portal de bienvenida
$router->get('/portal', [InicioController::class, 'portal']);

// /inicio → página de inicio principal del sitio
$router->get('/inicio', [InicioController::class, 'index']);

// Compatibilidad con acceso directo a index.php
$router->get('/index.php', [InicioController::class, 'index']);

// /nosotros → página institucional
$router->get('/nosotros', [PaginaController::class, 'nosotros']);

// /sedes → lista pública de sedes (sin autenticación)
$router->get('/sedes', [WebSedesController::class, 'index']);

// /instructores → lista de instructores
$router->get('/instructores', [InstructoresController::class, 'index']);

// /galeria → galería de imágenes
$router->get('/galeria', [GaleriaController::class, 'index']);

// ────────────────────────────────────────────────────────────
// DEFINICIÓN DE RUTAS – ESTUDIANTE
// Requieren sesión activa (verifySession en constructor)
// ────────────────────────────────────────────────────────────

// /estudiante/dashboard → panel personal del alumno
$router->get('/estudiante/dashboard', [StudentDashboardController::class, 'index']);

// /estudiante/estudio → módulo de contenido teórico por nivel
$router->get('/estudiante/estudio', [StudentEstudioController::class, 'index']);

// /ascensos/toggle → endpoint AJAX para marcar/desmarcar favoritos
// Se mantiene la URL antigua por compatibilidad con el JavaScript del front-end
$router->post('/ascensos/toggle', [StudentEstudioController::class, 'toggle']);

// ────────────────────────────────────────────────────────────
// DEFINICIÓN DE RUTAS – AUTENTICACIÓN
// ────────────────────────────────────────────────────────────

// Formulario de login (GET) y procesamiento (POST)
$router->get('/login', [AutenticacionController::class, 'loginForm']);
$router->post('/login/process', [AutenticacionController::class, 'login']);

// Cierre de sesión
$router->get('/logout', [AutenticacionController::class, 'logout']);

// Formulario de registro público (GET) y procesamiento (POST)
$router->get('/registro', [AutenticacionController::class, 'registroForm']);
$router->post('/registro/process', [AutenticacionController::class, 'processRegistro']);

// ────────────────────────────────────────────────────────────
// DEFINICIÓN DE RUTAS – ADMINISTRADOR (PANEL DE CONTROL)
// Requieren sesión activa + rol Administrador
// ────────────────────────────────────────────────────────────

// Panel principal con estadísticas
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// CRUD de Ascensos / Contenido Teórico
$router->get('/admin/ascensos', [AscensosController::class, 'index']);
$router->post('/admin/ascensos/create', [AscensosController::class, 'store']);
$router->post('/admin/ascensos/update', [AscensosController::class, 'update']);
$router->post('/admin/ascensos/delete', [AscensosController::class, 'delete']);

// CRUD de Sedes (área administrativa)
$router->get('/admin/sedes', [AdminSedesController::class, 'index']);
$router->post('/admin/sedes/create', [AdminSedesController::class, 'store']);
$router->post('/admin/sedes/update', [AdminSedesController::class, 'update']);
$router->post('/admin/sedes/delete', [AdminSedesController::class, 'delete']);

// CRUD de Miembros
$router->get('/admin/miembros', [MiembrosController::class, 'index']);
$router->post('/admin/miembros/create', [MiembrosController::class, 'store']);
$router->post('/admin/miembros/update', [MiembrosController::class, 'update']);
$router->post('/admin/miembros/delete', [MiembrosController::class, 'delete']);

// Gestión de solicitudes de registro (aprobar / rechazar)
$router->get('/admin/registros', [RegistrosController::class, 'index']);
$router->post('/admin/registros/aprobar', [RegistrosController::class, 'aprobar']);
$router->post('/admin/registros/rechazar', [RegistrosController::class, 'rechazar']);

// ── DESPACHO ────────────────────────────────────────────────
// El enrutador compara la URL actual con las rutas registradas
// y llama al método del controlador correspondiente.
// Si no encuentra coincidencia, devuelve HTTP 404.
$router->dispatch();