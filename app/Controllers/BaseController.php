<?php

namespace App\Controllers;

abstract class BaseController
{
    protected function render($view, $data = [])
    {
        extract($data);

        ob_start();
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Vue introuvable: " . $view;
        }
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }
}
