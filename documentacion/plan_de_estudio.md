# Plan de Estudio Detallado: Proyecto Jinwha Corporation

Este documento es tu ruta estructurada paso a paso para estudiar, analizar y comprender el proyecto **Jinwha Corporation** desde cero, asegurando que entiendas cada línea, variable y detalle de la arquitectura del sistema.

Dado que el proyecto utiliza una arquitectura **MVC (Modelo-Vista-Controlador)** propia sin usar frameworks de terceros, el enfoque ideal es estudiar **de adentro hacia afuera**. Empezaremos por la base que levanta la aplicación, pasaremos por el núcleo lógico, los modelos de base de datos, el flujo de peticiones y, por último, la vista y el cliente.

---

## FASE 1: Inicialización y Configuración (Los Cimientos)

El primer paso es entender cómo arranca la aplicación y cómo se conecta a los recursos primarios.

### 1.1 `index.php` (Directorio Raíz)
- **Por qué estudiarlo:** Es el "Front Controller" o punto de entrada único. Toda petición a la web pasa primero por aquí.
- **Qué aprenderás:**
  - Inclusión de dependencias principales (`require_once`).
  - El concepto de mantener un único punto de entrada para mayor seguridad y control.
  - El inicio de sesiones seguras en PHP (`session_start()`).

### 1.2 `conexion.php` (Directorio Raíz)
- **Por qué estudiarlo:** Gestiona el acceso a la base de datos de manera centralizada.
- **Qué aprenderás:**
  - El **Patrón de Diseño Singleton**: Cómo garantizar que solo haya una conexión a MySQL en cada petición, ahorrando recursos.
  - Uso de la extensión `mysqli` en PHP.
  - Configuración de codificación de caracteres (`utf8mb4`).

### 1.3 `helpers/url_helper.php`
- **Por qué estudiarlo:** Son funciones de ayuda (helpers) que se usarán en toda la aplicación.
- **Qué aprenderás:**
  - Definición de funciones globales.
  - Redirecciones en PHP (`header('Location: ...')`) y constantes para URLs base.

---

## FASE 2: El Núcleo del Framework MVC (El Motor)

Aquí reside la lógica que hace que funcione el patrón MVC. Estos archivos están en el directorio `/core`.

### 2.1 `core/Router.php` y `ruteador.php`
- **Por qué estudiarlo:** El enrutador intercepta la URL escrita por el usuario y decide qué código ejecutar.
- **Qué aprenderás:**
  - **Arreglos asociativos en PHP:** Para mapear rutas (ej: `'/login'`) a controladores y métodos.
  - En `core/Router.php` verás cómo se procesa la variable `$_SERVER['REQUEST_URI']`.
  - En `ruteador.php` (Raíz) verás la lista completa de todas las URLs de tu aplicación y hacia dónde apuntan.

### 2.2 `core/Controller.php`
- **Por qué estudiarlo:** Es la clase "padre" de todos los controladores de la aplicación.
- **Qué aprenderás:**
  - **Herencia (POO):** Cómo otras clases extenderán de esta.
  - Cómo un controlador invoca a un Modelo para obtener datos.
  - Cómo invocar una Vista (HTML/PHP) y pasarle datos usando la función `extract()`.

### 2.3 `core/Model.php`
- **Por qué estudiarlo:** Es la clase "padre" para acceder a la base de datos.
- **Qué aprenderás:**
  - Uso de la conexión a base de datos (`$this->db`) inyectada a través de `conexion.php`.

### 2.4 `core/Security.php` y `core/Roles.php`
- **Por qué estudiarlo:** Controlan la seguridad y quién puede ver qué.
- **Qué aprenderás:**
  - Prevención de vulnerabilidades web comunes (fijación de sesión, secuestro de sesión a través del User-Agent).
  - Manejo de roles y permisos mediante constantes o validaciones de sesión.

---

## FASE 3: Capa de Datos (Los Modelos)

