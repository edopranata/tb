<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
        ]);

        for ($i = 1; $i <= 10; $i++) {
            Supplier::factory(5)
                ->create([
                    'user_id' => User::query()->inRandomOrder()->get()->first()->id
                ]);
        }

        for ($i = 1; $i <= 25; $i++) {
            Unit::factory(2)
                ->create([
                    'user_id' => User::query()->inRandomOrder()->get()->first()->id
                ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            Category::factory(2)
                ->create([
                    'user_id' => User::query()->inRandomOrder()->get()->first()->id
                ]);
        }

        for ($i = 1; $i <= 100; $i++) {
            Product::factory(2)
                ->create([
                    'unit_id' => Unit::query()->inRandomOrder()->get()->first()->id,
                    'category_id' => Category::query()->inRandomOrder()->get()->first()->id,
                    'user_id' => User::query()->inRandomOrder()->get()->first()->id,
                ]);
        }
    }
}
