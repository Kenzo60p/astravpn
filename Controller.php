<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Request;

abstract class Controller
{
    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function request(): Request
    {
        return Request::capture();
    }
}
