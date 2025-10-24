<?php
// tools/make_transparent.php
// Usage: php tools/make_transparent.php storage/app/public/画像1.png storage/app/public/画像1_transparent.png

if ($argc < 3) {
    echo "Usage: php tools/make_transparent.php <input.png> <output.png>\n";
    exit(1);
}
$in = $argv[1];
$out = $argv[2];

if (!file_exists($in)) {
    echo "Input file not found: $in\n";
    exit(1);
}

$info = getimagesize($in);
if (!$info) {
    echo "Cannot read image info\n";
    exit(1);
}
$mime = $info['mime'];
if ($mime !== 'image/png' && $mime !== 'image/jpeg') {
    echo "Unsupported image type: $mime\n";
    exit(1);
}

if ($mime === 'image/png') {
    $img = imagecreatefrompng($in);
} else {
    $img = imagecreatefromjpeg($in);
}

$w = imagesx($img);
$h = imagesy($img);

// Sample the four corners to estimate background color
function sampleCorners($img, $w, $h)
{
    $samples = [];
    $coords = [[0, 0], [max(0, $w - 1), 0], [0, max(0, $h - 1)], [max(0, $w - 1), max(0, $h - 1)]];
    foreach ($coords as $c) {
        $rgb = imagecolorsforindex($img, imagecolorat($img, $c[0], $c[1]));
        $samples[] = [$rgb['red'], $rgb['green'], $rgb['blue']];
    }
    // average
    $avg = [0, 0, 0];
    foreach ($samples as $s) {
        $avg[0] += $s[0];
        $avg[1] += $s[1];
        $avg[2] += $s[2];
    }
    $avg[0] = round($avg[0] / count($samples));
    $avg[1] = round($avg[1] / count($samples));
    $avg[2] = round($avg[2] / count($samples));
    return $avg;
}
$bg = sampleCorners($img, $w, $h);

// Create new truecolor with alpha
$outImg = imagecreatetruecolor($w, $h);
imagealphablending($outImg, false);
imagesavealpha($outImg, true);

$transparent = imagecolorallocatealpha($outImg, 0, 0, 0, 127);
imagefilledrectangle($outImg, 0, 0, $w, $h, $transparent);

// tolerance for color matching
$tolerance = 40; // adjust if needed

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $idx = imagecolorat($img, $x, $y);
        $c = imagecolorsforindex($img, $idx);
        $dr = abs($c['red'] - $bg[0]);
        $dg = abs($c['green'] - $bg[1]);
        $db = abs($c['blue'] - $bg[2]);
        if ($dr <= $tolerance && $dg <= $tolerance && $db <= $tolerance) {
            // make transparent
            imagesetpixel($outImg, $x, $y, $transparent);
        } else {
            $col = imagecolorallocatealpha($outImg, $c['red'], $c['green'], $c['blue'], 0);
            imagesetpixel($outImg, $x, $y, $col);
        }
    }
}

// Save PNG
if (imagepng($outImg, $out)) {
    echo "Saved transparent PNG to $out\n";
} else {
    echo "Failed to save $out\n";
}

imagedestroy($img);
imagedestroy($outImg);

return 0;
