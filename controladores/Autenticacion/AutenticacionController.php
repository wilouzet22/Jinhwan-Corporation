<?php
/**
 * ============================================================
 * CONTROLADOR DE AUTENTICACIÓN (AutenticacionController)
 * ============================================================
 * Maneja todo el flujo de autenticación de la aplicación:
 *   - Mostrar el formulario de login
 *   - Procesar el login (verificar credenciales + sesión segura)
 *   - Mostrar el formulario de registro público
 *   - Procesar el registro (crear miembro pendiente de aprobación)
 *   - Cerrar sesión
 *
 * Flujo de Login:
 *   1. Buscar el correo en 'userlog' + JOIN con 'miembros'
 *   2. Verificar la contraseña (bcrypt o texto plano con migración automática)
 *   3. Verificar que el miembro esté activo
 *   4. Crear sesión segura
 *   5. Redirigir según el rol (admin/estudiante/otro)
 *
 * Flujo de Registro:
 *   1. Insertar en 'miembros' con activo = 0 (pendiente de aprobación)
 *   2. Insertar credenciales en 'userlog' con clave hasheada
 *   3. El admin aprueba el registro desde /admin/registros
 * ============================================================
 */
namespace App\Controllers\Autenticacion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Roles;
use App\Config\Database;

class AutenticacionController extends Controller {

    /**
     * Muestra el formulario de inicio de sesión.
     * Ruta: GET /login
     */
    public function loginForm() {
        $this->view('autenticacion/login');
    }

    /**
     * Muestra el formulario de registro público.
     * Ruta: GET /registro
     */
    public function registroForm() {
        $this->view('autenticacion/registro');
    }

    /**
     * Procesa el formulario de inicio de sesión.
     * Ruta: POST /login/process
     *
     * Parámetros POST esperados:
     *   - email    → correo electrónico del usuario
     *   - password → contraseña en texto plano
     *
     * Errores posibles (redirige con ?error=):
     *   - pending → el miembro existe pero no ha sido aprobado (activo=0)
     *   - 1       → contraseña incorrecta
     *   - 2       → correo no encontrado en la BD
     */
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $clave = $_POST['password'];

            $db = Database::getInstance()->getConnection();

            // Buscar el usuario por correo en 'userlog' con JOIN a 'miembros'
            // para obtener el rol, nombre completo y estado de activación
            $stmt = $db->prepare("SELECT m.id_miembro as id, m.nombre, m.apellido, u.correo, u.clave, m.rol as rol_id, m.activo FROM userlog u JOIN miembros m ON u.id_miembro = m.id_miembro WHERE u.correo = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $registro = $resultado->fetch_assoc();

                // Verificar contraseña: soporta bcrypt (password_verify) y texto plano legacy
                // La comparación con texto plano es para cuentas que no han migrado aún
                if (password_verify($clave, $registro['clave']) || $clave === $registro['clave']) {

                    // Si el miembro existe pero está pendiente de aprobación (activo=0)
                    if ($registro['activo'] == 0) {
                        $this->redirect('/login?error=pending');
                    }

                    // MIGRACIÓN SILENCIOSA DE CONTRASEÑAS:
                    // Si la contraseña estaba en texto plano (la verificación bcrypt falló),
                    // se actualiza automáticamente a formato bcrypt seguro sin que el usuario note nada.
                    if (!password_verify($clave, $registro['clave'])) {
                        $nuevo_hash = password_hash($clave, PASSWORD_DEFAULT);
                        $stmtUpdate = $db->prepare("UPDATE userlog SET clave = ? WHERE id_miembro = ?");
                        $stmtUpdate->bind_param("si", $nuevo_hash, $registro['id']);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }

                    // Construir el array de datos de sesión con solo lo necesario
                    $usuario_data = [
                        'id'     => $registro['id'],
                        'nombre' => $registro['nombre'] . ' ' . $registro['apellido'],
                        'correo' => $registro['correo'],
                        'rol_id' => $registro['rol_id']
                    ];

                    // Crear sesión segura: regenera ID, guarda datos, timestamps y User-Agent
                    Security::startSecureSession($usuario_data);

                    // Registrar el login exitoso en el archivo de debug
                    $log = date('Y-m-d H:i:s') . " - Login Success: Email=" . $email . " | Rol=" . $registro['rol_id'] . "\n";
                    file_put_contents('debug_login.txt', $log, FILE_APPEND);

                    // Redirigir según el rol del usuario autenticado
                    if (Roles::esAdmin($registro['rol_id'])) {
                        $this->redirect('/admin/dashboard');        // Administrador → panel de control
                    } elseif (Roles::esMaestro($registro['rol_id'])
                           || $registro['rol_id'] == Roles::PROFESOR
                           || $registro['rol_id'] == Roles::MONITOR) {
                        $this->redirect('/maestro/dashboard');      // Maestro/Profesor/Monitor → panel de instructor
                    } elseif ($registro['rol_id'] == Roles::ESTUDIANTE) {
                        $this->redirect('/estudiante/dashboard');   // Estudiante → portal del alumno
                    } else {
                        $this->redirect('/');                       // Otros roles → portada
                    }

                } else {
                    // Contraseña incorrecta
                    $this->redirect('/login?error=1');
                }
            } else {
                // El correo no existe en la base de datos
                $this->redirect('/login?error=2');
            }

