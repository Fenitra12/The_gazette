<?php
declare(strict_types=1);

namespace BackOffice\Models;

final class User extends BaseModel
{
    /**
     * @return array{id:int,email:string,password_hash:string,role:string}|null
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? $row : null;
    }

    /**
     * @return array<int, array{id:int,email:string,role:string,created_at:string}>
     */
    public function all(): array
    {
        $stmt = $this->db->query('SELECT id, email, role, created_at FROM users ORDER BY id ASC');
        return $stmt->fetchAll() ?: [];
    }

    /**
     * @return array{id:int,email:string,role:string}|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, email, role FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row : null;
    }

    public function emailExists(string $email, ?int $ignoreId = null): bool
    {
        if ($ignoreId !== null) {
            $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email AND id <> :id LIMIT 1');
            $stmt->execute(['email' => $email, 'id' => $ignoreId]);
        } else {
            $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
        }
        return (bool)$stmt->fetchColumn();
    }

    public function create(string $email, string $passwordHash, string $role = 'admin'): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (email, password_hash, role) VALUES (:email, :password_hash, :role) RETURNING id');
        $stmt->execute(['email' => $email, 'password_hash' => $passwordHash, 'role' => $role]);
        return (int)$stmt->fetchColumn();
    }

    public function update(int $id, string $email, string $role): void
    {
        $stmt = $this->db->prepare('UPDATE users SET email = :email, role = :role, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id, 'email' => $email, 'role' => $role]);
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $stmt = $this->db->prepare('UPDATE users SET password_hash = :password_hash, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id, 'password_hash' => $passwordHash]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

