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
$observaciones  = trim((string)($cert['observaciones'] ?? ''));
$nombre_sede    = htmlspecialchars(trim($cert['nombre_sede'] ?? ''));

$plantilla_url  = asset('img/visual/diploma_base.png');
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

<div id="certificado-contenido" style="width:800px; height:1036px; max-width:100%; margin:0 auto; position:relative; background:#ffffff url('<?= $plantilla_url ?>') no-repeat center top; background-size:100% 100%; box-sizing:border-box; box-shadow:0 20px 45px rgba(15,23,42,0.18); border-radius:12px; overflow:hidden; font-family:'Playfair Display', Georgia, serif; color:#0f172a; user-select:none;">

    <!-- 1. ZONA DEL ESTUDIANTE: Nombre, documento, folio y sede sobre la línea de certificación -->
    <div style="position:absolute; top:222px; left:70px; right:70px; text-align:center;">
        <h2 style="font-family:'Cinzel', Georgia, serif; font-size:26px; font-weight:800; color:#0f172a; letter-spacing:1.8px; margin:0; line-height:1.2; text-shadow:0 0 1px rgba(0,0,0,0.15);">
            <?= $nombre_alumno ?>
        </h2>
        <div style="display:inline-flex; align-items:center; gap:8px; margin-top:5px; font-family:'Segoe UI', system-ui, sans-serif; font-size:11.5px; font-weight:700; color:#475569; letter-spacing:1px; text-transform:uppercase;">
            <?php if (!empty($num_doc)): ?>
                <span><?= $tipo_doc ?>: <?= $num_doc ?></span>
                <span style="color:#cbd5e1;">•</span>
            <?php endif; ?>
            <?php if (!empty($folio)): ?>
                <span style="font-family:'Courier New', monospace; letter-spacing:1.5px; color:#1e293b;">FOLIO: <?= $folio ?></span>
            <?php endif; ?>
            <?php if (!empty($nombre_sede)): ?>
                <span style="color:#cbd5e1;">•</span>
                <span>SEDE: <?= $nombre_sede ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- 
        NOTA: En la plantilla base (diploma_base.png), entre y=380px e y=495px ya se encuentra 
        impreso en el diseño oficial el texto:
        "Aprobó el examen reglamentario para Ascenso de grado, según lo requerido por el 
         Programa de esta institución, por lo cual se Acredita como:"
        Por directriz de diseño, NO se posiciona ningún elemento encima de este bloque.
    -->

    <!-- 2. ZONA DE ACREDITACIÓN: Foto, nuevo grado, grado previo, fecha oficial y observaciones -->
    <div style="position:absolute; top:514px; left:80px; right:80px; display:flex; flex-direction:column; align-items:center; text-align:center;">
        
        <!-- Foto oficial del estudiante con marco circular de honor -->
        <div style="width:68px; height:68px; border-radius:50%; border:2.5px solid #0f172a; box-shadow:0 4px 12px rgba(15,23,42,0.22); overflow:hidden; background:#ffffff; margin-bottom:5px; flex-shrink:0;">
            <img src="<?= $foto_url ?>" alt="Foto del estudiante" style="width:100%; height:100%; object-fit:cover; display:block;">
        </div>

        <!-- Grado Acreditado -->
        <div style="font-family:'Cinzel', Georgia, serif; font-size:23px; font-weight:900; color:#881337; letter-spacing:2px; text-transform:uppercase; margin:0; line-height:1.15; text-shadow:0 0 1px rgba(136,19,55,0.2);">
            <?= $grado_nuevo ?>
        </div>

        <!-- Indicador de Grado Anterior -->
        <?php if (!empty($grado_anterior)): ?>
            <div style="font-family:Georgia, serif; font-size:11px; font-style:italic; color:#475569; margin-top:2px;">
                Grado anterior: <?= $grado_anterior ?>
            </div>
        <?php endif; ?>

        <!-- Distintivo de Fecha Oficial del Examen (opaco para pulcritud total) -->
        <div style="display:inline-block; margin-top:6px; min-width:280px; max-width:330px; background:#ffffff; border:1px solid #94a3b8; border-radius:9999px; padding:3px 20px; font-family:Georgia, serif; font-size:12px; font-style:italic; font-weight:600; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.08); text-align:center;">
            Medellín, <?= $fecha_formateada ?>
        </div>

        <!-- Observaciones / Mención de Honor del Maestro Evaluador (en el centro libre sin invadir firmas) -->
        <?php if (!empty($observaciones)): ?>
            <div style="margin-top:5px; max-width:330px; font-family:Georgia, serif; font-size:9.5px; line-height:1.25; font-style:italic; color:#334155; padding:0 5px;">
                “<?= htmlspecialchars($observaciones) ?>”
            </div>
        <?php endif; ?>
    </div>

    <!-- 
        NOTA: En la plantilla base (diploma_base.png), entre y=680px e y=1036px ya se encuentran 
        impresas las firmas originales y sellos: Gran Maestro Nelson Restrepo (6 Dan), 
        Maestra Ana Patricia Giraldo (4 Dan), Emblema Oficial WTF, Cristian Hincapié y Fran Posada.
        La zona inferior queda 100% despejada y libre de elementos para respetar su autenticidad.
    -->

</div>
