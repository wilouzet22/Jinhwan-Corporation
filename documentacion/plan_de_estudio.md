# Plan de Estudio: Proyecto Jinwha Corporation

Este documento detalla la ruta estructurada y lógica para estudiar, analizar y comprender el proyecto **Jinwha Corporation** desde cero. El objetivo es que entiendas cada línea, función, variable y detalle arquitectónico del sistema.

Como el proyecto utiliza un patrón **MVC (Modelo-Vista-Controlador)** propio (sin frameworks comerciales), el enfoque de estudio será **de adentro hacia afuera**, es decir, desde el núcleo de la aplicación hasta las interfaces y scripts en el navegador.

---

## FASE 1: El Punto de Entrada y la Configuración (El "Cimiento")
Antes de ver cómo interactúan las piezas, debes entender cómo inicia la aplicación y cómo se conecta a sus recursos principales.

### 1.1 `index.php` (Directorio Raíz)
- **Por qué estudiarlo:** Es el Front Controller. Todas las peticiones web pasan por aquí.
- **Qué aprender:** 
  - Cómo se cargan las dependencias (`require_once`).
  - Definición de namespaces y alias (`use`).
  - Iniciación de sesión (`session_start()`).
  - Declaración y uso de rutas (`$router->get(...)`, `$router->post(...)`).
  - Despacho final de la ruta (`$router->dispatch()`).

### 1.2 Directorio `app/Config/`
- **`app/Config/Database.php`**: Entender cómo se aplica el patrón *Singleton* para conectarse a la base de datos (MySQLi) usando variables de entorno o constantes. Aquí ves la creación de la variable estática `$instance` y el método `getConnection()`.
- **`app/Config/Roles.php`**: Comprender el sistema de permisos. Verás constantes definidas para diferentes niveles de usuario (ADMINISTRADOR = 1, MAESTRO = 2, ESTUDIANTE = 3).

---

## FASE 2: El Núcleo de la Aplicación (El "Motor")
Aquí es donde reside la "magia" del MVC personalizado.

### 2.1 `app/Core/Router.php`
- **Por qué estudiarlo:** Es el responsable de "leer" la URL (ej. `/login`) y decidir qué controlador y método se deben ejecutar.
- **Qué aprender:** Cómo maneja los arrays de rutas GET y POST, cómo extrae la URI y cómo lanza (instancia) la clase y llama al método si existe.

### 2.2 `app/Core/Security.php`
- **Por qué estudiarlo:** Es vital para el manejo seguro de la sesión del usuario.
- **Qué aprender:** Ver cómo previene ataques (ej. Hijacking de sesiones revisando el `User-Agent`), manejo de timeouts (cierre por inactividad) y regeneración del ID de sesión para prevenir Session Fixation.

### 2.3 `app/Core/Controller.php`
- **Por qué estudiarlo:** Es la clase base para todos los controladores.
- **Qué aprender:** El método `view()` que incluye archivos HTML/PHP de la carpeta Views, y métodos útiles como `redirect()` y manejo de datos compartidos a la vista.

### 2.4 `app/Core/Model.php`
- **Por qué estudiarlo:** Es la clase base para consultar la base de datos.
- **Qué aprender:** Verás cómo invoca a `Database::getInstance()->getConnection()` para que cualquier modelo que herede de esta clase ya tenga acceso a `$this->db`.

---

## FASE 3: Interacción con la Base de Datos (Los "Modelos")
Una vez que entiendes la clase base `Model.php`, pasaremos a las clases específicas de datos. Aquí entenderás cómo se estructuran las consultas SQL y los parámetros (prepared statements).

1. **`app/Models/Usuario.php`**: Lógica de autenticación, verificación de credenciales, y CRUD de usuarios/miembros. Entender cómo guarda contraseñas y cómo las verifica (`password_verify` o similar).
2. **`app/Models/Nivel.php`**: Consultas básicas para obtener los niveles (cinturones/grados).
3. **`app/Models/Sede.php`**: Operaciones CRUD (Create, Read, Update, Delete) sobre las sedes.
4. **`app/Models/Teoria.php`**: Lógica fuerte relacionada con el material de estudio y los ascensos.
5. **`app/Models/jinhwa_corporation.sql`**: (Importante) Leer el esquema SQL para visualizar mentalmente las tablas y sus relaciones (Entidad-Relación).

