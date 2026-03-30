<?php
declare(strict_types=1);

namespace BackOffice\Core;

final class Helpers
{
    public static function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

