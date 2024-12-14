<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model' => $this->faker->word(),
            'manufacturer' => $this->faker->company(),
            'year' => $this->faker->year(),
            'fuel_type' => $this->faker->randomElement(['gasoline', 'diesel', 'ethanol', 'gnv', 'electric', 'hydrogen']),
            'status' => 'available',
            'owner_id' => $this->faker->numberBetween(1, 10),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
