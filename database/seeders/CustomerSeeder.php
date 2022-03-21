<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory()->times(20)
            ->create([
                'user_id' => User::query()->inRandomOrder()->get()->first()->id,
            ]);
    }
}
