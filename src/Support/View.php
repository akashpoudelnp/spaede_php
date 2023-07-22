<?php

namespace Spaede\Support;

use Spaede\Contracts\Response;

class View implements Response
{
    public function __construct(public string $path, public array $params)
    {
    }

    public function render(): string
    {
       extract($this->params);

        $viewFilePath = explode('.', $this->path);
        $fileIndex = array_key_last($viewFilePath);
        $viewFile = $viewFilePath[$fileIndex].".spaede.php";
        unset($viewFilePath[$fileIndex]);

        ob_start();
        include "../resources/views/".implode('/', $viewFilePath).'/'.$viewFile;
        $data = ob_get_clean();

        return $data;
    }
}