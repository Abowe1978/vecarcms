<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 10, 1000), // Use randomFloat for prices (2 decimals)
            'trial_days' => $this->faker->numberBetween(0, 365), // Use numberBetween for integers
            'stripe_plan_id' => $this->faker->uuid,
            'features' => [
                'feature1' => $this->faker->word,
                'feature2' => $this->faker->word,
                'feature3' => $this->faker->word,
            ],
        ];
    }
}
