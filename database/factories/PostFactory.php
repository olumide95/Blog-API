<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'author' => $this->faker->name,
            'publish_at' => null,
        ];
    }

    /**
     * Indicate that the user is suspended.
     */
    public function unpublished(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'publish_at' => $this->faker->dateTimeBetween('+3 day', '+5 day'),
            ];
        });
    }
}
