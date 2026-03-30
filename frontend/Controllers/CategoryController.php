<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    private $perPage = 6;

    public function show($slug, $page = '1')
    {
        $categoryModel = new CategoryModel();
        $articleModel = new ArticleModel();

        $category = $categoryModel->getBySlug($slug);
        if (!$category) {
            http_response_code(404);
            echo "Catégorie introuvable.";
            return;
        }

        $page = max(1, (int) $page);
        $offset = ($page - 1) * $this->perPage;
        $total = $articleModel->countByCategory($category['id']);
        $totalPages = max(1, ceil($total / $this->perPage));
        $articles = $articleModel->getByCategory($category['id'], $this->perPage, $offset);

        // Articles pour le slider éditorial
        $editorialArticles = $articleModel->getLatest(4);

        $data = [
            'metaTitle'         => htmlspecialchars($category['name']) . ' | TheGazette',
            'metaDescription'   => 'Articles about ' . htmlspecialchars($category['name']) . ' - Iran conflict and geopolitical analysis.',
            'category'          => $category,
            'articles'          => $articles,
            'editorialArticles' => $editorialArticles,
            'currentPage'       => $page,
            'totalPages'        => $totalPages,
        ];

        return $this->render('category/index', $data);
    }
}
