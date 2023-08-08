<?php

namespace Spaede\Support\Database\Traits;

use PDO;

trait HandlesRowsOperations
{
    public function get(array $columns = ['*']): false|array
    {
        $query = $this->toSql($columns);

        return $this->connection
            ->query(
                $query
            )
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toSql(array $columns = ['*']): string
    {
        $toSelect = implode(',', $columns);

        $wheres = $this->buildWheresToQuery();

        return sprintf(
            "SELECT %s FROM `%s` %s;",
            $toSelect,
            $this->table,
            $wheres
        );
    }

    private function filterTableColumns(array $values): array
    {
        $existingColumns = array_column($this->rows, 'Field');

        $requiredColumns = array_column(array_filter($this->rows, function ($row) {
            //Exclude id from required columns
            if ($row['Field'] === 'id') {
                return false;
            }
            return $row['Null'] === 'NO';
        }), 'Field');

        $columnsInArray = array_keys($values);

        $invalidColumns = array_diff($columnsInArray, $existingColumns);

        $missingColumns = array_diff($requiredColumns, $columnsInArray);


        return [$columnsInArray, $missingColumns, $invalidColumns];
    }

    private function createInsertQuery(array $rows): string
    {
        $columns = implode(',', array_keys($rows));
        $values = '';

        $index = 0;
        foreach ($rows as $value) {
            $values .= '"' . $value . '"';

            if (count($rows) - 1 !== $index) {
                $values .= ',';
            }

            $index++;
        }

        return sprintf(
            "INSERT INTO `%s` (%s) values (%s);",
            $this->table,
            $columns,
            $values
        );
    }
}