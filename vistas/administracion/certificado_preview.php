<?php
// Este archivo es llamado como include dentro de un modal.
// Espera recibir $cert = array con datos del certificado.
if (!isset($cert) || empty($cert)) return;

$foto_url = !empty($cert['foto_perfil'])
    ? base_url('/public/uploads/perfiles/' . $cert['foto_perfil'])
    : asset('img/visual/logo.svg');

$fecha_formateada = !empty($cert['fecha_examen'])
    ? date('d \de F \de Y', strtotime($cert['fecha_examen']))
    : date('d \de F \de Y');

$nombre_alumno = htmlspecialchars($cert['alumno_nombre'] ?? 'Estudiante');
$folio = htmlspecialchars($cert['folio'] ?? '');
$grado_anterior = htmlspecialchars($cert['grado_anterior'] ?? '');
$grado_nuevo = htmlspecialchars($cert['grado_nuevo'] ?? '');
$observaciones = trim((string)($cert['observaciones'] ?? ''));
$director = htmlspecialchars($cert['maestro_nombre'] ?? 'Director del Club');
$profesor = htmlspecialchars($cert['maestro_nombre'] ?? 'Profesor');
$plantilla_url = asset('img/visual/diploma_base.png');
?>
<div id="certificado-contenido" style="width:min(860px, calc(100vw - 2rem)); max-width:100%; margin:0 auto; position:relative; font-family: Georgia, serif; color:#111827; background:#f8f8f8; border:12px solid #111827; border-radius:18px; overflow:hidden; box-shadow:0 20px 50px rgba(15,23,42,.08);">
    <div style="position:relative; width:100%; min-height:1180px; background-image:url('<?= $plantilla_url ?>'); background-size:cover; background-position:center; background-repeat:no-repeat; box-sizing:border-box; padding:56px 54px 48px;">

        <div style="position:absolute; inset:0; background:rgba(255,255,255,0.02);"></div>

        <div style="position:relative; z-index:2;">

            <!-- El nombre del alumno va sobre el área en blanco que deja la plantilla -->
            <div style="text-align:center; margin-top:265px; font-size:48px; line-height:1.15; font-family:'Segoe Print','Bradley Hand',cursive; color:#111827; font-weight:600; letter-spacing:0.5px; text-shadow:0 0 1px rgba(17,24,39,0.2);">
                <?= $nombre_alumno ?>
            </div>

            <!-- El grado nuevo se superpone sobre el placeholder "T1" de la imagen -->
            <div style="text-align:center; margin-top:28px; font-size:96px; line-height:0.9; letter-spacing:2px; font-family:'Segoe UI', sans-serif; font-weight:800; color:rgba(15,23,42,0.55); text-transform:uppercase;">
                <?= $grado_nuevo ?>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:26px; padding:0 18px;">
                <div style="text-align:left; width:36%;">
                    <div style="font-size:18px; font-style:italic; color:#111827; font-family:Georgia, serif; margin-bottom:12px;"><?= $fecha_formateada ?></div>
                    <div style="border-bottom:2px solid #111827; width:100%; height:0; margin-bottom:8px;"></div>
                    <div style="font-size:13px; color:#111827; font-family:Georgia, serif; text-transform:uppercase; letter-spacing:1px;">
                        <?= $director ?>
                    </div>
                    <div style="font-size:18px; font-style:italic; color:#111827; font-family:Georgia, serif; margin-top:6px;">
                        Director del club
                    </div>
                </div>

                <div style="text-align:center; width:30%; padding-bottom:12px;">
                    <div style="display:inline-flex; align-items:center; justify-content:center; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.75); border:3px solid #111827; box-shadow:0 0 0 8px rgba(17,24,39,0.04);">
                        <img src="<?= $foto_url ?>" alt="Foto del estudiante" style="width:90px; height:90px; object-fit:cover; border-radius:50%; border:2px solid rgba(17,24,39,.15);">
                    </div>
                </div>

                <div style="text-align:right; width:36%;">
                    <div style="font-size:18px; font-style:italic; color:#111827; font-family:Georgia, serif; margin-bottom:12px;"><?= $fecha_formateada ?></div>
                    <div style="border-bottom:2px solid #111827; width:100%; height:0; margin-bottom:8px;"></div>
                    <div style="font-size:13px; color:#111827; font-family:Georgia, serif; text-transform:uppercase; letter-spacing:1px;">
                        <?= $profesor ?>
                    </div>
                    <div style="font-size:18px; font-style:italic; color:#111827; font-family:Georgia, serif; margin-top:6px;">
                        Profesor encargado
                    </div>
                </div>
            </div>

            <?php if (!empty($observaciones)): ?>
                <div style="margin-top:30px; text-align:center; font-size:15px; line-height:1.5; font-style:italic; color:#1f2937; font-family:Georgia, serif; padding:0 70px;">
                    “<?= htmlspecialchars($observaciones) ?>”
                </div>
            <?php endif; ?>

            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:34px; padding:0 34px;">
                <div style="text-align:center; width:48%;">
                    <div style="font-size:16px; font-style:italic; color:#111827; font-family:Georgia, serif; margin-bottom:6px;">Folio</div>
                    <div style="font-size:18px; font-weight:700; letter-spacing:2px; color:#111827; font-family:monospace;"><?= $folio ?></div>
                </div>
                <div style="text-align:center; width:48%;">
                    <div style="display:inline-block; border-top:2px solid #111827; padding-top:12px; min-width:220px; text-align:center; font-size:18px; font-weight:700; letter-spacing:2px; color:#111827; font-family:Georgia, serif; text-transform:uppercase;">
                        <?= $grado_anterior ?> → <?= $grado_nuevo ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
