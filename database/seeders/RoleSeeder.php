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
                'name' => 'super admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'employee',
                'guard_name' => 'web'
            ],
            [
                'name' => 'merchant',
                'guard_name' => 'web'
            ],
            [
                'name' => 'merchant',
                'guard_name' => 'api'
            ],
            [
                'name' => 'customer',
                'guard_name' => 'web'
            ],
            [
                'name' => 'customer',
                'guard_name' => 'api'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
