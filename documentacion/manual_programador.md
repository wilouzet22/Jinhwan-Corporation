# Manual del Programador - Jinhwa Corporation
### Documentacion Tecnica del Sistema de Gestion del Club de Taekwondo

> Version: 1.0  Fecha: Agosto 2026  Tecnologia: PHP 8+ sin framework (MVC puro)

---

## Tabla de Contenidos

1. Vision General del Sistema
2. Requisitos Previos y Configuracion del Entorno
3. Estructura de Directorios
4. Arquitectura MVC
5. Ciclo de Vida de una Peticion
6. Capa Core - Clases Base
7. Sistema de Enrutamiento (Router)
8. Controladores
9. Modelos
10. Vistas
11. Sistema de Seguridad
12. Base de Datos - Esquema Normalizado
13. Como Agregar una Nueva Funcionalidad
14. Convenciones de Codigo
15. Solucion de Problemas Comunes

---

## 1. Vision General del Sistema

El sistema Jinhwa Corporation es una aplicacion web en PHP puro siguiendo el patron MVC (Modelo - Vista - Controlador) sin frameworks externos.

| Capa | Tecnologia |
|------|-----------|
| Backend | PHP 8.x puro |
| Base de datos | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript vanilla |
| Servidor de desarrollo | Apache (Laragon) |
| Enrutamiento | Front Controller Pattern con .htaccess |
| Sesiones | PHP Sessions nativas con capas de seguridad |

---

## 2. Requisitos Previos y Configuracion del Entorno

Software necesario:
- Laragon (incluye Apache 2.4, PHP 8+, MySQL 8.0)
- Editor de codigo: VS Code, PhpStorm, Sublime Text
- Cliente MySQL: HeidiSQL, DBeaver o TablePlus
- Git para control de versiones

### Instalacion

Paso 1: Clonar el repositorio
    git clone <url-repositorio> c:\laragon\www\jinwha

Paso 2: Crear la base de datos en HeidiSQL o consola MySQL:
    CREATE DATABASE jinhwa_corporation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    USE jinhwa_corporation;
    SOURCE c:/laragon/www/jinwha/jinhwa_corporation.sql;

Paso 3: Configurar la conexion en conexion.php:
    private System.Management.Automation.Internal.Host.InternalHost = 'localhost';
    private  = 'root';
    private  = '';
    private  = 'jinhwa_corporation';

Paso 4: Crear el primer administrador ejecutando en el navegador:
    http://localhost/jinwha/crear_administrador.php
    (Eliminar este archivo despues de usarlo)

---

## 3. Estructura de Directorios

    jinwha/
    |-- index.php                  # Front Controller - punto de entrada unico
    |-- ruteador.php               # Registro de todas las rutas
    |-- conexion.php               # Clase Database (Singleton) para MySQL
    |-- .htaccess                  # Redirige todas las peticiones a index.php
    |
    |-- core/                      # Clases nucleo
    |   |-- Router.php
    |   |-- Controller.php
    |   |-- Model.php
    |   |-- Roles.php
    |   |-- Security.php
    |
    |-- controladores/             # Organizados por modulo
    |   |-- Autenticacion/
    |   |-- Web/                   # Portal publico
    |   |-- Administracion/        # Panel admin
    |   |-- Maestro/
    |   |-- Estudiante/
    |   |-- Usuario/               # Modulos compartidos
    |
    |-- modelos/                   # Logica de negocio y SQL
    |-- vistas/                    # Plantillas PHP/HTML
    |-- public/                    # CSS, JS, imagenes
    |-- storage/                   # Archivos subidos (fotos)
    |-- helpers/                   # Funciones utilitarias
    |-- documentacion/             # Esta documentacion
    |-- diagramas/                 # Diagramas ER, Clases, Casos de Uso

---

## 4. Arquitectura MVC

    [Navegador] --> [.htaccess] --> [index.php] --> [ruteador.php]
                                                         |
                                                  [Router::dispatch()]
                                                         |
                                           [XxxController::metodo()]
                                          /                \
                                  [Modelo.php]         [vista/xxx.php]
                                  (consultas SQL)      (HTML + datos)

Responsabilidades:
- Controlador: Recibe HTTP, valida permisos (Security), llama Modelo, pasa datos a Vista
- Modelo: Encapsula todas las consultas SQL. Hereda de App\Core\Model
- Vista: Archivo PHP que genera HTML. Recibe variables via extract(). Sin logica de negocio

