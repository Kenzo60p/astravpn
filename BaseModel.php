<?php

namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected static string $table;
    protected static array $fillable = [];

    public static function all(Database $db): array
    {
        return $db->query('SELECT * FROM `' . static::$table . '` ORDER BY id DESC');
    }

    public static function find(Database $db, int $id): ?array
    {
        $result = $db->query('SELECT * FROM `' . static::$table . '` WHERE id = :id LIMIT 1', ['id' => $id]);
        return $result[0] ?? null;
    }

    public static function create(Database $db, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($key) => ':' . $key, $columns);

        $sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', static::$table, implode(',', $columns), implode(',', $placeholders));
        $db->execute($sql, $data);
        return (int)$db->lastInsertId();
    }
}
