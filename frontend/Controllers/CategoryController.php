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

        // Schema.org BreadcrumbList
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $schemaOrg = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Home',
                    'item'     => $baseUrl . '/',
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => $category['name'],
                    'item'     => $baseUrl . '/categorie/' . $category['slug'],
                ],
            ],
        ];

        // LCP preload : premiere image du slider editorial
        $firstEditorial = $editorialArticles[0] ?? null;
        $lcpImage = $firstEditorial
            ? \App\Core\ImageHelper::url('blog-img/' . (($firstEditorial['id'] % 25) + 1) . '.jpg', 400)
            : null;

        $data = [
            'metaTitle'         => htmlspecialchars($category['name']) . ' | TheGazette',
            'metaDescription'   => 'Articles about ' . htmlspecialchars($category['name']) . ' - Iran conflict and geopolitical analysis.',
            'category'          => $category,
            'articles'          => $articles,
            'editorialArticles' => $editorialArticles,
            'currentPage'       => $page,
            'totalPages'        => $totalPages,
            'schemaOrg'         => $schemaOrg,
            'lcpImage'          => $lcpImage,
        ];

        return $this->render('category/index', $data);
    }
}
