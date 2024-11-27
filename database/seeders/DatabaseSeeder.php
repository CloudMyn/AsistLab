<?php

namespace Database\Seeders;

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
        $this->call([
            RoleSeeder::class,
        ]);;

        $admin = User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@mail.io',
        ]);

        $dev = User::factory()->create([
            'name' => 'dev',
            'username' => 'dev',
            'email' => 'dev@mail.io',
        ]);

        $admin->assignRole('admin');
        $dev->assignRole('developer');
     }
}
