# Plan de Implementación: Hojas de Vida por Cinturón (Certificados de Grado)

## Objetivo
Generar, guardar y exportar un **Certificado de Ascenso de Grado** cada vez que un **maestro** aprueba una solicitud de ascenso. Los estudiantes podrán ver y descargar sus certificados en su sección de **Historial de Ascensos**, y el administrador podrá ver el historial de movimientos realizados por los maestros.

---

## 🔄 Nuevo Flujo de Trabajo

### Flujo Actualizado:
1. **Maestro** crea solicitudes de ascenso para sus alumnos
2. **Maestro** aprueba/rechaza las solicitudes y redacta los certificados
3. **Estudiante** ve y descarga sus certificados en el historial
4. **Administrador** visualiza el historial de movimientos de todos los maestros

---

## 1. Base de Datos: Nueva tabla `certificados_ascenso`

Añadir una tabla en MySQL que almacene la información del certificado al momento de la aprobación.

```sql
CREATE TABLE `certificados_ascenso` (
  `id_certificado`   INT          NOT NULL AUTO_INCREMENT,
  `id_solicitud`     INT          NOT NULL,
  `id_persona`       INT          NOT NULL,
  `id_maestro`       INT          NOT NULL,
  `grado_anterior`   VARCHAR(80)  NOT NULL,
  `grado_nuevo`      VARCHAR(80)  NOT NULL,
  `fecha_examen`     DATE         NOT NULL,
  `observaciones`    TEXT,
  `folio`            VARCHAR(30)  NOT NULL,
  `creado_en`        TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_certificado`),
  UNIQUE KEY `uk_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_cert_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_ascenso` (`id_solicitud`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_persona`   FOREIGN KEY (`id_persona`)   REFERENCES `personas` (`id_persona`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_maestro`   FOREIGN KEY (`id_maestro`)   REFERENCES `personas` (`id_persona`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

> [!IMPORTANT]
> Este SQL debe ejecutarse manualmente en phpMyAdmin antes de que implementemos el código.

---

## 2. Backend PHP (MVC)

### Cambios en `MaestroSolicitudesAscensoController.php`
- **Nuevo método `aprobar()`**: Aprueba la solicitud, actualiza el grado del estudiante y genera el certificado
- **Nuevo método `rechazar()`**: Rechaza la solicitud de ascenso
- **Nuevo método `certificado()`**: Endpoint AJAX para mostrar el certificado en modal

### Cambios en `RegistrosController.php` (Administrador)
- **Modificado `index()`**: Ahora muestra historial completo de movimientos (no aprobar/rechazar)
- **Mantenido `certificadoPreview()`**: Para que el admin pueda ver certificados generados

### Modelo `Certificado.php`
Métodos: `create()`, `getByPersona()`, `getBySolicitud()`, `getById()`, `generarFolio()`

### Vista `certificado_preview.php`
HTML del certificado con diseño institucional de Jinhwan, que funciona como base para el PDF.

---

## 3. Frontend

### Panel del Maestro (solicitudes_ascenso.php)
- **Botones de acción**: Aprobar (✓) y Rechazar (✗) para solicitudes pendientes
- **Prompt de observaciones**: Al aprobar, el maestro redacta las observaciones del certificado
- **Botón "Ver Certificado"**: Para solicitudes ya aprobadas
- **Modal con certificado**: Visualización y descarga en PDF

### Historial del Estudiante
En cada tarjeta de ascenso **aprobado** aparecen dos botones:
```
🏅 Ver Certificado   →  modal con el diseño oficial
📄 Descargar PDF     →  exporta el certificado a PDF (html2pdf.js)
```

### Panel del Administrador (registros.php)
- **Historial de movimientos**: Tabla con todos los movimientos de ascensos (aprobados, rechazados, pendientes)
- **Solo visualización**: No puede aprobar/rechazar, solo ver el historial
- **Botón "Ver Certificado"**: Para movimientos aprobados con certificado

---

## 4. Diseño del Certificado

```
┌───────────────────────────────────────────────────┐
│  [LOGO]   JINHWAN CORPORATION    Folio: JH-25-042  │
│        Certificado de Ascenso de Grado             │
├───────────────────────────────────────────────────┤
│  Foto  │  Nombre Completo                          │
│        │  Documento: CC 12345678                   │
│        │  Sede: Principal                          │
│        │  Fecha: 29 Ago 2025                       │
├───────────────────────────────────────────────────┤
│      Cinturón Azul  ──►  Cinturón Rojo             │
├───────────────────────────────────────────────────┤
│  Observaciones del Maestro:                        │
│  "El alumno demostró excelente técnica..."         │
├───────────────────────────────────────────────────┤
│  ___________________   ____________________        │
│  Maestro Evaluador      Director Jinhwan Corp.     │
└───────────────────────────────────────────────────┘
```

---

## Archivos a Crear / Modificar

### Nuevos
| Archivo | Descripción |
|---|---|
| `modelos/Certificado.php` | Modelo de la tabla `certificados_ascenso` |
| `vistas/administracion/certificado_preview.php` | Vista HTML del certificado |

### Modificados
| Archivo | Cambio |
|---|---|
| `controladores/Maestro/SolicitudesAscensoController.php` | Agregar métodos aprobar(), rechazar(), certificado() |
| `controladores/Administracion/RegistrosController.php` | Cambiar a historial de movimientos solo lectura |
| `controladores/Estudiante/HistorialController.php` | Traer datos del certificado junto al historial |
| `vistas/maestro/solicitudes_ascenso.php` | Botones aprobar/rechazar y modal de certificado |
| `vistas/estudiante/historial.php` | Botones "Ver Certificado" / "Descargar PDF" |
| `vistas/administracion/registros.php` | Historial de movimientos y botón ver certificado |
| `ruteador.php` | Agregar rutas para maestro (aprobar, rechazar, certificado) |

---

## Estado de Implementación: ✅ COMPLETADO (Nuevo Flujo)

### ✅ Elementos Implementados

1. **Base de Datos**
   - ✅ Tabla `certificados_ascenso` creada en `jinhwa_corporation.sql`
   - ✅ Folio automático formato `JH-YYYY-XXXX`

2. **Backend PHP (MVC)**
   - ✅ Modelo `Certificado.php` con métodos: `create()`, `getByPersona()`, `getBySolicitud()`, `getById()`, `generarFolio()`
   - ✅ Controlador `MaestroSolicitudesAscensoController.php` con métodos `aprobar()`, `rechazar()`, `certificado()`
   - ✅ Controlador `RegistrosController.php` modificado para mostrar historial de movimientos
   - ✅ Controlador `HistorialController.php` con método `certificado()` para estudiantes

3. **Frontend**
   - ✅ Vista `certificado_preview.php` con diseño institucional Jinhwan
   - ✅ Panel de maestro (`solicitudes_ascenso.php`) con aprobación directa y generación de certificados
   - ✅ Panel de administrador (`registros.php`) con historial de movimientos solo lectura
   - ✅ Historial del estudiante (`historial.php`) con botones "Ver Certificado" y "Descargar PDF"
   - ✅ Modales para visualizar certificados en maestro y estudiante
   - ✅ Funcionalidad para exportar a PDF usando html2pdf.js

4. **Rutas**
   - ✅ `/maestro/solicitudes-ascenso/aprobar` agregada
   - ✅ `/maestro/solicitudes-ascenso/rechazar` agregada
   - ✅ `/maestro/solicitudes-ascenso/certificado` agregada
   - ✅ `/admin/certificado-preview` para visualización de admin
   - ✅ `/estudiante/historial/certificado` para estudiantes

### 📋 Instrucciones para Ejecutar

1. **Ejecutar el SQL** en phpMyAdmin:
   ```sql
   -- Ejecutar el contenido actualizado de jinhwa_corporation.sql
   -- Esto creará la tabla certificados_ascenso
   ```

2. **Flujo de Prueba (Nuevo)**:
   - Un maestro crea una solicitud de ascenso para sus alumnos
   - El maestro aprueba la solicitud (se le pedirá observaciones para el certificado)
   - Se genera automáticamente el certificado con folio único
   - El estudiante puede ver su certificado en el historial
   - El administrador puede ver el historial de movimientos de todos los maestros

### 🎨 Características del Certificado

- Diseño institucional con logo de Jinhwan Corporation
- Folio único automático (ej. `JH-2025-0042`)
- Información del alumno (nombre, documento, sede)
- Ascenso de grado (grado anterior → grado nuevo)
- Observaciones redactadas por el maestro evaluador
- Firmas del maestro evaluador y dirección nacional
- Exportación a PDF con alta calidad

### 🔒 Control de Accesos

- **Maestro**: Puede crear, aprobar, rechazar solicitudes y generar certificados
- **Estudiante**: Solo puede ver sus propios certificados
- **Administrador**: Solo puede ver el historial de movimientos (no modificar)

---

## Preguntas Resueltas

> [!NOTE]
> **Folio del certificado**: Automático formato `JH-YYYY-XXXX` (ej. `JH-2025-0042`)

> [!NOTE]
> **Tabla de base de datos**: Ya creada en `jinhwa_corporation.sql` - solo necesitas ejecutarla en phpMyAdmin

> [!NOTE]
> **Nuevo flujo**: Los maestros ahora aprueban directamente y redactan los certificados. El administrador solo visualiza movimientos.
