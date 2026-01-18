<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        // Creates a Student linked to a teacher and a user.
        // Note: role/status for the linked user is enforced in Sprint0Seeder.
        return [
            'user_id' => User::factory(),
            'teacher_id' => Teacher::factory(),
            'full_name' => $this->faker->name(),
        ];
    }
}
