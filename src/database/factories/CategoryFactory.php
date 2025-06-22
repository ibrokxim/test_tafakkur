<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(15),
            'image' => $this->faker->imageUrl(640, 480, 'cats'),
        ];
    }
}
