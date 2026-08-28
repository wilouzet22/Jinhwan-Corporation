<?php

include_once __DIR__ . '/../../modelos/Usuario.php';

class UsuarioPerfilController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
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
            $usuarioModel = new Usuario();
            $usuarioActual = $usuarioModel->getById($id_miembro);

            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $tipo_documento = trim($_POST['tipo_documento'] ?? '');
            $numero_documento = trim($_POST['numero_documento'] ?? '');
            $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $correo = strtolower(trim($_POST['correo'] ?? ''));
            $eps = trim($_POST['eps'] ?? '');
            $rh = trim($_POST['rh'] ?? '');
            $peso = trim($_POST['peso'] ?? '');
            $division = trim($_POST['division'] ?? '');
            $descripcion_perfil = trim($_POST['descripcion_perfil'] ?? '');
            $logros = trim($_POST['logros'] ?? '');
            $mostrar_en_web = isset($_POST['mostrar_en_web']) ? 1 : 0;

            $peso = empty($peso) ? NULL : (float)$peso;
            $fecha_nacimiento = empty($fecha_nacimiento) ? NULL : $fecha_nacimiento;

            $foto_perfil = $usuarioActual['foto_perfil'] ?? null;

            if (isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] === '1') {
                $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                if (!empty($foto_perfil)) {
                    $old_file = $uploadFileDir . $foto_perfil;
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $foto_perfil = null;
                $_SESSION['foto_perfil'] = null;
            }

            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
                $fileName = $_FILES['foto_perfil']['name'];
                $fileSize = $_FILES['foto_perfil']['size'];
                
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    if ($fileSize <= 2 * 1024 * 1024) {
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        
                        $dest_path = $uploadFileDir . $newFileName;
                        
                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            if (!empty($foto_perfil)) {
                                $old_file = $uploadFileDir . $foto_perfil;
                                if (file_exists($old_file)) {
                                    unlink($old_file);
                                }
                            }
                            $foto_perfil = $newFileName;
                            $_SESSION['foto_perfil'] = $newFileName;
                        }
                    }
                }
            }

            $updateData = [
                'nombre'             => $nombre,
                'apellido'           => $apellido,
                'tipo_documento'     => $tipo_documento,
                'numero_documento'   => $numero_documento,
                'fecha_nacimiento'   => $fecha_nacimiento,
                'telefono'           => $telefono,
                'correo'             => $correo,
                'eps'                => $eps,
                'rh'                 => $rh,
                'peso'               => $peso,
                'division'           => $division,
                'descripcion_perfil' => $descripcion_perfil,
                'logros'             => $logros,
                'mostrar_en_web'     => $mostrar_en_web,
                'foto_perfil'        => $foto_perfil,
                'rol_id'             => $usuarioActual['rol_id'] ?? 'Deportistas',
                'nivel_id'           => $usuarioActual['nivel_id'] ?? 1,
                'sede_id'            => $usuarioActual['sede_id'] ?? null,
                'categoria_id'       => $usuarioActual['categoria_id'] ?? 1,
                'permisos_extra'     => $usuarioActual['permisos_extra'] ?? null
            ];

            $usuarioModel->update($id_miembro, $updateData);

            if (!empty($correo)) {
                $_SESSION['correo'] = $correo;
            }
            $_SESSION['nombre'] = $nombre . ' ' . $apellido;

            $clave_nueva = $_POST['clave_nueva'] ?? '';
            $clave_confirmar = $_POST['clave_confirmar'] ?? '';

            if (!empty($clave_nueva) && $clave_nueva === $clave_confirmar) {
                $clave_hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE credenciales SET clave = ? WHERE id_persona = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $clave_hash, $id_miembro);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            $this->redirect('/usuario/perfil?msg=updated');
        } else {
            $this->redirect('/usuario/perfil');
        }
    }
}
