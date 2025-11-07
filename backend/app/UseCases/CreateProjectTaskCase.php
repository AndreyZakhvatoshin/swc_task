<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\CreateProjectTaskDto;
use App\Events\TaskCreated;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class CreateProjectTaskCase
{
    public function __invoke(User $user, Project $project, CreateProjectTaskDto $dto): Task
    {
        $dto->user_id = $user->id;

        $task = $project->tasks()->create($dto->toArray());

        if ($dto->attachments) {
            foreach ($dto->attachments as $attachment) {
                $task->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        event(new TaskCreated($user, $task, $project));

        return $task;
    }
}
