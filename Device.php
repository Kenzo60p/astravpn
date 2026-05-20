<?php

namespace App\Models;

use App\Core\Database;

class Device extends BaseModel
{
    protected static string $table = 'devices';

    public static function findByUser(Database $db, int $userId): array
    {
        return $db->query('SELECT * FROM `devices` WHERE user_id = :user_id ORDER BY last_seen DESC', ['user_id' => $userId]);
    }
}
