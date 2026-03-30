<?php

namespace App\Models;

class ArticleModel extends BaseModel
{
    public function getLatest($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name AS category_name, c.slug AS category_slug, au.name AS author_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN authors au ON a.author_id = au.id
            WHERE a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeatured($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name AS category_name, c.slug AS category_slug, au.name AS author_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN authors au ON a.author_id = au.id
            WHERE a.status = 'published'
            ORDER BY a.views DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name AS category_name, c.slug AS category_slug, au.name AS author_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN authors au ON a.author_id = au.id
            WHERE a.slug = :slug AND a.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function getByCategory($categoryId, $limit = 6, $offset = 0)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name AS category_name, c.slug AS category_slug, au.name AS author_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN authors au ON a.author_id = au.id
            WHERE a.category_id = :cat_id AND a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByCategory($categoryId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM articles
            WHERE category_id = :cat_id AND status = 'published'
        ");
        $stmt->execute([':cat_id' => $categoryId]);
        return (int) $stmt->fetchColumn();
    }

    public function incrementViews($id)
    {
        $stmt = $this->db->prepare("UPDATE articles SET views = views + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
