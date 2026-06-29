<?php

namespace Database\Seeders;

use App\Models\DocumentGroup;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentGroupMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'groupe_allocation' => [
                'demande_allocations_familiales',
                'Documents_necessaires',
                'avec_allocations_familiales',
            ],
            'groupe_mariage' => [
                'Documents_necessaires',
                'declaration_mariage',
            ],
            'groupe_naissance' => [
                'Documents_necessaires',
                'declaration_naissance',
            ],
        ];

        foreach ($groups as $groupName => $documentNames) {
            $group = DocumentGroup::where('name', $groupName)->firstOrFail();

            $documentTypeIds = DocumentType::whereIn('template_name', $documentNames)
                ->pluck('id')
                ->toArray();

            $group->documentTypes()->syncWithoutDetaching($documentTypeIds);
        }
    }
}