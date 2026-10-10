<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="min-h-screen py-16 relative overflow-hidden transition-colors duration-300">

    <!-- Fondo -->
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(135deg,#eff6ff 0%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 max-w-2xl">

        <!-- Encabezado -->
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-wider">
                Caracterización
            </h1>
            <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full mb-5"></div>
            <p class="text-slate-600 dark:text-slate-400 text-base leading-relaxed max-w-lg mx-auto">
                Ingresa tu número de documento para verificar si ya estás registrado en el sistema
                y completar o corregir tus datos.
            </p>
        </div>

        <!-- ── PASO 1: Buscar por documento ── -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-lg p-8 mb-8">
            <form method="GET" action="/caracterizacion" class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex-1">
                    <label for="doc" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Número de documento
                    </label>
                    <input
                        type="text"
                        id="doc"
                        name="doc"
                        value="<?= htmlspecialchars($num_doc) ?>"
                        placeholder="Ej: 1012345678"
                        autocomplete="off"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-3 text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition"
                    >
                </div>
                <button type="submit"
                    class="bg-tkd-blue hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 shadow hover:shadow-lg whitespace-nowrap">
                    🔍 Buscar
                </button>
            </form>
        </div>

        <!-- ── ERROR: no encontrado ── -->
        <?php if ($error === 'no_encontrado'): ?>
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-700 rounded-2xl p-6 text-center mb-8">
            <div class="text-4xl mb-3">🔎</div>
            <p class="text-amber-800 dark:text-amber-200 font-semibold text-lg mb-1">No encontrado</p>
            <p class="text-amber-700 dark:text-amber-300 text-sm">
                El documento <strong><?= htmlspecialchars($num_doc) ?></strong> no está registrado en el sistema.
                Si crees que es un error, comunícate con la academia.
            </p>
        </div>
        <?php endif; ?>

        <!-- ── ÉXITO: datos guardados ── -->
        <?php if ($success): ?>
        <div id="alert-success" class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-300 dark:border-emerald-700 rounded-2xl p-5 flex items-start gap-4 mb-6">
            <span class="text-3xl">✅</span>
            <div>
                <p class="text-emerald-800 dark:text-emerald-200 font-semibold">¡Datos actualizados correctamente!</p>
                <p class="text-emerald-700 dark:text-emerald-300 text-sm">Tu información ha sido guardada en el sistema.</p>
            </div>
            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-emerald-500 hover:text-emerald-700 text-xl leading-none">&times;</button>
        </div>
        <?php endif; ?>

        <!-- ── FORMULARIO: usuario encontrado ── -->
        <?php if ($usuario): ?>

        <!-- Badge de rol -->
        <div class="flex items-center gap-2 mb-5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                <?= $rol === 'maestro' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' ?>">
                <?= $rol === 'maestro' ? '🥋 Maestro/a' : '👤 Estudiante' ?>
            </span>
            <span class="text-slate-400 dark:text-slate-500 text-sm">Documento: <strong class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($usuario['num_doc']) ?></strong></span>
        </div>

        <!-- Aviso de corrección -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl px-5 py-4 flex items-start gap-3 mb-6">
            <span class="text-blue-500 text-xl mt-0.5">ℹ️</span>
            <p class="text-blue-800 dark:text-blue-200 text-sm leading-relaxed">
                Estos son los datos que tenemos de ti.
                <strong>Si algo está incorrecto, por favor corrígelo.</strong>
                Los campos que no conozcas puedes dejarlos en blanco, la academia los completará más adelante.
            </p>
        </div>

        <!-- Formulario de datos -->
        <form method="POST" action="/caracterizacion/update"
              class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-lg p-8 space-y-6">

            <input type="hidden" name="id"      value="<?= (int)$usuario['id'] ?>">
            <input type="hidden" name="rol"     value="<?= htmlspecialchars($rol) ?>">
            <input type="hidden" name="num_doc" value="<?= htmlspecialchars($usuario['num_doc']) ?>">

            <!-- Nombre y Apellido -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="f-nombre"
                           value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
                           class="form-input" placeholder="Tu nombre">
                </div>
                <div>
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" id="f-apellido"
                           value="<?= htmlspecialchars($usuario['apellido'] ?? '') ?>"
                           class="form-input" placeholder="Tu apellido">
                </div>
            </div>

            <!-- Tipo documento -->
            <div>
                <label class="form-label">Tipo de documento</label>
                <select name="tipo_documento" id="f-tipo-doc" class="form-input">
                    <?php
                    $tipos = ['TI' => 'Tarjeta de Identidad', 'CC' => 'Cédula de Ciudadanía',
                              'CE' => 'Cédula de Extranjería', 'PA' => 'Pasaporte', 'RC' => 'Registro Civil'];
                    foreach ($tipos as $val => $label):
                        $sel = ($usuario['tipo_documento'] ?? 'TI') === $val ? 'selected' : '';
                    ?>
                    <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Correo y Teléfono -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" id="f-correo"
                           value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>"
                           class="form-input" placeholder="correo@ejemplo.com">
                </div>
                <div>
                    <label class="form-label">Teléfono / Celular</label>
                    <input type="tel" name="telefono" id="f-telefono"
                           value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                           class="form-input" placeholder="300 000 0000">
                </div>
            </div>

            <?php if ($rol === 'estudiante'): ?>

            <!-- Fecha de nacimiento -->
            <div>
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="f-fecha-nac"
                       value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>"
                       class="form-input">
            </div>

            <!-- EPS y RH -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">EPS</label>
                    <input type="text" name="eps" id="f-eps"
                           value="<?= htmlspecialchars($usuario['eps'] ?? '') ?>"
                           class="form-input" placeholder="Nombre de tu EPS">
                </div>
                <div>
                    <label class="form-label">Tipo de sangre (RH)</label>
                    <select name="rh" id="f-rh" class="form-input">
                        <option value="">— No sé —</option>
                        <?php foreach (['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $rh):
                            $sel = ($usuario['rh'] ?? '') === $rh ? 'selected' : '';
                        ?>
                        <option value="<?= $rh ?>" <?= $sel ?>><?= $rh ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Peso y División -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" name="peso" id="f-peso" step="0.1" min="10" max="200"
                           value="<?= htmlspecialchars($usuario['peso'] ?? '') ?>"
                           class="form-input" placeholder="Ej: 65.5">
                </div>
                <div>
                    <label class="form-label">División / Categoría de peso</label>
                    <input type="text" name="division" id="f-division"
                           value="<?= htmlspecialchars($usuario['division'] ?? '') ?>"
                           class="form-input" placeholder="Ej: -68kg">
                </div>
            </div>

            <?php endif; ?>

            <!-- Botón guardar -->
            <div class="pt-2">
                <button type="submit" id="btn-guardar"
                    class="w-full bg-gradient-to-r from-tkd-red to-rose-600 hover:from-rose-600 hover:to-tkd-red text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-base tracking-wide">
                    💾 Guardar mis datos
                </button>
                <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-3">
                    Solo actualizamos tus datos. No cambiamos tu contraseña ni tu documento.
                </p>
            </div>

        </form>

        <?php endif; ?>

    </div>
</section>

<style>
.form-label {
    @apply block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5;
}
.form-input {
    @apply w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5
           text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-800
           focus:outline-none focus:ring-2 focus:ring-tkd-blue transition text-sm;
}
</style>

<script>
// Auto-scroll al formulario si ya se buscó un documento
<?php if ($usuario || $error === 'no_encontrado'): ?>
document.addEventListener('DOMContentLoaded', () => {
    const target = document.querySelector('<?= $usuario ? "form[action='/caracterizacion/update']" : "#alert-success, .bg-amber-50" ?>');
    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
});
<?php endif; ?>

// Feedback visual al guardar
const btnGuardar = document.getElementById('btn-guardar');
if (btnGuardar) {
    btnGuardar.closest('form').addEventListener('submit', () => {
        btnGuardar.disabled = true;
        btnGuardar.textContent = '⏳ Guardando...';
    });
}
</script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>
