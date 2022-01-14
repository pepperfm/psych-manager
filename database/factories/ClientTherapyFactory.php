<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\ClientTherapy;

class ClientTherapyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientTherapy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'problem_severity' => $this->faker->numberBetween(1, 5),
            'plan' => $this->faker->realText(),
            'concept_vision' => $this->faker->realText(350),
            'request' => $this->faker->realText(100),
            'notes' => $this->faker->realText(300),
        ];
    }
}
