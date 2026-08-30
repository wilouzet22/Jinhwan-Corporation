const fs = require('fs');

// ============================================================
// 1. modelos/Certificado.php
// ============================================================
fs.writeFileSync('c:/laragon/www/jinwha/modelos/Certificado.php', `<?php

class Certificado {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Crear un nuevo certificado de ascenso */
    public function create(array $data): bool {
        $sql = "INSERT INTO certificados_ascenso"
             . " (id_solicitud, id_persona, id_maestro, grado_anterior, grado_nuevo, fecha_examen, observaciones, folio)"
             . " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("iiisssss",
            $data["id_solicitud"], $data["id_persona"], $data["id_maestro"],
            $data["grado_anterior"], $data["grado_nuevo"],
            $data["fecha_examen"], $data["observaciones"], $data["folio"]
        );
        $r = $stmt->execute();
        $stmt->close();
        return $r;
    }

    /** Obtener todos los certificados de un estudiante */
    public function getByPersona(int $id_persona): array {
        $sql = "SELECT c.*, CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre"
             . " FROM certificados_ascenso c"
             . " LEFT JOIN personas m ON c.id_maestro = m.id_persona"
             . " WHERE c.id_persona = ? ORDER BY c.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $id_persona);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    /** Certificado completo por id de solicitud */
    public function getBySolicitud(int $id_solicitud): ?array {
        $sql = "SELECT c.*,"
             . " CONCAT(p.nombre, ' ', p.apellido) AS alumno_nombre,"
             . " p.num_doc, p.foto_perfil,"
             . " CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre,"
             . " se.nombre AS nombre_sede"
             . " FROM certificados_ascenso c"
             . " JOIN personas p ON c.id_persona = p.id_persona"
             . " LEFT JOIN personas m ON c.id_maestro = m.id_persona"
             . " LEFT JOIN perfil_deportistas pd ON pd.id_persona = c.id_persona"
             . " LEFT JOIN sedes se ON se.id_sede = pd.id_sede"
             . " WHERE c.id_solicitud = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id_solicitud);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Certificado por id propio */
    public function getById(int $id): ?array {
        $sql = "SELECT c.*,"
             . " CONCAT(p.nombre, ' ', p.apellido) AS alumno_nombre,"
             . " p.num_doc, p.foto_perfil,"
             . " CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre,"
             . " se.nombre AS nombre_sede"
             . " FROM certificados_ascenso c"
             . " JOIN personas p ON c.id_persona = p.id_persona"
             . " LEFT JOIN personas m ON c.id_maestro = m.id_persona"
             . " LEFT JOIN perfil_deportistas pd ON pd.id_persona = c.id_persona"
             . " LEFT JOIN sedes se ON se.id_sede = pd.id_sede"
             . " WHERE c.id_certificado = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Generar folio unico JH-YYYY-XXXX */
    public static function generarFolio(): string {
        $year = date("Y");
        $db   = Database::getInstance()->getConnection();
        $res  = $db->query("SELECT COUNT(*) AS total FROM certificados_ascenso WHERE YEAR(creado_en) = $year");
        $total = $res ? (int)$res->fetch_assoc()["total"] : 0;
        return sprintf("JH-%s-%04d", $year, $total + 1);
    }
}
`, 'utf8');

console.log('1. Certificado.php - OK: ' + fs.statSync('c:/laragon/www/jinwha/modelos/Certificado.php').size + ' bytes');

