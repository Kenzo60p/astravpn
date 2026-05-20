<?php

namespace App\Models;

use App\Core\Database;

class User extends BaseModel
{
    protected static string $table = 'users';

    public static function findByEmail(Database $db, string $email): ?array
    {
        $result = $db->query('SELECT * FROM `users` WHERE email = :email LIMIT 1', ['email' => $email]);
        return $result[0] ?? null;
    }

    public static function update(Database $db, int $id, array $data): bool
    {
        $assignments = array_map(fn($key) => "{$key} = :{$key}", array_keys($data));
        $data['id'] = $id;
        $sql = sprintf('UPDATE `users` SET %s WHERE id = :id', implode(', ', $assignments));
        return $db->execute($sql, $data);
    }
}
