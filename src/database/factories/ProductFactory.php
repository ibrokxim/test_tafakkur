<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{

    public function definition(): array
    {
        $title = $this->faker->realText(30);
        return [
            'title' => $title,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph(3),
            'image' => $this->faker->imageUrl(640, 480, 'technics'),
        ];
    }
}
