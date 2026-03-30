<?php

namespace App\Controllers;

use App\Models\CategoryModel;

abstract class BaseController
{
    protected function render($view, $data = [])
    {
        // Injecter les catégories pour le menu nav dans toutes les vues
        if (!isset($data['categories'])) {
            $catModel = new CategoryModel();
            $data['categories'] = $catModel->getAll();
        }

        // Valeurs par défaut SEO
        if (!isset($data['metaTitle'])) {
            $data['metaTitle'] = 'TheGazette - Iran Conflict News';
        }
        if (!isset($data['metaDescription'])) {
            $data['metaDescription'] = 'Latest news and analysis on the Iran conflict, Middle East tensions and geopolitical crisis.';
        }

        extract($data);

        ob_start();
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Vue introuvable: " . htmlspecialchars($view);
        }
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }
}
