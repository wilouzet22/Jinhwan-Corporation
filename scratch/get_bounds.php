<?php
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');
$minX = 2000; $maxX = 0; $minY = 2000; $maxY = 0;

for ($y = 1010; $y <= 1080; $y++) {
    for ($x = 300; $x <= 1000; $x++) {
        $rgb = imagecolorat($orig, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        // Text is black or very dark
        if ($r < 80 && $g < 80 && $b < 80) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}
echo "Date text bounds: X: $minX to $maxX, Y: $minY to $maxY\n";

// Same for TI
$minX = 2000; $maxX = 0; $minY = 2000; $maxY = 0;
for ($y = 480; $y <= 560; $y++) {
    for ($x = 400; $x <= 700; $x++) {
        $rgb = imagecolorat($orig, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r < 80 && $g < 80 && $b < 80) {
            // make sure not the horizontal line if y around line
            if ($y > 488) {
                if ($x < $minX) $minX = $x;
                if ($x > $maxX) $maxX = $x;
                if ($y < $minY) $minY = $y;
                if ($y > $maxY) $maxY = $y;
            }
        }
    }
}
echo "TI text bounds: X: $minX to $maxX, Y: $minY to $maxY\n";
