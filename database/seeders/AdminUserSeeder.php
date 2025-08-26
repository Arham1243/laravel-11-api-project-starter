<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@tmb.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'Administrator']);
        $admin->assignRole($role);
    }
}
