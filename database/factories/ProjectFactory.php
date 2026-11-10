<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => fn (array $attributes): int => Client::find($attributes['client_id'])->user_id,
            'title' => fake()->sentence(3),
            'status' => ProjectStatus::Doing,
            'progress' => 40,
            'budget' => 5000,
            'starts_on' => today()->subDays(10),
            'due_on' => today()->addDays(20),
            'notes' => null,
        ];
    }
}
