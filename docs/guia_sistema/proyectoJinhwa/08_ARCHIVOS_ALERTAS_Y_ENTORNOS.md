# ⚙️ 08 - Archivos, Alertas, Entornos y Extensión

Este capítulo final cubre aspectos operativos esenciales: manejo de archivos, notificaciones visuales, configuración de servidores y la guía para agregar nuevas funciones.

---

## 📂 Subida y Almacenamiento de Archivos

Las fotos de perfil, certificados y multimedia se gestionan siguiendo esta convención:

1. **Recepción en PHP:** A través del array global `$_FILES['archivo']`.
2. **Validaciones recomendadas:**
   - **MIME Type:** Comprobar que sea una imagen válida (`image/jpeg`, `image/png`, `image/webp`).
   - **Tamaño máximo:** Limitar las subidas (ej. 5 MB).
   - **Renombrado único:** Guardar con un identificador único para evitar sobreescrituras accidentales:
     ```php
     $nombreUnico = uniqid('foto_') . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
     move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../../storage/' . $nombreUnico);
     ```
3. **Persistencia en Base de Datos:** En la base de datos solo se almacena la ruta relativa o el nombre del archivo (ej. `storage/foto_64f1a2b.jpg`), nunca el archivo binario completo.

---

## 🔔 Sistema de Alertas y Feedback (UX)

El sistema utiliza principalmente dos mecanismos para informar al usuario:

### 1. Parámetros de URL (`?msg=` o `?error=`)
Al redirigir tras una acción, el controlador añade un indicador en la URL:
- `header("Location: /admin/sedes?msg=creado");`
- `header("Location: /login?error=timeout");`

### 2. SweetAlert2 en el Frontend
En las plantillas de `vistas/layout/` o en scripts de página, JavaScript intercepta esos parámetros o respuestas JSON para desplegar modales estilizados:
```javascript
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('msg') === 'creado') {
    Swal.fire({
        icon: 'success',
        title: '¡Completado!',
        text: 'El registro se ha guardado exitosamente',
        confirmButtonColor: '#2563eb'
    });
}
```

---

## 🌍 Entornos: Desarrollo Local (Laragon) vs Producción

El archivo `config/conexion.php` centraliza los parámetros de acceso:

```php
// config/conexion.php
private $host = 'sql113.infinityfree.com'; // O 'localhost' en Laragon
private $user = 'if0_42216592';           // O 'root' en local
private $pass = 'IfK0M0n94NKpHQq';        // O '' (vacío) en local
private $name = 'if0_42216592_jinhwa_corporation';
```

> [!TIP]
> **Recomendación para trabajar en local sin perder conexión remota:**  
> Puedes comprobar el host actual con `$_SERVER['SERVER_NAME']`. Si es `localhost` o `127.0.0.1`, conectar a MySQL de Laragon; si no, conectar a las credenciales de InfinityFree.

---

## 📖 Glosario de Términos del Software

- **Cinturón / Grado:** Nivel técnico del practicante (Kup para cinturones de color, Dan para cinturones negros).
- **Cronograma de Clase:** Plan pedagógico que detalla los temas y ejercicios que se dictarán en una fecha.
- **Poomsae (Formas):** Secuencias preestablecidas de defensa y ataque simulado evaluadas en los ascensos.
- **Sede:** Escuela o filial física perteneciente a Jinhwan Corporation.

---

## 🚀 Guía Rápida: Cómo Agregar una Nueva Función sin Romper Nada

Si deseas crear una nueva página en el sistema (por ejemplo, una sección de "Noticias"), sigue estos 4 pasos:

1. **Crear el Modelo (si requiere datos):**  
   Crea `modelos/Noticia.php` extendiendo de `Model`.
2. **Crear el Controlador:**  
   Crea `controladores/Administracion/NoticiasController.php` extendiendo de `Controller`:
   ```php
   class NoticiasController extends Controller {
       public function index() {
           Security::verifyAdmin();
           $this->view('administracion/noticias', ['titulo' => 'Gestión de Noticias']);
       }
   }
   ```
3. **Crear la Vista:**  
   Crea el archivo `vistas/administracion/noticias.php` con el diseño y layout correspondiente.
4. **Registrar la Ruta en `ruteador.php`:**  
   ```php
   include_once __DIR__ . '/controladores/Administracion/NoticiasController.php';
   $router->get('/admin/noticias', [NoticiasController::class, 'index']);
   ```

---

Volver al: [[00_INDICE_GENERAL|Índice General]].
