<?php
// Este archivo es llamado como include dentro del modal de certificados.
// Espera recibir $cert = array con datos del certificado.
if (!isset($cert) || empty($cert)) return;

$foto_url = !empty($cert['foto_perfil'])
    ? base_url('/public/uploads/perfiles/' . $cert['foto_perfil'])
    : asset('img/visual/logo.svg');

// Formato de fecha en español sin fugas de zona horaria
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$ts = !empty($cert['fecha_examen']) ? strtotime($cert['fecha_examen']) : time();
$dia = date('j', $ts);
$mes = $meses[(int)date('n', $ts)] ?? date('F', $ts);
$ano = date('Y', $ts);
$fecha_formateada = "{$dia} de {$mes} de {$ano}";

$nombre_alumno  = htmlspecialchars(mb_strtoupper(trim($cert['alumno_nombre'] ?? 'Estudiante'), 'UTF-8'));
$tipo_doc       = htmlspecialchars(trim($cert['tipo_documento'] ?? 'T.I.'));
$num_doc        = htmlspecialchars(trim($cert['num_doc'] ?? ''));
$folio          = htmlspecialchars(trim($cert['folio'] ?? ''));
$grado_anterior = htmlspecialchars(trim($cert['grado_anterior'] ?? ''));
$grado_nuevo    = htmlspecialchars(mb_strtoupper(trim($cert['grado_nuevo'] ?? ''), 'UTF-8'));
$observaciones_raw = trim((string)($cert['observaciones'] ?? ''));
$observaciones  = mb_strlen($observaciones_raw, 'UTF-8') > 280
    ? mb_substr($observaciones_raw, 0, 280, 'UTF-8') . '…'
    : $observaciones_raw;
$nombre_sede    = htmlspecialchars(trim($cert['nombre_sede'] ?? ''));

// Usamos la plantilla v3 limpia con parámetro de versión para evitar caché del navegador
$plantilla_url  = asset('img/visual/diploma_plantilla_v3.png') . '?v=' . filemtime(__DIR__ . '/../../public/img/visual/diploma_plantilla_v3.png');
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

<div id="certificado-contenido" style="width:800px; height:1036px; max-width:100%; margin:0 auto; position:relative; background:#ffffff url('<?= $plantilla_url ?>') no-repeat center top; background-size:100% 100%; box-sizing:border-box; box-shadow:0 20px 45px rgba(15,23,42,0.18); border-radius:12px; overflow:hidden; font-family:'Playfair Display', Georgia, serif; color:#0f172a; user-select:none;">

    <!-- 1. ZONA DEL ESTUDIANTE: Nombre completo sobre la línea y documento de identidad oficial -->
    <div style="position:absolute; top:236px; left:60px; right:60px; text-align:center;">
        <h2 style="font-family:'Cinzel', Georgia, serif; font-size:27px; font-weight:800; color:#0f172a; letter-spacing:2px; margin:0; line-height:1.2; text-shadow:0 0 1px rgba(0,0,0,0.12);">
            <?= $nombre_alumno ?>
        </h2>
        
        <!-- Línea caligráfica institucional -->
        <div style="width:540px; height:1.5px; background:linear-gradient(90deg, transparent, #1e293b 15%, #1e293b 85%, transparent); margin:7px auto 5px;"></div>

        <!-- Documento de identidad oficial destacado y sin saltos -->
        <?php if (!empty($num_doc)): ?>
            <div style="font-family:'Segoe UI', system-ui, sans-serif; font-size:13.5px; font-weight:800; color:#0f172a; letter-spacing:1.5px; text-transform:uppercase;">
                <?= $tipo_doc ?>: <?= $num_doc ?>
            </div>
        <?php endif; ?>

        <!-- Folio y Sede en segunda línea para máxima nitidez -->
        <div style="display:inline-flex; align-items:center; justify-content:center; gap:8px; margin-top:2px; font-family:'Segoe UI', system-ui, sans-serif; font-size:11px; font-weight:600; color:#64748b; letter-spacing:1px; text-transform:uppercase;">
            <?php if (!empty($folio)): ?>
                <span style="font-family:'Courier New', monospace; letter-spacing:1.5px; font-weight:700; color:#334155;">FOLIO: <?= $folio ?></span>
            <?php endif; ?>
            <?php if (!empty($nombre_sede)): ?>
                <span style="color:#cbd5e1;">•</span>
                <span><?= $nombre_sede ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- 
        NOTA: En la plantilla base (diploma_plantilla_v3.png), entre y=380px e y=516px ya se encuentra 
        impreso en el diseño oficial el texto:
        "Aprobó el examen reglamentario para Ascenso de grado, según lo requerido por el 
         Programa de esta institución, por lo cual se Acredita como:"
        Esta zona queda 100% respetada y visible sin ninguna superposición.
    -->

    <!-- 2. ZONA DE ACREDITACIÓN: Foto con insignia marcial, nuevo grado, fecha y observaciones -->
    <div style="position:absolute; top:524px; left:60px; right:60px; display:flex; flex-direction:column; align-items:center; text-align:center;">
        
        <!-- Bloque integrado: Foto del estudiante y Grado Acreditado -->
        <div style="display:flex; align-items:center; justify-content:center; gap:18px;">
            <!-- Foto oficial con marco circular de honor -->
            <div style="width:64px; height:64px; border-radius:50%; border:2.5px solid #0f172a; box-shadow:0 3px 10px rgba(15,23,42,0.22); overflow:hidden; background:#ffffff; flex-shrink:0;">
                <img src="<?= $foto_url ?>" alt="Foto del estudiante" style="width:100%; height:100%; object-fit:cover; display:block;">
            </div>

            <!-- Grado Acreditado e Indicador Previo -->
            <div style="text-align:left;">
                <div style="font-family:'Cinzel', Georgia, serif; font-size:24px; font-weight:900; color:#881337; letter-spacing:2px; text-transform:uppercase; margin:0; line-height:1.1; text-shadow:0 0 1px rgba(136,19,55,0.2);">
                    <?= $grado_nuevo ?>
                </div>
                <?php if (!empty($grado_anterior)): ?>
                    <div style="font-family:Georgia, serif; font-size:11.5px; font-style:italic; color:#475569; margin-top:3px;">
                        Grado anterior: <?= $grado_anterior ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Distintivo de Fecha Oficial del Examen (opaco para máxima nitidez) -->
        <div style="display:inline-block; margin-top:8px; min-width:280px; max-width:340px; background:#ffffff; border:1px solid #94a3b8; border-radius:9999px; padding:3px 22px; font-family:Georgia, serif; font-size:12px; font-style:italic; font-weight:600; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.06); text-align:center;">
            Medellín, <?= $fecha_formateada ?>
        </div>

        <!-- Observaciones / Mención de Honor del Maestro Evaluador (en el centro superior sin tocar firmas) -->
        <?php if (!empty($observaciones)): ?>
            <div style="margin-top:6px; max-width:380px; font-family:Georgia, serif; font-size:9.5px; line-height:1.25; font-style:italic; color:#334155; padding:0 10px;">
                “<?= htmlspecialchars($observaciones) ?>”
            </div>
        <?php endif; ?>
    </div>

    <!-- 
        NOTA: En la plantilla base (diploma_plantilla_v3.png), a partir de y=677px se encuentran 
        impresas las firmas originales y sellos: Gran Maestro Nelson Restrepo (6 Dan), 
        Maestra Ana Patricia Giraldo (4 Dan), Emblema Oficial WTF, Cristian Hincapié y Fran Posada.
        Toda esta zona queda 100% libre e intacta.
    -->

</div>
