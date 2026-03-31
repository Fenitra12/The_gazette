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
     * Genere une balise <img> complete avec redimensionnement et lazy loading.
     */
    public static function tag(string $src, string $alt, int $width, int $height = 0, bool $lazy = true): string
    {
        $url = self::url($src, $width, $height);
        $altEscaped = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
        $lazyAttr = $lazy ? ' loading="lazy"' : '';
        $sizeAttrs = '';
        if ($width > 0) {
            $sizeAttrs .= ' width="' . $width . '"';
        }
        if ($height > 0) {
            $sizeAttrs .= ' height="' . $height . '"';
        }

        return '<img src="' . $url . '"' . $lazyAttr . ' alt="' . $altEscaped . '"' . $sizeAttrs . '>';
    }
}
