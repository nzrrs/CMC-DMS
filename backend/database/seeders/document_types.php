<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class document_types extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doc_type_data=[
  [
        'template_name' => 'attestation _virement',
        'template_path' => '/templates/attestation _virement.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'attestation_revenu_salarial',
        'template_path' => '/templates/attestation_revenu_salarial.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'avec_allocations_familiales',
        'template_path' => '/templates/avec_allocations_familiales.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'changement_RIB',
        'template_path' => '/templates/changement_RIB.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'declaration_mariage',
        'template_path' => '/templates/declaration_mariage.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'declaration_naissance',
        'template_path' => '/templates/declaration_naissance.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'demande_allocations_familiales',
        'template_path' => '/templates/demande_allocations_familiales.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'Documents_necessaires',
        'template_path' => '/templates/Documents_necessaires.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'ordre_mission',
        'template_path' => '/templates/ordre_mission.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'template_name' => 'attestation_travail',
        'template_path' => '/templates/attestation_travail.docx',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]
        ];
        foreach ($doc_type_data as $data) {
    DocumentType::create($data);
}
    }
}