---

## 5. Ciclo de Vida de una Peticion

Ejemplo: el usuario accede a /admin/miembros

1. Apache recibe la peticion. .htaccess detecta que no es archivo/directorio real y redirige a index.php
2. index.php carga todas las dependencias e incluye ruteador.php
3. ruteador.php contiene: ->get('/admin/miembros', [MiembrosController::class, 'index']);
4. Router::dispatch() lee REQUEST_METHOD y REQUEST_URI, encuentra la ruta y llama MiembrosController::index()
5. El controlador ejecuta: Security::verifySession(), Security::verifyAdmin(), consulta el modelo, llama ->view()
6. La vista genera el HTML con los datos y lo envia al navegador

---

## 6. Capa Core - Clases Base

### Controller.php

Clase base de la que deben heredar todos los controladores.

Metodo view(string , array  = []):
- Carga la vista ubicada en vistas/.php
- Hace extract() para que las variables del array esten disponibles en la vista
- Uso: ->view('administracion/miembros', ['titulo' => 'Miembros']);

Metodo redirect(string ):
- Redirige al path indicado con la base del proyecto
- Uso: ->redirect('/admin/dashboard');

### Model.php

Clase base que inyecta la conexion a la BD en ->db.

    class Model {
        protected ;
        public function __construct() {
            ->db = Database::getInstance()->getConnection();
        }
    }

### Database.php (conexion.php)

Implementa el patron Singleton para garantizar una sola conexion MySQL por peticion.

     = Database::getInstance()->getConnection();
     = ->prepare("SELECT * FROM personas WHERE id_persona = ?");

### Roles.php

    class Roles {
        const ADMINISTRADOR = 'Administracion';
        const MAESTRO       = 'Maestros';
        const PROFESOR      = 'Profesores';
        const MONITOR       = 'Monitores';
        const ESTUDIANTE    = 'Deportistas';
    }

Metodos: Roles::esAdmin(), Roles::esMaestro()

---

## 7. Sistema de Enrutamiento (Router)

### Registro de rutas en ruteador.php:

    ->get('/ruta', [ControladorClase::class, 'metodo']);
    ->post('/ruta/process', [ControladorClase::class, 'metodo']);

### Agregar una nueva ruta:

1. Abrir ruteador.php
2. Agregar require_once del nuevo controlador al inicio del archivo
3. Agregar alias "use" del namespace
4. Registrar la ruta con ->get() o ->post()

Ejemplo:
    require_once __DIR__ . '/controladores/Administracion/NuevoController.php';
    use App\Controllers\Administracion\NuevoController;

    ->get('/admin/nuevo', [NuevoController::class, 'index']);
    ->post('/admin/nuevo/store', [NuevoController::class, 'store']);

---

## 8. Controladores

### Estructura minima de un controlador:

    namespace App\Controllers\Administracion;
    use App\Core\Controller;
    use App\Core\Security;

    class NuevoController extends Controller {

        public function index() {
            Security::verifySession();
            Security::verifyAdmin();

             = ['titulo' => 'Mi Modulo'];
            ->view('administracion/nuevo', );
        }

        public function store() {
            Security::verifySession();
            Security::verifyAdmin();

            if (['REQUEST_METHOD'] !== 'POST') {
                ->redirect('/admin/nuevo');
                return;
            }

             = trim(['nombre'] ?? '');
            // Guardar en BD...
            ->redirect('/admin/nuevo?msg=ok');
        }
    }

### Proteccion de rutas segun rol:

| Rol requerido | Metodo a usar |
|---------------|---------------|
| Sesion activa | Security::verifySession() |
| Administrador | Security::verifyAdmin() |
| Maestro | Security::verifyMaestro() |
| Permiso especifico | Security::verifyPermission('nombre_permiso') |

### Controladores existentes por modulo:

Autenticacion: loginForm, login, registroForm, processRegistro, completarRegistroForm, processCompletarRegistro, logout

Web (portal publico): InicioController (portal, index), PaginaController (nosotros), SedesController (index), MiembrosController (index), GaleriaController (index)

