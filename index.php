<?php
// Main Entry Point

require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Helpers/url_helper.php';
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Core/Security.php';
require_once __DIR__ . '/app/Config/Roles.php';

// Load Models
require_once __DIR__ . '/app/Models/Sede.php';
require_once __DIR__ . '/app/Models/Nivel.php';
require_once __DIR__ . '/app/Models/Teoria.php';
require_once __DIR__ . '/app/Models/Usuario.php';

// Load Controllers
require_once __DIR__ . '/app/Controllers/Web/InicioController.php';
require_once __DIR__ . '/app/Controllers/Web/SedesController.php';
require_once __DIR__ . '/app/Controllers/Administracion/AscensosController.php';
require_once __DIR__ . '/app/Controllers/Administracion/SedesController.php';
require_once __DIR__ . '/app/Controllers/Administracion/MiembrosController.php';
require_once __DIR__ . '/app/Controllers/Administracion/RegistrosController.php';
require_once __DIR__ . '/app/Controllers/Administracion/DashboardController.php';
require_once __DIR__ . '/app/Controllers/Autenticacion/AutenticacionController.php';
require_once __DIR__ . '/app/Controllers/Web/PaginaController.php';
require_once __DIR__ . '/app/Controllers/Web/InstructoresController.php';
require_once __DIR__ . '/app/Controllers/Web/GaleriaController.php';
require_once __DIR__ . '/app/Controllers/Estudiante/DashboardController.php';
require_once __DIR__ . '/app/Controllers/Estudiante/EstudioController.php';

use App\Core\Router;
use App\Models\Teoria;
use App\Models\Nivel;
use App\Models\Sede;
use App\Models\Usuario;
use App\Controllers\Web\InicioController;
use App\Controllers\Administracion\AscensosController;
use App\Controllers\Web\SedesController as WebSedesController;
use App\Controllers\Administracion\SedesController as AdminSedesController;
use App\Controllers\Administracion\MiembrosController;
use App\Controllers\Administracion\RegistrosController;
use App\Controllers\Administracion\DashboardController;
use App\Controllers\Autenticacion\AutenticacionController;
use App\Controllers\Web\PaginaController;
use App\Controllers\Web\InstructoresController;
use App\Controllers\Web\GaleriaController;
use App\Controllers\Estudiante\DashboardController as StudentDashboardController;
use App\Controllers\Estudiante\EstudioController as StudentEstudioController;

// Initialize Session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load Core logic for Role/Session if required by views
// require_once __DIR__ . '/session_security.php'; // Optional, controller handles auth

$router = new Router();

// Define Routes - Web
$router->get('/', [InicioController::class, 'portal']);
$router->get('/portal', [InicioController::class, 'portal']);
$router->get('/inicio', [InicioController::class, 'index']);
$router->get('/index.php', [InicioController::class, 'index']);
$router->get('/nosotros', [PaginaController::class, 'nosotros']);
$router->get('/sedes', [WebSedesController::class, 'index']);
$router->get('/instructores', [InstructoresController::class, 'index']);
$router->get('/galeria', [GaleriaController::class, 'index']);

// Student Routes
$router->get('/estudiante/dashboard', [StudentDashboardController::class, 'index']);
$router->get('/estudiante/estudio', [StudentEstudioController::class, 'index']);
$router->post('/ascensos/toggle', [StudentEstudioController::class, 'toggle']); // Keep old endpoint for JS compatibility

// Define Routes - Auth
$router->get('/login', [AutenticacionController::class, 'loginForm']);
$router->post('/login/process', [AutenticacionController::class, 'login']);
$router->get('/logout', [AutenticacionController::class, 'logout']);
$router->get('/registro', [AutenticacionController::class, 'registroForm']);
$router->post('/registro/process', [AutenticacionController::class, 'processRegistro']);

// Define Routes - Admin Dashboard
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// Define Routes - Admin
$router->get('/admin/ascensos', [AscensosController::class, 'index']);
$router->post('/admin/ascensos/create', [AscensosController::class, 'store']);
$router->post('/admin/ascensos/update', [AscensosController::class, 'update']);
$router->post('/admin/ascensos/delete', [AscensosController::class, 'delete']);

// Define Routes - Admin Sedes
$router->get('/admin/sedes', [AdminSedesController::class, 'index']);
$router->post('/admin/sedes/create', [AdminSedesController::class, 'store']);
$router->post('/admin/sedes/update', [AdminSedesController::class, 'update']);
$router->post('/admin/sedes/delete', [AdminSedesController::class, 'delete']);

// Define Routes - Admin Miembros
$router->get('/admin/miembros', [MiembrosController::class, 'index']);
$router->post('/admin/miembros/create', [MiembrosController::class, 'store']);
$router->post('/admin/miembros/update', [MiembrosController::class, 'update']);
$router->post('/admin/miembros/delete', [MiembrosController::class, 'delete']);

// Define Routes - Admin Registros (Solicitudes)
$router->get('/admin/registros', [RegistrosController::class, 'index']);
$router->post('/admin/registros/aprobar', [RegistrosController::class, 'aprobar']);
$router->post('/admin/registros/rechazar', [RegistrosController::class, 'rechazar']);


// Dispatch
$router->dispatch();