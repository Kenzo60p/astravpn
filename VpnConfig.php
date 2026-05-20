<?php

namespace App\Models;

use App\Core\Database;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class VpnConfig extends BaseModel
{
    protected static string $table = 'vpn_configs';

    public static function generate(Database $db, int $userId, array $server, string $protocol): array
    {
        $configData = self::buildConfig($server, $protocol);
        $qrCodePath = self::generateQrCode($configData, $userId, $server['id']);

        $id = self::create($db, [
            'user_id' => $userId,
            'server_id' => $server['id'],
            'protocol' => $protocol,
            'config_data' => $configData,
            'qr_code' => $qrCodePath,
        ]);

        return [
            'id' => $id,
            'server' => $server,
            'protocol' => $protocol,
            'config_data' => $configData,
            'qr_code' => $qrCodePath,
        ];
    }

    protected static function buildConfig(array $server, string $protocol): string
    {
        if ($server['type'] === 'wireguard' || $protocol === 'wireguard') {
            return self::wireguardTemplate($server);
        }

        return self::openVpnTemplate($server, $protocol);
    }

    protected static function openVpnTemplate(array $server, string $protocol): string
    {
        return trim(<<<OVPN
client
dev tun
proto {$protocol}
remote {$server['host']} 1194
resolv-retry infinite
nobind
persist-key
persist-tun
remote-cert-tls server
cipher AES-256-GCM
auth SHA256
verb 3
<ca>
# insert CA certificate here
</ca>
<cert>
# insert client certificate here
</cert>
<key>
# insert client key here
</key>
OVPN
);
    }

    protected static function wireguardTemplate(array $server): string
    {
        return trim(<<<WG
[Interface]
PrivateKey = <YOUR_PRIVATE_KEY>
Address = 10.0.0.2/32
DNS = 1.1.1.1

[Peer]
PublicKey = <SERVER_PUBLIC_KEY>
Endpoint = {$server['host']}:51820
AllowedIPs = 0.0.0.0/0, ::/0
PersistentKeepalive = 25
WG
);
    }

    protected static function generateQrCode(string $content, int $userId, int $serverId): string
    {
        $writer = new PngWriter();
        $qr = QrCode::create($content)
            ->setSize(280)
            ->setMargin(10);

        $result = $writer->write($qr);
        $filename = sprintf('config-%d-%d-%s.png', $userId, $serverId, uniqid());
        $path = dirname(__DIR__, 3) . '/public/storage/cache/' . $filename;
        $result->saveToFile($path);

        return '/storage/cache/' . $filename;
    }
}
