<?php

namespace App\Core;

class Response
{
    protected array|string $content;
    protected int $status;
    protected array $headers;

    public function __construct(array|string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = array_merge(['Content-Type' => 'application/json; charset=utf-8'], $headers);
    }

    public static function json(array $data, int $status = 200): self
    {
        return new self($data, $status, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        if (is_array($this->content)) {
            echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo $this->content;
        }
    }
}
