<?php

namespace Database\Factories\Academy;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => fake()->name(),
            'father_name'   => fake()->firstName(),
            'gender'        => fake()->randomElement([0, 1]),
            'mobile'        => fake()->phoneNumber(),
            'second_mobile' => fake()->e164PhoneNumber(),
            'address'       => fake()->address(),
        ];
    }
}
