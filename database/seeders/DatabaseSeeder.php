<?php

namespace Database\Seeders;

use App\Models\Category;
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

        Supplier::factory(100)
            ->create([
                'user_id'   => User::query()->inRandomOrder()->first()->id
            ]);

        Unit::factory(50)
            ->create([
                'user_id'   => User::query()->inRandomOrder()->first()->id
            ]);

        Category::factory(20)
            ->create([
                'user_id'   => User::query()->inRandomOrder()->first()->id
            ]);
    }
}
