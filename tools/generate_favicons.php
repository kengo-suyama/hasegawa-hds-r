<?php
// tools/generate_favicons.php
// Usage: php tools/generate_favicons.php
// Tries to find a source logo (storage/app/public/画像1.png or storage/app/public/logo.png or public/images/logo.png)
// and generates several favicon files under public/: favicon-32x32.png, favicon-16x16.png, apple-touch-icon.png, favicon.ico

$possible = [
    __DIR__ . '/../storage/app/public/画像1.png',
    __DIR__ . '/../storage/app/public/logo.png',
    __DIR__ . '/../public/images/logo.png',
    __DIR__ . '/../storage/app/public/63923.png',
];
$src = null;
foreach ($possible as $p) {
    if (file_exists($p)) {
        $src = $p;
        break;
    }
}

if (!$src) {
    echo "No source logo found. Please place a PNG file at storage/app/public/画像1.png or storage/app/public/logo.png or public/images/logo.png\n";
    exit(1);
}

// Ensure public dir exists
$publicDir = __DIR__ . '/../public';
if (!is_dir($publicDir)) mkdir($publicDir, 0755, true);

// Load source image via GD
$info = getimagesize($src);
if (!$info) {
    echo "Could not read image info from $src\n";
    exit(1);
}
$mime = $info['mime'];

if ($mime === 'image/png') {
    $img = imagecreatefrompng($src);
} elseif ($mime === 'image/jpeg') {
    $img = imagecreatefromjpeg($src);
} else {
    echo "Unsupported image type: $mime\n";
    exit(1);
}

// create function to resize and save PNG
function resizeAndSavePng($srcImg, $w, $h, $outPath)
{
    $dst = imagecreatetruecolor($w, $h);
    // preserve alpha
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
    $srcW = imagesx($srcImg);
    $srcH = imagesy($srcImg);
    // maintain aspect ratio, fit inside
    $ratio = min($w / $srcW, $h / $srcH);
    $newW = (int)($srcW * $ratio);
    $newH = (int)($srcH * $ratio);
    $dstX = (int)(($w - $newW) / 2);
    $dstY = (int)(($h - $newH) / 2);
    imagecopyresampled($dst, $srcImg, $dstX, $dstY, 0, 0, $newW, $newH, $srcW, $srcH);
    imagepng($dst, $outPath);
    imagedestroy($dst);
}

// Generate PNG favicons
resizeAndSavePng($img, 32, 32, $publicDir . '/favicon-32x32.png');
resizeAndSavePng($img, 16, 16, $publicDir . '/favicon-16x16.png');
resizeAndSavePng($img, 180, 180, $publicDir . '/apple-touch-icon.png');

echo "Generated PNG favicons in public/\n";

// Try to generate ICO (simple single-size ICO containing 32x32)
function saveIcoFromPng($pngPath, $icoPath)
{
    // This is a minimal ICO writer for a single 32x32 PNG (no palette)
    $png = file_get_contents($pngPath);
    if ($png === false) return false;
    // ICO header
    $data = pack('vvv', 0, 1, 1);
    // directory entry: width, height, color count, reserved, planes, bitcount, bytes in resource, image offset
    $width = 32;
    $height = 32;
    $colorCount = 0;
    $reserved = 0;
    $planes = 1;
    $bitcount = 32;
    $bytesInRes = strlen($png);
    $imageOffset = 6 + 16; // header + one dir entry
    $data .= pack('CCCCvvV', $width, $height, $colorCount, $reserved, $planes, $bitcount, $bytesInRes);
    $data .= $png;

    return (bool)file_put_contents($icoPath, $data);
}

$ok = saveIcoFromPng($publicDir . '/favicon-32x32.png', $publicDir . '/favicon.ico');
if ($ok) echo "Generated favicon.ico\n";
else echo "Failed to generate favicon.ico (ICO writer not supported)\n";

imagedestroy($img);

return 0;
