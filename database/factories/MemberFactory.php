<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'fname' => fake()->unique()->firstName(),
            'mname' => fake()->unique()->lastName(),
            'lname' => fake()->unique()->lastName(),
            'ext' => '',
            'birthdate' => fake()->dateTimeBetween('-30 years', '-15 years'),
            'sex' => fake()->randomElement(array('Male', 'Female')),
            'birthplace' => fake()->state(),
            'citizenship' => 'FILIPINO',
            'civil_status' => fake()->randomElement(array('Single', 'Married')),
            'contact_num' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
        ];
    }
}
