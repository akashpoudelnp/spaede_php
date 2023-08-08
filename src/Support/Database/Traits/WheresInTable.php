<?php

namespace Spaede\Support\Database\Traits;

trait WheresInTable
{
    public function where(string $column, string $operator = '=', string $value = ''): static
    {
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'type' => 'normal'
        ];

        return $this;
    }


    public function whereIn($column, array $values)
    {
        $toInValue = '( ';

        $index = 0;
        foreach ($values as $value) {

            $toInValue .= '"' . $value . '"';

            if (count($values) - 1 !== $index) {
                $toInValue .= ',';
            }

            $index++;
        }

        $toInValue .= ' )';

        $this->wheres[] = [
            'column' => $column,
            'operator' => 'IN',
            'value' => $toInValue,
            'type' => 'in'
        ];

        return $this;
    }

    public function buildWheresToQuery(): string
    {
        $query = ' WHERE ';

        $index = 0;
        foreach ($this->wheres as $where) {
            // TODO: Where In Handle
            $format = '`%s` %s "%s"';

            if ($where['type'] === 'normal') {
                $format = '`%s` %s "%s"';
            } elseif ($where['type'] === 'in') {
                $format = '`%s` %s %s';
            }

            $query .= sprintf(
                $format,
                $where['column'],
                $where['operator'],
                $where['value'],
            );

            if ($index !== (count($this->wheres) - 1)) {
                $query .= " AND ";
            }

            $index++;
        }

        return $query;
    }

}