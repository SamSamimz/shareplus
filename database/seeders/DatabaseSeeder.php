<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'user',
            'name' => 'Dam User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'username' => 'samim',
            'name' => 'Sam Samim',
            'email' => 'samim@gmail.com',
            'password' => bcrypt('password'),
        ]);
        Profile::create(['user_id' => 1]);
        Profile::create(['user_id' => 2]);
    }
}
