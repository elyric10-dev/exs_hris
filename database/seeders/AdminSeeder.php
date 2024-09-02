<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PersonalInformation;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'username' => 'elyric10',
            'password' => bcrypt('ejooch5A'),
            'email' => 'elyricmanatad@gmail.com',
            'user_role_id' => 1,
            'created_by_user_id' => 1,
        ]);

        PersonalInformation::create([
            'user_id' => 1,
            'first_name' => 'Elyric',
            'middle_name' => 'Abera',
            'last_name' => 'Manatad',
            'contact_number' => '0993702803',
            'address' => 'Cadahu-an, Talamban, Cebu City',
            'date_of_birth' => '1996-05-15',
        ]);

        $admin->logs()->create([
            'action' => 'create',
            'details' => 'Admin created successfully',
        ]);
    }
}
