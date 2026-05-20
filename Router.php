<?php

namespace App\Core;

class Router
{
    protected App $app;
    protected array $routes = [];
    protected array $groupStack = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function add(string $method, string $uri, $action, array $middleware = [])
    {
        $prefix = '';
        $groupMiddleware = [];

        foreach ($this->groupStack as $group) {
            $prefix .= '/' . trim($group['prefix'], '/');
            $groupMiddleware = array_merge($groupMiddleware, $group['middleware']);
        }

        $uri = '/' . trim($prefix . '/' . trim($uri, '/'), '/');
        if ($uri === '') {
            $uri = '/';
        }

        $this->routes[$method][$uri] = ['action' => $action, 'middleware' => array_merge($groupMiddleware, $middleware)];
    }

    public function get(string $uri, $action, array $middleware = [])
    {
        $this->add('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, $action, array $middleware = [])
    {
        $this->add('POST', $uri, $action, $middleware);
    }

    public function group(string $prefix, callable $callback, array $middleware = [])
    {
        $this->groupStack[] = ['prefix' => $prefix, 'middleware' => $middleware];
        $callback($this);
        array_pop($this->groupStack);
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $path = '/' . trim($request->path(), '/');
        $route = $this->routes[$method][$path] ?? null;
        if (!$route) {
            return new Response(['error' => 'Route not found'], 404);
        }

        $middleware = $route['middleware'];
        foreach ($middleware as $middlewareClass) {
            $middlewareInstance = new $middlewareClass();
            $result = $middlewareInstance->handle($request);
            if ($result instanceof Response) {
                return $result;
            }
        }

        $action = $route['action'];
        if (is_array($action)) {
            [$class, $method] = $action;
            $controller = new $class($this->app);
            return $controller->$method($request);
        }

        if (is_callable($action)) {
            return $action($request);
        }

        return new Response(['error' => 'Invalid route handler'], 500);
    }
}
