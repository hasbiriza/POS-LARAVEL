<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $superadminRole = Role::create(['name' => 'superadmin']);
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => bcrypt('12345678'), 
        ]);
        $superadmin->assignRole('superadmin');
    }
}
