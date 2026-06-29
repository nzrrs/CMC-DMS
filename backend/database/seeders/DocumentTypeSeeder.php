<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            [
                'template_name' => 'attestation_virement',
                'template_path' => 'templates/attestation_virement.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'attestation_revenu_salarial',
                'template_path' => 'templates/attestation_revenu_salarial.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'avec_allocations_familiales',
                'template_path' => 'templates/avec_allocations_familiales.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'changement_RIB',
                'template_path' => 'templates/changement_RIB.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'declaration_mariage',
                'template_path' => 'templates/declaration_mariage.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'declaration_naissance',
                'template_path' => 'templates/declaration_naissance.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'demande_allocations_familiales',
                'template_path' => 'templates/demande_allocations_familiales.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'Documents_necessaires',
                'template_path' => 'templates/Documents_necessaires.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'ordre_mission',
                'template_path' => 'templates/ordre_mission.docx',
                'is_active' => true,
            ],
            [
                'template_name' => 'attestation_travail',
                'template_path' => 'templates/attestation_travail.docx',
                'is_active' => true,
            ],
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::updateOrCreate(
                ['template_name' => $documentType['template_name']],
                $documentType
            );
        }
    }
}