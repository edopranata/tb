<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'unit_id' => Unit::query()->inRandomOrder()->get()->first()->id,
            'category_id' => Category::query()->inRandomOrder()->get()->first()->id,
            'name'  => $this->faker->words($this->faker->numberBetween(1,3), true),
            'barcode'   => $this->faker->unique()->isbn10(),
            'description' => $this->faker->text(),
        ];
    }
}
