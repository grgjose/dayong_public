<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MembersProgram>
 */
class MembersProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private static $order;

    public function definition(): array
    {
        return [
            'app_no' => '00'.self::$order++,
            'user_id' => fake()->randomElement(array(1, 2, 3)),
            'member_id' => self::$order++,
            'program_id' => self::$order++,
            'branch_id' => fake()->randomElement(array(1, 2, 3)),
            'claimants_id' => fake()->randomElement(array(1, 2, 3)),
            'beneficiaries_id' => fake()->randomElement(array(1, 2, 3)),
            'or_number' => fake()->randomElement(array(1, 2, 3)),
            'registration_fee' => fake()->randomElement(array(1, 2, 3)),
            'contact_person' => fake()->randomElement(array(1, 2, 3)),
            'contact_person_num' => fake()->randomElement(array(1, 2, 3)),
            'status' => fake()->randomElement(array(1, 2, 3)),
        ];
    }
}
