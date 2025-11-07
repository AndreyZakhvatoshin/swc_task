<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskShortResource;
use App\Models\Project;
use App\UseCases\CreateProjectTaskCase;
use App\UseCases\GetListTaskCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function getTasks(Project $project, FilterTaskRequest $request, GetListTaskCase $case): AnonymousResourceCollection
    {
        $tasks = $case($project, $request->validated());

        return TaskShortResource::collection($tasks);
    }

    public function addTask(Project $project, CreateProjectTaskRequest $request, CreateProjectTaskCase $case): JsonResponse
    {

        $data = $request->toData();
        $user = $request->user();

        $task = $case($user, $project, $data);

        return response()->json(new TaskResource($task), Response::HTTP_CREATED);
    }

}
