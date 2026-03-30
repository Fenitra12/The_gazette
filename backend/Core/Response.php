<?php
declare(strict_types=1);

namespace BackOffice\Core;

final class Response
{
    public static function redirect(string $to): void
    {
        header('Location: ' . $to, true, 302);
        exit;
    }
}

