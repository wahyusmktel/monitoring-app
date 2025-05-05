<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role
        $roles = ['superadmin', 'admin', 'operator', 'customer_service', 'accountancy'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat user dan assign role
        foreach ($roles as $index => $role) {
            $user = User::create([
                'name' => ucfirst($role),
                'email' => $role . '@example.com',
                'password' => Hash::make('password'), // default password
            ]);
            $user->assignRole($role);
        }
    }
}