Administracion: DashboardController, MiembrosController (CRUD + deleteBulk), SedesController (CRUD), AscensosController (CRUD), RegistrosController (aprobar/rechazar miembros y ascensos), CalendarioController (CRUD + getEventos), ReportesController (index), GaleriaController (CRUD), PerfilesPublicosController (index, update)

Maestro: DashboardController, AlumnosController, SolicitudesAscensoController (index, store)

Estudiante: DashboardController, EstudioController (index, toggle), HistorialController

Usuario (compartidos): CalendarioController (index, getEventos), PerfilController (index, update)

---

## 9. Modelos

### Estructura de un modelo:

    namespace App\Models;
    use App\Core\Model;

    class MiModelo extends Model {

        public function getAll() {
             = ->db->query("SELECT * FROM mi_tabla ORDER BY id DESC");
            return  ? ->fetch_all(MYSQLI_ASSOC) : [];
        }

        public function create() {
             = ->db->prepare("INSERT INTO mi_tabla (nombre) VALUES (?)");
            ->bind_param("s", ['nombre']);
            ->execute();
            return ->db->insert_id;
        }

        public function update(, ) {
             = ->db->prepare("UPDATE mi_tabla SET nombre = ? WHERE id = ?");
            ->bind_param("si", ['nombre'], );
            return ->execute();
        }

        public function delete() {
             = ->db->prepare("DELETE FROM mi_tabla WHERE id = ?");
            ->bind_param("i", );
            return ->execute();
        }
    }

### Modelos existentes:

| Archivo | Clase | Tabla principal |
|---------|-------|-----------------|
| Usuario.php | Usuario | personas + credenciales |
| Sede.php | Sede | sedes |
| Nivel.php | Nivel | grados |
| Teoria.php | Teoria | teorias |
| Evento.php | Evento | eventos |
| MultimediaGaleria.php | MultimediaGaleria | galeria_multimedia |
| Categoria.php | Categoria | categorias |

### Transacciones para operaciones multitabla:

    ->db->begin_transaction();
    try {
        // operaciones SQL...
        ->db->commit();
    } catch (Exception ) {
        ->db->rollback();
        throw ;
    }

---

## 10. Vistas

### Estructura de una vista:

    // vistas/administracion/nuevo.php
    require_once __DIR__ . '/../layout/header_admin.php';

    <div class="container">
        <h1><?= htmlspecialchars() ?></h1>
        <?php foreach ( as ): ?>
            <p><?= htmlspecialchars(['nombre']) ?></p>
        <?php endforeach; ?>
    </div>

    require_once __DIR__ . '/../layout/footer_admin.php';

### Buenas practicas:
- Usar htmlspecialchars() al imprimir datos del usuario (previene XSS)
- No realizar consultas SQL directas desde la vista
- Incluir siempre el layout (header/footer) correspondiente al modulo
- Los formularios deben enviar a una ruta POST separada

---

## 11. Sistema de Seguridad

La clase Security en core/Security.php implementa 6 capas de proteccion:

1. Verificacion de ID de sesion: Existe id en la sesion
2. Timeout de inactividad: 30 minutos sin actividad = cierre automatico
3. Coincidencia de User-Agent: Previene session hijacking
4. Regeneracion periodica del session_id: Cada 30 minutos
5. Tiempo de login registrado: Requerido para sesiones validas
6. Tiempo maximo de sesion: 8 horas continuas maximas

### Metodos disponibles:

    Security::initSession()          // Inicia sesion PHP si no esta activa
    Security::verifySession()        // Verificacion completa (todas las capas)
    Security::verifyAdmin()          // Solo permite rol Administracion
    Security::verifyMaestro()        // Permite Maestros, Profesores, Monitores
    Security::verifyPermission()   // Verifica permiso extra en JSON
    Security::hasPermission()      // Como verifyPermission pero retorna bool
    Security::startSecureSession() // Inicia sesion segura al hacer login
    Security::logout()               // Destruye sesion completamente

### Variables en sesion tras login exitoso:

| Variable | Tipo | Descripcion |
|----------|------|-------------|
| id | int | id_persona del usuario |
| nombre | string | Nombre completo |
| correo | string | Correo electronico |
| rol_id | string | Rol (ej: 'Administracion') |
| foto_perfil | string|null | Ruta a la foto de perfil |
| permisos_extra | string|null | JSON con permisos adicionales |
| login_time | int | Timestamp inicio de sesion |
| last_activity | int | Timestamp ultima actividad |
| user_agent | string | User-Agent del navegador al login |

