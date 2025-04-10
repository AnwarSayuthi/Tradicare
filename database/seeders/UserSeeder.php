<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'tradicare@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'tel_number' => '1234567890',
        ]);

        // Create specified customer
        User::create([
            'name' => 'Anwar Sayuthi',
            'email' => 'anwarsayuthi@gmail.com',
            'password' => Hash::make('101010'),
            'role' => 'customer',
            'tel_number' => '9876543210',
        ]);

        // Create 20 random customers
        \App\Models\User::factory()->count(20)->create([
            'role' => 'customer'
        ]);
    }
}