<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->name(),
            'description'   => $this->faker->words(5, true),
            'phone'         => $this->faker->phoneNumber(),
            'address'       => $this->faker->address(),
        ];
    }
}
