<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'client_id' => null,
            'title' => fake()->sentence(3),
            'scheduled_on' => today()->addDays(2),
            'starts_at' => '10:00',
            'duration_minutes' => 30,
            'type' => 'مكالمة',
            'location' => null,
        ];
    }
}
