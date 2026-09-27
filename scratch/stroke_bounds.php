<?php
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');

echo "Stroke detection at Y=1005 (above date text):\n";
for ($x = 350; $x <= 900; $x += 5) {
    $rgb = imagecolorat($orig, $x, 1005);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    if ($r < 240) {
        echo "x=$x (RGB: $r, $g, $b) ";
    }
}
echo "\n";
