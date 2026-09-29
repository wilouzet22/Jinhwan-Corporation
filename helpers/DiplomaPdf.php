<?php

class DiplomaPdf {

    public static function generar(array $cert): string {
        $plantillaPath = __DIR__ . '/../public/img/visual/diploma_plantilla_v3.png';
        if (!file_exists($plantillaPath)) {
            $plantillaPath = __DIR__ . '/../public/img/visual/diploma_base.png';
        }

        $fontPath = __DIR__ . '/../public/fonts/ScriptMTBold.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = 'C:/Windows/Fonts/SCRIPTBL.TTF';
        }

        // Cargar imagen base (1278 x 1654 px)
        $im = @imagecreatefrompng($plantillaPath);
        if (!$im) {
            $im = imagecreatetruecolor(1278, 1654);
            $white = imagecolorallocate($im, 255, 255, 255);
            imagefilledrectangle($im, 0, 0, 1278, 1654, $white);
        }

        $black = imagecolorallocate($im, 0, 0, 0);

        // Procesamiento de datos
        $nombreRaw = trim($cert['alumno_nombre'] ?? 'Estudiante');
        $nombreAlumno = mb_convert_case($nombreRaw, MB_CASE_TITLE, 'UTF-8');

        $tipoDocRaw = trim($cert['tipo_documento'] ?? 'TI');
        $tipoDoc = strtoupper(str_replace(['.', ' ', ':'], '', $tipoDocRaw));
        $numDoc = trim($cert['num_doc'] ?? '');
        $documentoTexto = !empty($numDoc) ? "{$tipoDoc} {$numDoc}" : '';

        $gradoRaw = trim($cert['grado_nuevo'] ?? '');
        $gradoLower = mb_strtolower($gradoRaw, 'UTF-8');

        // Cálculo de Gup / Dan
        $gupCalculado = '';
        if (preg_match('/(gup\s*\d+|\d+\s*gup|dan\s*\d+|\d+\s*dan)/i', $gradoRaw, $m)) {
            $gupCalculado = mb_convert_case($m[0], MB_CASE_TITLE, 'UTF-8');
        } elseif (str_contains($gradoLower, 'blanco')) {
            $gupCalculado = '10° Gup';
        } elseif (str_contains($gradoLower, 'pinta amarilla') || str_contains($gradoLower, 'punta amarilla')) {
            $gupCalculado = '9° Gup';
        } elseif (str_contains($gradoLower, 'amarillo')) {
            $gupCalculado = '8° Gup';
        } elseif (str_contains($gradoLower, 'pinta verde') || str_contains($gradoLower, 'punta verde')) {
            $gupCalculado = '7° Gup';
        } elseif (str_contains($gradoLower, 'verde')) {
            $gupCalculado = '6° Gup';
        } elseif (str_contains($gradoLower, 'pinta azul') || str_contains($gradoLower, 'punta azul')) {
            $gupCalculado = '5° Gup';
        } elseif (str_contains($gradoLower, 'azul')) {
            $gupCalculado = '4° Gup';
        } elseif (str_contains($gradoLower, 'pinta roja') || str_contains($gradoLower, 'punta roja') || str_contains($gradoLower, 'pinta rojo') || str_contains($gradoLower, 'punta rojo')) {
            $gupCalculado = '3° Gup';
        } elseif (str_contains($gradoLower, 'rojo')) {
            if (str_contains($gradoLower, 'negra') || str_contains($gradoLower, 'negro') || str_contains($gradoLower, 'pinta') || str_contains($gradoLower, 'punta')) {
                $gupCalculado = 'Gup 1';
            } else {
                $gupCalculado = '2° Gup';
            }
        } elseif (str_contains($gradoLower, 'pinta negra') || str_contains($gradoLower, 'punta negra')) {
            $gupCalculado = 'Gup 1';
        } elseif (str_contains($gradoLower, 'negro') || str_contains($gradoLower, 'dan')) {
            if (preg_match('/(\d+)/', $gradoRaw, $d)) {
                $gupCalculado = $d[1] . ' Dan';
            } else {
                $gupCalculado = '1er Dan';
            }
        }

