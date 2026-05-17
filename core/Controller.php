<?php

namespace Core;

class Controller
{
    protected array $middlewares = [];
    public string $action = '';

    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
