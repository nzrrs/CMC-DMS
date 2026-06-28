<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'role_id' => 1,
                'first_name' => 'Nizar',
                'last_name' => 'Sadiki',
                'gender' => 'M',
                'email' => 'admin@cmc.ma',
                'employee' => [
                    'matricule' => 'ADM001',
                    'grade' => 'Directeur',
                    'position' => 'Directeur du Centre',
                    'recruitment_date' => '2018-01-10',
                ],
            ],
            [
                'role_id' => 2,
                'first_name' => 'Fatima',
                'last_name' => 'Zahra',
                'gender' => 'F',
                'email' => 'hr@cmc.ma',
                'employee' => [
                    'matricule' => 'HR001',
                    'grade' => 'Responsable RH',
                    'position' => 'HR Manager',
                    'recruitment_date' => '2022-03-15',
                ],
            ],
            [
                'role_id' => 3,
                'first_name' => 'Ahmed',
                'last_name' => 'Alaoui',
                'gender' => 'M',
                'email' => 'ahmed@cmc.ma',
                'employee' => [
                    'matricule' => 'EMP001',
                    'grade' => 'Technicien spécialisé',
                    'position' => 'Formateur',
                    'recruitment_date' => '2021-09-01',
                ],
            ],
        ];

        foreach ($users as $data) {
            $employeeData = $data['employee'];
            unset($data['employee']);

            $user = User::factory()->create($data);

            Employee::factory()->create([
                'user_id' => $user->id,
                ...$employeeData,
            ]);
        }
    }
}