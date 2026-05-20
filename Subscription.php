<?php

namespace App\Models;

use App\Core\Database;

class Subscription extends BaseModel
{
    protected static string $table = 'subscriptions';

    public static function findActive(Database $db): array
    {
        return $db->query('SELECT * FROM `subscriptions` WHERE active = 1 ORDER BY price ASC');
    }
}