---

## FASE 4: La Lógica de Negocio y Flujo (Los "Controladores")
Aprenderás cómo los Controladores actúan como mediadores: reciben los datos del Router, solicitan información al Modelo, y se la envían a la Vista.

### 4.1 Controladores Web y Autenticación (Públicos)
- **`app/Controllers/Web/InicioController.php`**: El portal y la portada pública.
- **`app/Controllers/Autenticacion/AutenticacionController.php`**: *¡Crítico!* Entender línea a línea cómo sanitiza el input (POST) del usuario, verifica credenciales usando `Usuario.php`, y cómo guarda los datos del usuario en la `$_SESSION` y en el objeto `Security`.

### 4.2 Controladores del Estudiante
- **`app/Controllers/Estudiante/DashboardController.php`**: Panel principal. Fíjate cómo verifica la sesión en su constructor (sólo permite acceso al rol 3).
- **`app/Controllers/Estudiante/EstudioController.php`**: Cómo carga el temario y cómo responde a las peticiones AJAX (ej. el método `toggle()` que devuelve JSON).

### 4.3 Controladores de Administración (Protegidos)
- **`app/Controllers/Administracion/AscensosController.php`, `SedesController.php`, `MiembrosController.php`**: Estos controladores aplican lógica transaccional y responden tanto con redirecciones HTML como con respuestas JSON. Aquí aprenderás el manejo completo de Formularios POST -> Validación -> Modelo -> Redirección.

---

## FASE 5: La Lógica de Frontend (Los "Scripts JavaScript")
El frontend no es solo HTML y CSS; el sistema hace un fuerte uso de JavaScript modular para hacer la página interactiva sin tener que recargar.

### 5.1 Elementos Globales
- **`public/js/modules/navigation.js`**: Manejo de la barra de navegación responsiva, comportamiento del header al hacer scroll (sticky header).
- **`public/js/modules/index-slider.js`**: (Si aplica) El carrusel de imágenes de la página inicial.

### 5.2 Interactividad del Estudiante (Asíncrona / Fetch API)
- **`public/js/modules/estudiante-ascensos.js`**: *¡Altamente importante!* Aquí estudiaremos cómo JavaScript realiza peticiones `fetch()` asíncronas al endpoint `/ascensos/toggle`. Veremos cómo lee las respuestas JSON del backend y cómo manipula el DOM (el HTML) "al vuelo" para mostrar u ocultar elementos o cambiar estados.

### 5.3 Paneles de Administración (Manipulación del DOM y Formularios)
- **`public/js/modules/administracion-miembros.js`**
- **`public/js/modules/administracion-sedes.js`**
- **`public/js/modules/administracion-ascensos.js`**
- **Qué aprender aquí**: 
  - Cómo JavaScript "escucha" los eventos de `submit` en los formularios para evitar que la página recargue (`e.preventDefault()`).
  - Cómo recoge datos, los envía por `fetch()` o `XMLHttpRequest` y cómo procesa la respuesta para lanzar "Modales" (alertas) de éxito o de error.

---

## FASE 6: Las Interfaces y Diseño (Las "Vistas")
Finalmente, uniremos todo estudiando las Vistas.

- **Directorio `app/Views/`** (y subdirectorios): 
  - Observaremos cómo se construyen layouts maestros (ej. un `header.php` y `footer.php` comunes).
  - Cómo el Controlador pasa variables (ej. `$datos = ['nombre' => 'Juan']`) y cómo estas se imprimen en el HTML usando `<?= htmlspecialchars($datos['nombre']) ?>`.
- **CSS (`public/styles/`)**: Revisión a vista de pájaro de cómo se estructuran las hojas de estilo (variables, clases utilitarias y componentes) para cumplir con el estándar dictado en `ia.txt` de interfaces "profesionales".

---

## ¿Cómo ejecutaremos este plan de estudio?

Nuestra metodología será:
1. **Paso a paso, archivo por archivo:** Siguiendo este orden rigurosamente.
2. **Análisis profundo de código:** Explicación línea por línea (por qué existe cada variable y cómo viaja la información desde la línea A a la línea B).
3. **Resúmenes de consolidación:** Al finalizar cada archivo, haremos una pausa para asegurar la comprensión total antes de avanzar al siguiente.
