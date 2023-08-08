<?php

namespace Spaede\Support\Database;

class DB
{
    public static function table(string $table): Table
    {
        return (new Table(new Connection(), $table));
    }
}