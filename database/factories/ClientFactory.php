<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Enums\RoleEnum;

use App\Models\Category;
use App\Models\User;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @throws \Exception
     * @return array
     */
    public function definition(): array
    {
        $categories = Category::q()->select(['id'])->get();
        $user = User::query()->select(['id'])->get();

        return [
            'user_id' => $user->random()->id,
            'name' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => $this->faker->password,
            'gender' => $this->faker->numberBetween(0, 1),
            'role' => RoleEnum::CLIENT->value,
            'connection_type_id' => random_int(1, 6),
            'category_id' => $categories->random()->id,
            'meeting_type' => random_int(0, 1)
        ];
    }
}
