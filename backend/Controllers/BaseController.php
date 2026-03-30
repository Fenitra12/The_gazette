<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Csrf;
use BackOffice\Core\Response;
use BackOffice\Core\View;

abstract class BaseController
{
    /**
     * @param array<string,mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function flash(string $key, ?string $value = null): ?string
    {
        if ($value !== null) {
            $_SESSION['flash'][$key] = $value;
            return null;
        }
        $v = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return is_string($v) ? $v : null;
    }

    protected function requirePostCsrf(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit;
        }

        $token = $_POST['_csrf'] ?? null;
        if (!Csrf::verify(is_string($token) ? $token : null)) {
            http_response_code(419);
            echo 'Invalid CSRF token.';
            exit;
        }
    }

    protected function redirect(string $to): void
    {
        Response::redirect($to);
    }
}

