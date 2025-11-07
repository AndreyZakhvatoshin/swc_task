<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\UseCases\UpdateTaskCase;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(Task $task, UpdateProjectTaskRequest $request, UpdateTaskCase $case): TaskResource
    {
        $data = $request->toData();

        $task = $case($task, $data);

        return new TaskResource($task);
    }

    public function destroy(Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }
}