---

## 12. Base de Datos - Esquema Normalizado

### Relaciones principales:

    personas (tabla base)
      |-- credenciales (1:1 - autenticacion)
      |-- perfil_deportistas (1:1 - datos deportista)
      |-- perfil_maestros (1:1 - datos maestro)
      |-- sedes (N:1 - cada persona pertenece a una sede)

    solicitudes_ascenso --> personas (deportista + maestro) + grados
    teorias --> grados (N:1)
    eventos (independiente)
    galeria_multimedia (puede referenciar personas)

### Tabla: personas

    id_persona (PK, AUTO_INCREMENT)
    nombre, apellido
    tipo_documento (TI, CC, CE, PP)
    num_doc
    telefono
    id_sede (FK -> sedes)
    foto_perfil
    activo (0=pendiente, 1=activo)
    created_at

### Tabla: credenciales

    id_credencial (PK)
    id_persona (FK -> personas, UNIQUE, ON DELETE CASCADE)
    correo (UNIQUE)
    clave (bcrypt hash)
    rol (Administracion | Maestros | Profesores | Monitores | Deportistas)
    permisos_extra (JSON)

### Tabla: perfil_deportistas

    id_perfil (PK)
    id_persona (FK -> personas, UNIQUE, ON DELETE CASCADE)
    id_grado (FK -> grados)
    id_categoria (FK -> categorias)
    fecha_n (fecha de nacimiento)
    peso, division, ctgc, eps, rh

### Tabla: perfil_maestros

    id_perfil (PK)
    id_persona (FK -> personas, UNIQUE, ON DELETE CASCADE)
    id_grado (FK -> grados)
    descripcion_perfil, logros
    mostrar_en_web (0|1)

### Tabla: sedes

    id_sede (PK), nombre, direccion, telefono, horario

### Tabla: grados

    id_grado (PK), nombre, orden

### Tabla: categorias

    id_categoria (PK), nombre

### Tabla: solicitudes_ascenso

    id_solicitud (PK)
    id_deportista (FK -> personas)
    id_maestro (FK -> personas)
    id_grado_actual, id_grado_solicitado (FK -> grados)
    estado (pendiente | aprobado | rechazado)
    observaciones
    fecha_solicitud, fecha_resolucion

### Tabla: teorias

    id_teoria (PK)
    id_grado (FK -> grados)
    id_tipo_teoria (FK -> tipos_teoria)
    nombre, contenido, url_video

### Tabla: eventos

    id_evento (PK)
    titulo, descripcion
    fecha_inicio, fecha_fin
    created_at

### Tabla: galeria_multimedia

    id_multimedia (PK)
    id_persona (FK nullable)
    url, titulo, descripcion, tipo (video|imagen)
    created_at

### Politica de integridad referencial:

Todas las FK sobre personas.id_persona tienen ON DELETE CASCADE. Eliminar una persona elimina automaticamente sus credenciales, perfil deportista o maestro.

---

## 13. Como Agregar una Nueva Funcionalidad

Ejemplo completo: modulo de "Noticias" para el administrador.

