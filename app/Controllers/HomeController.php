<?php

namespace App\Controllers;

use App\Core\Database;

class HomeController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'The Gazette - Accueil',
            'message' => 'Bienvenue dans l\'architecture MVC PHP Natif de The Gazette!'
        ];

        return $this->render('home/index', $data);
    }
    
    public function testDb()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT version()");
            $version = $stmt->fetchColumn();
            
            $data = [
                'title' => 'Test Base de données',
                'message' => 'Connexion réussie ! Version de PostgreSQL : ' . $version
            ];
            
            return $this->render('home/index', $data);
        } catch (\Exception $e) {
            $data = [
                'title' => 'Erreur de base de données',
                'message' => 'Erreur : ' . $e->getMessage()
            ];
            return $this->render('home/index', $data);
        }
    }
}
