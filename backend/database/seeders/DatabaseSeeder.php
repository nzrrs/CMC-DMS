<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        // ── Roles ────────────────────────────────────────────────
        DB::table('roles')->insert([
            ['name' => 'Admin'],
            ['name' => 'HR'],
            ['name' => 'Employee'],
        ]);

        // ── Users ────────────────────────────────────────────────
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

        // ── Employees ────────────────────────────────────────────
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

        // ── Document Types ───────────────────────────────────────
        DB::table('document_types')->insert([
            [
                'template_name' => 'Attestation de Travail',
                'template_path' => 'templates/attestation_travail.docx',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ── Document Fields ──────────────────────────────────────
        DB::table('document_fields')->insert([
            [
                'document_type_id' => 1,
                'field_key' => 'ref',
                'field_type' => 'SYSTEM',
                'source_table' => null,
                'source_field' => null,
                'is_required' => true,
                'config_json' => json_encode(['generator' => 'reference_number']),
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'current_date',
                'field_type' => 'SYSTEM',
                'source_table' => null,
                'source_field' => null,
                'is_required' => true,
                'config_json' => json_encode(['generator' => 'current_date', 'format' => 'd/m/Y']),
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'title',
                'field_type' => 'SYSTEM',
                'source_table' => null,
                'source_field' => null,
                'is_required' => true,
                'config_json' => json_encode(['generator' => 'title']),
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'fullname',
                'field_type' => 'SYSTEM',
                'source_table' => null,
                'source_field' => null,
                'is_required' => true,
                'config_json' => json_encode(['generator' => 'fullname']),
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'matricule',
                'field_type' => 'AUTO',
                'source_table' => 'employees',
                'source_field' => 'matricule',
                'is_required' => true,
                'config_json' => null,
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'grade',
                'field_type' => 'AUTO',
                'source_table' => 'employees',
                'source_field' => 'grade',
                'is_required' => true,
                'config_json' => null,
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'position',
                'field_type' => 'AUTO',
                'source_table' => 'employees',
                'source_field' => 'position',
                'is_required' => true,
                'config_json' => null,
            ],
            [
                'document_type_id' => 1,
                'field_key' => 'recruitment_date',
                'field_type' => 'AUTO',
                'source_table' => 'employees',
                'source_field' => 'recruitment_date',
                'is_required' => true,
                'config_json' => json_encode(['format' => 'd/m/Y']),
            ],
        ]);

        // ── Role Document Types ──────────────────────────────────
        DB::table('role_document_types')->insert([
            ['role_id' => 2, 'document_type_id' => 1],
        ]);
    }
}
