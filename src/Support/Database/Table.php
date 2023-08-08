<?php

namespace Spaede\Support\Database;

use Exception;
use PDO;
use PDOException;
use Spaede\Support\Database\Traits\CanExecuteQueries;
use Spaede\Support\Database\Traits\HandlesRowsOperations;
use Spaede\Support\Database\Traits\WheresInTable;

class Table
{
    use CanExecuteQueries, HandlesRowsOperations, WheresInTable;

    private array $rows;

    private array $wheres = [];

    public function __construct(public Connection $connection, public string $table)
    {
        $this->resolveRows();
    }

    private function resolveRows(): void
    {
        $rows = $this->connection->query("SHOW COLUMNS FROM $this->table;")->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            throw new PDOException(
                sprintf(
                    'Table of name %s was not found',
                    $this->table
                )
            );
        }
        $this->rows = $rows;
    }

    public function insert(array $values): bool
    {
        [$toInsertColumns, $missingColumns, $invalidColumns] = $this->filterTableColumns($values);

        if (count($missingColumns) > 0) {
            $this->missingColumnsException($missingColumns);
        }

        return $this->connection
            ->exec($this->createInsertQuery($values));
    }

    private function missingColumnsException($missingColumns)
    {
        $missingColumns = implode(',', $missingColumns);

        throw new Exception(
            sprintf(
                'Invalid Query: missing columns %s',
                $missingColumns
            )
        );
    }

}