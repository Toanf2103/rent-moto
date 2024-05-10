<?php

namespace Database\Factories;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'phone_number' => '0' . mt_rand(100000000, 999999999),
            'dob' => fake()->dateTimeBetween('-50 years', '-18 years'),
            'role' => UserRole::USER,
            'avatar' => fake()->imageUrl(),
            'status' => UserStatus::ACTIVE,
            'address' => fake()->address()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
