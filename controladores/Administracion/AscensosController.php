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

        // Lista de maestros para evaluador opcional
        $resM = $db->query("SELECT id_maestro, CONCAT(nombre, ' ', apellido) as nombre_completo FROM maestro WHERE activo = 1 ORDER BY nombre ASC");
        $maestros_list = $resM ? $resM->fetch_all(MYSQLI_ASSOC) : [];

        // Historial de ascensos y diplomas generados
        $sql = "SELECT c.id_certificado as id, c.grado_anterior, c.grado_nuevo,
                       c.fecha_examen, c.folio, c.observaciones, c.creado_en,
                       CONCAT(e.nombre, ' ', e.apellido) as nombre_alumno,
                       e.foto_perfil, e.tipo_documento, e.num_doc,
                       COALESCE(CONCAT(m.nombre, ' ', m.apellido), 'Administración') as nombre_maestro
                FROM certificados_ascenso c
                JOIN estudiante e ON c.id_estudiante = e.id_estudiante
                LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
                ORDER BY c.creado_en DESC";
        $res = $db->query($sql);
        $historial = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->view('administracion/ascensos', [
            'alumnos'       => $alumnos,
            'grados_list'   => $grados_list,
            'sedes_list'    => $sedes_list,
            'grupos_list'   => $grupos_list,
            'maestros_list' => $maestros_list,
            'historial'     => $historial,
            'page_title'    => 'Ascensos y Diplomas',
            'current_page'  => 'ascensos'
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
        $fecha_examen       = !empty($_POST['fecha_examen']) ? $_POST['fecha_examen'] : date('Y-m-d');
        $id_maestro         = !empty($_POST['id_maestro']) ? (int)$_POST['id_maestro'] : null;
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

        // Si grado_anterior no viene, consultar el grado actual del alumno desde la BD
        if (empty($grado_anterior)) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT g.nombre FROM estudiante e LEFT JOIN grados g ON e.id_grado = g.id_grado WHERE e.id_estudiante = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("i", $id_alumno);
                $stmt->execute();
                $r = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $grado_anterior = $r['nombre'] ?? 'Blanco';
            }
        }

        $ok = $this->certModel->create([
            'id_estudiante'  => $id_alumno,
            'id_maestro'     => $id_maestro,
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

    /** Ascenso masivo: varios alumnos al mismo nuevo grado */
    public function storeBulk() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/ascensos');
            return;
        }

        $ids_alumnos    = array_filter(array_map('intval', $_POST['ids_alumnos'] ?? []), fn($i) => $i > 0);
        $id_grado_nuevo = (int)($_POST['id_grado_nuevo'] ?? 0);
        $fecha_examen   = !empty($_POST['fecha_examen']) ? $_POST['fecha_examen'] : date('Y-m-d');
        $id_maestro     = !empty($_POST['id_maestro']) ? (int)$_POST['id_maestro'] : null;
        $observaciones  = trim($_POST['observaciones'] ?? '');

        if (empty($ids_alumnos) || $id_grado_nuevo <= 0) {
            $this->redirect('/admin/ascensos?error=datos_invalidos');
            return;
        }

        // Obtener nombre del nuevo grado
        $grados = $this->nivelModel->getAll();
        $gradosMap = [];
        foreach ($grados as $g) { $gradosMap[(int)$g['id']] = $g['nombre']; }
        $grado_nuevo = $gradosMap[$id_grado_nuevo] ?? 'Desconocido';

        $db = Database::getInstance()->getConnection();
        $exitosos = 0;

        foreach ($ids_alumnos as $id_alumno) {
            // Obtener grado actual del alumno
            $stmt = $db->prepare("SELECT g.nombre FROM estudiante e LEFT JOIN grados g ON e.id_grado = g.id_grado WHERE e.id_estudiante = ? LIMIT 1");
            $grado_anterior = 'Blanco';
            if ($stmt) {
                $stmt->bind_param("i", $id_alumno);
                $stmt->execute();
                $r = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $grado_anterior = $r['nombre'] ?? 'Blanco';
            }

            $ok = $this->certModel->create([
                'id_estudiante'  => $id_alumno,
                'id_maestro'     => $id_maestro,
                'grado_anterior' => $grado_anterior,
                'grado_nuevo'    => $grado_nuevo,
                'id_grado_nuevo' => $id_grado_nuevo,
                'fecha_examen'   => $fecha_examen,
                'observaciones'  => $observaciones,
                'folio'          => Certificado::generarFolio(),
            ]);
            if ($ok) $exitosos++;
        }

        if ($exitosos > 0) {
            $this->redirect('/admin/ascensos?success=' . $exitosos . '&bulk=1');
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

    public function descargar() {
        Security::verifyPermission('ascensos');

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            exit('Solicitud inválida.');
        }

        $cert = $this->certModel->getById($id);
        if (!$cert) {
            http_response_code(404);
            exit('Certificado no encontrado.');
        }

        require_once __DIR__ . '/../../helpers/DiplomaPdf.php';
        DiplomaPdf::descargar($cert);
    }
}

