<?php

namespace App\Models;

use App\Core\Database;

class VpnServer extends BaseModel
{
    protected static string $table = 'vpn_servers';

    public static function findByType(Database $db, string $type): array
    {
        return $db->query('SELECT * FROM `vpn_servers` WHERE type = :type ORDER BY created_at DESC', ['type' => $type]);
    }

    public static function updatePing(Database $db, int $id, int $ping): bool
    {
        return $db->execute('UPDATE `vpn_servers` SET ping_ms = :ping WHERE id = :id', ['ping' => $ping, 'id' => $id]);
    }
}
