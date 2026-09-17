# 🥋 Jinhwan Corporation - Guía Maestra del Sistema

Bienvenido a la documentación oficial e interactiva del software **Jinhwan Corporation**. Este espacio está diseñado para leerse cómodamente tanto en **Obsidian** (con soporte nativo para diagramas visuales y enlaces internos) como en cualquier visor de Markdown.

---

## 🗺️ Mapa Visual de la Arquitectura

```mermaid
graph TD
    classDef cliente fill:#2563eb,stroke:#1d4ed8,stroke-width:2px,color:#fff;
    classDef router fill:#7c3aed,stroke:#6d28d9,stroke-width:2px,color:#fff;
    classDef backend fill:#059669,stroke:#047857,stroke-width:2px,color:#fff;
    classDef db fill:#d97706,stroke:#b45309,stroke-width:2px,color:#fff;

    Nav["🌐 Navegador Web (Usuario / Maestro / Admin)"]:::cliente
    Rout["🔀 core/Router.php + ruteador.php"]:::router
    Sec["🛡️ core/Security.php & core/Roles.php"]:::router
    Ctrl["🎮 controladores/ (Web, Admin, Maestro, Estudiante)"]:::backend
    Mod["📦 modelos/ (Usuario, Cronograma, Ejercicio, etc.)"]:::backend
    DB[("🗄️ Base de Datos MySQL (Database)")]:::db
    Vistas["🖼️ vistas/ (HTML5 + TailwindCSS + JS)"]:::cliente

    Nav -->|"1. Petición HTTP (GET / POST)"| Rout
    Rout -->|"2. Valida Sesión y Rol"| Sec
    Sec -->|"3. Ejecuta Acción"| Ctrl
    Ctrl -->|"4. Consulta / Modifica"| Mod
    Mod -->|"5. SQL Query (mysqli)"| DB
    DB -->|"6. Retorna Datos"| Mod
    Mod -->|"7. Entrega Objetos/Arrays"| Ctrl
    Ctrl -->|"8. Renderiza con variables"| Vistas
    Vistas -->|"9. Respuesta Visual / JSON"| Nav
```

---

## 📚 Índice de Capítulos

Haz clic en cualquier enlace para ir al tema correspondiente:

| N° | Capítulo | ¿Qué aprenderás aquí? |
|---|---|---|
| **01** | [[01_ESTRUCTURA_Y_CARPETAS\|Estructura y Carpetas]] | Para qué sirve cada carpeta del proyecto y sus archivos raíz. |
| **02** | [[02_ENRUTAMIENTO_Y_DESPACHO\|Enrutamiento y Despacho]] | Cómo viaja una URL desde `Router.php` hasta el controlador exacto. |
| **03** | [[03_COMUNICACION_ENTRE_LENGUAJES\|Comunicación entre Lenguajes]] | Cómo dialogan PHP, JavaScript (AJAX/Fetch/JSON), MySQL y HTML/CSS. |
| **04** | [[04_BASE_DE_DATOS_Y_RELACIONES\|Base de Datos y Relaciones]] | Esquema de tablas, llaves primarias, foráneas y su significado. |
| **05** | [[05_ROLES_PERMISOS_Y_SEGURIDAD\|Roles, Permisos y Seguridad]] | Protección de rutas, control de sesiones por inactividad y hash de claves. |
| **06** | [[06_CONTROLADORES_Y_MODELOS\|Controladores y Modelos]] | Directorio detallado de cada controlador y modelo por módulo. |
| **07** | [[07_CASOS_DE_USO_PASO_A_PASO\|Casos de Uso Paso a Paso]] | El viaje completo de una petición real (Login, Calificar, Cronograma). |
| **08** | [[08_ARCHIVOS_ALERTAS_Y_ENTORNOS\|Archivos, Alertas y Entornos]] | Subida de archivos, alertas con SweetAlert2, Laragon vs Hosting y cómo agregar funciones. |

---

> 💡 **Tip para Obsidian:** Activa la vista de grafo (*Graph View*) para visualizar cómo todos los capítulos y módulos se conectan dinámicamente entre sí.
