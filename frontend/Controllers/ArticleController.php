<?php

namespace App\Controllers;

use App\Models\ArticleModel;

class ArticleController extends BaseController
{
    public function show($slug)
    {
        $articleModel = new ArticleModel();
        $article = $articleModel->getBySlug($slug);

        if (!$article) {
            http_response_code(404);
            echo "Article introuvable.";
            return;
        }

        $articleModel->incrementViews($article['id']);

        // Articles similaires (même catégorie)
        $related = $articleModel->getByCategory($article['category_id'], 3);

        $data = [
            'metaTitle'       => htmlspecialchars($article['meta_title'] ?: $article['title']) . ' | TheGazette',
            'metaDescription' => htmlspecialchars($article['meta_description'] ?: $article['excerpt']),
            'article'         => $article,
            'related'         => $related,
        ];

        return $this->render('article/show', $data);
    }
}
