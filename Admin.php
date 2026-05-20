<?php

namespace App\Models;

use App\Core\Database;

class Admin extends BaseModel
{
    protected static string $table = 'admins';

    public static function findByEmail(Database $db, string $email): ?array
    {
        $result = $db->query('SELECT * FROM `admins` WHERE email = :email LIMIT 1', ['email' => $email]);
        return $result[0] ?? null;
    }
}
