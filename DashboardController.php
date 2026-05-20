<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\User;
use App\Models\VpnServer;
use App\Models\VpnConfig;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (empty($_SESSION['user_id'])) {
            return View::render('auth/login', ['title' => 'AstraVPN Login']);
        }

        $user = User::find($this->app->db(), $_SESSION['user_id']);
        $servers = VpnServer::all($this->app->db());
        $configs = VpnConfig::all($this->app->db());

        return View::render('dashboard', [
            'title' => 'AstraVPN Dashboard',
            'user' => $user,
            'servers' => $servers,
            'configs' => array_filter($configs, fn($config) => $config['user_id'] === $user['id']),
        ]);
    }
}
