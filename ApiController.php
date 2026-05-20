<?php

namespace App\Controllers;

use App\Core\Helpers;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\Device;
use App\Models\User;
use App\Models\VpnConfig;
use App\Models\VpnServer;

class ApiController extends Controller
{
    public function login(Request $request): Response
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $validator = new Validator();
        $validator->required('email', $email, 'Email is required')->email('email', $email, 'Invalid email')->required('password', $password, 'Password is required');

        if (!$validator->passes()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $user = User::findByEmail($this->app->db(), $email);
        if (!$user || !password_verify($password, $user['password'])) {
            return Response::json(['error' => 'Invalid credentials'], 401);
        }

        $jwt = Helpers::makeJwt(['sub' => $user['id'], 'email' => $user['email']]);
        return Response::json(['token' => $jwt, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']]]);
    }

    public function register(Request $request): Response
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $validator = new Validator();
        $validator->required('name', $name, 'Name is required')->required('email', $email, 'Email is required')->email('email', $email, 'Invalid email')->required('password', $password, 'Password is required')->minLength('password', $password, 8, 'Password must be at least 8 characters');

        if (!$validator->passes()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        if (User::findByEmail($this->app->db(), $email)) {
            return Response::json(['error' => 'Email already registered'], 409);
        }

        $userId = User::create($this->app->db(), ['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_ARGON2ID), 'status' => 'pending']);
        return Response::json(['message' => 'User created', 'user_id' => $userId], 201);
    }

    public function profile(Request $request): Response
    {
        $user = $request->user;
        unset($user['password']);
        return Response::json(['user' => $user]);
    }

    public function servers(Request $request): Response
    {
        $servers = VpnServer::all($this->app->db());
        return Response::json(['servers' => $servers]);
    }

    public function generateConfig(Request $request): Response
    {
        $serverId = (int)$request->input('server_id');
        $protocol = $request->input('protocol', 'udp');
        $user = $request->user;

        $server = VpnServer::find($this->app->db(), $serverId);
        if (!$server) {
            return Response::json(['error' => 'Server not found'], 404);
        }

        $config = VpnConfig::generate($this->app->db(), $user['id'], $server, $protocol);
        return Response::json(['config' => $config]);
    }

    public function devices(Request $request): Response
    {
        $devices = Device::findByUser($this->app->db(), $request->user['id']);
        return Response::json(['devices' => $devices]);
    }

    public function logout(Request $request): Response
    {
        return Response::json(['message' => 'Logged out']);
    }
}
