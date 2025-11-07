<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class QueryFilters
{
    protected $params;
    protected $builder;

    /**
     * apply filters
     *
     * @param Builder $builder
     * @param array $params
     * @return Builder
     */
    public function apply(Builder $builder, array $params = []): Builder
    {
        $this->builder = $builder;
        $this->params = $params;

        foreach ($this->params as $filter => $value) {
            $filter = lcfirst(implode('', array_map('ucfirst', explode('_', $filter))));

            if (!method_exists($this, $filter)) {
                continue;
            }
            $this->$filter($value);
        }

        return $this->builder;
    }
}
