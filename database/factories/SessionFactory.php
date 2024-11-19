<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "ip_adress" => $this->faker->ipv4(),
            "user_agent" => $this->faker->userAgent(),
            "auth_token" => $this->faker->uuid(),
            "is_active" => $this->faker->randomElement([true, false]),
            "is_deleted" => $this->faker->randomElement([true, false]),
            "expires_at" => $this->faker->dateTimeBetween("2024-11-11", "2024-12-11"),
            "authenticated_at" => now(),
            "accessed_at" => $this->faker->randomElement([now(), null]),
            "logout_at" => $this->faker->randomElement([now(), null]),
            "last_activity" => now(),
            "login_attempts" => $this->faker->randomElement([0, 1, 2]),
        ];
    }
}
