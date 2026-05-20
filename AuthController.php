<?php

namespace App\Controllers;

use App\Core\Helpers;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): Response
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $token = $request->input('csrf_token');

        if (!Helpers::verifyCsrf($token)) {
            return Response::json(['error' => 'Invalid CSRF token'], 400);
        }

        $validator = new Validator();
        $validator->required('email', $email, 'Email is required')->email('email', $email, 'Invalid email')->required('password', $password, 'Password is required');

        if (!$validator->passes()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $user = User::findByEmail($this->app->db(), $email);
        if (!$user || !password_verify($password, $user['password'])) {
            return Response::json(['error' => 'Invalid credentials'], 401);
        }

        $_SESSION['user_id'] = $user['id'];
        return Response::json(['message' => 'Login successful', 'redirect' => '/dashboard']);
    }

    public function register(Request $request): Response
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $token = $request->input('csrf_token');

        if (!Helpers::verifyCsrf($token)) {
            return Response::json(['error' => 'Invalid CSRF token'], 400);
        }

        $validator = new Validator();
        $validator->required('name', $name, 'Name is required')->required('email', $email, 'Email is required')->email('email', $email, 'Invalid email')->required('password', $password, 'Password is required')->minLength('password', $password, 8, 'Password must be at least 8 characters');

        if (!$validator->passes()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        if (User::findByEmail($this->app->db(), $email)) {
            return Response::json(['error' => 'Email already registered'], 409);
        }

        $hashed = password_hash($password, PASSWORD_ARGON2ID);
        User::create($this->app->db(), ['name' => $name, 'email' => $email, 'password' => $hashed, 'status' => 'pending']);

        return Response::json(['message' => 'Registration successful. Please wait for account activation.']);
    }

    public function logout(Request $request): Response
    {
        session_unset();
        session_destroy();

        return Response::json(['message' => 'Logged out', 'redirect' => '/']);
    }
}
