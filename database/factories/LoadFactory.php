<?php

namespace Database\Factories;

use App\Enums\LoadStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference_no' => 'LD-' . date('Ymd') . '-' . strtoupper($this->faker->bothify('####')),
            'pickup_address' => $this->faker->address(),
            'delivery_address' => $this->faker->address(),
            'pickup_at' => $this->faker->dateTimeBetween('now', '+7 days'),
            'delivery_at' => $this->faker->dateTimeBetween('+7 days', '+14 days'),
            'status' => $this->faker->randomElement(LoadStatus::cases()),
            'assigned_driver_id' => null,
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
