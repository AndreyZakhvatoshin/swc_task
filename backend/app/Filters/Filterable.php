<?php

namespace App\Filters;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param Builder $query
     * @param array $params
     * @return Builder
     * @throws Exception
     */
    public function scopeFilter(Builder $query, array $params = []): Builder
    {
        $filter = new $this->filterClass;

        if (!($filter instanceof QueryFilters)) {
            throw new Exception('Filter class is not appropriate');
        }

        return $filter->apply($query, $params);
    }
}
