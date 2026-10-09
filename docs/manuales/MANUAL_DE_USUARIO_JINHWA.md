# MANUAL DE USUARIO
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

1. [Introducción al Sistema](#1-introducción-al-sistema)
2. [Objetivo General del Software](#2-objetivo-general-del-software)
3. [Roles de Usuario y Matriz de Acceso](#3-roles-de-usuario-y-matriz-de-acceso)
4. [Portal Web Público (Visitantes)](#4-portal-web-público-visitantes)
   - 4.1 Portal de Bienvenida y Landing Page (`/portal`)
   - 4.2 Página de Inicio Institucional (`/inicio`)
   - 4.3 Nosotros y Filosofía del Taekwondo (`/nosotros`)
   - 4.4 Catálogo de Sedes y Dojangs (`/sedes`)
   - 4.5 Horarios y Grupos de Práctica (`/grupos`)
   - 4.6 Directorio de Maestros (`/miembros`)
   - 4.7 Galería de Eventos y Torneos (`/galeria`)
5. [Módulo de Autenticación y Registro](#5-módulo-de-autenticación-y-registro)
   - 5.1 Inicio de Sesión (Login)
   - 5.2 Registro Público de Nuevos Practicantes (`/registro`)
   - 5.3 Completar Perfil Deportivo Inicial (`/completar_registro`)
6. [Módulo del Administrador (Gestión Directiva)](#6-módulo-del-administrador-gestión-directiva)
   - 6.1 Dashboard Directivo y Métricas Clave (`/administracion/dashboard`)
   - 6.2 Gestión de Estudiantes: Tabla, Filtros y Paginación (`/administracion/estudiantes`)
   - 6.3 Formulario de Alta de Nuevo Estudiante
   - 6.4 Gestión de Maestros y Asignación de Sedes (`/administracion/maestros`)
   - 6.5 Administración de Sedes Físicas y Dojangs (`/administracion/sedes`)
   - 6.6 Creación y Programación de Grupos y Horarios (`/administracion/grupos`)
   - 6.7 Aprobación de Ascensos y Promoción de Cinturón (`/administracion/ascensos`)
   - 6.8 Emisión y Vista Previa del Diploma Oficial (`/administracion/certificado_preview`)
   - 6.9 Gestión de Planes de Clase y Cronogramas (`/administracion/cronogramas`)
   - 6.10 Administración de Guías Teóricas y Pedagogía (`/administracion/teoria`)
   - 6.11 Calendario Institucional de Eventos y Torneos (`/administracion/calendario`)
   - 6.12 Gestión de la Galería Multimedia (`/administracion/galeria`)
   - 6.13 Moderación de Solicitudes de Registro (`/administracion/registros`)
   - 6.14 Control de Perfiles Públicos de Maestros (`/administracion/perfiles_publicos`)
   - 6.15 Módulo de Reportes Operativos y Estadísticos (`/administracion/reportes`)
7. [Módulo del Maestro (Instrucción y Dojang)](#7-módulo-del-maestro-instrucción-y-dojang)
   - 7.1 Dashboard del Maestro: Clases del Día (`/maestro/dashboard`)
   - 7.2 Listado y Seguimiento de Alumnos a Cargo (`/maestro/alumnos`)
   - 7.3 Ficha Técnica Individual del Alumno (`/maestro/alumnos/detalle`)
   - 7.4 Registro de Asistencias a Práctica
   - 7.5 Planificador de Clases: Cronogramas de Entrenamiento (`/maestro/cronogramas`)
   - 7.6 Diseñador de Clases con Selección de Ejercicios
   - 7.7 Catálogo de Técnicas y Ejercicios Marciales (`/maestro/ejercicios`)
   - 7.8 Solicitudes de Ascenso a Examen de Grado (`/maestro/solicitudes_ascenso`)
   - 7.9 Asistente Inteligente con IA para Creación de Rutinas (`/maestro/ia`)
8. [Módulo del Estudiante (Deportista)](#8-módulo-del-estudiante-deportista)
   - 8.1 Dashboard Personal con Visualizador de Cinturón (`/estudiante/dashboard`)
   - 8.2 Aula Teórica Marcial y Guías por Grado (`/estudiante/estudio`)
   - 8.3 Historial de Ascensos y Promociones (`/estudiante/historial`)
   - 8.4 Visualización y Descarga del Certificado Oficial
9. [Perfil de Usuario y Preferencias del Sistema](#9-perfil-de-usuario-y-preferencias-del-sistema)
   - 9.1 Actualización de Información Personal y Foto (`/usuario/perfil`)
   - 9.2 Cambio Seguro de Contraseña
   - 9.3 Conmutador de Modo Oscuro y Modo Claro
10. [Preguntas Frecuentes, Ayuda y Soporte Técnico](#10-preguntas-frecuentes-ayuda-y-soporte-técnico)

---

## 1. Introducción al Sistema

Bienvenido a **Jinhwan Corporation**, la plataforma integral de gestión y formación marcial diseñada especialmente para academias y dojangs de Taekwondo. Este sistema conecta en un único ecosistema digital a directores, maestros de entrenamiento, deportistas practicantes y a toda la comunidad interesada en la disciplina del Taekwondo.

A diferencia de herramientas de administración genéricas, Jinhwan Corporation está adaptado rigurosamente a las tradiciones, jerarquías marciales y requerimientos pedagógicos del Taekwondo:
- Gestiona el avance técnico de cinturones (grados Kup y Dan).
- Planifica clases por grupos de edad y nivel.
- Ofrece material de estudio teórico con terminología coreana oficial.
- Cuenta con un asistente de Inteligencia Artificial para apoyar la labor del maestro.
- Emite certificados oficiales de ascenso verificables digitalmente.

---

## 2. Objetivo General del Software

El objetivo primordial de Jinhwan Corporation es optimizar, centralizar y transparentar los procesos administrativos, formativos y operativos de una academia de Taekwondo:

- **Digitalización de Expedientes:** Mantener una base de datos segura de deportistas, datos de contacto de acudientes y nivel de cinturón.
- **Planificación Metodológica:** Dotar a los instructores de herramientas ágiles para estructurar clases balanceadas (acondicionamiento, poomsae/formas, kyorugi/combate y defensa personal).
- **Trazabilidad de Ascensos:** Formalizar el proceso de examen de grado mediante postulaciones del maestro, aprobación directiva y emisión instantánea de diplomas digitales.
- **Formación Teórica y Filosófica:** Proporcionar a los deportistas acceso interactivo a los principios del Taekwondo (Cortesía, Integridad, Perseverancia, Autocontrol y Espíritu Indomable), vocabulario coreano e historia.

---

## 3. Roles de Usuario y Matriz de Acceso

- **Rol Administración (Directores):** Control total del sistema: gestión de sedes, creación de grupos, vinculación de profesores, aprobación de ascensos, reportes y moderación de registros.
- **Rol Maestros (Instructores / Sabonim):** Acceso a sus alumnos asignados, control de asistencias, diseño de cronogramas de entrenamiento, biblioteca técnica de ejercicios, postulación de candidatos a examen y uso del asistente con IA.
- **Rol Estudiantes (Deportistas):** Acceso a su expediente personal, visualización interactiva de su cinturón actual, aula de teoría con terminología coreana, historial de ascensos aprobados y descarga de certificados.
- **Visitantes Públicos:** Acceso al portal institucional informativo: consultar información de la academia, directorio de dojangs, horarios de entrenamiento y formulario de pre-inscripción.

---

## 4. Portal Web Público (Visitantes)

### 4.1 Portal de Bienvenida y Landing Page (`/portal`)
Presenta una bienvenida atractiva con la identidad institucional de Jinhwan Corporation, accesos rápidos para iniciar sesión o registrarse y un resumen de las disciplinas impartidas.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Portal de Bienvenida / Landing Page                          │
│ Ruta: http://localhost/portal o raíz                                   │
│ Qué debe verse: Vista completa de la portada con banner principal,    │
│                 lema institucional y botones de acceso.                │
└────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Página de Inicio Institucional (`/inicio`)
Ofrece una visión general de la academia, noticias recientes, próximos torneos y enlaces a los diferentes canales de atención.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Inicio Institucional                                         │
│ Ruta: http://localhost/inicio                                          │
│ Qué debe verse: Tarjetas de bienvenida, noticias y eventos.            │
└────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Nosotros y Filosofía del Taekwondo (`/nosotros`)
Expone la misión, visión, historia de la academia y los 5 principios morales del Taekwondo.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Página Nosotros y Filosofía                                  │
│ Ruta: http://localhost/nosotros                                        │
│ Qué debe verse: Reseña histórica y principios morales del Taekwondo.   │
└────────────────────────────────────────────────────────────────────────┘
```

### 4.4 Catálogo de Sedes y Dojangs (`/sedes`)
Muestra el listado de todas las sedes físicas y academias asociadas con dirección, teléfonos y horarios de atención.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Directorio Público de Sedes                                  │
│ Ruta: http://localhost/sedes                                           │
│ Qué debe verse: Tarjetas de escuelas físicas con ubicación y contacto. │
└────────────────────────────────────────────────────────────────────────┘
```

### 4.5 Horarios y Grupos de Práctica (`/grupos`)
Permite consultar los turnos disponibles organizados por edad (infantil, juvenil, adultos) y grado técnico.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Horarios y Grupos de Entrenamiento                           │
│ Ruta: http://localhost/grupos                                          │
│ Qué debe verse: Tabla de horarios y turnos de práctica disponibles.    │
└────────────────────────────────────────────────────────────────────────┘
```

### 4.6 Directorio de Maestros (`/miembros`)
Presenta al equipo de maestros certificados, con su fotografía marcial, grado Dan alcanzado y trayectoria deportiva.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Directorio de Maestros                                       │
│ Ruta: http://localhost/miembros                                        │
│ Qué debe verse: Tarjetas de presentación con foto y grado de maestros. │
└────────────────────────────────────────────────────────────────────────┘
```

### 4.7 Galería de Eventos y Torneos (`/galeria`)
Álbum fotográfico digital con fotografías de torneos, seminarios y ceremonias de cambio de cinturón.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Galería Fotográfica Institucional                            │
│ Ruta: http://localhost/galeria                                         │
│ Qué debe verse: Galería de imágenes en cuadrícula responsiva.          │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 5. Módulo de Autenticación y Registro

### 5.1 Inicio de Sesión (Login)
Permite a los usuarios registrados ingresar al sistema.
- **Paso 1:** Ingresa a `/login` o haz clic en el botón de iniciar sesión.
- **Paso 2:** Digita tu correo electrónico registrado y contraseña. *(Para nuevos estudiantes la contraseña predeterminada es `jinhwa2024`)*.
- **Paso 3:** Haz clic en **Ingresar**.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Formulario de Inicio de Sesión (Login)                       │
│ Ruta: /login o /inicio                                                 │
│ Qué debe verse: Interfaz de login con campos de usuario y contraseña.  │
└────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Registro Público de Nuevos Practicantes (`/registro`)
Formulario de pre-inscripción para personas interesadas en comenzar clases.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Formulario de Registro Público                               │
│ Ruta: /registro                                                        │
│ Qué debe verse: Formulario con campos de nombre, correo, sede y botón. │
└────────────────────────────────────────────────────────────────────────┘
```

### 5.3 Completar Perfil Deportivo Inicial (`/completar_registro`)
Formulario complementario con información médica básica, acudiente y talla de dobok.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Completar Perfil Deportivo                                   │
│ Ruta: /completar_registro                                              │
│ Qué debe verse: Formulario complementario del alumno.                  │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 6. Módulo del Administrador (Gestión Directiva)

### 6.1 Dashboard Directivo y Métricas Clave (`/administracion/dashboard`)
Panel principal con contadores de estudiantes, profesores, sedes activas y accesos directos.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Dashboard Principal de Administración                       │
│ Ruta: /administracion/dashboard                                        │
│ Qué debe verse: Tarjetas métricas en tiempo real y gráficos.           │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Gestión de Estudiantes: Tabla, Filtros y Paginación (`/administracion/estudiantes`)
Tabla centralizada de alumnos con buscador instantáneo, filtro por grado Kup/Dan y selector de página.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Directorio y Listado de Estudiantes                          │
│ Ruta: /administracion/estudiantes                                      │
│ Qué debe verse: Tabla con buscador, filtros y controles de paginación. │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.3 Formulario de Alta de Nuevo Estudiante
Modal o formulario para registrar formalmente a un practicante en el sistema.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Formulario de Registro de Estudiante                         │
│ Ruta: Botón 'Nuevo Estudiante' en /administracion/estudiantes          │
│ Qué debe verse: Modal con campos de datos personales, acudiente y sede.│
└────────────────────────────────────────────────────────────────────────┘
```

### 6.4 Gestión de Maestros y Asignación de Sedes (`/administracion/maestros`)
Directorio de profesores vinculados con su sede asignada y estado activo.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Directorio de Maestros (Administración)                      │
│ Ruta: /administracion/maestros                                         │
│ Qué debe verse: Tabla de instructores con datos de contacto y sede.    │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.5 Administración de Sedes Físicas y Dojangs (`/administracion/sedes`)
Mantenimiento de sucursales físicas (dirección, teléfono y ciudad).

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Gestión de Sedes y Dojangs                                   │
│ Ruta: /administracion/sedes                                            │
│ Qué debe verse: Tabla de sedes físicas con botones de agregar y editar.│
└────────────────────────────────────────────────────────────────────────┘
```

### 6.6 Creación y Programación de Grupos y Horarios (`/administracion/grupos`)
Definición de turnos de práctica, cupos máximos y profesor asignado.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Gestión de Grupos y Horarios                                 │
│ Ruta: /administracion/grupos                                           │
│ Qué debe verse: Interfaz de grupos con horarios y maestro a cargo.     │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.7 Aprobación de Ascensos y Promoción de Cinturón (`/administracion/ascensos`)
Bandeja de postulaciones a examen de grado enviadas por los profesores.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Bandeja de Aprobación de Ascensos                            │
│ Ruta: /administracion/ascensos                                         │
│ Qué debe verse: Lista de postulaciones con botones de Aprobar/Evaluar. │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.8 Emisión y Vista Previa del Diploma Oficial (`/administracion/certificado_preview`)
Visualizador del diploma oficial de ascenso con orla marcial y folio digital.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Vista Previa del Certificado Oficial                         │
│ Ruta: /administracion/certificado_preview                              │
│ Qué debe verse: Diploma con diseño ceremonial y datos del alumno.      │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.9 Gestión de Planes de Clase y Cronogramas (`/administracion/cronogramas`)
Supervisión directiva de las sesiones de entrenamiento creadas por los profesores.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Cronogramas de Clase (Supervisión)                           │
│ Ruta: /administracion/cronogramas                                      │
│ Qué debe verse: Lista de clases planificadas por fecha y grupo.        │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.10 Administración de Guías Teóricas y Pedagogía (`/administracion/teoria`)
Editor de contenido pedagógico clasificado por cinturón.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Gestión de Material Teórico                                  │
│ Ruta: /administracion/teoria                                           │
│ Qué debe verse: Gestor de artículos educativos con editor de texto.    │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.11 Calendario Institucional de Eventos y Torneos (`/administracion/calendario`)
Publicación de fechas de torneos, seminarios y exámenes.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Calendario de Eventos Institucionales                        │
│ Ruta: /administracion/calendario                                       │
│ Qué debe verse: Calendario interactivo con eventos programados.        │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.12 Gestión de la Galería Multimedia (`/administracion/galeria`)
Carga y administración de fotografías institucionales.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Administración de Galería Multimedia                         │
│ Ruta: /administracion/galeria                                          │
│ Qué debe verse: Panel de subida de imágenes con previsualización.      │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.13 Moderación de Solicitudes de Registro (`/administracion/registros`)
Bandeja para aceptar o rechazar solicitudes de inscripción enviadas desde la web.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Moderación de Solicitudes de Registro                        │
│ Ruta: /administracion/registros                                        │
│ Qué debe verse: Bandeja de solicitudes pendientes con botones de acción│
└────────────────────────────────────────────────────────────────────────┘
```

### 6.14 Control de Perfiles Públicos de Maestros (`/administracion/perfiles_publicos`)
Control de qué profesores aparecen en el directorio público y edición de biografía.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Perfiles Públicos de Maestros                                │
│ Ruta: /administracion/perfiles_publicos                                │
│ Qué debe verse: Interruptores de visibilidad pública de docentes.      │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.15 Módulo de Reportes Operativos y Estadísticos (`/administracion/reportes`)
Generación de balances de asistencia, censo de estudiantes y promociones.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Reportes y Estadísticas Generales                            │
│ Ruta: /administracion/reportes                                         │
│ Qué debe verse: Filtros de reportes y visualizador de estadísticas.    │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 7. Módulo del Maestro (Instrucción y Dojang)

### 7.1 Dashboard del Maestro: Clases del Día (`/maestro/dashboard`)
Panel inicial del profesor con su horario de clases para la jornada.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Dashboard del Maestro                                        │
│ Ruta: /maestro/dashboard                                               │
│ Qué debe verse: Clases del día, alumnos convocados y accesos directos. │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Listado y Seguimiento de Alumnos a Cargo (`/maestro/alumnos`)
Deportistas inscritos en los grupos del profesor con su grado actual.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Lista de Alumnos a Cargo                                     │
│ Ruta: /maestro/alumnos                                                 │
│ Qué debe verse: Tabla de estudiantes asignados con su cinturón.        │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.3 Ficha Técnica Individual del Alumno (`/maestro/alumnos/detalle`)
Expediente marcial detallado del alumno con tiempo en el grado y notas.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Ficha Técnica del Alumno                                     │
│ Ruta: /maestro/alumnos/detalle?id=...                                  │
│ Qué debe verse: Ficha técnica del practicante con su historial.        │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.4 Registro de Asistencias a Práctica
Toma de lista digital durante o al finalizar el entrenamiento.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Control de Asistencias                                       │
│ Ruta: Sección de asistencia en /maestro/alumnos                        │
│ Qué debe verse: Listado de alumnos con casillas de asistencia.         │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.5 Planificador de Clases: Cronogramas de Entrenamiento (`/maestro/cronogramas`)
Historial de entrenamientos planificados por el profesor.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Cronogramas de Entrenamiento                                 │
│ Ruta: /maestro/cronogramas                                             │
│ Qué debe verse: Lista de clases programadas por el instructor.         │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.6 Diseñador de Clases con Selección de Ejercicios
Herramienta interactiva para planificar la sesión: objetivo, técnicas de pateo y combate.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Diseñador Interactivo de Sesiones de Clase                   │
│ Ruta: Formulario de crear en /maestro/cronogramas                      │
│ Qué debe verse: Interfaz de selección de ejercicios y series/tiempos.  │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.7 Catálogo de Técnicas y Ejercicios Marciales (`/maestro/ejercicios`)
Biblioteca técnica de Taekwondo clasificada por categorías (Poomsae, Kyorugi, pateo).

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Biblioteca de Ejercicios Técnicos                            │
│ Ruta: /maestro/ejercicios                                              │
│ Qué debe verse: Catálogo con tarjetas de técnicas marciales y filtros. │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.8 Solicitudes de Ascenso a Examen de Grado (`/maestro/solicitudes_ascenso`)
Postulación de deportistas que cumplen con el tiempo y técnica para cambio de cinturón.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Bandeja de Solicitudes de Ascenso (Maestro)                  │
│ Ruta: /maestro/solicitudes_ascenso                                     │
│ Qué debe verse: Selector de estudiante, grado propuesto y justificación│
└────────────────────────────────────────────────────────────────────────┘
```

### 7.9 Asistente Inteligente con IA para Creación de Rutinas (`/maestro/ia`)
Generación de propuestas de entrenamiento personalizadas con Inteligencia Artificial.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Asistente de Entrenamiento con IA                            │
│ Ruta: Widget flotante o /maestro/ia                                    │
│ Qué debe verse: Formulario con parámetros de clase y rutina generada.  │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 8. Módulo del Estudiante (Deportista)

### 8.1 Dashboard Personal con Visualizador de Cinturón (`/estudiante/dashboard`)
Panel del alumno con visualizador gráfico de su cinturón actual y porcentaje de asistencia.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Dashboard del Estudiante                                     │
│ Ruta: /estudiante/dashboard                                            │
│ Qué debe verse: Cinturón visual del deportista, asistencias y avisos.  │
└────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Aula Teórica Marcial y Guías por Grado (`/estudiante/estudio`)
Módulos interactivos de estudio teórico con terminología en coreano y filosofía.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Aula de Estudio Teórico                                      │
│ Ruta: /estudiante/estudio                                              │
│ Qué debe verse: Lecciones teóricas marciales con indicador de avance.  │
└────────────────────────────────────────────────────────────────────────┘
```

### 8.3 Historial de Ascensos y Promociones (`/estudiante/historial`)
Línea de tiempo cronológica con los exámenes aprobados desde el inicio.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Historial de Grados del Alumno                               │
│ Ruta: /estudiante/historial                                            │
│ Qué debe verse: Línea de tiempo con grados alcanzados y certificados.  │
└────────────────────────────────────────────────────────────────────────┘
```

### 8.4 Visualización y Descarga del Certificado Oficial
Diploma de grado oficial emitido digitalmente para descargar o imprimir.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Visualizador de Certificado Oficial Digital                  │
│ Ruta: Botón 'Ver Certificado' en historial                             │
│ Qué debe verse: Diploma con orla ceremonial dorada y sello oficial.    │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 9. Perfil de Usuario y Preferencias del Sistema

### 9.1 Actualización de Información Personal y Foto (`/usuario/perfil`)
Formulario de datos personales y subida de fotografía para carnet.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Perfil de Usuario                                            │
│ Ruta: /usuario/perfil                                                  │
│ Qué debe verse: Datos de contacto y fotografía del usuario.            │
└────────────────────────────────────────────────────────────────────────┘
```

### 9.2 Cambio Seguro de Contraseña
Formulario de actualización de contraseña con validación de clave previa.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Cambio Seguro de Contraseña                                  │
│ Ruta: Sección de contraseña en /usuario/perfil                         │
│ Qué debe verse: Campos de contraseña actual, nueva y confirmación.     │
└────────────────────────────────────────────────────────────────────────┘
```

### 9.3 Conmutador de Modo Oscuro y Modo Claro
Botón en la barra superior para alternar entre temas visuales.

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Conmutador de Modo Oscuro / Claro                            │
│ Ubicación: Barra superior (Navbar)                                     │
│ Qué debe verse: Botón conmutador de tema claro y oscuro en la barra.   │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 10. Preguntas Frecuentes, Ayuda y Soporte Técnico

- **¿Olvidé mi contraseña, cómo la recupero?** Acércate a la dirección de tu sede o solicita a tu maestro el restablecimiento de tu clave inicial.
- **¿Cuál es la contraseña predeterminada para nuevos estudiantes?** La contraseña predeterminada es `jinhwa2024`.
- **¿Cómo verifico la validez de un certificado marcial?** Cada diploma emitido por Jinhwan Corporation incluye un código de folio alfanumérico único.
- **¿El sistema funciona en celulares y tablets?** Sí, Jinhwan Corporation cuenta con diseño 100% responsivo adaptable a pantallas de computadores, tabletas y teléfonos inteligentes.

### Canales de Contacto
- **Línea Telefónica / WhatsApp:** +57 (300) 123-4567
- **Correo de Soporte:** soporte@jinhwancorporation.com / jinhwa.taekwondo@gmail.com
- **Sede Central:** Medellín, Antioquia, Colombia

```
┌────────────────────────────────────────────────────────────────────────┐
│ 📷 [INSERTAR CAPTURA DE PANTALLA DE LA INTERFAZ AQUÍ]                 │
│ Pantalla: Canales de Soporte y Pie de Página                           │
│ Ubicación: Pie de página de todas las vistas                           │
│ Qué debe verse: Bloque de soporte, redes sociales y contacto.          │
└────────────────────────────────────────────────────────────────────────┘
```
