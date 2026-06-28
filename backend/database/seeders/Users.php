<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('users')->insert([
            [
                'role_id' => 1,
                'first_name' => 'Nizar',
                'last_name' => 'Sadiki',
                'gender' => 'M',
                'email' => 'admin@cmc.ma',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'first_name' => 'Fatima',
                'last_name' => 'Zahra',
                'gender' => 'F',
                'email' => 'hr@cmc.ma',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Ahmed',
                'last_name' => 'Alaoui',
                'gender' => 'M',
                'email' => 'ahmed@cmc.ma',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
          DB::table('employees')->insert([
            [
                'user_id' => 1,
                'matricule' => 'ADM001',
                'grade' => 'Directeur',
                'position' => 'Directeur du Centre',
                'recruitment_date' => '2018-01-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'matricule' => 'HR001',
                'grade' => 'Responsable RH',
                'position' => 'HR Manager',
                'recruitment_date' => '2022-03-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'matricule' => 'EMP001',
                'grade' => 'Technicien spécialisé',
                'position' => 'Formateur',
                'recruitment_date' => '2021-09-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
