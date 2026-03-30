<?php
declare(strict_types=1);

namespace BackOffice\Models;

use BackOffice\Core\Database;
use PDO;

abstract class BaseModel
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }
}

