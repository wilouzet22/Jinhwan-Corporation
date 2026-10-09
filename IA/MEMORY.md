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
- **Hosting y Entorno de Producción**:
  - Servidor en **InfinityFree** con base de datos remota MariaDB (`if0_42216592_jinhwa_corporation` en `sql113.infinityfree.com`).
  - Todo cambio o script SQL de migración debe tenerse en cuenta para aplicarse tanto en Laragon (local) como en phpMyAdmin de **InfinityFree**.
- **Módulo de Teoría**:
  - Se estructuró la tabla `tipos_teoria` con los 4 tipos oficiales del programa de Taekwondo:
    1. `Poomsae` (Formas y secuencias oficiales).
    2. `Técnicas` (Chagui, Makki, Jireugi).
    3. `Vocabulario Coreano` (Términos y comandos en dojang).
    4. `Código de Honor` (Filosofía y principios del club).
  - El panel administrativo (`teoria.php`) y el modelo `Teoria.php` quedan sincronizados con estos 4 tipos.

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
- **Limpieza del Dashboard y Módulo de Solicitudes y Ascensos**:
  - Se eliminó el bloque obsoleto de "Solicitudes de Ascenso" del dashboard principal (`vistas/administracion/dashboard.php`).
  - Se eliminó la barra de búsqueda obsoleta ("Buscar miembro...") del banner superior del Centro de Control en `dashboard.php`.
  - La opción **"Solicitudes"** (`/admin/registros`) se mantiene en el menú lateral de administración dedicada **exclusivamente a las nuevas solicitudes de registro** de alumnos al sistema.
  - El administrador ahora cuenta con la columna **"Diploma"** en el Historial de Ascensos (`ascensos.php`) con botón para previsualizar el diploma oficial y descargarlo en formato PDF en alta calidad mediante `html2pdf.js`.
  - **Reestructuración y Estandarización Oficial del Diploma (`certificado_preview.php`)**:
    - **Plantilla base oficial (`diploma_base.pdf`)**: Se extrajo y limpió de `resources/assets/diploma_base.pdf` la plantilla base institucional en alta definición (`1278 x 1654 px`, proporción Letter 8.5" x 11") eliminando remanentes de líneas, texto estático ("TI") y fechas antiguas, conservando intactos los logos, textos preimpresos, firmas y el ideograma de agua coreano (*태권도*). Se guardó en `public/img/visual/diploma_plantilla_v3.png` y `diploma_base.png`.
    - **Tipografía Oficial Caligráfica (`Script MT Bold`)**: Se identificó que la tipografía de todo el diploma oficial es **Script MT Bold** (`SCRIPTBL.TTF`). Se alojó en `public/fonts/ScriptMTBold.ttf` y se incrustó en Base64 en el CSS `@font-face`, garantizando renderizado perfecto en cualquier dispositivo y compatibilidad inmediata con `html2canvas` / `html2pdf.js` sin solicitudes de red externas ni fallos de CORS.
    - **Estructura visual idéntica al certificado físico del club**:
      1. **Nombre del Estudiante**: Centrado en Title Case con tipografía `Script MT Bold` (38px, color negro puro `#000000`) debajo de *"Certifica que"*.
      2. **Documento de Identidad**: Centrado con tipografía `Script MT Bold` (26px, ej. *"TI 1013462218"*).
      3. **Cuerpo formal**: Texto preimpreso *"Aprobó el examen reglamentario para Ascenso de grado..."* 100% respetado sin superposiciones.
      4. **Bloque de Acreditación**: Directamente bajo *"Acredita como:"* y centrado sobre la marca de agua coreana:
         - **Grado alcanzado**: En `Script MT Bold` (30px, ej. *"Cinturon Rojo P, Negra"*).
         - **Gup / Nivel**: En `Script MT Bold` (26px, ej. *"Gup 1"*, *"10° Gup"*, etc. calculado automáticamente según el grado).
         - **Fecha del examen**: En `Script MT Bold` (22px, ej. *"16 de Noviembre del 2025"*).
      5. **Área de firmas**: Mantiene las firmas de los directores, maestros evaluadores y el logo WTF intactos.
    - **Descarga y Exportación PDF**:
      - Configuración de `html2pdf.js` con margen `0`, escala `2` (alta resolución) y orientación `letter portrait`.
      - Nombre de archivo dinámico basado en el alumno: `diploma-[nombre-alumno].pdf`.
      - Script local `html2pdf.bundle.min.js` configurado con `charset="utf-8"` y fallback a CDNJS.
      - Soporte para `@media print` nativo para guardar como PDF en cualquier navegador.

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
