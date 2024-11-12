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
            "mac_adress" => $this->faker->macAddress(),
            "user_agent" => $this->faker->userAgent(),
            "auth_token" => $this->faker->uuid(),
            "is_deleted" => $this->faker->randomElement([true, false]),
            "expires_at" => $this->faker->dateTimeBetween("2024-11-11", "2024-12-11"),
            "login_at" => now(),
            "last_activity" => now(),
            "login_attempts" => $this->faker->randomElement([0, 1, 2]),
        ];
    }

    /*
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_system_id")->constrained("user_systems")->onDelete("cascade")->onUpdate("cascade");
            $table->string("ip_adress");
            $table->string("mac_adress");
            $table->string("user_agent");
            $table->string("auth_token");
            $table->boolean("is_deleted");
            $table->date("expires_at");
            $table->date("login_at");
            $table->date("logout_at")->nullable();
            $table->date("last_activity");
            $table->integer("login_attempts");
        });
    */
}
