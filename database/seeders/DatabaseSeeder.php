<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            WriterProfilesTableSeeder::class,
            ClientProfilesTableSeeder::class,
            OrdersTableSeeder::class,
            // Add more seeders here as you create them
        ]);
    }
}