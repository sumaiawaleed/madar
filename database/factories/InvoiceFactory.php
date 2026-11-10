<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
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
            'status' => InvoiceStatus::Sent,
            'issued_on' => today(),
            'due_on' => today()->addDays(14),
            'has_vat' => true,
            'items' => [['description' => fake()->words(3, true), 'quantity' => 1, 'price' => 1000]],
        ];
    }

    public function paid(): static
    {
        return $this->state(['status' => InvoiceStatus::Paid, 'paid_on' => today()]);
    }

    public function overdue(): static
    {
        return $this->state(['issued_on' => today()->subDays(20), 'due_on' => today()->subDays(3)]);
    }
}
