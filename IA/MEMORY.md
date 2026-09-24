# MEMORY.md - Registro de Decisiones y Memoria del Proyecto

Este archivo documenta las decisiones clave de arquitectura, cambios estructurales y preferencias del usuario para mantener la consistencia a lo largo del desarrollo.

---

## 1. Decisiones de Base de Datos y Modelado

- **Separación de Entidades**: Se desechó la antigua tabla genérica `miembros`/`usuarios` para alumnos. Actualmente el sistema opera con tablas dedicadas:
  - `estudiante`: Alumnos de la academia.
  - `maestro`: Profesores e instructores.
  - `admin`: Personal administrativo.
- **Campos Médicos y Deportivos**: La tabla `estudiante` integra directamente información deportiva (`id_grado`, `id_grupo`, `id_categoria`, `division`) e información médica (`peso`, `eps`, `rh`).
- **Relaciones Clave**: Los estudiantes están asignados a un `id_grupo`, y a través del grupo se determina su sede (`id_sede`) y su maestro asignado (`id_maestro`).

---

## 2. Decisiones de Interfaz y Vistas Administrativas

- **Sustitución de `miembros.php` por `estudiantes.php`**:
  - Toda la gestión de alumnos se realiza en `vistas/administracion/estudiantes.php`.
  - Esta vista cuenta con paridad funcional total: exportación a Excel (SheetJS), exportación a PDF (jsPDF), paginación de 10 registros por página, selección múltiple y eliminación masiva.
  - Rutas vinculadas: `/admin/estudiantes`, `/admin/estudiantes/create`, `/admin/estudiantes/update`, `/admin/estudiantes/delete`, `/admin/estudiantes/delete-bulk`.
- **Gráficas de la Vista de Estudiantes**:
  - **Gráfica 1 (Cinturones)**: Gráfica tipo Dona de Chart.js con la jerarquía oficial de grados de Taekwondo. Para cinturones con punta/pinta (ej. Punta Amarilla, Punta Verde, Punta Azul, Punta Roja, Punta Negra) se renderizan tramas rayadas mediante un canvas pattern (`createStripePattern`).
  - **Gráfica 2 (Grupos)**: Gráfica de barras horizontales/verticales que representa **"Alumnos por Grupo"** (se cambió la distribución original por sedes a distribución por grupos).
- **Filtros de Estudiantes**:
  - El orden de visualización de los filtros en la barra superior es:
    1. Buscador en tiempo real (por nombre y documento).
    2. **Filtro de Grupo** (priorizado).
    3. Filtro de Sede.
    4. Filtro de Grado / Cinturón.
    5. Filtro de Estado (Activo / Inactivo).
    6. Botón de limpieza rápida de filtros.
- **Limpieza del Dashboard**:
  - Se eliminó el bloque obsoleto de "Solicitudes de Ascenso" del dashboard principal (`vistas/administracion/dashboard.php`).

---

## 3. Decisiones de Diseño y Portal Web

- **Redes Sociales en Footer (`sitio_pie.php`)**:
  - Se configuraron los colores corporativos oficiales:
    - Facebook: `#1877F2`
    - Instagram: Degradado / Color `#E1306C`
    - TikTok: Añadido con icono y enlaces corporativos.
    - WhatsApp: Añadido con icono y enlace directo a chat.

---

## 4. Preferencias del Desarrollador / Usuario

1. **Separador de comandos en terminal**: Usar siempre `;` (punto y coma) en Windows PowerShell/CMD. Nunca usar `&&`.
2. **Protocolo de comunicación**:
   - Avisar qué se va a hacer antes de actuar.
   - Declarar si se utilizará la terminal.
   - Entregar respuestas cortas y concisas al finalizar.
   - Generar el comando Git de una sola línea listo para copiar:
     `git add . ; git commit -m "..." ; git push`
