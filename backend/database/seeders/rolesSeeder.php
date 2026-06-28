<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles = [
            ['name' => 'Admin'],
            ['name' => 'head of department'],
            ['name' => 'Employee'],
            ['name' => 'academic director'],
            ['name' => 'HR'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
