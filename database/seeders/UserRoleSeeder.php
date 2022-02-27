<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        $users = User::all();
        foreach ($users as $user) {
            if($user->id === 1){
                $user->assignRole('Administrator');
            }else{
                $user->assignRole(Role::query()->where('name', '<>', 'Administrator')->inRandomOrder()->first()->name);
            }
        }
    }
}
