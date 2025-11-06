<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->text(),
            'status' => TaskStatus::PLANNED->value,
            'completion_date' => $this->faker->date(),
            'attachment' => $this->faker->imageUrl(),
        ];
    }
}
