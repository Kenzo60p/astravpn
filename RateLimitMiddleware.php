<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class RateLimitMiddleware
{
    public function handle(Request $request)
    {
        $window = (int)($_ENV['RATE_LIMIT_WINDOW'] ?? 60);
        $max = (int)($_ENV['RATE_LIMIT_MAX'] ?? 120);
        $key = 'rate_limit_' . md5($request->header('x-forwarded-for') . '|' . $request->header('user-agent'));
        $record = $_SESSION[$key] ?? ['count' => 0, 'expires_at' => time() + $window];

        if ($record['expires_at'] < time()) {
            $record = ['count' => 0, 'expires_at' => time() + $window];
        }

        $record['count']++;
        $_SESSION[$key] = $record;

        if ($record['count'] > $max) {
            return Response::json(['error' => 'Rate limit exceeded. Try again later.'], 429);
        }

        return null;
    }
}
