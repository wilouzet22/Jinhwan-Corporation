<?php

if (!function_exists('obtener_tema_marcial')) {
    /**
     * Obtiene la configuración de diseño, paleta cromática y simbolismo marcial
     * según el rol del usuario y su grado/cinturón de Taekwondo.
     *
     * @param string $rol 'Administracion' | 'Maestros' | 'Profesores' | 'Monitores' | 'Deportistas'
     * @param string|null $nombre_nivel Nombre del cinturón (ej. 'Blanco', 'Amarillo', 'Punta Azul', etc.)
     * @return array Configuración visual completa
     */
    function obtener_tema_marcial($rol, $nombre_nivel = null) {
        $rol = trim($rol ?? '');
        $nivel = mb_strtolower(trim($nombre_nivel ?? ''));

        // 1. ADMINISTRADOR -> Dorado Imperial / Oro Olímpico
        if ($rol === 'Administracion' || stripos($rol, 'admin') !== false) {
            return [
                'rol_clave'       => 'admin',
                'rol_etiqueta'    => 'Administración Central',
                'jerarquia'       => 'Dirección Institucional & Honor',
                'codigo'          => 'dorado',
                'nombre_visual'   => 'Dorado Imperial • Honor',
                'hangul'          => '지도', // Jido (Liderazgo / Dirección)
                'filosofia'       => 'Sabiduría, templanza y dirección marcial',
                'color_hex'       => '#D4AF37', // Oro clásico
                'color_dark'      => '#996515',
                'color_light'     => '#FDF6E2',
                'color_accent'    => '#F59E0B',
                'color_rgb'       => '212, 175, 55',
                'glow_rgba'       => 'rgba(212, 175, 55, 0.28)',
                'belt_gradient'   => 'linear-gradient(135deg, #B8860B 0%, #D4AF37 50%, #F59E0B 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(212,175,55,0.18) 0%, rgba(184,134,11,0.06) 100%)',
                'badge_classes'   => 'bg-amber-500/10 text-amber-500 border border-amber-500/30 dark:bg-amber-400/15 dark:text-amber-300 dark:border-amber-400/40',
                'card_border'     => '#D4AF37',
                'stripe_color'    => null,
                'es_punta'        => false
            ];
        }

        // 2. MAESTRO / INSTRUCTOR -> Negro Carbón Dan con Carmesí Marcial
        if (in_array($rol, ['Maestros', 'Profesores', 'Monitores']) || stripos($rol, 'maestro') !== false || stripos($rol, 'profesor') !== false) {
            return [
                'rol_clave'       => 'maestro',
                'rol_etiqueta'    => 'Cuerpo de Maestros',
                'jerarquia'       => 'Cinturón Negro • Sabomnim',
                'codigo'          => 'negro_dan',
                'nombre_visual'   => 'Negro Marcial • Dan',
                'hangul'          => '사범', // Sabom (Maestro / Instructor)
                'filosofia'       => 'Maestría, humildad y dominio del espíritu',
                'color_hex'       => '#18181B', // Negro carbón
                'color_dark'      => '#09090B',
                'color_light'     => '#27272A',
                'color_accent'    => '#DC2626', // Carmesí marcial tradicional
                'color_rgb'       => '24, 24, 27',
                'glow_rgba'       => 'rgba(220, 38, 38, 0.28)',
                'belt_gradient'   => 'linear-gradient(135deg, #09090b 0%, #18181b 40%, #27272a 70%, #dc2626 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(24,24,27,0.85) 0%, rgba(220,38,38,0.15) 100%)',
                'badge_classes'   => 'bg-zinc-900 text-zinc-100 border border-red-600/40 shadow-sm dark:bg-black dark:text-zinc-100 dark:border-red-500/50',
                'card_border'     => '#DC2626',
                'stripe_color'    => '#DC2626',
                'es_punta'        => false
            ];
        }

        // 3. ESTUDIANTES / DEPORTISTAS -> Dinámico por cinturón
        $base = [
            'rol_clave'    => 'estudiante',
            'rol_etiqueta' => 'Alumno Dojang',
            'jerarquia'    => !empty($nombre_nivel) ? $nombre_nivel : 'Grado de Formación',
            'es_punta'     => false,
            'stripe_color' => null
        ];

        // Mapeo detallado de cinturones de Taekwondo
        if (strpos($nivel, 'punta amarillo') !== false || strpos($nivel, 'pinta amarillo') !== false) {
            return array_merge($base, [
                'codigo'          => 'punta_amarillo',
                'nombre_visual'   => 'Blanco Punta Amarilla (9° Gup)',
                'hangul'          => '순수',
                'filosofia'       => 'La semilla despierta en la pureza de la nieve',
                'color_hex'       => '#EAB308',
                'color_dark'      => '#CA8A04',
                'color_light'     => '#FEF9C3',
                'color_accent'    => '#EAB308',
                'color_rgb'       => '234, 179, 8',
                'glow_rgba'       => 'rgba(234, 179, 8, 0.25)',
                'belt_gradient'   => 'linear-gradient(90deg, #F8FAFC 0%, #F8FAFC 75%, #EAB308 75%, #EAB308 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(254,240,138,0.20) 0%, rgba(234,179,8,0.08) 100%)',
                'badge_classes'   => 'bg-yellow-500/10 text-yellow-600 border border-yellow-500/30 dark:bg-yellow-400/15 dark:text-yellow-300 dark:border-yellow-400/40',
                'card_border'     => '#EAB308',
                'stripe_color'    => '#EAB308',
                'es_punta'        => true
            ]);
        }

        if (strpos($nivel, 'amarillo') !== false) {
            return array_merge($base, [
                'codigo'          => 'amarillo',
                'nombre_visual'   => 'Cinturón Amarillo (8° Gup)',
                'hangul'          => '대지',
                'filosofia'       => 'La tierra fértil donde germina y echa raíces el Taekwondo',
                'color_hex'       => '#EAB308',
                'color_dark'      => '#CA8A04',
                'color_light'     => '#FEF08A',
                'color_accent'    => '#F59E0B',
                'color_rgb'       => '234, 179, 8',
                'glow_rgba'       => 'rgba(234, 179, 8, 0.28)',
                'belt_gradient'   => 'linear-gradient(135deg, #CA8A04 0%, #EAB308 50%, #FDE047 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(234,179,8,0.18) 0%, rgba(202,138,4,0.06) 100%)',
                'badge_classes'   => 'bg-yellow-500/15 text-yellow-600 border border-yellow-500/40 dark:bg-yellow-400/20 dark:text-yellow-300 dark:border-yellow-400/50',
                'card_border'     => '#EAB308'
            ]);
        }

        if (strpos($nivel, 'punta verde') !== false || strpos($nivel, 'pinta verde') !== false) {
            return array_merge($base, [
                'codigo'          => 'punta_verde',
                'nombre_visual'   => 'Amarillo Punta Verde (7° Gup)',
                'hangul'          => '새싹',
                'filosofia'       => 'El brote verde que emerge de la tierra hacia el sol',
                'color_hex'       => '#16A34A',
                'color_dark'      => '#15803D',
                'color_light'     => '#DCFCE7',
                'color_accent'    => '#EAB308',
                'color_rgb'       => '22, 163, 74',
                'glow_rgba'       => 'rgba(22, 163, 74, 0.25)',
                'belt_gradient'   => 'linear-gradient(90deg, #EAB308 0%, #EAB308 75%, #16A34A 75%, #16A34A 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(34,197,94,0.15) 0%, rgba(234,179,8,0.08) 100%)',
                'badge_classes'   => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/30 dark:bg-emerald-400/15 dark:text-emerald-300 dark:border-emerald-400/40',
                'card_border'     => '#16A34A',
                'stripe_color'    => '#16A34A',
                'es_punta'        => true
            ]);
        }

        if (strpos($nivel, 'verde') !== false) {
            return array_merge($base, [
                'codigo'          => 'verde',
                'nombre_visual'   => 'Cinturón Verde (6° Gup)',
                'hangul'          => '성장',
                'filosofia'       => 'La planta que crece vigorosa en técnica y disciplina',
                'color_hex'       => '#16A34A',
                'color_dark'      => '#15803D',
                'color_light'     => '#BBF7D0',
                'color_accent'    => '#10B981',
                'color_rgb'       => '22, 163, 74',
                'glow_rgba'       => 'rgba(22, 163, 74, 0.28)',
                'belt_gradient'   => 'linear-gradient(135deg, #15803D 0%, #16A34A 50%, #4ADE80 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(22,163,74,0.18) 0%, rgba(21,128,61,0.06) 100%)',
                'badge_classes'   => 'bg-emerald-500/15 text-emerald-600 border border-emerald-500/40 dark:bg-emerald-400/20 dark:text-emerald-300 dark:border-emerald-400/50',
                'card_border'     => '#16A34A'
            ]);
        }

        if (strpos($nivel, 'punta azul') !== false || strpos($nivel, 'pinta azul') !== false) {
            return array_merge($base, [
                'codigo'          => 'punta_azul',
                'nombre_visual'   => 'Verde Punta Azul (5° Gup)',
                'hangul'          => '지평',
                'filosofia'       => 'La cima del árbol que roza los primeros vientos celestes',
                'color_hex'       => '#2563EB',
                'color_dark'      => '#1D4ED8',
                'color_light'     => '#DBEAFE',
                'color_accent'    => '#16A34A',
                'color_rgb'       => '37, 99, 235',
                'glow_rgba'       => 'rgba(37, 99, 235, 0.25)',
                'belt_gradient'   => 'linear-gradient(90deg, #16A34A 0%, #16A34A 75%, #2563EB 75%, #2563EB 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(37,99,235,0.15) 0%, rgba(22,163,74,0.08) 100%)',
                'badge_classes'   => 'bg-blue-500/10 text-blue-600 border border-blue-500/30 dark:bg-blue-400/15 dark:text-blue-300 dark:border-blue-400/40',
                'card_border'     => '#2563EB',
                'stripe_color'    => '#2563EB',
                'es_punta'        => true
            ]);
        }

        if (strpos($nivel, 'azul') !== false) {
            return array_merge($base, [
                'codigo'          => 'azul',
                'nombre_visual'   => 'Cinturón Azul (4° Gup)',
                'hangul'          => '하늘',
                'filosofia'       => 'El cielo infinito hacia el cual madura la práctica diaria',
                'color_hex'       => '#2563EB',
                'color_dark'      => '#1D4ED8',
                'color_light'     => '#BFDBFE',
                'color_accent'    => '#0284C7',
                'color_rgb'       => '37, 99, 235',
                'glow_rgba'       => 'rgba(37, 99, 235, 0.28)',
                'belt_gradient'   => 'linear-gradient(135deg, #1E40AF 0%, #2563EB 50%, #60A5FA 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(37,99,235,0.18) 0%, rgba(30,64,175,0.06) 100%)',
                'badge_classes'   => 'bg-blue-500/15 text-blue-600 border border-blue-500/40 dark:bg-blue-400/20 dark:text-blue-300 dark:border-blue-400/50',
                'card_border'     => '#2563EB'
            ]);
        }

        if (strpos($nivel, 'punta rojo') !== false || strpos($nivel, 'pinta rojo') !== false) {
            return array_merge($base, [
                'codigo'          => 'punta_rojo',
                'nombre_visual'   => 'Azul Punta Roja (3° Gup)',
                'hangul'          => '열정',
                'filosofia'       => 'El fuego del fervor que despierta la fuerza interior',
                'color_hex'       => '#DC2626',
                'color_dark'      => '#B91C1C',
                'color_light'     => '#FEE2E2',
                'color_accent'    => '#2563EB',
                'color_rgb'       => '220, 38, 38',
                'glow_rgba'       => 'rgba(220, 38, 38, 0.25)',
                'belt_gradient'   => 'linear-gradient(90deg, #2563EB 0%, #2563EB 75%, #DC2626 75%, #DC2626 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(220,38,38,0.15) 0%, rgba(37,99,235,0.08) 100%)',
                'badge_classes'   => 'bg-rose-500/10 text-rose-600 border border-rose-500/30 dark:bg-rose-400/15 dark:text-rose-300 dark:border-rose-400/40',
                'card_border'     => '#DC2626',
                'stripe_color'    => '#DC2626',
                'es_punta'        => true
            ]);
        }

        if (strpos($nivel, 'rojo') !== false) {
            return array_merge($base, [
                'codigo'          => 'rojo',
                'nombre_visual'   => 'Cinturón Rojo (2° Gup)',
                'hangul'          => '경계',
                'filosofia'       => 'El peligro y la advertencia: gran poder que exige máximo autocontrol',
                'color_hex'       => '#DC2626',
                'color_dark'      => '#991B1B',
                'color_light'     => '#FECACA',
                'color_accent'    => '#EA580C',
                'color_rgb'       => '220, 38, 38',
                'glow_rgba'       => 'rgba(220, 38, 38, 0.32)',
                'belt_gradient'   => 'linear-gradient(135deg, #991B1B 0%, #DC2626 50%, #F87171 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(220,38,38,0.20) 0%, rgba(153,27,27,0.08) 100%)',
                'badge_classes'   => 'bg-red-500/15 text-red-600 border border-red-500/40 dark:bg-red-400/20 dark:text-red-300 dark:border-red-400/50',
                'card_border'     => '#DC2626'
            ]);
        }

        if (strpos($nivel, 'punta negro') !== false || strpos($nivel, 'pinta negro') !== false) {
            return array_merge($base, [
                'codigo'          => 'punta_negro',
                'nombre_visual'   => 'Rojo Punta Negra (1° Gup)',
                'hangul'          => '완성',
                'filosofia'       => 'El umbral de la maestría, donde el fuego se funde en la sombra sabia',
                'color_hex'       => '#0F172A',
                'color_dark'      => '#020617',
                'color_light'     => '#E2E8F0',
                'color_accent'    => '#DC2626',
                'color_rgb'       => '15, 23, 42',
                'glow_rgba'       => 'rgba(220, 38, 38, 0.30)',
                'belt_gradient'   => 'linear-gradient(90deg, #DC2626 0%, #DC2626 75%, #0F172A 75%, #0F172A 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(15,23,42,0.40) 0%, rgba(220,38,38,0.15) 100%)',
                'badge_classes'   => 'bg-slate-900 text-red-400 border border-red-500/50 shadow-sm dark:bg-black dark:text-red-300 dark:border-red-400/60',
                'card_border'     => '#DC2626',
                'stripe_color'    => '#0F172A',
                'es_punta'        => true
            ]);
        }

        if (strpos($nivel, 'negro') !== false || strpos($nivel, 'dan') !== false || strpos($nivel, 'poom') !== false) {
            return array_merge($base, [
                'codigo'          => 'negro',
                'nombre_visual'   => !empty($nombre_nivel) ? $nombre_nivel : 'Cinturón Negro / Poom',
                'hangul'          => '태권',
                'filosofia'       => 'La maestría que absorbe todos los colores para nacer de nuevo',
                'color_hex'       => '#0F172A',
                'color_dark'      => '#020617',
                'color_light'     => '#F8FAFC',
                'color_accent'    => '#DC2626',
                'color_rgb'       => '15, 23, 42',
                'glow_rgba'       => 'rgba(220, 38, 38, 0.28)',
                'belt_gradient'   => 'linear-gradient(135deg, #020617 0%, #0F172A 50%, #334155 100%)',
                'banner_gradient' => 'linear-gradient(135deg, rgba(15,23,42,0.60) 0%, rgba(220,38,38,0.12) 100%)',
                'badge_classes'   => 'bg-black text-amber-300 border border-amber-500/40 dark:bg-zinc-950 dark:text-amber-200 dark:border-amber-400/50',
                'card_border'     => '#0F172A'
            ]);
        }

        // Por defecto: Cinturón Blanco (10° Gup)
        return array_merge($base, [
            'codigo'          => 'blanco',
            'nombre_visual'   => 'Cinturón Blanco (10° Gup)',
            'hangul'          => '백색',
            'filosofia'       => 'La pureza y el lienzo en blanco sin conocimiento previo',
            'color_hex'       => '#64748B',
            'color_dark'      => '#334155',
            'color_light'     => '#F8FAFC',
            'color_accent'    => '#94A3B8',
            'color_rgb'       => '100, 116, 139',
            'glow_rgba'       => 'rgba(148, 163, 184, 0.22)',
            'belt_gradient'   => 'linear-gradient(135deg, #E2E8F0 0%, #FFFFFF 50%, #CBD5E1 100%)',
            'banner_gradient' => 'linear-gradient(135deg, rgba(226,232,240,0.30) 0%, rgba(203,213,225,0.10) 100%)',
            'badge_classes'   => 'bg-slate-200/60 text-slate-700 border border-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700',
            'card_border'     => '#94A3B8'
        ]);
    }
}

if (!function_exists('renderizar_variables_tema_marcial')) {
    /**
     * Genera la etiqueta <style> con variables CSS personalizadas
     * para inyectar dinámicamente en cualquier vista o layout.
     */
    function renderizar_variables_tema_marcial($tema) {
        $primary   = $tema['color_hex'] ?? '#2563EB';
        $accent    = $tema['color_accent'] ?? '#DC2626';
        $glow      = $tema['glow_rgba'] ?? 'rgba(37,99,235,0.25)';
        $gradient  = $tema['belt_gradient'] ?? 'linear-gradient(135deg, #2563EB, #1D4ED8)';
        $border    = $tema['card_border'] ?? $primary;
        $rgb       = $tema['color_rgb'] ?? '37, 99, 235';

        return "
        <style id=\"tkd-martial-theme-tokens\">
            :root {
                --tkd-primary: {$primary};
                --tkd-accent: {$accent};
                --tkd-glow: {$glow};
                --tkd-gradient: {$gradient};
                --tkd-border: {$border};
                --tkd-rgb: {$rgb};
            }
        </style>";
    }
}
