# SKILLS.md - Habilidades y Recetas Paso a Paso del Proyecto

Este archivo describe las instrucciones exactas y estandarizadas para llevar a cabo tareas comunes y repetitivas dentro de la arquitectura de Jinhwan Corporation.

---

## Skill 1: Crear o Actualizar una Vista Administrativa con Tabla, Filtros y Paginación

Cuando se deba construir o refactorizar un módulo CRUD en `vistas/administracion/`:

1. **Cabecera y Métricas**:
   - Incluir `include __DIR__ . '/../layout/administracion_cabecera.php';`.
   - Calcular tarjetas de estadísticas en la parte superior (Total, Activos, Inactivos u otra métrica relevante).
2. **Barra de Herramientas**:
   - Barra de eliminación masiva oculta por defecto (`#bulk-action-bar`).
   - Botones de acción: Exportar Excel (`openExportModal('excel')`), Exportar PDF (`openExportModal('pdf')`) y Nuevo Registro (`openModal('add')`).
3. **Barra de Filtros**:
   - Buscador por texto con icono `search`.
   - Selectores de filtrado con valor por defecto `all`.
   - Botón de reset `#btn-reset-filters` que se muestra únicamente cuando hay filtros activos.
4. **Tabla Principal**:
   - Checkbox maestro en el `<thead>` para seleccionar todos los elementos visibles.
   - Filas con atributos `data-*` correspondientes para búsqueda rápida sin recargar la página.
   - Columnas claras: Checkbox, Entidad principal (avatar/nombre/doc), Datos clave, Contacto/Detalles, Estado (Badge), Acciones (Ver detalle, Editar, Eliminar).
5. **Paginación en Cliente**:
   - Controles de paginación limitados a 10 registros por página (`pageSize = 10`).
   - Texto informativo: *"Mostrando X a Y de Z registros"*.
   - Botones "Anterior", "Siguiente" y botones numéricos dinámicos generados por JS.
6. **Modales Obligatorios**:
   - Modal Formulario: Para agregar y editar (con campo oculto `id`, reset de formulario y action dinámico).
   - Modal Detalle Completo: Vista de lectura detallada con botón rápido "Editar".
   - Modal de Exportación: Selección de columnas con checkboxes y descarga directa.
7. **Pie de Página**:
   - Incluir `include __DIR__ . '/../layout/administracion_pie.php';`.

---

## Skill 2: Crear un Endpoint o Acción CRUD en Controlador MVC

Al implementar acciones en `controladores/Administracion/`:

1. **Seguridad y Método HTTP**:
   ```php
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $db = Database::getInstance()->getConnection();
       // Sanitizar y validar
   }
   ```
2. **Subida de Archivos / Fotos**:
   - Validar extensiones permitidas (`jpg`, `jpeg`, `png`, `webp`) y tamaño máximo (2MB).
   - Guardar en `public/uploads/perfiles/` con nombre hash único (`md5(time() . $name)`).
   - Eliminar el archivo anterior si se sustituye o si se marca la casilla `eliminar_foto`.
3. **Sentencias Preparadas con MySQLi**:
   ```php
   $stmt = $db->prepare("INSERT INTO estudiante (...) VALUES (?, ?, ...)");
   $stmt->bind_param("sss...", $param1, $param2, ...);
   $stmt->execute();
   $stmt->close();
   ```
4. **Redirección Final**:
   ```php
   $this->redirect('/admin/estudiantes');
   ```

---

## Skill 3: Implementar Gráficas Interactivas con Chart.js

Para renderizar gráficas sincronizadas con la tabla en cualquier vista:

1. **Cargar la librería**:
   ```html
   <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
   ```
2. **Estructura HTML**:
   - Colocar el canvas dentro de un contenedor relativo con altura mínima (`min-h-[190px]`).
3. **Inicialización JavaScript**:
   - Detectar tema claro/oscuro para contrastes:
     ```javascript
     const isDark = document.documentElement.classList.contains('dark');
     const tickColor = isDark ? '#94a3b8' : '#64748b';
     const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
     ```
4. **Sincronización con Filtros (`updateCharts`)**:
   - Cada vez que el usuario filtre o busque, la función `updateCharts(visibleRows)` debe iterar sobre las filas visibles (`display !== 'none'`) y refrescar `chart.data` llamando a `chart.update()`.

---

## Skill 4: Implementar Exportación de Datos (Excel y PDF)

Para añadir descarga de reportes en el navegador sin recargar:

1. **CDNs Requeridos**:
   ```html
   <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
   ```
2. **Objeto de Definición de Columnas**:
   ```javascript
   const EXPORT_COLUMNS = {
       nombre: { label: 'Nombre Completo', get: d => (d.nombre + ' ' + d.apellido).trim() },
       documento: { label: 'Documento', get: d => d.numero_documento || '' },
       // ...
   };
   ```
3. **Lógica de Generación**:
   - Filtrar únicamente los IDs de los registros visibles en pantalla.
   - Si es **Excel**: Construir con `XLSX.utils.aoa_to_sheet([headers, ...rows])` y guardar con `XLSX.writeFile`.
   - Si es **PDF**: Instanciar `new window.jspdf.jsPDF()`, llamar a `doc.autoTable(...)` y guardar con `doc.save`.
