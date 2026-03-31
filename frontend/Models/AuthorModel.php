<?php

namespace App\Models;

class AuthorModel extends BaseModel
{
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
