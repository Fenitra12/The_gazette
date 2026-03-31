<?php
declare(strict_types=1);

namespace BackOffice\Models;

final class Author extends BaseModel
{
    /**
     * @param string $search
     * @return array<int, array{id:int,name:string}>
     */
    public function all(string $search = ''): array
    {
        if ($search !== '') {
            $stmt = $this->db->prepare('SELECT id, name FROM authors WHERE name ILIKE :search ORDER BY name ASC');
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt = $this->db->query('SELECT id, name FROM authors ORDER BY name ASC');
        }
        return $stmt->fetchAll() ?: [];
    }

    /**
     * @return array{id:int,name:string,email:string|null}|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email FROM authors WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row : null;
    }

    public function create(string $name, ?string $email): int
    {
        $stmt = $this->db->prepare('INSERT INTO authors (name, email) VALUES (:name, :email) RETURNING id');
        $stmt->execute(['name' => $name, 'email' => $email]);
        return (int)$stmt->fetchColumn();
    }

    public function update(int $id, string $name, ?string $email): void
    {
        $stmt = $this->db->prepare('UPDATE authors SET name = :name, email = :email WHERE id = :id');
        $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM authors WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

