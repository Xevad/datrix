<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AffiliateClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'name' => $this->faker->name(),
            'project_name' => $this->faker->name(),
            'type' => 'web',
            'contact' => $this->faker->phoneNumber(),
            'status_id' => 2,
            'amount' => $this->faker->numerify(),
            'commission' => $this->faker->numerify(),
        ];
    }
}
