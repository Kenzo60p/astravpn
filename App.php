<?php

namespace App\Core;

class App
{
    protected array $config;
    protected array $dbConfig;
    protected Router $router;
    protected Database $database;

    public function __construct(array $config, array $dbConfig)
    {
        $this->config = $config;
        $this->dbConfig = $dbConfig;
        $this->database = new Database($dbConfig);
        $this->router = new Router($this);
    }

    public function handle(Request $request): Response
    {
        return $this->router->dispatch($request);
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function db(): Database
    {
        return $this->database;
    }

    public function config(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}
