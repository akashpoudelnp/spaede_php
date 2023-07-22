<?php

namespace Spaede\Support;

use Spaede\Contracts\ServerRequest;
use function Spaede\Helpers\dd;

class Request implements ServerRequest
{
    private array $_server, $_request;

    public function __construct()
    {
        $this->_server = $_SERVER;
        $this->_request = $_SERVER;
    }

    public function __get($name)
    {
        return $this->_request[$name];
    }

    public function getScheme(): string
    {
        return isset($this->_server['HTTPS']) ? 'https' : 'http';
    }

    public function url(): string
    {
        return sprintf(
            "%s://%s%s",
            $this->getScheme(),
            $this->host(),
            $this->uri()
        );
    }

    public function host()
    {
        return $this->_server['HTTP_HOST'];
    }

    public function uri()
    {
        return $this->_server['REQUEST_URI'];
    }

    public function method()
    {
        return $this->_server['REQUEST_METHOD'];
    }

    public function input($key = null, $default = null): mixed
    {
        $inputs = array_merge($_GET, $_POST);

        if (!$key) {
            return $inputs;
        }

        return $inputs[$key] ?? $default;
    }
}