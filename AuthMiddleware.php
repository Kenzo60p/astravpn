<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Models\User;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        if (empty($_SESSION['user_id'])) {
            if (!str_starts_with($request->path(), '/api')) {
                return \App\Core\View::render('auth.login', ['title' => 'AstraVPN Login']);
            }
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $db = new Database(require dirname(__DIR__, 3) . '/config/database.php');
        $user = User::find($db, $_SESSION['user_id']);
        if (!$user || $user['status'] !== 'active') {
            if (!str_starts_with($request->path(), '/api')) {
                return \App\Core\View::render('auth.login', ['title' => 'Account Suspended']);
            }
            return Response::json(['error' => 'Account inactive or suspended'], 403);
        }

        return null;
    }
}
