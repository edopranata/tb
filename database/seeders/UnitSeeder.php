<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = ['Paket', 'Slop', 'Dus', 'Gram', 'Bks', 'Batang', 'Ikat', 'Pcs', 'Sak'];
        foreach ($units as $unit) {
            Unit::query()
                ->create([
                    'name'      => $unit,
                    'user_id'   => User::query()->inRandomOrder()->get()->first()->id
                ]);
        }

    }
}
