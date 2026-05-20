<?php

namespace App\Core;

class Request
{
    protected array $body;
    protected array $query;
    protected array $server;
    protected array $headers;

    public function __construct()
    {
        $this->body = $this->sanitize($_POST);
        $this->query = $this->sanitize($_GET);
        $this->server = $_SERVER;
        $this->headers = $this->buildHeaders();
    }

    public static function capture(): self
    {
        return new self();
    }

    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return strtok($uri, '?') ?: '/';
    }

    public function header(string $key, $default = null)
    {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }

    protected function buildHeaders(): array
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[strtolower(str_replace('_', '-', substr($key, 5)))] = $value;
            }
        }
        return $headers;
    }

    protected function sanitize(array $data): array
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
            if (is_array($value)) {
                return $this->sanitize($value);
            }
            return $value;
        }, $data);
    }
}
