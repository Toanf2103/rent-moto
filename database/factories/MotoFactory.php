<?php

namespace Database\Factories;

use App\Models\MotoType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Moto>
 */
class MotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();
        return [
            'name' => 'Xe ' . fake()->unique()->firstName(),
            'license_plate' => $faker->unique()->regexify(config('define.regex.license_plate')),
            'price' => random_int(8, 20) * 10000,
            'moto_type_id' => MotoType::select('id')->inRandomOrder()->first(),
            'description' => $faker->text(200),
        ];
    }
}
