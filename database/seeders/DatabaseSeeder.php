<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

//            UnitSeeder::class,
//            CategorySeeder::class,
//            SupplierSeeder::class,
//            CustomerSeeder::class,
//            ProductSeeder::class,
//            SingleProductSeeder::class,

        ]);

    }
}
