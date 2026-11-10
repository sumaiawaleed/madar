<?php

namespace Database\Factories;

use App\Enums\ProposalStatus;
use App\Models\Client;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proposal>
 */
class ProposalFactory extends Factory
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
            'status' => ProposalStatus::Draft,
            'issued_on' => today(),
            'valid_until' => today()->addDays(14),
            'items' => [['description' => fake()->words(3, true), 'quantity' => 1, 'price' => 1000]],
            'notes' => null,
        ];
    }

    public function accepted(): static
    {
        return $this->state(['status' => ProposalStatus::Accepted]);
    }

    public function sent(): static
    {
        return $this->state(['status' => ProposalStatus::Sent]);
    }
}
