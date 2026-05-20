<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\Admin;
use App\Models\User;
use App\Models\VpnServer;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::all($this->app->db());
        $users = User::all($this->app->db());
        $servers = VpnServer::all($this->app->db());

        return View::render('admin.index', [
            'title' => 'AstraVPN Admin',
            'admins' => $admins,
            'users' => $users,
            'servers' => $servers,
            'stats' => [
                'users' => count($users),
                'servers' => count($servers),
                'revenue' => 0,
            ],
        ]);
    }

    public function users(Request $request)
    {
        $users = User::all($this->app->db());
        return View::render('admin.users', ['title' => 'User Management', 'users' => $users]);
    }

    public function servers(Request $request)
    {
        $servers = VpnServer::all($this->app->db());
        return View::render('admin.servers', ['title' => 'Server Management', 'servers' => $servers]);
    }
}