        // Texto del Grado
        if (!empty($gradoRaw)) {
            if (str_contains($gradoLower, 'pinta negro') || str_contains($gradoLower, 'punta negra') || str_contains($gradoLower, 'rojo p, negra')) {
                $gradoTexto = 'Cinturon Rojo P, Negra';
            } else {
                $gradoTexto = mb_convert_case($gradoRaw, MB_CASE_TITLE, 'UTF-8');
                if (!str_starts_with(mb_strtolower($gradoTexto, 'UTF-8'), 'cintur')) {
                    $gradoTexto = 'Cinturón ' . $gradoTexto;
                }
            }
        } else {
            $gradoTexto = 'Cinturón de Taekwondo';
        }

        // Formato de Fecha
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $ts = !empty($cert['fecha_examen']) ? strtotime($cert['fecha_examen']) : time();
        $dia = date('j', $ts);
        $mes = $meses[(int)date('n', $ts)] ?? date('F', $ts);
        $ano = date('Y', $ts);
        $fechaTexto = "{$dia} de {$mes} del {$ano}";

        // Función para centrar texto horizontalmente en la imagen
        $drawCentered = function($size, $y, $text) use ($im, $black, $fontPath) {
            if (empty($text)) return;
            $bbox = imagettfbbox($size, 0, $fontPath, $text);
            $textWidth = abs($bbox[2] - $bbox[0]);
            $imWidth = imagesx($im);
            $x = (int)round(($imWidth - $textWidth) / 2);
            imagettftext($im, $size, 0, $x, $y, $black, $fontPath, $text);
        };

        // 1. Nombre del alumno (debajo de "Certifica que")
        $drawCentered(38, 445, $nombreAlumno);

        // 2. Documento de identidad
        if (!empty($documentoTexto)) {
            $drawCentered(28, 510, $documentoTexto);
        }

        // 3. Bloque de Acreditación (sobre la marca de agua)
        $drawCentered(34, 895, $gradoTexto);
        if (!empty($gupCalculado)) {
            $drawCentered(30, 955, $gupCalculado);
        }
        $drawCentered(26, 1035, $fechaTexto);

        // Exportar imagen como JPEG en alta calidad
        ob_start();
        imagejpeg($im, null, 98);
        $jpegData = ob_get_clean();
        imagedestroy($im);

        // Empaquetar como PDF estándar Letter (612 x 792 pt)
        return self::wrapJpegInPdf($jpegData, 612, 792, 1278, 1654);
    }

    public static function descargar(array $cert) {
        $nombreRaw = trim($cert['alumno_nombre'] ?? 'alumno');
        $cleanName = mb_strtolower($nombreRaw, 'UTF-8');
        $cleanName = str_replace(
            ['á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ'],
            ['a','e','i','o','u','n','a','e','i','o','u','n'],
            $cleanName
        );
        $cleanName = preg_replace('/[^a-z0-9]+/', '-', $cleanName);
        $cleanName = trim($cleanName, '-') ?: 'jinhwan';
        $filename = "diploma-{$cleanName}.pdf";

        $pdf = self::generar($cert);

        // Limpiar buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        echo $pdf;
        exit;
    }

    private static function wrapJpegInPdf(string $jpegData, int $widthPt, int $heightPt, int $imgW, int $imgH): string {
        $size = strlen($jpegData);
        $out = "%PDF-1.4\n";
        $offsets = [];

        // 1 0 obj: Catalog
        $offsets[1] = strlen($out);
        $out .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        // 2 0 obj: Pages
        $offsets[2] = strlen($out);
        $out .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";

        // 3 0 obj: Page
        $offsets[3] = strlen($out);
        $out .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 $widthPt $heightPt] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";

        // 4 0 obj: Image XObject
        $offsets[4] = strlen($out);
        $out .= "4 0 obj\n<< /Type /XObject /Subtype /Image /Width $imgW /Height $imgH /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length $size >>\nstream\n";
        $out .= $jpegData;
        $out .= "\nendstream\nendobj\n";

        // 5 0 obj: Contents stream
        $content = "q $widthPt 0 0 $heightPt 0 0 cm /Im0 Do Q\n";
        $contentLen = strlen($content);
        $offsets[5] = strlen($out);
        $out .= "5 0 obj\n<< /Length $contentLen >>\nstream\n$content\nendstream\nendobj\n";

        // xref
        $xrefOffset = strlen($out);
        $out .= "xref\n0 6\n0000000000 65535 f \n";
        for ($i = 1; $i <= 5; $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $out .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n$xrefOffset\n%%EOF\n";

        return $out;
    }
}
