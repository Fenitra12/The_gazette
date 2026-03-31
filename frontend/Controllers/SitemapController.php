<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CategoryModel;

class SitemapController
{
    public function index()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        $articleModel = new ArticleModel();
        $categoryModel = new CategoryModel();

        $articles = $articleModel->getLatest(1000);
        $categories = $categoryModel->getAll();

        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Page d'accueil
        echo '  <url><loc>' . $baseUrl . '/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";

        // Pages statiques
        echo '  <url><loc>' . $baseUrl . '/about</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\n";
        echo '  <url><loc>' . $baseUrl . '/contact</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\n";

        // Categories
        foreach ($categories as $cat) {
            echo '  <url><loc>' . $baseUrl . '/categorie/' . htmlspecialchars($cat['slug']) . '</loc><changefreq>daily</changefreq><priority>0.8</priority></url>' . "\n";
        }

        // Articles
        foreach ($articles as $article) {
            $lastmod = date('Y-m-d', strtotime($article['published_at']));
            echo '  <url><loc>' . $baseUrl . '/article/' . htmlspecialchars($article['slug']) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
        }

        echo '</urlset>';
    }
}
