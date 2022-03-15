<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Supplier::factory()->times(10)
            ->create([
                'user_id' => User::query()->inRandomOrder()->get()->first()->id
            ]);
    }
}
