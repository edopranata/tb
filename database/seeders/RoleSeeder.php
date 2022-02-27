<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Administrator', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Warehouse admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Store Admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cashier', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        Role::query()
            ->insert($data);
    }
}
