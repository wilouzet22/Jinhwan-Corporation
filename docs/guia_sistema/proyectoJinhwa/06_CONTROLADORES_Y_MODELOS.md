# 🎮 06 - Catálogo de Controladores y Modelos

Este capítulo documenta cada controlador y modelo del sistema, indicando su propósito, de dónde obtiene datos y qué vista renderiza.

---

## 📦 Modelos (`modelos/`)

Todos los modelos heredan de `core/Model.php` y se comunican con la base de datos a través de `$this->db` (`mysqli`).

| Modelo | Archivo | Responsabilidad Principal |
|---|---|---|
| `Usuario` | `Usuario.php` | Autenticación, listado de miembros, datos de perfil, actualización de contraseñas. |
| `Cronograma` | `Cronograma.php` | Creación, listado y eliminación de planes de clase; asociación de ejercicios por fecha. |
| `Ejercicio` | `Ejercicio.php` | CRUD de ejercicios técnicos de Taekwondo (formas, combate, patadas). |
| `Certificado`| `Certificado.php`| Consulta y emisión de diplomas de ascenso de grado. |
| `Evento` | `Evento.php` | Calendario institucional, torneos, seminarios y clases especiales. |
| `Grupo` | `Grupo.php` | Agrupación de estudiantes por horarios, edades y niveles. |
| `Sede` | `Sede.php` | Gestión de dojos y academias físicas registradas. |
| `Teoria` | `Teoria.php` | Material de estudio para los deportistas por nivel y cinturón. |
| `MultimediaGaleria` | `MultimediaGaleria.php` | Álbumes fotográficos e imágenes institucionales. |
| `Categoria` | `Categoria.php` | Categorías de técnicas y ejercicios. |
| `Nivel` | `Nivel.php` | Niveles de dificultad y grado técnico. |

---

## 🕹️ Controladores (`controladores/`)

### 1. Módulo Administración (`controladores/Administracion/`)
Exige permisos de Administrador (`Security::verifyAdmin()`).

- **`DashboardController.php`:** Métricas generales, total de alumnos, maestros activos y gráficos.
- **`EstudiantesController.php`:** Altas, bajas, modificaciones y exportación de expedientes de alumnos.
- **`MaestrosController.php`:** Administración de profesores, sedes asignadas y credenciales.
- **`AscensosController.php`:** Aprobación formal de ascensos de grado y generación de certificados.
- **`GruposController.php`:** Creación de horarios y grupos de entrenamiento.
- **`SedesController.php`:** Gestión de ubicaciones geográficas y escuelas.
- **`TeoriaController.php`:** Carga y edición de material teórico y manuales.
- **`CalendarioController.php`:** Publicación de eventos en el calendario general.
- **`GaleriaController.php`:** Subida y borrado de fotos institucionales.
- **`PerfilesPublicosController.php`:** Control de visibilidad pública de maestros y miembros destacados.
- **`RegistrosController.php`:** Moderación de solicitudes de nuevo registro de usuarios.

---

### 2. Módulo Maestro (`controladores/Maestro/`)
Exige rol de Maestro o Monitor (`Security::verifyMaestro()`).

- **`DashboardController.php`:** Panel con próximos cronogramas y accesos directos del profesor.
- **`AlumnosController.php`:** Listado de alumnos a su cargo, detalle de asistencias y progreso.
- **`CronogramasController.php`:** Diseñador de clases: crear plan, añadir ejercicios y definir orden.
- **`EjerciciosController.php`:** Biblioteca personal y general de ejercicios y técnicas.
- **`SolicitudesAscensoController.php`:** Postulación de alumnos preparados para examen de cinturón.

---

### 3. Módulo Estudiante (`controladores/Estudiante/`)
Para deportistas activos.

- **`DashboardController.php`:** Resumen personal: cinturón actual, asistencias y próximos eventos.
- **`EstudioController.php`:** Acceso a guías teóricas, terminología coreana y videos según su grado.
- **`HistorialController.php`:** Historial de exámenes aprobados y descarga de certificados digitales.

---

### 4. Módulos Autenticación, Usuario y Web Pública

- **`AutenticacionController.php`:** Procesamiento de login, validación de credenciales, logout seguro y registro.
- **`UsuarioPerfilController.php`:** Edición de avatar, cambio de contraseña y datos personales.
- **`WebInicioController.php`, `WebSedesController.php`, `WebGruposController.php`:** Páginas del portal público accesibles sin iniciar sesión.

---

Siguiente capítulo: [[07_CASOS_DE_USO_PASO_A_PASO|07 - Casos de Uso Paso a Paso]].
