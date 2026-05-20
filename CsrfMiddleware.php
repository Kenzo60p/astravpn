<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware
{
    public function handle(Request $request)
    {
        if ($request->method() === 'POST') {
            $token = $request->input('csrf_token');
            if (!Helpers::verifyCsrf($token)) {
                return Response::json(['error' => 'Invalid CSRF token'], 403);
            }
        }

        return null;
    }
}
