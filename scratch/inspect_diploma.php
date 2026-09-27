<?php
$img = imagecreatefrompng('public/img/visual/diploma_base.png');
$w = imagesx($img);
$h = imagesy($img);

// Scan rows and print rows that have dark pixels (r < 60, g < 60, b < 60) between x=200 and x=1078
$rowCounts = [];
for ($y = 200; $y < 1200; $y += 5) {
    $c = 0;
    for ($x = 200; $x < 1078; $x += 2) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r < 60 && $g < 60 && $b < 60) {
            $c++;
        }
    }
    if ($c > 10) {
        $rowCounts[] = "y=$y: $c dark pixels";
    }
}
echo implode("\n", $rowCounts);
