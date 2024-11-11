<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "personal_email" => $this->faker->email(),
            "profile_picture" => $this->faker->url(),
            "birth" => $this->faker->date(),
            "gender" => $this->faker->randomElement(['m', 'f']),
            "is_deleted" => false
        ];
    }
}
