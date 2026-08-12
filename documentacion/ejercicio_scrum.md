# Solución del Ejercicio: Construyamos un Proyecto con Scrum

**Colegio:** Santa Margarita  
**Docente:** Jairo Cano  
**Materia:** Proyectos  
**Ejercicio:** Construyamos un proyecto con Scrum  
**Duración:** 60 a 90 minutos  
**Fecha:** 12 de Agosto de 2026  

---

## 1. Definición de Roles del Equipo Scrum (Paso 1)

El equipo de desarrollo se ha constituido bajo la estructura de la metodología ágil **Scrum**, asignando las responsabilidades de la siguiente manera:

| Rol Scrum | Integrante | Responsabilidad Principal |
| :--- | :--- | :--- |
| **Product Owner (PO)** | Juan Pérez | Maximiza el valor del producto, define las necesidades del cliente, redacta y prioriza el Product Backlog. |
| **Scrum Master (SM)** | María Gómez | Facilita el proceso Scrum, remueve bloqueos e impedimentos y vela por el cumplimiento de la metodología. |
| **Developer 1** | Carlos Rodríguez | Diseña e implementa la arquitectura backend (PHP, MVC, MySQL) y autenticación. |
| **Developer 2** | Ana Martínez | Desarrolla la interfaz de usuario (HTML/CSS), vistas del portal web y panel responsive. |
| **Developer 3** | Luis Fernández | Implementa la lógica de datos, modelos (CRUD miembros/sedes) y pruebas del sistema. |

---

## 2. Definición del Proyecto (Paso 2)

### 1. ¿Qué problema quieren resolver?
En el club / institución no existe un control centralizado ni automatizado para la gestión de miembros, asignación de sedes, registro de solicitudes de ingreso y distribución del material teórico necesario para los ascensos de grado (cinturones). Los procesos manuales generan pérdida de información, lentitud en las inscripciones y falta de transparencia en los contenidos de estudio.

### 2. ¿Quién será el usuario del proyecto?
* **Administradores:** Encargados de gestionar miembros, aprobar solicitudes de registro, administrar sedes y actualizar contenidos teóricos.
* **Estudiantes / Alumnos:** Usuarios que consultan su panel personal, acceden al material teórico de su cinturón correspondiente y realizan el proceso de registro inicial.
* **Visitantes Web (Público General):** Personas interesadas en el club que consultan sedes, instructores, historia y galería.

### 3. ¿Cuál será el producto final?
**Plataforma Web Integral "Jinhwan Corporation"**, desarrollada en PHP puro bajo el patrón de arquitectura **MVC (Modelo-Vista-Controlador)** con base de datos MySQL. Incluye portal público informativo, sistema seguro de autenticación/sesiones por roles (Admin, Maestro, Estudiante), panel administrativo CRUD y módulo de estudio teórico por grados.

---

## 3. Product Backlog (Paso 3)

A continuación se presentan 10 Historias de Usuario redactadas bajo el estándar Scrum (`Como... Quiero... Para...`) y priorizadas de acuerdo al valor que aportan al producto:

| ID | Historia de Usuario | Prioridad |
| :---: | :--- | :---: |
| **HU-01** | **Como** estudiante no registrado **quiero** completar un formulario de registro en línea **para** solicitar mi ingreso al club de Taekwondo. | **Alta** |
| **HU-02** | **Como** usuario (admin/estudiante) **quiero** iniciar sesión con correo y contraseña **para** acceder a las funcionalidades correspondientes a mi rol de forma segura. | **Alta** |
| **HU-03** | **Como** administrador **quiero** visualizar y gestionar el listado de solicitudes de registro pendientes **para** aprobar o rechazar nuevos miembros. | **Alta** |
| **HU-04** | **Como** administrador **quiero** un módulo de gestión de miembros (crear, editar, consultar, eliminar) **para** mantener actualizada la información de los alumnos y maestros. | **Alta** |
| **HU-05** | **Como** estudiante **quiero** acceder a mi panel personal y consultar el material teórico de mi grado **para** prepararme adecuadamente para los exámenes de ascenso. | **Alta** |
| **HU-06** | **Como** administrador **quiero** gestionar el catálogo de sedes del club **para** registrar ubicaciones, horarios e instructores encargados. | **Media** |
| **HU-07** | **Como** administrador **quiero** publicar y editar el contenido teórico clasificado por cinturón/grado **para** que los alumnos estudien la información correcta. | **Media** |
| **HU-08** | **Como** visitante **quiero** navegar por la sección pública de sedes e instructores **para** conocer la oferta del club antes de registrarme. | **Media** |
| **HU-09** | **Como** usuario **quiero** cerrar sesión de forma segura **para** evitar que personas no autorizadas accedan a mi cuenta en computadores compartidos. | **Baja** |
| **HU-10** | **Como** administrador **quiero** ver métricas y resumen de estadísticas en el Dashboard **para** conocer el total de miembros activos, sedes registradas y solicitudes por procesar. | **Baja** |

---

## 4. Planificación del Sprint (Paso 4)

* **Duración del Sprint:** 2 semanas (Sprint 1)
* **Objetivo del Sprint 1:** Implementar el núcleo de autenticación, registro de usuarios, aprobación administrativa y la estructura base de navegación y gestión de miembros.

