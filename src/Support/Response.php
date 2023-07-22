<?php

namespace Spaede\Support;

use Spaede\Contracts\Response as ResponseContract;
use function Spaede\Helpers\dd;

class Response implements ResponseContract
{
    public mixed $responsable;

    public function view($path, $params): View
    {
        return (new View($path, $params));
    }

    public function redirect($url)
    {
        $this->responsable = $url;

        return $this;
    }

    public function render(): string
    {
        header("location:".$this->responsable);
        return '';
    }
}