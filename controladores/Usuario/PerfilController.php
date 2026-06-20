<?php
/**
 * ============================================================
 * CONTROLADOR DE PERFIL - COMPARTIDO (ADMIN, MAESTRO, ESTUDIANTE)
 * ============================================================
 * Permite a cualquier usuario ver y actualizar sus datos personales,
 * cambiar su contraseña y subir una foto de perfil.
 * ============================================================
 */
namespace App\Controllers\Usuario;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Config\Database;

class PerfilController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        // Asegurar silenciosamente que la columna de foto_perfil existe en la base de datos
        try {
            $db->query("ALTER TABLE miembros ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL");
        } catch (\Exception $e) {
            // Ignorar el error de columna duplicada
        }
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($_SESSION['id']);

        $this->view('usuario/perfil', [
            'usuario'      => $usuario,
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

            // Eliminar foto de perfil si se solicita
            if (isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] === '1') {
                $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                $stmtFoto = $db->prepare("SELECT foto_perfil FROM miembros WHERE id_miembro = ?");
                $stmtFoto->bind_param("i", $id_miembro);
                $stmtFoto->execute();
                $resFoto = $stmtFoto->get_result()->fetch_assoc();
                if (!empty($resFoto['foto_perfil'])) {
                    $old_file = $uploadFileDir . $resFoto['foto_perfil'];
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $stmtFoto->close();
                
                $stmtUpdateFoto = $db->prepare("UPDATE miembros SET foto_perfil = NULL WHERE id_miembro = ?");
                $stmtUpdateFoto->bind_param("i", $id_miembro);
                $stmtUpdateFoto->execute();
                $stmtUpdateFoto->close();
                
                $_SESSION['foto_perfil'] = null;
            }

            // Procesar subida de foto de perfil
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
                $fileName = $_FILES['foto_perfil']['name'];
                $fileSize = $_FILES['foto_perfil']['size'];
                
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    // Validar tamaño: 2MB máximo
                    if ($fileSize <= 2 * 1024 * 1024) {
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        
                        $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        
                        $dest_path = $uploadFileDir . $newFileName;
                        
                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            // Obtener foto anterior para borrarla
                            $stmtFoto = $db->prepare("SELECT foto_perfil FROM miembros WHERE id_miembro = ?");
                            $stmtFoto->bind_param("i", $id_miembro);
                            $stmtFoto->execute();
                            $resFoto = $stmtFoto->get_result()->fetch_assoc();
                            if (!empty($resFoto['foto_perfil'])) {
                                $old_file = $uploadFileDir . $resFoto['foto_perfil'];
                                if (file_exists($old_file)) {
                                    unlink($old_file);
                                }
                            }
                            $stmtFoto->close();
                            
                            // Guardar en base de datos
                            $stmtUpdateFoto = $db->prepare("UPDATE miembros SET foto_perfil = ? WHERE id_miembro = ?");
                            $stmtUpdateFoto->bind_param("si", $newFileName, $id_miembro);
                            $stmtUpdateFoto->execute();
                            $stmtUpdateFoto->close();
                            
                            // Actualizar en sesión
                            $_SESSION['foto_perfil'] = $newFileName;
                        }
                    }
                }
            }

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
            $this->redirect('/usuario/perfil?msg=updated');
        } else {
            $this->redirect('/usuario/perfil');
        }
    }
}
