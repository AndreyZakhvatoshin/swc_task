<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testShowTaskSuccessfully(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['id', 'title', 'description', 'status', 'user']])
                 ->assertJson([
                     'data' => [
                         'id' => $task->id,
                         'title' => $task->title,
                         'description' => $task->description,
                         'status' => $task->status
                     ]
                 ]);
    }

    public function testShowTaskWithoutAuthentication(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(401);
    }

    public function testUpdateTaskSuccessfully(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Old title',
            'status' => TaskStatus::PLANNED->value
        ]);

        Sanctum::actingAs($user);

        $updatedData = [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'user_id' => $user->id,
            'status' => TaskStatus::IN_PROGRESS->value
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'title' => 'Updated title',
                         'description' => 'Updated description',
                         'status' => TaskStatus::IN_PROGRESS->value
                     ]
                 ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated title',
            'description' => 'Updated description',
            'status' => TaskStatus::IN_PROGRESS->value
        ]);
    }

    public function testUpdateTaskWithInvalidData(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        Sanctum::actingAs($user);

        $invalidData = [
            'title' => '',
            'status' => 'invalid_status'
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $invalidData);

        $response->assertStatus(422);
    }

    public function testUpdateTaskWithoutAuthentication(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        $updatedData = [
            'title' => 'Updated title',
            'status' => 'in_progress'
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(401);
    }

    public function testDeleteTaskSuccessfully(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }

    public function testDeleteTaskWithoutAuthentication(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(401);
    }
}