Una vez entiendas `core/Model.php`, pasaremos a los modelos específicos de la aplicación en el directorio `/modelos`.

- **Archivos a estudiar:** `Usuario.php`, `Sede.php`, `Nivel.php`, `Teoria.php`.
- **Por qué estudiarlos:** Son los únicos autorizados para ejecutar lenguaje SQL (hablar con la base de datos).
- **Qué aprenderás:**
  - **Consultas CRUD:** Create (INSERT), Read (SELECT), Update, Delete en MySQL.
  - **Sentencias Preparadas (Prepared Statements):** El concepto más vital de seguridad en base de datos para prevenir Inyecciones SQL (`$stmt = $db->prepare(...)`).
  - Hashing de contraseñas (`password_hash`, `password_verify`).

---

## FASE 4: La Lógica de Negocio (Los Controladores)

Están ubicados en `/controladores`. Aquí el sistema toma decisiones lógicas: recibe datos, llama al modelo correspondiente, y entrega el resultado a una vista.

### 4.1 `Autenticacion/AutenticacionController.php`
- **Conceptos clave:** Manejo de formularios de Login/Registro, recepción de variables `$_POST`, validación de datos de entrada, y establecimiento de variables de sesión (`$_SESSION`).

### 4.2 `Web/` (Controladores Públicos)
- Ej: `InicioController.php`, `SedesController.php`.
- **Conceptos clave:** Recopilación de datos públicos para inyectarlos en las páginas informativas de la web.

### 4.3 `Estudiante/` y `Administracion/`
- **Conceptos clave:** 
  - Restricción de acceso en los constructores (`__construct`) comprobando los roles del usuario logueado.
  - Procesamiento de peticiones AJAX (respuestas en formato JSON con `json_encode()`) en lugar de recargar toda la página web.

---

## FASE 5: Interfaces de Usuario (Las Vistas)

Ubicadas en el directorio `/vistas`. Aquí convertimos los datos puros en páginas visualmente atractivas.

- **Por qué estudiarlas:** Es lo que el cliente final ve.
- **Qué aprenderás:**
  - Separación de responsabilidades: La vista no consulta la base de datos, solo muestra variables que le pasó el Controlador (ej. `<?= htmlspecialchars($variable) ?>`).
  - Estructuración de "Layouts" (separar el `header.php` y `footer.php` e incluirlos en otras vistas).
  - Uso de **XSS Protection**: Por qué siempre debemos escapar los datos que imprimimos con `htmlspecialchars()`.

---

## FASE 6: Interactividad y Frontend (JavaScript y CSS)

El último paso es estudiar la carpeta `/public` que contiene los archivos enviados directamente al navegador.

### 6.1 Hojas de Estilo (CSS)
- Entender cómo la aplicación está estructurada visualmente, el uso de variables CSS (si existen) y principios de diseño responsivo.

### 6.2 JavaScript y Fetch API (`/scripts` o `/public/js`)
- **Qué aprenderás:**
  - Cómo hacer la página "dinámica" sin recargar.
  - El uso de la API `fetch()` para enviar peticiones a rutas como `/ascensos/toggle` o procesar un login en segundo plano.
  - Manipulación del DOM (Document Object Model) para ocultar/mostrar elementos basándose en la respuesta JSON del servidor.
  - Intercepción de eventos de formulario (`e.preventDefault()`).

---

## ¿Cómo ejecutar este plan en tu sesión de estudio?

1. **Abre el archivo indicado** en tu editor, línea por línea.
2. Si encuentras una función predefinida de PHP que no conoces (ej. `session_status()`, `extract()`), **detente y búscala en el manual de PHP** (o consúltalo en el chat).
3. Analiza **de dónde viene cada variable** y hacia dónde va (el flujo de datos).
4. **Haz pruebas rompiendo el código:** Cambia una línea, cambia una variable, o escribe un `echo`/`var_dump()` para ver qué contiene esa variable y cómo afecta a la aplicación.
5. Pasa al siguiente archivo solo cuando hayas asimilado completamente el actual.
