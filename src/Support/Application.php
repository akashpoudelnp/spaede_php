<?php

namespace Spaede\Support;

use Closure;
use Exception;
use Spaede\Contracts\Container;
use Spaede\Support\Engine\EnvParser;

class Application implements Container
{
    public static ?Application $instance = null;

    public static function make(): self
    {
        if (static::$instance) {
            return static::$instance;
        }

        static::$instance = new self();

        return static::$instance;
    }

    private array $definitions = [];

    public function register(string $className, Closure $callback): Container
    {
        $this->definitions[$className] = $callback();

        return $this;
    }

    public function get(string $className): object
    {
        return $this->definitions[$className]
            ??
            throw new Exception(
                sprintf('Class %s::class was not registered to the application', $className)
            );
    }

    /*
     * Bootstrap the application
     */
    public function start(): self
    {
        $this->definitions[Request::class] = new Request();

        return $this;
    }

    public function request(): Request
    {
        return $this->definitions[Request::class];
    }

    public function sendRequestThroughRouter()
    {
        return (new Routing($this))->resolve();
    }
}