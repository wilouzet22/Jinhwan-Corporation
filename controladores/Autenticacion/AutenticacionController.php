<?php

include_once __DIR__ . '/../../modelos/Sede.php';

class AutenticacionController extends Controller {

    public function loginForm() {
        $this->view('autenticacion/login');
    }

    public function registroForm() {
        $this->view('autenticacion/registro');
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $clave = $_POST['password'] ?? '';

            $db = Database::getInstance()->getConnection();

            $sql = "SELECT p.id_persona as id, p.nombre, p.apellido, c.correo, c.clave, c.rol as rol_id,
                    p.activo, p.foto_perfil, c.permisos_extra
                    FROM credenciales c
                    JOIN personas p ON c.id_persona = p.id_persona
                    WHERE c.correo = ? LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $registro = $resultado->fetch_assoc();

                if (password_verify($clave, $registro['clave']) || $clave === $registro['clave']) {

                    if ((int)$registro['activo'] === 0) {
                        $this->redirect('/login?error=pending');
                    }

                    if (!password_verify($clave, $registro['clave'])) {
                        $nuevo_hash = password_hash($clave, PASSWORD_DEFAULT);
                        $stmtUpdate = $db->prepare("UPDATE credenciales SET clave = ? WHERE id_persona = ?");
                        $stmtUpdate->bind_param("si", $nuevo_hash, $registro['id']);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }

                    $usuario_data = [
                        'id'             => $registro['id'],
                        'nombre'         => $registro['nombre'] . ' ' . $registro['apellido'],
                        'correo'         => $registro['correo'],
                        'rol_id'         => $registro['rol_id'],
                        'foto_perfil'    => $registro['foto_perfil'],
                        'permisos_extra' => $registro['permisos_extra']
                    ];

                    Security::startSecureSession($usuario_data);

                    if (Roles::esAdmin($registro['rol_id'])) {
                        $this->redirect('/admin/dashboard');        
                    } elseif (Roles::esMaestro($registro['rol_id'])
                           || $registro['rol_id'] == Roles::PROFESOR
                           || $registro['rol_id'] == Roles::MONITOR) {
                        $this->redirect('/maestro/dashboard');      
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
            $num_doc  = trim($_POST['num_doc'] ?? '');
            $email    = strtolower(trim($_POST['email'] ?? '')); 
            $password = $_POST['password'] ?? '';

            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT id_persona, activo FROM personas WHERE num_doc = ? LIMIT 1");
            $stmt->bind_param("s", $num_doc);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $persona = $resultado->fetch_assoc();
                $id_persona = $persona['id_persona'];

                $stmtCheck = $db->prepare("SELECT id_credencial FROM credenciales WHERE id_persona = ? LIMIT 1");
                $stmtCheck->bind_param("i", $id_persona);
                $stmtCheck->execute();
                $resCheck = $stmtCheck->get_result();

                if ($resCheck && $resCheck->num_rows > 0) {
                            
                    $this->redirect('/registro?error=already_registered');
                } else {
                    $clave_hash = password_hash($password, PASSWORD_DEFAULT);
                    $rol_defecto = Roles::ESTUDIANTE;
                    $stmt_log = $db->prepare("INSERT INTO credenciales (id_persona, correo, clave, rol) VALUES (?, ?, ?, ?)");
                    $stmt_log->bind_param("isss", $id_persona, $email, $clave_hash, $rol_defecto);

                    if ($stmt_log->execute()) {
                        $this->redirect('/login?msg=sent');
                    } else {
                        $this->redirect('/registro?error=db_error');
                    }
                    $stmt_log->close();
                }
                $stmtCheck->close();
            } else {
                $_SESSION['temp_registro'] = [
                    'num_doc' => $num_doc,
                    'email'   => $email,
                    'password'=> $password
                ];
                $this->redirect('/registro/completar');
            }
            $stmt->close();
        }
    }

    public function completarRegistroForm() {
        if (!isset($_SESSION['temp_registro'])) {
            $this->redirect('/registro');
        }

        $sedeModel = new Sede();
        $sedes = $sedeModel->getAll();

        $this->view('autenticacion/completar_registro', [
            'sedes' => $sedes
        ]);
    }

    public function processCompletarRegistro() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['temp_registro'])) {
            $temp = $_SESSION['temp_registro'];
            
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $telefono = trim($_POST['telefono'] ?? '');
            $sede_id = !empty($_POST['sede_id']) ? (int)$_POST['sede_id'] : null;
            
            $db = Database::getInstance()->getConnection();
            $db->begin_transaction();

            try {
                // 1. Insertar en personas
                $sql = "INSERT INTO personas (nombre, apellido, num_doc, tipo_documento, telefono, id_sede, activo) VALUES (?, ?, ?, 'TI', ?, ?, 0)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ssssi", $nombre, $apellido, $temp['num_doc'], $telefono, $sede_id);
                $stmt->execute();
                $id_persona = $stmt->insert_id;
                $stmt->close();

                // 2. Insertar en perfil_deportistas
                $sqlDep = "INSERT INTO perfil_deportistas (id_persona, id_grado, id_categoria, fecha_n) VALUES (?, 1, 1, ?)";
                $stmtDep = $db->prepare($sqlDep);
                $stmtDep->bind_param("is", $id_persona, $fecha_nacimiento);
                $stmtDep->execute();
                $stmtDep->close();

                // 3. Insertar en credenciales
                $clave_hash = password_hash($temp['password'], PASSWORD_DEFAULT);
                $rol_defecto = Roles::ESTUDIANTE;
                $stmt_log = $db->prepare("INSERT INTO credenciales (id_persona, correo, clave, rol) VALUES (?, ?, ?, ?)");
                $stmt_log->bind_param("isss", $id_persona, $temp['email'], $clave_hash, $rol_defecto);
                $stmt_log->execute();
                $stmt_log->close();

                $db->commit();

                unset($_SESSION['temp_registro']);

                $this->redirect('/login?msg=sent');

            } catch (\Exception $e) {
                $db->rollback();
                $this->redirect('/registro?error=db_error');
            }
        }
    }

    public function logout() {
        Security::logout();
        $this->redirect('/login');
    }
}
