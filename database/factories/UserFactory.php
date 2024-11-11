<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'email_verified_at' => now(),
            'password' => Hash::make("1234578"), // password
            'remember_token' => Str::random(10),
            'is_deleted' => false
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}


/*
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string("phone_number");
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId("created_by")->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("updated_by")->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("status_record_id")->constrained("status_records")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();

        });


*/