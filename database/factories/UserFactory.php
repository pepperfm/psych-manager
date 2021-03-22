<?php

namespace Database\Factories;

use App\Models\Api\Admin\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Api\Admin\Category;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $categories = Category::query()->select(['id'])->get();
        $doctors = Doctor::query()->select(['id'])->get();

        return [
            'doctor_id' => $doctors->random()->id,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => $this->faker->password,
            'gender' => $this->faker->numberBetween(0, 1),
            'role' => 0,
            'connection_type_id' => rand(1, 6),
            'category_id' => $categories->random()->id,
            'meeting_type' => rand(0, 1)
        ];
    }
}
