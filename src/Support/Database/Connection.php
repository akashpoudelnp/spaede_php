<?php

namespace Spaede\Support\Database;

use PDO;

class Connection extends PDO
{
    private array $connectionConfig = [];

    public function __construct()
    {
        $defaultConnection = config('database.default', 'mysql');

        $this->connectionConfig = config("database.drivers.$defaultConnection") ?? [];

        $dsn = sprintf(
            'mysql:dbname=%s;host=%s',
            $this->connectionConfig['database'],
            $this->connectionConfig['host'],
        );

        parent::__construct(
            $dsn,
            $this->connectionConfig['username'],
            $this->connectionConfig['password']
        );
    }

    public function getConfig(): array
    {
        return $this->connectionConfig;
    }

    public function getDatabase(): ?string
    {
        return $this->connectionConfig['database'] ?? null;
    }
}