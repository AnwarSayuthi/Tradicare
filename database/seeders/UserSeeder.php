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
            'tel_number' => '019-3325968',
        ]);

        // Create specified customer
        User::create([
            'name' => 'Anwar Sayuthi',
            'email' => 'anwarsayuthi@gmail.com',
            'password' => Hash::make('101010'),
            'role' => 'customer',
            'tel_number' => '016-2650425',
        ]);
        User::create([
            'name' => 'Irfan Faiz',
            'email' => 'irfanfaiz@gmail.com',
            'password' => Hash::make('123321'),
            'role' => 'customer',
            'tel_number' => '012-3456789',
        ]);
    }
}