// ============================================================
// 2. vistas/administracion/certificado_preview.php
//    Vista HTML del certificado (base para el PDF)
// ============================================================
fs.writeFileSync('c:/laragon/www/jinwha/vistas/administracion/certificado_preview.php', `<?php
// Este archivo es llamado como include dentro de un modal.
// Espera recibir $cert = array con datos del certificado.
if (!isset($cert) || empty($cert)) return;
$foto_url = !empty($cert['foto_perfil'])
    ? base_url('/public/uploads/perfiles/' . $cert['foto_perfil'])
    : asset('img/visual/logo.svg');
$fecha_formateada = !empty($cert['fecha_examen'])
    ? date('d \\\\de F \\\\de Y', strtotime($cert['fecha_examen']))
    : date('d \\\\de F \\\\de Y');
?>
<div id="certificado-contenido" class="bg-white text-slate-900 font-sans p-0 rounded-2xl overflow-hidden" style="width:680px; max-width:100%; font-family: Georgia, serif;">

    <!-- Franja superior -->
    <div style="background: linear-gradient(135deg,#1e293b 0%,#0f172a 100%); padding:28px 36px; display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:16px;">
            <img src="<?= asset('img/visual/logo.svg') ?>" alt="Logo" style="width:64px; height:64px; object-fit:contain;">
            <div>
                <div style="color:#ffffff; font-size:22px; font-weight:800; letter-spacing:4px; font-family:sans-serif; text-transform:uppercase;">JINHWAN</div>
                <div style="color:#dc2626; font-size:12px; font-weight:700; letter-spacing:3px; font-family:sans-serif; text-transform:uppercase;">CORPORATION</div>
            </div>
        </div>
        <div style="text-align:right;">
            <div style="color:#94a3b8; font-size:10px; font-family:sans-serif; text-transform:uppercase; letter-spacing:1px;">Folio de Grado</div>
            <div style="color:#f59e0b; font-size:18px; font-weight:800; font-family:monospace;"><?= htmlspecialchars($cert['folio']) ?></div>
            <div style="color:#64748b; font-size:10px; font-family:sans-serif; margin-top:2px;"><?= $fecha_formateada ?></div>
        </div>
    </div>

    <!-- Título -->
    <div style="background:#dc2626; padding:14px 36px; text-align:center;">
        <div style="color:#fff; font-size:13px; font-weight:700; letter-spacing:5px; text-transform:uppercase; font-family:sans-serif;">Certificado de Ascenso de Grado en Taekwondo</div>
    </div>

    <!-- Cuerpo -->
    <div style="padding:28px 36px 20px;">

        <!-- Alumno -->
        <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid #e2e8f0;">
            <img src="<?= $foto_url ?>" alt="Foto" style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid #dc2626;">
            <div>
                <div style="font-size:11px; color:#64748b; text-transform:uppercase; letter-spacing:1px; font-family:sans-serif; margin-bottom:3px;">Deportista</div>
                <div style="font-size:20px; font-weight:700; color:#0f172a;"><?= htmlspecialchars($cert['alumno_nombre']) ?></div>
                <div style="font-size:12px; color:#475569; margin-top:4px; font-family:sans-serif;">
                    <span style="background:#f1f5f9; border:1px solid #e2e8f0; border-radius:4px; padding:1px 6px; font-size:10px; font-weight:700; margin-right:4px;">CC</span>
                    <?= htmlspecialchars($cert['num_doc'] ?? '-') ?>
                    <?php if (!empty($cert['nombre_sede'])): ?>
                        &nbsp;&nbsp;•&nbsp;&nbsp;Sede: <?= htmlspecialchars($cert['nombre_sede']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ascenso -->
        <div style="text-align:center; margin-bottom:24px; padding:20px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
            <div style="font-size:10px; color:#64748b; text-transform:uppercase; letter-spacing:2px; font-family:sans-serif; margin-bottom:12px;">Ascenso de Cinturón</div>
            <div style="display:flex; align-items:center; justify-content:center; gap:16px; flex-wrap:wrap;">
                <div style="background:#1e293b; color:#ffffff; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:700; letter-spacing:1px; font-family:sans-serif;">
                    <?= htmlspecialchars($cert['grado_anterior']) ?>
                </div>
                <div style="font-size:24px; color:#dc2626;">&#8594;</div>
                <div style="background:#dc2626; color:#ffffff; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:700; letter-spacing:1px; font-family:sans-serif;">
                    <?= htmlspecialchars($cert['grado_nuevo']) ?>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <?php if (!empty($cert['observaciones'])): ?>
        <div style="margin-bottom:24px; padding:14px 16px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px;">
            <div style="font-size:10px; color:#92400e; text-transform:uppercase; letter-spacing:1px; font-family:sans-serif; font-weight:700; margin-bottom:6px;">Observaciones del Evaluador</div>
            <div style="font-size:13px; color:#451a03; font-style:italic;">"<?= htmlspecialchars($cert['observaciones']) ?>"</div>
        </div>
        <?php endif; ?>

        <!-- Firmas -->
        <div style="display:flex; justify-content:space-around; padding-top:20px; border-top:1px solid #e2e8f0; margin-top:8px;">
            <div style="text-align:center; flex:1;">
                <div style="border-top:1px solid #94a3b8; padding-top:8px; margin-top:32px; margin-bottom:4px;"></div>
                <div style="font-size:12px; font-weight:700; color:#1e293b; font-family:sans-serif;"><?= htmlspecialchars($cert['maestro_nombre'] ?? 'Maestro Evaluador') ?></div>
                <div style="font-size:10px; color:#64748b; font-family:sans-serif;">Maestro Evaluador</div>
            </div>
            <div style="flex:0 0 40px;"></div>
            <div style="text-align:center; flex:1;">
                <div style="border-top:1px solid #94a3b8; padding-top:8px; margin-top:32px; margin-bottom:4px;"></div>
                <div style="font-size:12px; font-weight:700; color:#1e293b; font-family:sans-serif;">Jinhwan Corporation</div>
                <div style="font-size:10px; color:#64748b; font-family:sans-serif;">Dirección Nacional</div>
            </div>
        </div>
    </div>

    <!-- Franja inferior -->
    <div style="background:#0f172a; padding:10px 36px; display:flex; align-items:center; justify-content:space-between;">
        <div style="color:#475569; font-size:9px; font-family:sans-serif; letter-spacing:1px; text-transform:uppercase;">Corporación Jinhwan de Taekwondo — Documento Oficial</div>
        <div style="color:#334155; font-size:9px; font-family:monospace;"><?= htmlspecialchars($cert['folio']) ?></div>
    </div>

</div>
`, 'utf8');
console.log('2. certificado_preview.php - OK: ' + fs.statSync('c:/laragon/www/jinwha/vistas/administracion/certificado_preview.php').size + ' bytes');