<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    public function testGetProjectTasksSuccessfully(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $tasks = Task::factory()->count(3)->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/projects/{$project->id}/tasks");

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function testGetProjectTasksWithFilters(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Important task',
            'status' => 'in_progress'
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/projects/{$project->id}/tasks?title=Important&status=in_progress");

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function testGetProjectTasksWithoutAuthentication(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}/tasks");

        $response->assertStatus(401);
    }

    public function testAddTaskToProjectSuccessfully(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();

        Sanctum::actingAs($user);

        $taskData = [
            'title' => 'New task',
            'description' => 'New task description',
            'status' => 'planned',
            'user_id' => $user->id
        ];

        $response = $this->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'description', 'status', 'user']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New task',
            'description' => 'New task description',
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
    }

    public function testAddTaskToProjectWithInvalidData(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();

        Sanctum::actingAs($user);

        $invalidTaskData = [
            'title' => '',
            'description' => 'Description without title',
            'status' => 'invalid_status',
            'user_id' => $user->id
        ];

        $response = $this->postJson("/api/projects/{$project->id}/tasks", $invalidTaskData);

        $response->assertStatus(422);
    }

    public function testAddTaskToProjectWithoutAuthentication(): void
    {
        $project = Project::factory()->create();

        $taskData = [
            'title' => 'New task',
            'description' => 'New task description',
            'status' => 'planned'
        ];

        $response = $this->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(401);
    }
}
