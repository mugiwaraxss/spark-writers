<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create writer users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Writer $i",
                'email' => "writer$i@example.com",
                'password' => Hash::make('password'),
                'phone' => "12345$i",
                'role' => 'writer',
                'status' => 'active',
            ]);
        }

        // Create client users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Client $i",
                'email' => "client$i@example.com",
                'password' => Hash::make('password'),
                'phone' => "98765$i",
                'role' => 'client',
                'status' => 'active',
            ]);
        }
