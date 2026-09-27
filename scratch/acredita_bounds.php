<?php
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');
// Scan y from 750 to 900, x from 400 to 900
$maxY = 0;
for ($y = 750; $y <= 900; $y++) {
    for ($x = 400; $x <= 900; $x++) {
        $rgb = imagecolorat($orig, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        if ($r < 80) {
            if ($y > $maxY) $maxY = $y;
        }
    }
}
echo "Acredita como ends at y=$maxY (scaled to 800: " . round($maxY*800/1278) . ")\n";
