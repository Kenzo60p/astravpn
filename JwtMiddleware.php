<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;

class JwtMiddleware
{
    public function handle(Request $request)
    {
        $authHeader = $request->header('authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return Response::json(['error' => 'Missing authorization header'], 401);
        }

        $token = trim(substr($authHeader, 7));
        $payload = Helpers::parseJwt($token);
        if (!$payload || empty($payload->sub)) {
            return Response::json(['error' => 'Invalid token'], 401);
        }

        $user = User::find(
            new \App\Core\Database(require dirname(__DIR__, 3) . '/config/database.php'),
            (int)$payload->sub
        );

        if (!$user) {
            return Response::json(['error' => 'User not found'], 401);
        }

        $request->user = $user;
        return null;
    }
}
