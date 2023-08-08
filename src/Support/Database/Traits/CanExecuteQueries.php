<?php

namespace Spaede\Support\Database\Traits;

trait CanExecuteQueries
{
    public function statement($query): bool
    {
        return $this->connection
            ->query($query)
            ->execute();
    }

}