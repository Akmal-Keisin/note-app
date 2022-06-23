<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 3),
            'title' => $this->faker->sentence(rand(2, 5)),
            'body' => $this->faker->paragraph(rand(5, 10)),
            'image' => null
        ];
    }
}
