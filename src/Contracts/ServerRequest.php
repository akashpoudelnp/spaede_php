<?php

namespace Spaede\Contracts;

interface ServerRequest
{
    public function getScheme(): string;

    public function url(): string;

    public function host();

    public function uri();
}