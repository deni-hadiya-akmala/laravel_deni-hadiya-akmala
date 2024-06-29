<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'fullname' => 'User One',
            'username' => 'userone',
            'email' => 'userone@example.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'fullname' => 'User Two',
            'username' => 'usertwo',
            'email' => 'usertwo@example.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'fullname' => 'User Three',
            'username' => 'userthree',
            'email' => 'userthree@example.com',
            'password' => Hash::make('12345678'),
        ]);
        // User::factory()->count(5)->create();
    }
}
