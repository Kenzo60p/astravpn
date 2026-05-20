<?php

namespace App\Core;

class Helpers
{
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    public static function jsonResponse(array $payload, int $status = 200): Response
    {
        return Response::json($payload, $status);
    }

    public static function redirect(string $uri): Response
    {
        header('Location: ' . $uri);
        exit;
    }

    public static function makeJwt(array $payload): string
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $issuedAt = time();
        $expiration = $issuedAt + $config['jwt']['expiration'];
        $data = array_merge($payload, [
            'iss' => $config['jwt']['issuer'],
            'aud' => $config['jwt']['audience'],
            'iat' => $issuedAt,
            'exp' => $expiration,
        ]);

        return \Firebase\JWT\JWT::encode($data, $config['jwt']['secret'], 'HS512');
    }

    public static function parseJwt(string $token): ?object
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        try {
            return \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($config['jwt']['secret'], 'HS512'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
