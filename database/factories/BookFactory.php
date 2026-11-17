<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'  => fake()->sentence(3),
            'author' => fake()->name(),
            'status' => fake()->randomElement(['to-read', 'reading', 'finished']),
            'rating' => fake()->optional()->numberBetween(1, 5),
        ];
    }
}