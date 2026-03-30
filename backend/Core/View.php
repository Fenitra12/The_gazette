<?php
declare(strict_types=1);

namespace BackOffice\Core;

final class View
{
    /**
     * @param array<string,mixed> $data
     */
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found.';
            return;
        }

        ob_start();
        require $viewFile;
        $content = (string)ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }
}

