<?php
// Este archivo es llamado como include dentro de un modal.
// Espera recibir $cert = array con datos del certificado.
if (!isset($cert) || empty($cert)) return;
$foto_url = !empty($cert['foto_perfil'])
    ? base_url('/public/uploads/perfiles/' . $cert['foto_perfil'])
    : asset('img/visual/logo.svg');
$fecha_formateada = !empty($cert['fecha_examen'])
    ? date('d \\de F \\de Y', strtotime($cert['fecha_examen']))
    : date('d \\de F \\de Y');
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
