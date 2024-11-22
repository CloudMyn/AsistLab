<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles  =   [
            [
                'name' => 'developer',
                'guard_name' => 'web'
            ],
            [
                'name' => 'admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kepala_lab',
                'guard_name' => 'web'
            ],
            [
                'name' => 'asisten',
                'guard_name' => 'web'
            ],
            [
                'name' => 'praktikan',
                'guard_name' => 'api'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