            $stmt->close();
        } else {
            // Si se accede por GET en lugar de POST, redirigir al formulario
            $this->redirect('/login');
        }
    }

    /**
     * Procesa el formulario de registro público de nuevos estudiantes.
     * Ruta: POST /registro/process
     *
     * El registro crea el miembro con activo = 0 (pendiente).
     * El administrador debe aprobarlo desde /admin/registros.
     *
     * Parámetros POST esperados:
     *   - nombre, apellido, email, password, num_doc, fecha_n, telefono
     *
     * Al finalizar con éxito, redirige a /login?msg=sent para mostrar
     * el mensaje "Solicitud enviada, espere aprobación".
     */
    public function processRegistro() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Leer todos los campos del formulario
            $nombre   = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email    = $_POST['email'];
            $password = $_POST['password'];
            $num_doc  = $_POST['num_doc'];
            $fecha_n  = $_POST['fecha_n'];
            $telefono = $_POST['telefono'];

            $db = Database::getInstance()->getConnection();

            // Paso 1: Insertar en 'miembros' con activo = 0 (pendiente de aprobación)
            // El rol se fija como ESTUDIANTE y el grado inicial es 1 (grado más bajo)
            $rol_id   = Roles::ESTUDIANTE;
            $id_grado = 1; // Grado inicial por defecto (Blanco)

            $stmt = $db->prepare("INSERT INTO miembros (nombre, apellido, num_doc, fecha_n, id_grado, telefono, rol, activo) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("ssssisi", $nombre, $apellido, $num_doc, $fecha_n, $id_grado, $telefono, $rol_id);

            if ($stmt->execute()) {
                // Obtener el ID asignado al nuevo miembro
                $id_miembro = $stmt->insert_id;

                // Paso 2: Insertar credenciales en 'userlog' con la contraseña hasheada
                $clave_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt_log   = $db->prepare("INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, ?)");
                $stmt_log->bind_param("iss", $id_miembro, $email, $clave_hash);

                if ($stmt_log->execute()) {
                    // Registro exitoso → redirigir al login con mensaje de confirmación
                    $this->redirect('/login?msg=sent');
                } else {
                    // Error al insertar en userlog
                    $this->redirect('/registro?error=db_error');
                }
            } else {
                // Error al insertar en miembros
                $this->redirect('/registro?error=db_error');
            }
        }
    }

    /**
     * Cierra la sesión del usuario actual de forma segura.
     * Ruta: GET /logout
     *
     * Llama a Security::logout() que destruye toda la sesión,
     * luego redirige al formulario de login.
     */
    public function logout() {
        Security::logout();
        $this->redirect('/login');
    }
}
