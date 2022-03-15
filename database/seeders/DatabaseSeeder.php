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
        $faker = Factory::create();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
        ]);

//            Supplier::factory()->times(10)
//                ->create([
//                    'user_id' => User::query()->inRandomOrder()->get()->first()->id
//                ]);
//
//
//            Unit::factory()->times(5)
//                ->create([
//                    'user_id' => User::query()->inRandomOrder()->get()->first()->id
//                ]);
//
//            Category::factory()->times(10)
//                ->create([
//                    'user_id' => User::query()->inRandomOrder()->get()->first()->id
//                ]);
//
//            $products = Product::factory()->times(100)
//                ->create([
//                    'user_id' => User::query()->inRandomOrder()->get()->first()->id,
//                ]);
//
//            foreach ($products as $product){
//                $prices = $faker->randomNumber(3);
//                $product->prices()->create([
//                    'unit_id'   => $product->unit_id,
//                    'quantity'  => 1,
//                    'sell_price'    => $prices . 2900,
//                    'wholesale_price'   => $prices . 2500,
//                    'customer_price'    => $prices . 2100,
//                    'default'   => '1',
//                ]);
//
//                $quantity[1] = $faker->randomElement([5, 10]);
//                $quantity[2] = $faker->randomElement([12,  20]);
//
//
//                for ($i=1;$i <= 2; $i++){
//                    $product->prices()->create([
//                        'unit_id' => Unit::query()->whereNotIn('id', [$product->unit_id])->inRandomOrder()->first()->id,
//                        'quantity' => $quantity[$i],
//                        'sell_price'    => ($prices . 2900 * $quantity[$i]) - 3000,
//                        'wholesale_price'   => ($prices . 2500 * $quantity[$i]) - 3000,
//                        'customer_price'    => ($prices . 2100 * $quantity[$i]) - 3000,
//                    ]);
//                }
//
//                $stock = $faker->randomElement([100,120,150,130,140,180,160,170,175,185]);
//
//                $product->stocks()->create([
//                    'supplier_id'   => Supplier::query()->inRandomOrder()->first()->id,
//                    'first_stock'   => $stock,
//                    'available_stock'   => $stock,
//                    'buying_price'      => $prices . 900,
//                    'expired_at'        => now()->addYears($faker->randomDigitNotNull()),
//                    'description'       => 'STOCK AWAL',
//                ]);
//
//                $product->update([
//                    'warehouse_stock'   => $stock,
//                ]);
//            }
    }
}
