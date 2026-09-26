<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Nivel.php';
include_once __DIR__ . '/../../modelos/Certificado.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Grupo.php';

class AdminAscensosController extends Controller {

    private $usuarioModel;
    private $nivelModel;
    private $certModel;
    private $sedeModel;
    private $grupoModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyPermission('ascensos');

        $this->usuarioModel = new Usuario();
        $this->nivelModel   = new Nivel();
        $this->certModel    = new Certificado();
        $this->sedeModel    = new Sede();
        $this->grupoModel   = new Grupo();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        // Obtener alumnos con detalles
        $miembros = $this->usuarioModel->getAllWithDetails();
        $alumnos = array_values(array_filter($miembros, function($m) {
            return $m['rol_id'] === Roles::ESTUDIANTE && (int)$m['activo'] === 1;
        }));

        $grados_list = $this->nivelModel->getAll();
        $sedes_list  = $this->sedeModel->getAll();
        $grupos_list = $this->grupoModel->getAll();

        // Historial de ascensos realizados
        $sql = "SELECT c.id_certificado as id, c.grado_anterior, c.grado_nuevo,
                       c.fecha_examen, c.folio, c.observaciones, c.creado_en,
                       CONCAT(e.nombre, ' ', e.apellido) as nombre_alumno,
                       e.foto_perfil,
                       COALESCE(CONCAT(m.nombre, ' ', m.apellido), 'Administrador') as nombre_maestro
                FROM certificados_ascenso c
                JOIN estudiante e ON c.id_estudiante = e.id_estudiante
                LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
                ORDER BY c.creado_en DESC
                LIMIT 50";
        $res = $db->query($sql);
        $historial = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->view('administracion/ascensos', [
            'alumnos'      => $alumnos,
            'grados_list'  => $grados_list,
            'sedes_list'   => $sedes_list,
            'grupos_list'  => $grupos_list,
            'historial'    => $historial,
            'page_title'   => 'Ascensos de Alumnos',
            'current_page' => 'ascensos'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/ascensos');
            return;
        }

        $id_alumno          = (int)($_POST['id_alumno'] ?? 0);
        $id_grado_nuevo     = (int)($_POST['id_grado_nuevo'] ?? 0);
        $grado_anterior     = trim($_POST['grado_anterior'] ?? '');
        $fecha_examen       = $_POST['fecha_examen'] ?? date('Y-m-d');
        $observaciones      = trim($_POST['observaciones'] ?? '');

        if ($id_alumno <= 0 || $id_grado_nuevo <= 0) {
            $this->redirect('/admin/ascensos?error=datos_invalidos');
            return;
        }

        // Obtener nombre del nuevo grado
        $grados = $this->nivelModel->getAll();
        $gradosMap = [];
        foreach ($grados as $g) { $gradosMap[(int)$g['id']] = $g['nombre']; }
        $grado_nuevo = $gradosMap[$id_grado_nuevo] ?? 'Desconocido';

        $ok = $this->certModel->create([
            'id_estudiante'  => $id_alumno,
            'id_maestro'     => null,
            'grado_anterior' => $grado_anterior,
            'grado_nuevo'    => $grado_nuevo,
            'id_grado_nuevo' => $id_grado_nuevo,
            'fecha_examen'   => $fecha_examen,
            'observaciones'  => $observaciones,
            'folio'          => Certificado::generarFolio(),
        ]);

        if ($ok) {
            $this->redirect('/admin/ascensos?success=1');
        } else {
            $this->redirect('/admin/ascensos?error=fallo');
        }
    }

    /** Endpoint AJAX: devuelve el HTML del certificado/diploma para el modal del admin */
    public function certificado() {
        Security::verifySession();
        Security::verifyPermission('ascensos');

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo '<p class="text-center text-rose-500 py-8">Solicitud inválida.</p>';
            return;
        }

        $cert = $this->certModel->getById($id);
        if (!$cert) {
            http_response_code(404);
            echo '<p class="text-center text-slate-500 py-8">Certificado no encontrado.</p>';
            return;
        }

        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }
}
