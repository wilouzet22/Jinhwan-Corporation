<?php
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');
$white = imagecolorallocate($orig, 255, 255, 255);
$wmColor = imagecolorallocate($orig, 196, 214, 214);

// Clear TI
imagefilledrectangle($orig, 510, 494, 610, 555, $white);

// Clear date: X: 370 to 865, Y: 1020 to 1080
for ($y = 1020; $y <= 1080; $y++) {
    for ($x = 370; $x <= 865; $x++) {
        $rgb = imagecolorat($orig, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // If not pure white background (> 250) and not exact watermark
        $isCleanWhite = ($r >= 253 && $g >= 253 && $b >= 253);
        $isCleanWm    = ($r == 196 && $g == 214 && $b == 214);

        if (!$isCleanWhite && !$isCleanWm) {
            // Check reference at y=995
            $refRgb = imagecolorat($orig, $x, 995);
            $refR = ($refRgb >> 16) & 0xFF;
            $refG = ($refRgb >> 8) & 0xFF;
            $refB = $refRgb & 0xFF;

            // Also check reference at y=1095
            $refRgb2 = imagecolorat($orig, $x, 1095);
            $refR2 = ($refRgb2 >> 16) & 0xFF;
            $refG2 = ($refRgb2 >> 8) & 0xFF;
            $refB2 = $refRgb2 & 0xFF;

            $isWm1 = ($refR < 220 && $refG > 190 && $refB > 190);
            $isWm2 = ($refR2 < 220 && $refG2 > 190 && $refB2 > 190);

            if ($isWm1 || $isWm2) {
                imagesetpixel($orig, $x, $y, $wmColor);
            } else {
                imagesetpixel($orig, $x, $y, $white);
            }
        }
    }
}

imagepng($orig, 'public/img/visual/diploma_base_clean.png');
echo "Cleaned seamlessly!\n";
