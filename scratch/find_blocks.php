<?php
$im = imagecreatefrompng('scratch/diploma_clean_hd.png');
$w = imagesx($im);
$h = imagesy($im);

// Let's find rows with black pixels between y=250 and y=700
echo "Scanning for Certifica que and Aprobó...\n";
$blocks = [];
$currentBlock = null;

for ($y = 200; $y < 900; $y++) {
    $hasDark = false;
    for ($x = 250; $x < 1000; $x += 2) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r < 80 && $g < 80 && $b < 80) {
            $hasDark = true;
            break;
        }
    }
    if ($hasDark) {
        if ($currentBlock === null) {
            $currentBlock = ['start' => $y, 'end' => $y];
        } else {
            $currentBlock['end'] = $y;
        }
    } else {
        if ($currentBlock !== null) {
            $blocks[] = $currentBlock;
            $currentBlock = null;
        }
    }
}
if ($currentBlock !== null) $blocks[] = $currentBlock;

foreach ($blocks as $i => $b) {
    echo "Block $i: y={$b['start']} to y={$b['end']} (height: " . ($b['end'] - $b['start']) . ")\n";
}
