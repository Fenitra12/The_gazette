<?php
declare(strict_types=1);

namespace BackOffice\Models;

final class Category extends BaseModel
{
    /**
     * @param string $search
     * @return array<int, array{id:int,name:string,slug:string}>
     */
    public function all(string $search = ''): array
    {
        if ($search !== '') {
            $stmt = $this->db->prepare('SELECT id, name, slug FROM categories WHERE name ILIKE :search OR slug ILIKE :search ORDER BY name ASC');
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt = $this->db->query('SELECT id, name, slug FROM categories ORDER BY name ASC');
        }
        return $stmt->fetchAll() ?: [];
    }

    /**
     * @return array{id:int,name:string,slug:string}|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, slug FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row : null;
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        if ($ignoreId !== null) {
            $stmt = $this->db->prepare('SELECT 1 FROM categories WHERE slug = :slug AND id <> :id LIMIT 1');
            $stmt->execute(['slug' => $slug, 'id' => $ignoreId]);
        } else {
            $stmt = $this->db->prepare('SELECT 1 FROM categories WHERE slug = :slug LIMIT 1');
            $stmt->execute(['slug' => $slug]);
        }
        return (bool)$stmt->fetchColumn();
    }

    public function create(string $name, string $slug): int
    {
        $stmt = $this->db->prepare('INSERT INTO categories (name, slug) VALUES (:name, :slug) RETURNING id');
        $stmt->execute(['name' => $name, 'slug' => $slug]);
        return (int)$stmt->fetchColumn();
    }

    public function update(int $id, string $name, string $slug): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = :name, slug = :slug WHERE id = :id');
        $stmt->execute(['id' => $id, 'name' => $name, 'slug' => $slug]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

