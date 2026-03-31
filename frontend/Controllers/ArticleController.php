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

        // Schema.org NewsArticle
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $schemaOrg = [
            '@context'      => 'https://schema.org',
            '@type'         => 'NewsArticle',
            'headline'      => $article['title'],
            'description'   => $article['excerpt'] ?? '',
            'datePublished' => date('c', strtotime($article['published_at'])),
            'author'        => [
                '@type' => 'Person',
                'name'  => $article['author_name'],
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'TheGazette',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => $baseUrl . '/img/core-img/logo.png',
                ],
            ],
            'mainEntityOfPage' => $baseUrl . '/article/' . $article['slug'],
            'image' => $baseUrl . '/img/blog-img/' . (($article['id'] % 25) + 1) . '.jpg',
        ];

        $data = [
            'metaTitle'       => htmlspecialchars($article['meta_title'] ?: $article['title']) . ' | TheGazette',
            'metaDescription' => htmlspecialchars($article['meta_description'] ?: $article['excerpt']),
            'article'         => $article,
            'related'         => $related,
            'schemaOrg'       => $schemaOrg,
        ];

        return $this->render('article/show', $data);
    }
}
