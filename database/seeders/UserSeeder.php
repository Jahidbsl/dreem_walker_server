<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'demo@dreemwalker.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        User::factory(9)->create();
    }
}