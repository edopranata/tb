<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Rokok', 'Mie Instan', 'Minyak Goreng'];
        foreach ($categories as $category) {
            Category::query()
                ->create([
                    'name'      => $category,
                    'user_id'   => User::query()->inRandomOrder()->get()->first()->id
                ]);
        }

    }
}
