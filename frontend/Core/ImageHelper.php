<?php

namespace App\Core;

class ImageHelper
{
    /**
     * Build responsive srcset and sizes for an image.
     * Returns an array with 'srcset' and 'sizes'.
     */
    private static function buildSrcset(string $src, int $width, int $height = 0): array
    {
        if ($width <= 0) {
            return ['srcset' => '', 'sizes' => ''];
        }

        $candidates = [
            (int) round($width * 0.5),
            $width,
            (int) round($width * 1.5),
        ];

        $sizes = array_values(array_unique(array_filter($candidates, function ($w) {
            return $w >= 320 && $w <= 2000;
        })));
        sort($sizes);

        $srcsetParts = [];
        foreach ($sizes as $w) {
            $srcsetParts[] = self::url($src, $w, $height) . ' ' . $w . 'w';
        }

        $srcset = implode(', ', $srcsetParts);
        $sizesAttr = '(max-width: 767px) 90vw, ' . $width . 'px';

        return ['srcset' => $srcset, 'sizes' => $sizesAttr];
    }
    /**
     * Retourne l'URL d'une image redimensionnee.
     * Si width = 0, retourne l'URL originale.
     */
    public static function url(string $src, int $width, int $height = 0): string
    {
        if ($width <= 0) {
            return '/img/' . $src;
        }
        return '/img/cache/' . $width . 'x' . $height . '/' . $src;
    }

    /**
     * Retourne l'URL WebP d'une image redimensionnee.
     */
    public static function webpUrl(string $src, int $width, int $height = 0): string
    {
        $webpSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
        if ($width <= 0) {
            return '/img/' . $webpSrc;
        }
        return '/img/cache/' . $width . 'x' . $height . '/' . $webpSrc;
    }

    /**
     * Genere un srcset responsif optimise pour le LCP (hero image).
     * Retourne une chaine srcset et une chaine sizes.
     * 
     * @return array ['srcset' => string, 'sizes' => string]
     */
    public static function responsiveSrcset(string $src, int $desktopWidth, int $desktopHeight = 0): array
    {
        // Breakpoints pour mobile/tablet/desktop
        $widths = [400, 600, 800, 1000, 1200];
        $srcsetParts = [];

        foreach ($widths as $w) {
            $url = self::url($src, $w, $desktopHeight);
            $webpUrl = self::webpUrl($src, $w, $desktopHeight);
            // Format: srcset utilise les deux formats (WebP et fallback)
            $srcsetParts[] = $webpUrl . ' ' . $w . 'w';
        }

        $srcset = implode(', ', $srcsetParts);
        
        // sizes: sur mobile (< 768px), charge 90vw; sur desktop, charge 1200px
        $sizes = '(max-width: 767px) 90vw, 1200px';

        return [
            'srcset' => $srcset,
            'sizes' => $sizes,
        ];
    }

    /**
     * Genere une balise <picture> avec source WebP et fallback <img>.
     */
    public static function tag(string $src, string $alt, int $width, int $height = 0, bool $lazy = true): string
    {
        $url = self::url($src, $width, $height);
        $altEscaped = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
        $lazyAttr = $lazy ? ' loading="lazy"' : ' fetchpriority="high"';
        // When height is not specified, assume 2:3 aspect ratio to avoid layout shift
        $effectiveHeight = $height > 0 ? $height : ($width > 0 ? (int) round($width * 2 / 3) : 0);
        $sizeAttrs = '';
        if ($width > 0) {
            $sizeAttrs .= ' width="' . $width . '"';
        }
        if ($effectiveHeight > 0) {
            $sizeAttrs .= ' height="' . $effectiveHeight . '"';
        }

        $responsive = self::buildSrcset($src, $width, $height);
        $hasSrcset = $responsive['srcset'] !== '';
        $sizesAttr = $hasSrcset ? ' sizes="' . $responsive['sizes'] . '"' : '';
        $srcsetAttr = $hasSrcset ? ' srcset="' . $responsive['srcset'] . '"' . $sizesAttr : '';

        // Generer <picture> avec WebP pour jpg/jpeg/png
        if (preg_match('/\.(jpg|jpeg|png)$/i', $src)) {
            $webpSrcset = self::webpUrl($src, $width, $height) . ' ' . $width . 'w';
            if ($hasSrcset) {
                $webpParts = [];
                foreach (explode(', ', $responsive['srcset']) as $part) {
                    $segment = explode(' ', $part);
                    $path = $segment[0] ?? '';
                    $w = $segment[1] ?? '';
                    $webpParts[] = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path) . ' ' . $w;
                }
                $webpSrcset = implode(', ', $webpParts);
            }

            return '<picture>'
                . '<source srcset="' . $webpSrcset . '"' . $sizesAttr . ' type="image/webp">'
                . '<img src="' . $url . '"' . $lazyAttr . ' alt="' . $altEscaped . '"' . $sizeAttrs . $srcsetAttr . '>'
                . '</picture>';
        }

        return '<img src="' . $url . '"' . $lazyAttr . ' alt="' . $altEscaped . '"' . $sizeAttrs . $srcsetAttr . '>';
    }
}
