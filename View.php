<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): Response
    {
        $viewPath = dirname(__DIR__, 2) . '/resources/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewPath)) {
            return new Response(['error' => 'View not found: ' . $viewPath], 500);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $viewPath;
        $body = ob_get_clean();

        return new Response($body, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
