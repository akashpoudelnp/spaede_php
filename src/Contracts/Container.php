<?php

namespace Spaede\Contracts;

use Closure;

interface Container
{
    public function register(string $className, Closure $callback): self;

    /** @template TClass */
    /** @param  class-string<Tclass>  $className */
    public function get(string $className): object;
}