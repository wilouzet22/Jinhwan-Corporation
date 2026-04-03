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

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {   
            $email = $_POST['email'];
            $clave = $_POST['password'];

            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("SELECT id, nombre, apellido, correo, clave, rol_id FROM usuarios WHERE correo = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $registro = $resultado->fetch_assoc();
                
                if (password_verify($clave, $registro['clave'])) {
                    $usuario_data = [
                        'id'     => $registro['id'],
                        'nombre' => $registro['nombre'] . ' ' . $registro['apellido'],
                        'correo' => $registro['correo'],
                        'rol_id' => $registro['rol_id']
                    ];
                    
                    Security::startSecureSession($usuario_data);
                    
                    if (Roles::esAdmin($registro['rol_id'])) {
                        $this->redirect('/admin/sedes');
                    } elseif ($registro['rol_id'] == Roles::ESTUDIANTE) {
                        // TODO: Update when student view is migrated
                        $this->redirect('/ascensos'); // Assuming public/student view
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

    public function logout() {
        Security::logout();
        $this->redirect('/login');
    }
}
