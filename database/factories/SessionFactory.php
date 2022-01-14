<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $today = Carbon::today();

        return [
            'session_date' => $today->addDays(rand(1, 14))->addHours(rand(10, 18))->format('Y-m-d H:i:s'),
            'comment' => $this->faker->realText(350),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
