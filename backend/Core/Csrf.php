<?php
declare(strict_types=1);

namespace BackOffice\Core;

final class Csrf
{
    public static function token(): string
    {
        return (string)($_SESSION['csrf_token'] ?? '');
    }

    public static function verify(?string $token): bool
    {
        $sessionToken = (string)($_SESSION['csrf_token'] ?? '');
        if ($sessionToken === '' || $token === null) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }
}

