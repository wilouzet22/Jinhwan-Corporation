# MANUAL DE PROGRAMADOR
## JINHWAN CORPORATION - SISTEMA INTEGRAL DE GESTIÓN MARCIAL

**Institución:** COLEGIO DE LA UNIVERSIDAD PONTIFICIA BOLIVARIANA  
**Proyecto:** Proyecto de Vida / Tecnología e Informática  
**Fecha:** Octubre del 2026  
**Medellín, Colombia**

**Integrantes / Equipo de Desarrollo:**
- [Nombre del Estudiante / Desarrollador 1]
- [Nombre del Estudiante / Desarrollador 2]
- [Nombre del Estudiante / Desarrollador 3]

---

## ÍNDICE GENERAL

1. [Introducción](#1-introducción)
2. [Librerías y Tecnologías Utilizadas](#2-librerías-y-tecnologías-utilizadas)
3. [Estructura General del Sistema (Arquitectura MVC)](#3-estructura-general-del-sistema)
4. [Configuración del Entorno y Base de Datos](#4-configuración-del-entorno-y-base-de-datos)
5. [Núcleo del Framework (Core)](#5-núcleo-del-framework-core)
6. [Modelos de Datos Principales (Models)](#6-modelos-de-datos-models)
7. [Controladores Base y Flujo de Negocio](#7-controladores-y-lógica-de-negocio)
8. [Vistas y Componentes del Frontend](#8-vistas-y-componentes-del-frontend)
9. [Casos de Uso Críticos y Flujo de Datos](#9-casos-de-uso-críticos-y-flujo-de-datos)
10. [Guía de Instalación y Mantenimiento Técnico](#10-guía-de-instalación-y-mantenimiento-técnico)
11. [Controladores Restantes del Módulo de Administración](#11-controladores-restantes-del-módulo-de-administración)
12. [Controladores Restantes del Módulo del Maestro](#12-controladores-restantes-del-módulo-del-maestro)
13. [Controladores Restantes de Estudiante, Usuario y Portal Web](#13-controladores-restantes-de-estudiante-usuario-y-portal-web)
14. [Motor de Enrutamiento y Funciones Auxiliares (ruteador.php y helpers/)](#14-motor-de-enrutamiento-y-funciones-auxiliares)
15. [Modelos de Entidad Complementarios (modelos/)](#15-modelos-de-entidad-complementarios)
16. [Catálogo de Vistas y Plantillas de Interfaz (vistas/)](#16-catálogo-de-vistas-y-plantillas-de-interfaz)
17. [Scripts de Interactividad Cliente y Notificaciones](#17-scripts-de-interactividad-cliente-y-notificaciones)

---

## 1. Introducción

El presente **Manual de Programador** tiene como propósito fundamental explicar con rigurosidad técnica la arquitectura interna, estructura de código, componentes modulares y lógica de negocio de la plataforma **Jinhwan Corporation**. 

El sistema fue concebido como una solución integral orientada a la gestión pedagógica, deportiva y administrativa de academias de Taekwondo. Permite coordinar roles diferenciados: **Administración** (directores de academia), **Maestros** (instructores de dojang) y **Estudiantes** (deportistas practicantes), además de ofrecer un portal institucional abierto al público general.

---

## 2. Librerías y Tecnologías Utilizadas

- **PHP 8.x (Backend):** Núcleo del servidor. Se emplea bajo el paradigma de Programación Orientada a Objetos (POO), con tipado estricto, manejo seguro de sesiones nativas y sin depender de microframeworks externos.
- **MySQL / MariaDB & Extensión MySQLi (Persistencia):** Motor de base de datos relacional para transacciones ACID. La interacción se efectúa mediante sentencias preparadas (*Prepared Statements*), mitigando riesgos de inyección SQL.
- **Tailwind CSS v4 (Capa de Presentación):** Framework de utilidades CSS moderno. Permite una maquetación responsiva, estilizada con paletas armónicas, compatibilidad con modo claro y modo oscuro, y óptima velocidad de renderizado.
- **TypeScript & JavaScript ES6+ (Interactividad Dinámica):** Control del comportamiento reactivo del cliente, validaciones de formularios asíncronas vía Fetch API, modales dinámicos y filtros de tablas sin recargar la página.
- **Vite (Empaquetador y Bundler):** Automatización y optimización de recursos estáticos en caliente.
- **SweetAlert2 (Feedback y Notificaciones):** Librería gráfica para diálogos modales interactivos y alertas estéticas.
- **OpenRouter AI API (Inteligencia Artificial Marcial):** Integración con modelos de lenguaje natural para la generación asistida de planes de entrenamiento técnico en el módulo del maestro.

---

## 3. Estructura General del Sistema

El software adopta el patrón **Modelo - Vista - Controlador (MVC)**:

```
[ Petición HTTP ] ──▶ [ index.php ] ──▶ [ ruteador.php ] ──▶ [ Controlador ] ──▶ [ Modelo ] ──▶ [ MySQL ]
                                                                     │
                                                                     ▼
                                                              [ Vista (HTML) ] ──▶ [ Navegador ]
```

### Archivo de Entrada Principal: `index.php`

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 1: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: index.php                                                     │
│ Sección: Carga de librerías del core, modelos e inicio de sesión       │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 4. Configuración del Entorno y Base de Datos

### a. Archivo de Conexión: `config/conexion.php`

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 2: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: config/conexion.php                                           │
│ Sección: Definición de variables y función getDBConnection()          │
└────────────────────────────────────────────────────────────────────────┘
```

### b. Configuración Asistente IA: `config/ia.php`

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 3: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: config/ia.php                                                 │
│ Sección: Constantes de API Key, URL endpoint y modelo base de IA      │
└────────────────────────────────────────────────────────────────────────┘
```

### c. Esquema de Base de Datos

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 4: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: database/if0_42216592_jinhwa_corporation.sql                  │
│ Sección: Sentencias DDL de creación de tablas principales             │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 5. Núcleo del Framework (Core)

### a. `core/Router.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 5: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: core/Router.php                                               │
│ Sección: Métodos get(), post() y lógica de despacho en dispatch()      │
└────────────────────────────────────────────────────────────────────────┘
```

### b. `core/Controller.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 6: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: core/Controller.php                                           │
│ Sección: Método renderView() y utilidades de respuesta JSON / redirect │
└────────────────────────────────────────────────────────────────────────┘
```

### c. `core/Model.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 7: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: core/Model.php                                                │
│ Sección: Constructor e inicialización de la propiedad protegida $this->db│
└────────────────────────────────────────────────────────────────────────┘
```

### d. `core/Security.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 8: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: core/Security.php                                             │
│ Sección: Métodos verifyAdmin(), verifyMaestro(), hash y verificación  │
└────────────────────────────────────────────────────────────────────────┘
```

### e. `core/Roles.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 9: YA INSTALADA EN DOCUMENTO]                             │
│ Archivo: core/Roles.php                                                │
│ Sección: Definición de constantes de roles del sistema                 │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 6. Modelos de Datos Principales (Models)

### a. `modelos/Usuario.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 10: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: modelos/Usuario.php                                           │
│ Sección: Método autenticar() y consultas multi-tabla por rol           │
└────────────────────────────────────────────────────────────────────────┘
```

### b. `modelos/Cronograma.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 11: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: modelos/Cronograma.php                                        │
│ Sección: Métodos crearCronograma() y asignarEjercicios()               │
└────────────────────────────────────────────────────────────────────────┘
```

### c. `modelos/Ejercicio.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 12: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: modelos/Ejercicio.php                                         │
│ Sección: Métodos listarTodos(), crear() y filtros de búsqueda         │
└────────────────────────────────────────────────────────────────────────┘
```

### d. `modelos/Certificado.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 13: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: modelos/Certificado.php                                       │
│ Sección: Métodos emitirCertificado() y consulta por estudiante         │
└────────────────────────────────────────────────────────────────────────┘
```

### e. `modelos/Teoria.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 14: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: modelos/Teoria.php                                            │
│ Sección: Método obtenerPorGrado() y filtros teóricos                   │
└────────────────────────────────────────────────────────────────────────┘
```

### f. `modelos/Sede.php` y `modelos/Grupo.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 15: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: modelos/Sede.php y modelos/Grupo.php                          │
│ Sección: Consultas de sedes físicas y grupos de entrenamiento          │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 7. Controladores Base y Flujo de Negocio

### a. `controladores/Autenticacion/AutenticacionController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 16: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Autenticacion/AutenticacionController.php       │
│ Sección: Métodos autenticar() y logout()                               │
└────────────────────────────────────────────────────────────────────────┘
```

### b. `controladores/Administracion/EstudiantesController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 17: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Administracion/EstudiantesController.php        │
│ Sección: Métodos index(), crear(), guardar() y paginar()               │
└────────────────────────────────────────────────────────────────────────┘
```

### c. `controladores/Administracion/AscensosController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 18: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Administracion/AscensosController.php           │
│ Sección: Método aprobarAscenso() y generación del certificado digital  │
└────────────────────────────────────────────────────────────────────────┘
```

### d. `controladores/Maestro/CronogramasController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 19: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Maestro/CronogramasController.php               │
│ Sección: Métodos guardarClase() y asignación de ejercicios             │
└────────────────────────────────────────────────────────────────────────┘
```

### e. `controladores/Maestro/IAController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 20: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Maestro/IAController.php                        │
│ Sección: Método generarPlanIA() e integración con OpenRouter API       │
└────────────────────────────────────────────────────────────────────────┘
```

### f. `controladores/Estudiante/EstudioController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 21: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Estudiante/EstudioController.php                │
│ Sección: Método index() y consulta de contenidos por grado             │
└────────────────────────────────────────────────────────────────────────┘
```

### g. `controladores/Usuario/PerfilController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 22: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Usuario/PerfilController.php                    │
│ Sección: Métodos update() y validación de contraseñas                  │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 8. Vistas y Componentes del Frontend

### a. `vistas/layouts/sidebar.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 23: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: vistas/layouts/sidebar.php                                    │
│ Sección: Estructura del menú dinámico condicionado por rol             │
└────────────────────────────────────────────────────────────────────────┘
```

### b. `vistas/administracion/estudiantes/index.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 24: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: vistas/administracion/estudiantes/index.php                   │
│ Sección: Tabla de datos con paginación y diseño Tailwind CSS           │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 9. Casos de Uso Críticos y Flujo de Datos

### Caso 1: Flujo de Autenticación
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 25: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Autenticacion/AutenticacionController.php       │
│ Sección: Lógica de verificación y bifurcación por rol                  │
└────────────────────────────────────────────────────────────────────────┘
```

### Caso 2: Persistencia de Clase y Ejercicios
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 26: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Maestro/CronogramasController.php               │
│ Sección: Método de persistencia de clase y ejercicios                  │
└────────────────────────────────────────────────────────────────────────┘
```

### Caso 3: Aprobación y Promoción de Grado
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 27: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: controladores/Administracion/AscensosController.php           │
│ Sección: Método de aprobación y actualización de grado                 │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 10. Guía de Instalación y Mantenimiento Técnico

### `.htaccess`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [CAPTURA 28: YA INSTALADA EN DOCUMENTO]                            │
│ Archivo: .htaccess                                                     │
│ Sección: Reglas mod_rewrite de Apache para redirección a index.php     │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 11. Controladores Restantes del Módulo de Administración

*(Módulos directivos que no habían sido capturados en las secciones anteriores)*

### 11.1 `controladores/Administracion/DashboardController.php`
- **Propósito:** Métricas y contadores consolidados de la academia.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/DashboardController.php          │
│ Sección: Método index() y consultas de contadores                      │
│ Detalle: Captura del método index() con las consultas COUNT() globales │
└────────────────────────────────────────────────────────────────────────┘
```

### 11.2 `controladores/Administracion/MaestrosController.php`
- **Propósito:** Gestión de profesores, asignación a sedes físicas y credenciales.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/MaestrosController.php           │
│ Sección: Métodos index() y guardar()                                   │
│ Detalle: Captura del listado y procesamiento del formulario de maestro │
└────────────────────────────────────────────────────────────────────────┘
```

### 11.3 `controladores/Administracion/SedesController.php`
- **Propósito:** Administración de sucursales físicas y dojangs.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/SedesController.php              │
│ Sección: Métodos index(), guardar() y eliminar()                       │
│ Detalle: Captura de la lógica de mantenimiento de sedes físicas        │
└────────────────────────────────────────────────────────────────────────┘
```

### 11.4 `controladores/Administracion/GruposController.php`
- **Propósito:** Estructuración de horarios, turnos y asignación de instructores.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/GruposController.php             │
│ Sección: Métodos index() y guardar()                                   │
│ Detalle: Captura de la creación de grupos de entrenamiento con horarios│
└────────────────────────────────────────────────────────────────────────┘
```

### 11.5 `controladores/Administracion/TeoriaController.php`
- **Propósito:** Gestión de contenidos pedagógicos y manuales por grado.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/TeoriaController.php             │
│ Sección: Métodos index(), crear() y guardar()                          │
│ Detalle: Captura de la carga y edición de guías teóricas según grado   │
└────────────────────────────────────────────────────────────────────────┘
```

### 11.6 `controladores/Administracion/CalendarioController.php` y `GaleriaController.php`

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/CalendarioController.php         │
│ Sección: Método index() y guardarEvento()                              │
│ Detalle: Captura de la persistencia de eventos institucionales         │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/GaleriaController.php            │
│ Sección: Métodos index(), subir() y eliminar()                         │
│ Detalle: Captura de la subida y almacenamiento de fotos                │
└────────────────────────────────────────────────────────────────────────┘
```

### 11.7 `controladores/Administracion/RegistrosController.php` y `ReportesController.php`

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/RegistrosController.php          │
│ Sección: Métodos index() y moderar()                                   │
│ Detalle: Captura de la moderación y activación de nuevos alumnos       │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Administracion/ReportesController.php           │
│ Sección: Método index() y filtros de informe                           │
│ Detalle: Captura del módulo de generación de reportes                  │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 12. Controladores Restantes del Módulo del Maestro

### 12.1 `controladores/Maestro/DashboardController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Maestro/DashboardController.php                 │
│ Sección: Método index()                                                │
│ Detalle: Captura del panel del profesor y filtrado de clases activas   │
└────────────────────────────────────────────────────────────────────────┘
```

### 12.2 `controladores/Maestro/AlumnosController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Maestro/AlumnosController.php                   │
│ Sección: Métodos index(), perfil() y asistencia()                      │
│ Detalle: Captura de la consulta de estudiantes y registro de asistencia│
└────────────────────────────────────────────────────────────────────────┘
```

### 12.3 `controladores/Maestro/EjerciciosController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Maestro/EjerciciosController.php                │
│ Sección: Métodos index(), crear() y guardar()                          │
│ Detalle: Captura del catálogo técnico marcial y filtros por categoría  │
└────────────────────────────────────────────────────────────────────────┘
```

### 12.4 `controladores/Maestro/SolicitudesAscensoController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Maestro/SolicitudesAscensoController.php        │
│ Sección: Métodos index() y postular()                                  │
│ Detalle: Captura de la formulación y validación de la postulación      │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 13. Controladores Restantes de Estudiante, Usuario y Portal Web

### 13.1 `controladores/Estudiante/DashboardController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Estudiante/DashboardController.php              │
│ Sección: Método index()                                                │
│ Detalle: Captura del método index() del estudiante con métricas       │
└────────────────────────────────────────────────────────────────────────┘
```

### 13.2 `controladores/Estudiante/HistorialController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Estudiante/HistorialController.php              │
│ Sección: Métodos index(), certificado() y descargar()                  │
│ Detalle: Captura de la consulta del historial de grados y diploma      │
└────────────────────────────────────────────────────────────────────────┘
```

### 13.3 `controladores/Usuario/CalendarioController.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Usuario/CalendarioController.php                │
│ Sección: Método index()                                                │
│ Detalle: Captura del calendario del usuario                            │
└────────────────────────────────────────────────────────────────────────┘
```

### 13.4 Controladores del Portal Web Público
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Web/InicioController.php                        │
│ Sección: Métodos portal() e index()                                    │
│ Detalle: Captura del controlador de inicio del portal web              │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Web/SedesController.php y GruposController.php   │
│ Sección: Métodos index() públicos                                      │
│ Detalle: Captura de las consultas públicas de sedes y horarios         │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: controladores/Web/MiembrosController.php y PaginaController.php│
│ Sección: Métodos de información institucional                          │
│ Detalle: Captura de presentación de maestros y filosofía marcial       │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 14. Motor de Enrutamiento y Funciones Auxiliares

### 14.1 `ruteador.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: ruteador.php                                                  │
│ Sección: Definición y registro de rutas amigables                      │
│ Detalle: Captura de ruteador.php mostrando el registro de rutas        │
└────────────────────────────────────────────────────────────────────────┘
```

### 14.2 `helpers/url_helper.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: helpers/url_helper.php                                        │
│ Sección: Funciones base_url() y asset()                                │
│ Detalle: Captura de helpers/url_helper.php                             │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 15. Modelos de Entidad Complementarios

### 15.1 `modelos/Evento.php` y `MultimediaGaleria.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: modelos/Evento.php y MultimediaGaleria.php                    │
│ Sección: Métodos de persistencia de eventos y fotos                    │
│ Detalle: Captura de las consultas preparadas de eventos y fotos        │
└────────────────────────────────────────────────────────────────────────┘
```

### 15.2 `modelos/Categoria.php` y `Nivel.php`
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: modelos/Categoria.php y Nivel.php                             │
│ Sección: Consultas de catálogos marciales                              │
│ Detalle: Captura de los métodos de catálogos técnicos                  │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 16. Catálogo de Vistas y Plantillas de Interfaz (vistas/)

### 16.1 Layouts y Cabeceras Modulares
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/layout/administracion_cabecera.php                     │
│ Sección: Cabecera del panel de administración                          │
│ Detalle: Captura de administracion_cabecera.php                        │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/layout/maestro_cabecera.php                            │
│ Sección: Cabecera del panel de maestro                                 │
│ Detalle: Captura de maestro_cabecera.php                               │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/layout/estudiante_cabecera.php                         │
│ Sección: Cabecera del panel de estudiante                              │
│ Detalle: Captura de estudiante_cabecera.php                            │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/layout/ia_asistente_widget.php                         │
│ Sección: Widget flotante del asistente IA                              │
│ Detalle: Captura del componente interactivo de IA                      │
└────────────────────────────────────────────────────────────────────────┘
```

### 16.2 Vistas de Autenticación
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/autenticacion/login.php                                │
│ Sección: Interfaz del Formulario de Login                              │
│ Detalle: Captura de login.php                                          │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/autenticacion/registro.php                             │
│ Sección: Interfaz de Formulario de Registro                            │
│ Detalle: Captura de registro.php                                       │
└────────────────────────────────────────────────────────────────────────┘
```

### 16.3 Vistas de Administración
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/administracion/dashboard.php                           │
│ Sección: Vista del Dashboard Directivo                                 │
│ Detalle: Captura de dashboard.php con tarjetas métricas                │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/administracion/maestros.php                            │
│ Sección: Vista del Módulo de Maestros                                  │
│ Detalle: Captura de maestros.php                                       │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/administracion/sedes.php                               │
│ Sección: Vista de Administración de Sedes                              │
│ Detalle: Captura de sedes.php                                          │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/administracion/teoria.php                              │
│ Sección: Vista de Gestión Teórica                                      │
│ Detalle: Captura de teoria.php                                         │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/administracion/certificado_preview.php                 │
│ Sección: Vista Previa de Diploma Oficial                               │
│ Detalle: Captura de certificado_preview.php con orla marcial           │
└────────────────────────────────────────────────────────────────────────┘
```

### 16.4 Vistas del Maestro
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/maestro/dashboard.php                                  │
│ Sección: Vista del Dashboard del Maestro                               │
│ Detalle: Captura de dashboard.php de maestro                           │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/maestro/alumnos.php                                    │
│ Sección: Vista de Lista de Alumnos                                     │
│ Detalle: Captura de alumnos.php                                        │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/maestro/ejercicios.php                                 │
│ Sección: Vista del Catálogo de Ejercicios                              │
│ Detalle: Captura de ejercicios.php                                     │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/maestro/solicitudes_ascenso.php                        │
│ Sección: Vista de Solicitudes de Ascenso                               │
│ Detalle: Captura de solicitudes_ascenso.php                            │
└────────────────────────────────────────────────────────────────────────┘
```

### 16.5 Vistas del Estudiante
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/estudiante/dashboard.php                               │
│ Sección: Vista del Dashboard del Estudiante                            │
│ Detalle: Captura del dashboard personal del alumno                     │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/estudiante/estudio.php                                 │
│ Sección: Vista del Módulo de Estudio Teórico                           │
│ Detalle: Captura de estudio.php                                        │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/estudiante/historial.php                               │
│ Sección: Vista de Historial de Grados y Diplomas                       │
│ Detalle: Captura de historial.php                                      │
└────────────────────────────────────────────────────────────────────────┘
```

### 16.6 Vistas del Portal Público Institucional
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/web/portal.php                                         │
│ Sección: Vista del Portal Institucional                                │
│ Detalle: Captura de portal.php                                         │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/web/sedes.php                                          │
│ Sección: Vista Pública de Sedes y Dojangs                              │
│ Detalle: Captura de sedes.php                                          │
└────────────────────────────────────────────────────────────────────────┘
```

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: vistas/web/nosotros.php                                       │
│ Sección: Vista de Nosotros y Filosofía Marcial                         │
│ Detalle: Captura de nosotros.php                                       │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 17. Scripts de Interactividad Cliente y Notificaciones

### 17.1 SweetAlert2
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: recursos/js/alertas.js / vistas/layout/                       │
│ Sección: Script de Alertas con SweetAlert2                             │
│ Detalle: Captura de la invocación de Swal.fire()                       │
└────────────────────────────────────────────────────────────────────────┘
```

### 17.2 Peticiones Asíncronas vía Fetch API
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: recursos/js/ / vistas/                                        │
│ Sección: Script de Peticiones Fetch API                                │
│ Detalle: Captura del código JS con la llamada fetch()                  │
└────────────────────────────────────────────────────────────────────────┘
```

### 17.3 Filtrado en Tiempo Real en Tablas
```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR AQUÍ CAPTURA DE PANTALLA DEL CÓDIGO]                     │
│ Archivo: recursos/js/ / vistas/                                        │
│ Sección: Script de Búsqueda y Filtrado Dinámico                        │
│ Detalle: Captura del filtrado dinámico de filas                        │
└────────────────────────────────────────────────────────────────────────┘
```
