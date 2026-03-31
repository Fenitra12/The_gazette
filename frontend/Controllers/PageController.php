<?php

namespace App\Controllers;

class PageController extends BaseController
{
    public function about()
    {
        $data = [
            'metaTitle'       => 'About Us | TheGazette',
            'metaDescription' => 'Learn about TheGazette team covering the Iran conflict and Middle East geopolitical news.',
        ];

        return $this->render('page/about', $data);
    }

    public function contact()
    {
        $data = [
            'metaTitle'       => 'Contact | TheGazette',
            'metaDescription' => 'Contact TheGazette for news tips, feedback and inquiries.',
        ];

        return $this->render('page/contact', $data);
    }
}
