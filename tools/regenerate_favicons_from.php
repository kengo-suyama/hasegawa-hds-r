<?php
// tools/regenerate_favicons_from.php
// Usage: php tools/regenerate_favicons_from.php <source.png>
// Regenerates public/favicon-32x32.png, favicon-16x16.png, apple-touch-icon.png, favicon.ico preserving alpha.

if ($argc < 2) {
    echo "Usage: php tools/regenerate_favicons_from.php <source.png>\n";
    exit(1);
}
$src = $argv[1];
if (!file_exists($src)) {
    echo "Source not found: $src\n";
    exit(1);
}

$publicDir = __DIR__ . '/../public';
if (!is_dir($publicDir)) mkdir($publicDir, 0755, true);

$info = getimagesize($src);
if (!$info) {
    echo "Cannot read image info\n";
    exit(1);
}
$mime = $info['mime'];

if ($mime === 'image/png') $img = imagecreatefrompng($src);
elseif ($mime === 'image/jpeg') $img = imagecreatefromjpeg($src);
else {
    echo "Unsupported type: $mime\n";
    exit(1);
}

function resizePNG($img, $w, $h, $out)
{
    $dst = imagecreatetruecolor($w, $h);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
    $srcW = imagesx($img);
    $srcH = imagesy($img);
    $ratio = min($w / $srcW, $h / $srcH);
    $newW = (int)($srcW * $ratio);
    $newH = (int)($srcH * $ratio);
    $dstX = (int)(($w - $newW) / 2);
    $dstY = (int)(($h - $newH) / 2);
    imagecopyresampled($dst, $img, $dstX, $dstY, 0, 0, $newW, $newH, $srcW, $srcH);
    imagepng($dst, $out);
    imagedestroy($dst);
}

resizePNG($img, 32, 32, $publicDir . '/favicon-32x32.png');
resizePNG($img, 16, 16, $publicDir . '/favicon-16x16.png');
resizePNG($img, 180, 180, $publicDir . '/apple-touch-icon.png');

// generate ICO from 32x32 PNG bytes
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
    echo "Regenerated favicons in public/\n";
} else {
    echo "Failed to read generated 32 PNG\n";
}

imagedestroy($img);

return 0;
