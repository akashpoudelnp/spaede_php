<?php

namespace Spaede\Contracts\Database;

interface Connection
{
    public function getConfig(): array;

    public function getConnection();

    public function getDatabase(): ?string;
}