<?php
declare(strict_types=1);

namespace BackOffice\Core;

final class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && is_int($_SESSION['user']['id']);
    }

    public static function id(): ?int
    {
        if (!self::check()) {
            return null;
        }
        return $_SESSION['user']['id'];
    }

    /**
     * @param array{id:int,email:string,role:string} $user
     */
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => (string)$user['email'],
            'role' => (string)$user['role'],
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool)$params['secure'], (bool)$params['httponly']);
        }
        session_destroy();
    }
}

