<?php
namespace App\Controllers\Autenticacion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Roles;
use App\Config\Database;

class AutenticacionController extends Controller {

    public function loginForm() {
        $this->view('autenticacion/login');
    }

    public function registroForm() {
        $this->view('autenticacion/registro');
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {   
            $email = $_POST['email'];
            $clave = $_POST['password'];

            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("SELECT m.id_miembro as id, m.nombre, m.apellido, u.correo, u.clave, m.rol as rol_id, m.activo FROM userlog u JOIN miembros m ON u.id_miembro = m.id_miembro WHERE u.correo = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $registro = $resultado->fetch_assoc();
                
                if (password_verify($clave, $registro['clave']) || $clave === $registro['clave']) {
                    
                    if ($registro['activo'] == 0) {
                        $this->redirect('/login?error=pending');
                    }

                    // Actualización silenciosa de contraseña plana a Hash seguro
                    if (!password_verify($clave, $registro['clave'])) {
                        $nuevo_hash = password_hash($clave, PASSWORD_DEFAULT);
                        $stmtUpdate = $db->prepare("UPDATE userlog SET clave = ? WHERE id_miembro = ?");
                        $stmtUpdate->bind_param("si", $nuevo_hash, $registro['id']);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }

                    $usuario_data = [
                        'id'     => $registro['id'],
                        'nombre' => $registro['nombre'] . ' ' . $registro['apellido'],
                        'correo' => $registro['correo'],
                        'rol_id' => $registro['rol_id']
                    ];
                    
                    Security::startSecureSession($usuario_data);
                    
                    // DEBUG LOG
                    $log = date('Y-m-d H:i:s') . " - Login Success: Email=" . $email . " | Rol=" . $registro['rol_id'] . "\n";
                    file_put_contents('debug_login.txt', $log, FILE_APPEND);
                    
                    if (Roles::esAdmin($registro['rol_id'])) {
                        $this->redirect('/admin/dashboard');
                    } elseif ($registro['rol_id'] == Roles::ESTUDIANTE) {
                        $this->redirect('/estudiante/dashboard'); 
                    } else {
                        $this->redirect('/');
                    }
                } else {
                    $this->redirect('/login?error=1');
                }
            } else {
                $this->redirect('/login?error=2');
            }
            $stmt->close();
        } else {
            $this->redirect('/login');
        }
    }

    public function processRegistro() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $num_doc = $_POST['num_doc'];
            $fecha_n = $_POST['fecha_n'];
            $telefono = $_POST['telefono'];

            $db = Database::getInstance()->getConnection();
            
            // 1. Insertar en miembros (activo = 0 por defecto)
            $rol_id = Roles::ESTUDIANTE;
            $id_grado = 1; // Default or null if allowed
            
            $stmt = $db->prepare("INSERT INTO miembros (nombre, apellido, num_doc, fecha_n, id_grado, telefono, rol, activo) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("ssssisi", $nombre, $apellido, $num_doc, $fecha_n, $id_grado, $telefono, $rol_id);
            
            if ($stmt->execute()) {
                $id_miembro = $stmt->insert_id;
                
                // 2. Insertar en userlog
                $clave_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt_log = $db->prepare("INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, ?)");
                $stmt_log->bind_param("iss", $id_miembro, $email, $clave_hash);
                
                if ($stmt_log->execute()) {
                    $this->redirect('/login?msg=sent');
                } else {
                    $this->redirect('/registro?error=db_error');
                }
            } else {
                $this->redirect('/registro?error=db_error');
            }
        }
    }

    public function logout() {
        Security::logout();
        $this->redirect('/login');
    }
}
