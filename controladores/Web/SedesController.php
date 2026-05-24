<?php
/**
 * ============================================================
 * CONTROLADOR DE SEDES – SITIO WEB PÚBLICO (SedesController)
 * ============================================================
 * Muestra la lista pública de sedes del club de Taekwondo.
 * No requiere autenticación (acceso libre).
 *
 * Ruta: GET /sedes
 * Vista: web/sedes
 *
 * Obtiene las sedes de la base de datos con su conteo de miembros
 * y las presenta de forma pública para informar a visitantes
 * sobre las ubicaciones disponibles del club.
 * ============================================================
 */
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Sede;

class SedesController extends Controller {

    /**
     * Lista las sedes disponibles del club para el público general.
     * Ruta: GET /sedes
     *
     * Usa Sede::getAll() que retorna nombre, dirección, horario
     * y número de estudiantes de cada sede.
     */
    public function index() {
        $sedeModel = new Sede();
        $sedes     = $sedeModel->getAll(); // Incluye JOIN con miembros para el conteo

        $this->view('web/sedes', [
            'sedes'      => $sedes,
            'page_title' => 'Nuestras Sedes'
        ]);
    }
}
