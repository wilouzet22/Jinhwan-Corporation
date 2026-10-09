# AGENTS.md - Reglas y Directrices del Proyecto Jinhwan Corporation

Este archivo contiene las directrices, restricciones y reglas operativas que cualquier Inteligencia Artificial debe acatar obligatoriamente al interactuar con este proyecto.

---

## 1. Protocolo Obligatorio de Interacción con el Usuario

Cada vez que el usuario solicite una tarea o cambio, la IA debe cumplir estrictamente las siguientes reglas:

1. **Aviso Previo**: Antes de realizar cualquier acción (editar archivos, buscar código, etc.), explicar con claridad qué se va a hacer y declarar explícitamente si se accederá o no a la terminal.
2. **Terminal y Comandos Git**:
   - En Windows (PowerShell/CMD), **NUNCA usar `&&`** para encadenar comandos. Usar siempre el separador `;`.
   - Al finalizar cualquier proceso o cambio, generar siempre el comando de Git listo para copiar:
     ```bash
     git add . ; git commit -m "tipo: descripción concisa del cambio" ; git push
     ```
3. **Respuestas Concisas**: Al finalizar una tarea, responder de forma corta, directa y concisa. Evitar textos largos, explicaciones redundantes o rellenos innecesarios.
4. **Preguntar ante la Duda**: Si existe ambigüedad en los requerimientos o en cómo abordar una funcionalidad, preguntar directamente al usuario antes de asumir o inventar.

---

## 2. Stack Tecnológico

- **Backend**: PHP nativo / puro con arquitectura MVC personalizada (sin frameworks pesados como Laravel o Symfony).
- **Base de Datos**: MySQL / MariaDB gestionado con `mysqli` a través de la clase singleton `Database::getInstance()->getConnection()`.
- **Frontend**: HTML5 semántico, Vanilla JavaScript (ES6+), Tailwind CSS (clases utilitarias directas).
- **Librerías Frontend**:
  - Gráficas: `Chart.js` (UMD).
  - Exportación Excel: `SheetJS (xlsx.full.min.js)`.
  - Exportación PDF: `jsPDF` y `jsPDF-AutoTable`.
  - Iconografía: Google Material Icons Outlined (`class="material-icons-outlined"`).
- **Entorno de Servidor y Hosting**:
  - **Local**: Laragon en Windows (Apache / Nginx, PHP 8+, MySQL).
  - **Producción / Despliegue**: **InfinityFree** (Apache, PHP, base de datos MySQL/MariaDB `if0_42216592_jinhwa_corporation` vía phpMyAdmin).
  - **Compatibilidad SQL**: Todos los scripts de migración o consultas deben ser compatibles con el motor MariaDB/MySQL de InfinityFree.

---

## 3. Modelo de Datos y Tablas (CRÍTICO)

Queda terminantemente prohibido inventar tablas genéricas. La arquitectura de usuarios está dividida en tres entidades específicas:

1. **`estudiante`**: Contiene exclusivamente los alumnos.
   - Campos: `id_estudiante`, `id_grado`, `id_categoria`, `id_grupo`, `id_maestro`, `nombre`, `apellido`, `tipo_documento`, `num_doc`, `telefono`, `fecha_nacimiento`, `correo`, `clave`, `activo`, `peso`, `division`, `eps`, `rh`, `foto_perfil`.
2. **`maestro`**: Contiene profesores e instructores de la academia.
3. **`admin`**: Contiene directivos y administradores del sistema.

> ⚠️ **PROHIBICIÓN**: NO existe ni debe usarse una tabla llamada `miembros` ni `usuarios` para la lógica de alumnos. Cualquier consulta, controlador o vista de estudiantes debe consultar la tabla `estudiante`.

### Tablas Auxiliares
- `sedes`: Sedes físicas de la academia (`id_sede`, `nombre`, `direccion`, etc.).
- `grupos`: Grupos de entrenamiento vinculados a una sede y un maestro (`id_grupo`, `nombre`, `id_sede`, `id_maestro`).
- `grados`: Cinturones y grados de Taekwondo (`id_grado`, `nombre`, `color`, `orden`).
- `categorias`: Categorías de edad/competencia (`id_categoria`, `nombre`).
- `certificados_ascenso`: Registro de exámenes y ascensos aprobados.
- `eventos`: Calendario académico y torneos.

---

## 4. Convenciones de Nomenclatura y Estructura

- **Idioma**: Todo el código, nombres de variables, métodos, tablas, vistas, rutas y comentarios deben estar en **español**.
- **Estructura de Carpetas**:
  - `controladores/`: Controladores divididos por rol (`Administracion/`, `Maestro/`, `Estudiante/`, `Web/`).
  - `modelos/`: Clases de acceso a datos y lógica de negocio.
  - `vistas/`:
    - `vistas/administracion/`: Módulos del panel de control administrativo.
    - `vistas/maestro/`: Módulos exclusivos para profesores.
    - `vistas/estudiante/`: Panel del alumno.
    - `vistas/layout/`: Plantillas maestras de cabecera y pie (`administracion_cabecera.php`, `administracion_pie.php`, `sitio_cabecera.php`, `sitio_pie.php`).
    - `vistas/web/`: Portal público informativo.
  - `public/`: Assets estáticos (`css/`, `js/`, `img/`, `uploads/perfiles/`).

---

## 5. Prohibiciones Estrictas

1. ❌ **NO usar `&&` en la terminal de Windows**; usar siempre `;`.
2. ❌ **NO inventar tablas o campos** que no existan en la base de datos real.
3. ❌ **NO usar la palabra ni tabla `miembros`** al referirse o consultar a los estudiantes.
4. ❌ **NO emitir respuestas gigantescas** al concluir una tarea; mantenerlas concisas.
5. ❌ **NO usar comandos `cd`** en la terminal.
6. ❌ **NO sobrescribir código sin previo aviso** al usuario.
