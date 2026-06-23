<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create([
            'name' => 'Admin',
            'email' => 'admin@toko.com',
            'password' => bcrypt('password')
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            SaleSeeder::class,
        ]);
    }
}