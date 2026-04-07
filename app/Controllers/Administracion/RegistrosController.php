<?php
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Config\Database;

class RegistrosController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // Obtener miembros pendientes (activo = 0) unidos con su correo en userlog
        $sql = "SELECT m.id_miembro as id, m.nombre, m.apellido, m.num_doc, m.telefono, u.correo, m.fecha_n 
                FROM miembros m 
                JOIN userlog u ON m.id_miembro = u.id_miembro 
                WHERE m.activo = 0 
                ORDER BY m.id_miembro DESC";
        
        $result = $db->query($sql);
        $solicitudes = $result->fetch_all(MYSQLI_ASSOC);

        $this->view('administracion/registros', [
            'solicitudes' => $solicitudes,
            'page_title' => 'Solicitudes de Registro',
            'current_page' => 'registros'
        ]);
    }

    public function aprobar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("UPDATE miembros SET activo = 1 WHERE id_miembro = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=approved');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
        }
    }

    public function rechazar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $db = Database::getInstance()->getConnection();
            
            // Eliminar de userlog primero por la relación (si aplica)
            $stmt_log = $db->prepare("DELETE FROM userlog WHERE id_miembro = ?");
            $stmt_log->bind_param("i", $id);
            $stmt_log->execute();
            
            // Eliminar de miembros
            $stmt = $db->prepare("DELETE FROM miembros WHERE id_miembro = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $this->redirect('/admin/registros?msg=rejected');
            } else {
                $this->redirect('/admin/registros?error=1');
            }
        }
    }
}
