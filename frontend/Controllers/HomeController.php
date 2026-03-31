<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CategoryModel;

class HomeController extends BaseController
{
    public function index()
    {
        $articleModel = new ArticleModel();
        $categoryModel = new CategoryModel();

        $latestArticles = $articleModel->getLatest(4);
        $popularArticles = $articleModel->getFeatured(3);
        $allLatest = $articleModel->getLatest(15);
        $categories = $categoryModel->getAll();

        // Articles groupés par catégorie (pour la grille)
        $articlesByCategory = [];
        foreach ($categories as $cat) {
            $articlesByCategory[$cat['id']] = $articleModel->getByCategory($cat['id'], 4);
        }

        // LCP : preload the EXACT same image used in the first slide
        // Single 800x600 version = perfect match for preload + HTML
        $firstSlide = $latestArticles[0] ?? null;
        $lcpImageUrl = null;
        if ($firstSlide) {
            $imagePath = 'bg-img/' . (($firstSlide['id'] % 4) + 1) . '.jpg';
            $lcpImageUrl = \App\Core\ImageHelper::url($imagePath, 800, 600);
        }

        $data = [
            'metaTitle'          => 'TheGazette - Iran Conflict News | Accueil',
            'metaDescription'    => 'Latest news and in-depth analysis on the Iran conflict, Middle East tensions and geopolitical crisis.',
            'latestArticles'     => $latestArticles,
            'popularArticles'    => $popularArticles,
            'allLatest'          => $allLatest,
            'categories'         => $categories,
            'articlesByCategory'  => $articlesByCategory,
            'lcpImageUrl'        => $lcpImageUrl,
        ];

        return $this->render('home/index', $data);
    }
}
