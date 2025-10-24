<?php
// tools/sharpen_favicons.php
// Usage: php tools/sharpen_favicons.php <source.png>
// Loads a PNG with alpha and produces sharpened favicon PNGs (32x32,16x16,180x180) using a simple unsharp mask.

$strength = 'medium';
if ($argc < 2) {
    echo "Usage: php tools/sharpen_favicons.php <source.png> [light|medium|strong]\n";
    exit(1);
}
$src = $argv[1];
if (!file_exists($src)) {
    echo "Source missing: $src\n";
    exit(1);
}
if ($argc >= 3) {
    $strength = $argv[2];
}
$publicDir = __DIR__ . '/../public';
if (!is_dir($publicDir)) mkdir($publicDir, 0755, true);

$img = imagecreatefrompng($src);
if (!$img) {
    echo "Failed to load PNG\n";
    exit(1);
}
$srcW = imagesx($img);
$srcH = imagesy($img);

function resampleSharpenSave($srcImg, $targetW, $targetH, $outPath)
{
    $srcW = imagesx($srcImg);
    $srcH = imagesy($srcImg);
    // upscale small sources to a working size then downsample
    $workW = max($targetW * 4, $srcW);
    $workH = max($targetH * 4, (int)($srcH * ($workW / $srcW)));
    $work = imagecreatetruecolor($workW, $workH);
    imagealphablending($work, false);
    imagesavealpha($work, true);
    $trans = imagecolorallocatealpha($work, 0, 0, 0, 127);
    imagefilledrectangle($work, 0, 0, $workW, $workH, $trans);
    $ratio = min($workW / $srcW, $workH / $srcH);
    $newW = (int)($srcW * $ratio);
    $newH = (int)($srcH * $ratio);
    $dx = (int)(($workW - $newW) / 2);
    $dy = (int)(($workH - $newH) / 2);
    imagecopyresampled($work, $srcImg, $dx, $dy, 0, 0, $newW, $newH, $srcW, $srcH);

    // apply simple unsharp mask via convolution kernel
    // Choose kernel based on requested strength
    global $strength;
    if (!isset($strength)) $strength = 'medium';
    if ($strength === 'light') {
        $kernel = [[-1, -1, -1], [-1, 16, -1], [-1, -1, -1]];
    } elseif ($strength === 'strong') {
        // stronger sharpening
        $kernel = [[-2, -4, -2], [-4, 48, -4], [-2, -4, -2]];
    } else {
        // medium
        $kernel = [[-1, -2, -1], [-2, 32, -2], [-1, -2, -1]];
    }
    $div = array_sum(array_map('array_sum', $kernel));
    if ($div == 0) $div = 1;
    @imageconvolution($work, $kernel, $div, 0);

    // downsample to target size
    $dst = imagecreatetruecolor($targetW, $targetH);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefilledrectangle($dst, 0, 0, $targetW, $targetH, $transparent);
    imagecopyresampled($dst, $work, 0, 0, 0, 0, $targetW, $targetH, $workW, $workH);

    imagepng($dst, $outPath);
    imagedestroy($work);
    imagedestroy($dst);
}

resampleSharpenSave($img, 32, 32, $publicDir . '/favicon-32x32.png');
resampleSharpenSave($img, 16, 16, $publicDir . '/favicon-16x16.png');
resampleSharpenSave($img, 180, 180, $publicDir . '/apple-touch-icon.png');

// generate ICO using the 32 PNG
$png32 = file_get_contents($publicDir . '/favicon-32x32.png');
if ($png32 !== false) {
    $data = pack('vvv', 0, 1, 1);
    $width = 32;
    $height = 32;
    $colorCount = 0;
    $reserved = 0;
    $planes = 1;
    $bitcount = 32;
    $bytesInRes = strlen($png32);
    $data .= pack('CCCCvvV', $width, $height, $colorCount, $reserved, $planes, $bitcount, $bytesInRes);
    $data .= $png32;
    file_put_contents($publicDir . '/favicon.ico', $data);
}

imagedestroy($img);

echo "Sharpened favicons regenerated in public/\n";
return 0;
