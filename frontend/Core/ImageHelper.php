<?php

namespace App\Core;

class ImageHelper
{
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
     * Genere une balise <picture> avec source WebP et fallback <img>.
     */
    public static function tag(string $src, string $alt, int $width, int $height = 0, bool $lazy = true): string
    {
        $url = self::url($src, $width, $height);
        $altEscaped = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
        $lazyAttr = $lazy ? ' loading="lazy"' : '';
        // When height is not specified, assume 2:3 aspect ratio to avoid layout shift
        $effectiveHeight = $height > 0 ? $height : ($width > 0 ? (int) round($width * 2 / 3) : 0);
        $sizeAttrs = '';
        if ($width > 0) {
            $sizeAttrs .= ' width="' . $width . '"';
        }
        if ($effectiveHeight > 0) {
            $sizeAttrs .= ' height="' . $effectiveHeight . '"';
        }

        // Generer <picture> avec WebP pour jpg/jpeg/png
        if (preg_match('/\.(jpg|jpeg|png)$/i', $src)) {
            $webpUrl = self::webpUrl($src, $width, $height);
            return '<picture>'
                . '<source srcset="' . $webpUrl . '" type="image/webp">'
                . '<img src="' . $url . '"' . $lazyAttr . ' alt="' . $altEscaped . '"' . $sizeAttrs . '>'
                . '</picture>';
        }

        return '<img src="' . $url . '"' . $lazyAttr . ' alt="' . $altEscaped . '"' . $sizeAttrs . '>';
    }
}
