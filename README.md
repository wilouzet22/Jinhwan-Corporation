# 🥋 Jinhwan Corporation - Plataforma Integral de Gestión Marcial

Sistema integral de gestión académica, deportiva y administrativa diseñado a medida para academias y dojangs de **Taekwondo**. Permite la administración de alumnos, profesores, sedes, grupos de entrenamiento, eventos y emisión oficial de diplomas de ascenso de cinturón en alta resolución.

---

## 🚀 Características Principales

- **Gestión de Estudiantes**: Registro completo de deportistas con información médica (EPS, RH, peso), deportiva (cinturón, categoría, división, grupo) y fotografía oficial.
- **Jerarquía y Roles Separados**:
  - **Administrador**: Control total sobre finanzas, sedes, grupos, auditoría, ascensos y personal.
  - **Maestro / Instructor**: Gestión de grupos asignados, control de asistencia, seguimiento marcial y calificaciones.
  - **Estudiante**: Portal personal con historial de cinturones, certificados oficiales y material teórico.
- **Emisión Oficial de Diplomas de Ascenso**:
  - Previsualización dinámica de certificados y diplomas en alta resolución con plantilla marcial oficial.
  - Exportación directa a PDF y modo de impresión vectorial optimizado (sin bloqueos CORS mediante incrustación Base64).
- **Métricas y Analítica**: Gráficas interactivas con Chart.js sincronizadas en tiempo real (distribución por cinturones oficiales con patrones de franjas y distribución de alumnos por grupos).
- **Exportación de Datos**: Reportes en tiempo real a formato Excel (`.xlsx` con SheetJS) y PDF (`jsPDF`).
- **Portal Público Web**: Sitio institucional responsivo con catálogo de sedes, eventos, galería multimedia y solicitud en línea de nuevos aspirantes.

---

## 🛠️ Stack Tecnológico

- **Backend**: PHP 8+ nativo (Arquitectura MVC pura y modular, sin frameworks pesados).
- **Base de Datos**: MySQL / MariaDB con sentencias preparadas y Singleton `Database::getInstance()->getConnection()`.
- **Frontend**:
  - HTML5 Semántico + Vanilla JavaScript (ES6+).
  - Tailwind CSS para diseño corporativo y modo oscuro/claro.
  - Material Icons Outlined (Google Fonts).
  - **Librerías Clave**: `Chart.js` (gráficas), `SheetJS` (Excel), `jsPDF` / `html2pdf.js` (documentos).
- **Herramientas de Desarrollo**: Vite, TypeScript y Tailwind CSS v4 para compilación de assets modernos.

---

## 📂 Estructura del Proyecto

```text
jinwha/
├── config/             # Configuración de base de datos y entorno
├── controladores/      # Controladores MVC organizados por rol
│   ├── Administracion/ # Controladores del panel directivo
│   ├── Maestro/        # Controladores para instructores
│   ├── Estudiante/     # Controladores para el portal de alumnos
│   └── Web/            # Controladores del portal público
├── core/               # Núcleo MVC (Router, Model, Controller, Security, Roles)
├── database/           # Scripts SQL y migraciones
├── docs/               # Diagramas de flujo y arquitectura detallada
├── helpers/            # Funciones auxiliares globales
├── IA/                 # Protocolos, memoria y recetas para agentes IA
│   ├── AGENTS.md       # Reglas operativas estrictas
│   ├── MEMORY.md       # Historial de decisiones y arquitectura
│   └── SKILLS.md       # Recetas y patrones de desarrollo
├── modelos/            # Clases de acceso a datos y lógica de negocio
├── public/             # Archivos públicos estáticos (css, js, img, uploads)
├── resources/          # Plantillas gráficas y assets originales
├── vistas/             # Vistas del sistema organizadas por contexto
│   ├── administracion/ # Módulos del panel administrativo
│   ├── maestro/        # Vistas de instructores
│   ├── estudiante/     # Vistas de alumnos
│   ├── layout/         # Plantillas maestras de cabecera y pie
│   └── web/            # Páginas informativas públicas
├── index.php           # Punto de entrada principal (Front Controller)
├── ruteador.php        # Definición de rutas del sistema
└── package.json        # Dependencias de tooling frontend
```

---

## 🗄️ Modelo de Datos (Regla de Entidades)

El sistema opera con tablas de usuarios estrictamente segregadas:

1. **`estudiante`**: Contiene exclusivamente los alumnos y su expediente marcial/médico.
2. **`maestro`**: Profesores e instructores vinculados a grupos de entrenamiento.
3. **`admin`**: Directivos y personal administrativo.

> ⚠️ **Nota Importante**: Queda prohibido el uso o creación de tablas genéricas como `miembros` o `usuarios` para la lógica de alumnos.

---

## ⚙️ Instalación y Configuración

### 1. Requisitos Previos
- Entorno de desarrollo local como **Laragon**, **XAMPP** o servidor **Apache/Nginx**.
- **PHP 8.0** o superior con extensiones `mysqli`, `pdo`, `mbstring`, `gd`.
- **MySQL / MariaDB**.
- **Node.js 18+** y **npm** (opcional, para compilar estilos o herramientas Vite).

### 2. Configuración del Servidor Web
Si usas Laragon:
1. Clona o copia el repositorio dentro de tu directorio raíz:
   ```text
   C:\laragon\www\jinwha
   ```
2. Asegúrate de que el módulo `mod_rewrite` de Apache esté habilitado.

### 3. Base de Datos
1. Configura tus credenciales en el archivo [`config/conexion.php`](file:///c:/laragon/www/jinwha/config/conexion.php).
2. Importa la estructura inicial de tablas desde la carpeta [`database/`](file:///c:/laragon/www/jinwha/database).

### 4. Compilación de Assets (Opcional)
```bash
npm install
npm run dev
```

---

## 🤖 Protocolo para Asistentes de IA

Cualquier cambio asistido por IA en este repositorio debe consultar previamente la carpeta [`IA/`](file:///c:/laragon/www/jinwha/IA):
- **Aviso previo**: Explicar qué se hará y declarar si se usa o no la terminal.
- **Separador Windows**: Usar `;` (nunca `&&`).
- **Comandos Git**: Entregar la línea de commit y push lista para copiar.
- **Nomenclatura**: Todo el código, métodos y variables en idioma español.

---

## 📄 Licencia

Desarrollado para **Jinhwan Corporation**. Todos los derechos reservados.
