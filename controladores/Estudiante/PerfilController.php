<?php
/**
 * ============================================================
 * CONTROLADOR DE PERFIL - ESTUDIANTE
 * ============================================================
 * Permite al estudiante ver y actualizar sus datos personales
 * y cambiar su contraseña.
 * ============================================================
 */
namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Config\Database;

class PerfilController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $usuarioModel = new Usuario();
        $estudiante = $usuarioModel->getById($_SESSION['id']);

        $this->view('estudiante/perfil', [
            'estudiante'   => $estudiante,
            'page_title'   => 'Mi Perfil',
            'current_page' => 'perfil'
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance()->getConnection();
            $id_miembro = $_SESSION['id'];

            // Datos personales a actualizar
            $telefono = trim($_POST['telefono'] ?? '');
            $peso = trim($_POST['peso'] ?? '');
            
            // Actualizar tabla miembros
            $peso = empty($peso) ? NULL : $peso;
            
            $stmt = $db->prepare("UPDATE miembros SET telefono = ?, peso = ? WHERE id_miembro = ?");
            $stmt->bind_param("sdi", $telefono, $peso, $id_miembro);
            $stmt->execute();
            $stmt->close();

            // Cambio de contraseña
            $clave_nueva = $_POST['clave_nueva'] ?? '';
            $clave_confirmar = $_POST['clave_confirmar'] ?? '';

            if (!empty($clave_nueva) && $clave_nueva === $clave_confirmar) {
                $clave_hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE userlog SET clave = ? WHERE id_miembro = ?");
                $stmt->bind_param("si", $clave_hash, $id_miembro);
                $stmt->execute();
                $stmt->close();
            }

            // Redirigir con mensaje de éxito
            $this->redirect('/estudiante/perfil?msg=updated');
        } else {
            $this->redirect('/estudiante/perfil');
        }
    }
}
