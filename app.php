<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'AstraVPN Enterprise',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? 'changeme',
        'issuer' => $_ENV['JWT_ISSUER'] ?? 'astravpn.local',
        'audience' => $_ENV['JWT_AUDIENCE'] ?? 'astravpn.local',
        'expiration' => (int)($_ENV['JWT_EXPIRATION'] ?? 3600),
    ],
    'rate_limit' => [
        'window' => (int)($_ENV['RATE_LIMIT_WINDOW'] ?? 60),
        'max' => (int)($_ENV['RATE_LIMIT_MAX'] ?? 120),
    ],
    'paths' => [
        'openvpn' => $_ENV['OPENVPN_PATH'] ?? '/etc/openvpn',
        'wireguard' => $_ENV['WIREGUARD_PATH'] ?? '/etc/wireguard',
    ],
];