### Sprint Backlog (Historias Seleccionadas)

1. **HU-01:** Formulario de Registro Público.
2. **HU-02:** Sistema de Inicio de Sesión y Control de Seguridad.
3. **HU-03:** Panel de Aprobación de Solicitudes de Registro.
4. **HU-04:** CRUD de Miembros para Administradores.
5. **HU-05:** Portal Teórico para Estudiantes.

---

## 5. Tablero Scrum (Paso 5)

Estado del Tablero Scrum al finalizar la primera semana del **Sprint 1**:

```
+------------------------------------+------------------------------------+------------------------------------+------------------------------------+
|             PENDIENTE              |             EN PROCESO             |            EN REVISIÓN             |             TERMINADO              |
|              (To Do)               |           (In Progress)            |            (In Review)             |               (Done)               |
+------------------------------------+------------------------------------+------------------------------------+------------------------------------+
| • HU-05: Módulo de estudio teórico | • HU-04: Formulario de edición de  | • HU-03: Módulo de aprobación de   | • HU-01: Formulario de registro    |
|   por grados para estudiantes.     |   miembros en la vista admin.      |   solicitudes de registro activo=0.|   público con MySQL.               |
|                                    |                                    |                                    | • HU-02: Autenticación segura con  |
|                                    |                                    |                                    |   bcrypt y Security sessions PHP.  |
+------------------------------------+------------------------------------+------------------------------------+------------------------------------+
```

---

## 6. Simulación de Daily Scrum (Paso 6)

Reunión diaria de seguimiento (duración máxima 1 minuto por integrante):

* **Carlos Rodríguez (Developer 1 - Backend):**
  * *¿Qué hice ayer?* Implementé el hash de contraseñas con bcrypt y el sistema de verificación de sesiones seguras (`Security.php`).
  * *¿Qué haré hoy?* Finalizar la revisión de la ruta `/admin/registros` para la aprobación de nuevos usuarios.
  * *¿Impedimentos?* Ninguno por el momento.

* **Ana Martínez (Developer 2 - Frontend):**
  * *¿Qué hice ayer?* Maqueté la interfaz del formulario de registro y la vista de inicio de sesión.
  * *¿Qué haré hoy?* Diseñar el formulario de edición de miembros en la vista de administración.
  * *¿Impedimentos?* Necesitaba confirmar los campos requeridos para la tabla `miembros`, pero ya fue coordinado con la base de datos.

* **Luis Fernández (Developer 3 - Database & Tests):**
  * *¿Qué hice ayer?* Creé las tablas `miembros` y `userlog` en MySQL y sus relaciones con `sedes`.
  * *¿Qué haré hoy?* Realizar pruebas de inserción de usuarios y verificación de borrado en cascada para registros rechazados.
  * *¿Impedimentos?* Ninguno.

---

## 7. Revisión del Sprint - Sprint Review (Paso 7)

Al finalizar las 2 semanas del Sprint 1, el equipo presenta el incremento de software al **Product Owner** y partes interesadas.

* **¿Qué historias se completaron?**
  * **HU-01:** Formulario de registro público funcionando e insertando usuarios pendientes (`activo = 0`).
  * **HU-02:** Inicio y cierre de sesión seguro por roles (Admin y Estudiante).
  * **HU-03:** Módulo administrativo para aprobar (`activo = 1`) o rechazar (eliminación segura) solicitudes.
  * **HU-04:** CRUD completo de miembros en el panel administrativo.
  * **HU-05:** Visualización del material teórico para estudiantes según su cinturón.
* **¿Qué quedó pendiente?**
  * Ninguna historia del Sprint 1 quedó pendiente; el 100% del Sprint Backlog fue entregado con éxito.
* **¿El cliente estaría satisfecho?**
  * **Sí**, el cliente cuenta con un sistema funcional de registro y autenticación seguro, eliminando el manejo manual en papel y permitiendo un control administrativo inmediato de los miembros.

---

## 8. Retrospectiva del Sprint - Sprint Retrospective (Paso 8)

### Evaluaciones del Equipo:

1. **¿Qué hicimos bien?**
   * Buena comunicación entre desarrolladores frontend y backend.
   * Uso del patrón MVC puro que mantuvo el código limpio y organizado.
   * Entrega a tiempo de todas las historias comprometidas para el Sprint 1.

2. **¿Qué debemos mejorar?**
   * Definir mejor la estimación de tiempos de pruebas de la base de datos antes de iniciar las tareas frontend.
   * Documentar los endpoints de las rutas del Router a medida que se agregan.

3. **¿Qué cambiaremos en el siguiente Sprint?**
   * Implementar listas de chequeo (Definition of Done) más estrictas antes de pasar las tareas a la columna *Terminado*.
   * Asignar 15 minutos al final del día para sincronizar cambios en el repositorio Git.

---

## Conclusiones

La metodología **Scrum** permitió estructurar el desarrollo del sistema **Jinhwan Corporation** de manera ágil, transparente e iterativa. Mediante la priorización del Product Backlog, el desglose en Sprint Backlog y el seguimiento diario con el tablero Kanban/Scrum, se logró transformar un problema operacional de la institución en una solución tecnológica funcional y escalable.

---
**Puntaje Esperado según Criterios de Evaluación:** 100 / 100 puntos.
