<?php
declare(strict_types=1);

namespace BackOffice\Models;

use PDO;

final class Article extends BaseModel
{
    /**
     * @return array<int, array<string,mixed>>
     */
    public function paginate(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.id, a.title, a.slug, a.status, a.views, a.published_at,
                    c.name AS category_name, au.name AS author_name
             FROM articles a
             JOIN categories c ON c.id = a.category_id
             JOIN authors au ON au.id = a.author_id
             ORDER BY a.published_at DESC NULLS LAST, a.id DESC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) AS cnt FROM articles')->fetchColumn();
    }

    /**
     * @return array<string,mixed>|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, excerpt, content, featured_image, category_id, author_id,
                    is_featured, status, meta_title, meta_description, views, published_at
             FROM articles
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row : null;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO articles (
                title, slug, excerpt, content, featured_image,
                category_id, author_id,
                is_featured, status,
                meta_title, meta_description,
                views, published_at
            ) VALUES (
                :title, :slug, :excerpt, :content, :featured_image,
                :category_id, :author_id,
                :is_featured, :status,
                :meta_title, :meta_description,
                :views, :published_at
            )
            RETURNING id'
        );
        $stmt->execute([
            'title' => (string)$data['title'],
            'slug' => (string)$data['slug'],
            'excerpt' => $data['excerpt'] !== '' ? (string)$data['excerpt'] : null,
            'content' => (string)$data['content'],
            'featured_image' => $data['featured_image'] !== '' ? (string)$data['featured_image'] : null,
            'category_id' => (int)$data['category_id'],
            'author_id' => (int)$data['author_id'],
            'is_featured' => !empty($data['is_featured']) ? 'true' : 'false',
            'status' => (string)$data['status'],
            'meta_title' => $data['meta_title'] !== '' ? (string)$data['meta_title'] : null,
            'meta_description' => $data['meta_description'] !== '' ? (string)$data['meta_description'] : null,
            'views' => (int)$data['views'],
            'published_at' => $data['published_at'] !== '' ? (string)$data['published_at'] : null,
        ]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * @param array<string,mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE articles SET
                title = :title,
                slug = :slug,
                excerpt = :excerpt,
                content = :content,
                featured_image = :featured_image,
                category_id = :category_id,
                author_id = :author_id,
                is_featured = :is_featured,
                status = :status,
                meta_title = :meta_title,
                meta_description = :meta_description,
                views = :views,
                published_at = :published_at,
                updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'title' => (string)$data['title'],
            'slug' => (string)$data['slug'],
            'excerpt' => $data['excerpt'] !== '' ? (string)$data['excerpt'] : null,
            'content' => (string)$data['content'],
            'featured_image' => $data['featured_image'] !== '' ? (string)$data['featured_image'] : null,
            'category_id' => (int)$data['category_id'],
            'author_id' => (int)$data['author_id'],
            'is_featured' => !empty($data['is_featured']) ? 'true' : 'false',
            'status' => (string)$data['status'],
            'meta_title' => $data['meta_title'] !== '' ? (string)$data['meta_title'] : null,
            'meta_description' => $data['meta_description'] !== '' ? (string)$data['meta_description'] : null,
            'views' => (int)$data['views'],
            'published_at' => $data['published_at'] !== '' ? (string)$data['published_at'] : null,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        if ($ignoreId !== null) {
            $stmt = $this->db->prepare('SELECT 1 FROM articles WHERE slug = :slug AND id <> :id LIMIT 1');
            $stmt->execute(['slug' => $slug, 'id' => $ignoreId]);
        } else {
            $stmt = $this->db->prepare('SELECT 1 FROM articles WHERE slug = :slug LIMIT 1');
            $stmt->execute(['slug' => $slug]);
        }
        return (bool)$stmt->fetchColumn();
    }
}

