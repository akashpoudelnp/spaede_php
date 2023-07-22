<?php

namespace Spaede\Support;

class Router
{
    private static array $routes = [];

    private static function setRoute(string $method, string $path, mixed $action): void
    {
        static::$routes[$path] = [
            'method' => $method,
            'action' => $action,
        ];
    }

    public static function get($path, $action): void
    {
        self::setRoute('GET', $path, $action);
    }

    public static function post($path, $action): void
    {
        self::setRoute('POST', $path, $action);
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }
}