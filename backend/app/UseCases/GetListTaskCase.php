<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

class GetListTaskCase
{
    public function __invoke(Project $project, array $filters): LengthAwarePaginator
    {
        return $project->tasks()->filter($filters)->paginate();
    }
}
