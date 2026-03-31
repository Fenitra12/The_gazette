<?php

/**
 * Endpoint de redimensionnement d'images.
 * Appele par Nginx via @resize quand l'image en cache n'existe pas encore.
 *
 * URL attendue : /img/cache/{width}x{height}/{subdir}/{filename}
 * subdir accepte : bg-img, blog-img, core-img, uploads
 */

// Parse l'URI
$uri = $_SERVER['REQUEST_URI'] ?? '';
if (!preg_match('#^/img/cache/(\d+)x(\d+)/(bg-img|blog-img|core-img|uploads)/([a-zA-Z0-9._-]+\.(jpg|jpeg|png|gif|webp))$#', $uri, $matches)) {
    http_response_code(404);
    exit('Not found');
}

$width = (int) $matches[1];
$height = (int) $matches[2];
$subdir = $matches[3];
$filename = $matches[4];
$ext = strtolower($matches[5]);
$requestedWebp = ($ext === 'webp');

// Validation des dimensions
if ($width <= 0 || $width > 2000 || $height < 0 || $height > 2000) {
    http_response_code(400);
    exit('Invalid dimensions');
}

// Chemin source (pour les requetes WebP, trouver le fichier original jpg/png/gif)
$frontendImageDir = '/var/www/html/frontend/Views/img';
$backendUploadDir = '/var/www/html/backend/public/uploads';
$cacheBaseDir = $frontendImageDir . '/cache';

$sourceDir = $frontendImageDir;
if ($subdir === 'uploads') {
    $sourceDir = $backendUploadDir;
}
if ($requestedWebp) {
    $originalFilename = null;
    $baseName = pathinfo($filename, PATHINFO_FILENAME);
    foreach (['jpg', 'jpeg', 'png', 'gif'] as $tryExt) {
        $tryPath = $sourceDir . '/' . $subdir . '/' . $baseName . '.' . $tryExt;
        if (file_exists($tryPath)) {
            $originalFilename = $baseName . '.' . $tryExt;
            $sourcePath = $tryPath;
            break;
        }
    }
    if (!$originalFilename) {
        http_response_code(404);
        exit('Source image not found');
    }
} else {
    $sourcePath = $subdir === 'uploads'
        ? $sourceDir . '/' . $filename
        : $sourceDir . '/' . $subdir . '/' . $filename;
    if (!file_exists($sourcePath)) {
        http_response_code(404);
        exit('Source image not found');
    }
}

// Charger l'image source
$imageInfo = getimagesize($sourcePath);
if ($imageInfo === false) {
    http_response_code(400);
    exit('Invalid image');
}

$origWidth = $imageInfo[0];
$origHeight = $imageInfo[1];
$mimeType = $imageInfo['mime'];

// Charger selon le type
switch ($mimeType) {
    case 'image/jpeg':
        $source = imagecreatefromjpeg($sourcePath);
        break;
    case 'image/png':
        $source = imagecreatefrompng($sourcePath);
        break;
    case 'image/gif':
        $source = imagecreatefromgif($sourcePath);
        break;
    default:
        http_response_code(400);
        exit('Unsupported image type');
}

if (!$source) {
    http_response_code(500);
    exit('Failed to load image');
}

// Calculer les dimensions finales
$newWidth = $width;
$newHeight = $height;

// Redimensionner
$resized = imagecreatetruecolor($newWidth, $newHeight);

// Preserver la transparence pour PNG
if ($mimeType === 'image/png') {
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
}

if ($height === 0) {
    // Proportionnel (pas de crop)
    $newHeight = (int) round($origHeight * ($newWidth / $origWidth));

    // Ne pas upscaler en mode proportionnel
    if ($newWidth > $origWidth) {
        $newWidth = $origWidth;
        $newHeight = $origHeight;
    }

    $resized = imagecreatetruecolor($newWidth, $newHeight);
    if ($mimeType === 'image/png') {
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
    }

    imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
} else {
    // Crop centré pour respecter EXACTEMENT la largeur/hauteur demandées
    $targetRatio = $newWidth / $newHeight;
    $sourceRatio = $origWidth / $origHeight;

    if ($sourceRatio > $targetRatio) {
        $cropHeight = $origHeight;
        $cropWidth = (int) round($origHeight * $targetRatio);
        $srcX = (int) round(($origWidth - $cropWidth) / 2);
        $srcY = 0;
    } else {
        $cropWidth = $origWidth;
        $cropHeight = (int) round($origWidth / $targetRatio);
        $srcX = 0;
        $srcY = (int) round(($origHeight - $cropHeight) / 2);
    }

    imagecopyresampled(
        $resized,
        $source,
        0,
        0,
        $srcX,
        $srcY,
        $newWidth,
        $newHeight,
        $cropWidth,
        $cropHeight
    );
}

// Creer le repertoire cache
$cacheDir = $cacheBaseDir . '/' . $width . 'x' . $height . '/' . $subdir;
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

// Detecter le support WebP via le header Accept du navigateur
$acceptWebp = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
$useWebp = $requestedWebp || ($acceptWebp && function_exists('imagewebp') && $mimeType !== 'image/gif');

if ($useWebp && function_exists('imagewebp')) {
    $webpFilename = pathinfo($requestedWebp ? $filename : $filename, PATHINFO_FILENAME) . '.webp';
    $cachePath = $cacheDir . '/' . $webpFilename;
    imagewebp($resized, $cachePath, 65);
    $outputMime = 'image/webp';
} else {
    $cachePath = $cacheDir . '/' . $filename;
    // Sauvegarder dans le format original
    switch ($mimeType) {
        case 'image/jpeg':
            imagejpeg($resized, $cachePath, 80);
            break;
        case 'image/png':
            imagepng($resized, $cachePath, 8);
            break;
        case 'image/gif':
            imagegif($resized, $cachePath);
            break;
    }
    $outputMime = $mimeType;
}

// Liberer la memoire
imagedestroy($source);
imagedestroy($resized);

// Servir le fichier
header('Content-Type: ' . $outputMime);
header('Content-Length: ' . filesize($cachePath));
header('Cache-Control: public, max-age=31536000, immutable');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
header('Vary: Accept');

readfile($cachePath);
exit;
