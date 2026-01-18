<?php

namespace Database\Factories;

use App\Enums\TeacherStatus;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        // Creates a Teacher tenant with a linked user.
        // Note: role/status for the linked user is enforced in Sprint0Seeder.
        return [
            'user_id' => User::factory(),
            'display_name' => $this->faker->name(),
            'status' => TeacherStatus::ACTIVE,
        ];
    }
}
