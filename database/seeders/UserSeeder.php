<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Owner Pyramidsoft',
            'email' => 'admin@pyramidsoft.com',
            'password' => Hash::make('admin123'), 
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Bagus Ardin Prayoga',
            'email' => 'bagus@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
