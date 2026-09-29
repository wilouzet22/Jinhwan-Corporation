<?php
$im = imagecreatefrompng('scratch/diploma_clean_hd.png');
echo "Scanning for signatures below y=824...\n";
for ($y = 825; $y < 1400; $y++) {
    for ($x = 200; $x < 1100; $x += 2) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r < 80 && $g < 80 && $b < 80) {
            echo "First dark pixel found at y=$y, x=$x\n";
            break 2;
        }
    }
}
