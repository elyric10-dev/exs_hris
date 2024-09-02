<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::create([
            'role_name' => 'Admin',
            'description' => 'Administrator role with full permissions.',
            'permissions' => json_encode(['*']),
        ]);

        UserRole::create([
            'role_name' => 'Manager',
            'description' => 'Manager role with limited permissions.',
            'permissions' => json_encode(['view_reports', 'manage_users']),
        ]);

        UserRole::create([
            'role_name' => 'User',
            'description' => 'Regular user role with basic permissions.',
            'permissions' => json_encode(['view_dashboard']),
        ]);
    }
}
