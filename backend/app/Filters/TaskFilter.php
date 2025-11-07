<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Exception;

class TaskFilter extends QueryFilters
{
    /**
     * @param string $value
     * @return void
     */
    public function status(string $value): void
    {
        $this->builder->where('status', $value);
    }

    /**
     * @param string $value
     * @return void
     */
    public function user(string $value): void
    {
        $this->builder->whereHas('user', function (Builder $query) use ($value) {
            $query->where('name', 'ilike', '%' . $value . '%');
            $query->orWhere('email', 'ilike', '%' . $value . '%');
        });
    }

    /**
     * @param string $value
     * @return void
     * @throws Exception
     */
    public function completionDate(string $value): void
    {
        $this->builder->whereDate('completion_date', $value);
    }
}