### Paso 1: Crear la tabla en MySQL

    CREATE TABLE noticias (
        id_noticia INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        contenido TEXT,
        publicado TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

### Paso 2: Crear el Modelo (modelos/Noticia.php)

    namespace App\Models;
    use App\Core\Model;

    class Noticia extends Model {
        public function getAll() {
             = ->db->query("SELECT * FROM noticias ORDER BY created_at DESC");
            return  ? ->fetch_all(MYSQLI_ASSOC) : [];
        }
        public function create() {
             = ->db->prepare("INSERT INTO noticias (titulo, contenido) VALUES (?, ?)");
            ->bind_param("ss", ['titulo'], ['contenido']);
            ->execute();
            return ->db->insert_id;
        }
    }

### Paso 3: Crear el Controlador (controladores/Administracion/NoticiasController.php)

    namespace App\Controllers\Administracion;
    use App\Core\Controller;
    use App\Core\Security;
    use App\Models\Noticia;

    class NoticiasController extends Controller {
        public function index() {
            Security::verifySession();
            Security::verifyAdmin();
             = new Noticia();
            ->view('administracion/noticias', ['noticias' => ->getAll()]);
        }
        public function store() {
            Security::verifySession();
            Security::verifyAdmin();
            if (['REQUEST_METHOD'] !== 'POST') {
                ->redirect('/admin/noticias');
                return;
            }
            (new Noticia())->create([
                'titulo'    => trim(['titulo'] ?? ''),
                'contenido' => trim(['contenido'] ?? '')
            ]);
            ->redirect('/admin/noticias?msg=created');
        }
    }

### Paso 4: Crear la Vista (vistas/administracion/noticias.php)

    // Incluir header del layout
    // Incluir formulario POST hacia /admin/noticias/store
    // Iterar  con htmlspecialchars()
    // Incluir footer del layout

### Paso 5: Registrar en ruteador.php

    require_once __DIR__ . '/controladores/Administracion/NoticiasController.php';
    use App\Controllers\Administracion\NoticiasController;

    ->get('/admin/noticias', [NoticiasController::class, 'index']);
    ->post('/admin/noticias/store', [NoticiasController::class, 'store']);

### Paso 6: Incluir el modelo en index.php

    require_once __DIR__ . '/modelos/Noticia.php';

---

## 14. Convenciones de Codigo

| Elemento | Convencion | Ejemplo |
|----------|-----------|---------|
| Clases | PascalCase | AutenticacionController |
| Metodos | camelCase | getAllWithDetails() |
| Variables | camelCase |  |
| Tablas BD | snake_case | perfil_deportistas |
| Columnas BD | snake_case | id_persona, fecha_n |
| Archivos PHP clases | PascalCase | Controller.php |
| Archivos PHP vistas | snake_case | dashboard.php |
| Rutas URL | kebab-case | /admin/perfiles-publicos |

### Namespaces:

    App\Core\           -> core/
    App\Config\         -> conexion.php, Roles.php
    App\Controllers\    -> controladores/
    App\Models\         -> modelos/

### Reglas de Seguridad (OBLIGATORIAS):

- SIEMPRE usar ->prepare() + bind_param() para queries con datos del usuario
- SIEMPRE llamar Security::verifySession() al inicio de metodos protegidos
- SIEMPRE usar htmlspecialchars() al imprimir datos en vistas
- NUNCA confiar en POST o GET sin validar

---

## 15. Solucion de Problemas Comunes

### El sistema redirige siempre a /login

Causa: La sesion no se esta iniciando correctamente.
Solucion: Revisar el archivo debug_login.txt en la raiz. Registra todos los intentos con timestamps.

### Error "View not found"

Causa: El nombre de la vista no coincide con la ruta del archivo.
Solucion: Verificar que el archivo exista en vistas/ y que el path sea correcto.

### Error de conexion a la base de datos

Causa: Credenciales incorrectas o la BD no existe.
Solucion: Revisar conexion.php. Verificar que la BD jinhwa_corporation existe y que Laragon este corriendo.

### Las rutas retornan 404

Causa: mod_rewrite no activo o .htaccess no se procesa.
Solucion:
1. Verificar que mod_rewrite este habilitado en Apache
2. Verificar que AllowOverride All este configurado para /laragon/www
3. Reiniciar Apache en Laragon

### Error access_denied al acceder a un modulo

Causa: El usuario no tiene el rol correcto.
Solucion:
- Revisar que credenciales.rol sea el correcto para ese usuario
- Si es maestro con acceso extendido, verificar credenciales.permisos_extra en JSON

### Error de clave foranea al eliminar un miembro

Causa: Existen registros relacionados sin ON DELETE CASCADE.
Solucion: Revisar las FK en jinhwa_corporation.sql. Todas las FK sobre personas.id_persona deben tener ON DELETE CASCADE.

---

## Apendice: Archivos de Referencia

- Esquema completo de BD: jinhwa_corporation.sql (raiz del proyecto)
- Migracion a nueva estructura: migracion_nueva_estructura.sql
- Diagrama ER: documentacion/diagrama_er_jinhwa.html
- Diagrama de Clases: documentacion/diagrama_clases_jinhwa.html
- Diagrama de Casos de Uso: documentacion/diagrama_casos_uso_jinhwa.html

---

Manual del Programador - Jinhwa Corporation - Version 1.0 - Agosto 2026
