<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentField;


class DocumentFieldSeeder extends Seeder
{
    
    public function run(): void
    {
         $fields = [

        // attestation_virement (1)
        [
            'document_type_id' => 1,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 1,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 1,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],
        [
            'document_type_id' => 1,
            'field_key' => 'phone',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'phone',
            'is_required' => true,
        ],

        // attestation_revenu_salarial (2)
        [
            'document_type_id' => 2,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 2,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 2,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],
        [
            'document_type_id' => 2,
            'field_key' => 'position',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'position',
            'is_required' => true,
        ],
        [
            'document_type_id' => 2,
            'field_key' => 'year',
            'field_type' => 'SELECT',
            'is_required' => true,
            'config_json' => json_encode([
                "min" => 1974,
                "max" => "current_year"
            ]),
        ],

        // changement_RIB (4)
        [
            'document_type_id' => 4,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 4,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 4,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],

        // declaration_mariage (5)
        [
            'document_type_id' => 5,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 5,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 5,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],

        // declaration_naissance (6)
        [
            'document_type_id' => 6,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 6,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 6,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],

        // demande_allocations_familiales (7)
        [
            'document_type_id' => 7,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 7,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 7,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],

        // ordre_mission (9)
        [
            'document_type_id' => 9,
            'field_key' => 'ref',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'reference_number',
            ]),
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'title',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'title',
            ]),
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],
       [
    'document_type_id' => 9,
    'field_key' => 'city',
    'field_type' => 'SELECT',
    'is_required' => true,
    'config_json' => json_encode([
        'options' => [
            'Casablanca',
            'Rabat',
            'Fès',
            'Marrakech',
            'Tangier',
            'Agadir',
            'Meknès',
            'Oujda',
            'Kénitra',
            'Tétouan',
            'Safi',
            'El Jadida',
            'Béni Mellal',
            'Nador',
            'Khouribga',
            'Errachidia',
            'Settat',
            'Larache',
            'Khémisset',
            'Guelmim',
            'Berkane',
            'Taourirt',
            'Essaouira',
            'Ouarzazate',
            'Taroudant',
            'Taza',
            'Ifrane',
            'Azrou',
            'Al Hoceima',
            'Dakhla',
            'Laâyoune',
            'Sidi Kacem',
            'Sidi Slimane',
            'Oued Zem',
            'Youssoufia',
            'Tinghir',
            'Chefchaouen',
            'Midelt',
            'Ksar El Kebir',
            'Fnideq',
            'Martil',
            'Asilah',
            'Sefrou',
            'Boujdour',
            'Tan-Tan',
            'Zagora',
            'Azilal',
            'Benslimane',
            'Ouazzane',
            'Jerada',
        ],
    ]),
],
        [
            'document_type_id' => 9,
            'field_key' => 'mission',
            'field_type' => 'INPUT',
            'is_required' => true,
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'date_depart',
            'field_type' => 'DATE',
            'is_required' => true,
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'date_retour',
            'field_type' => 'DATE',
            'is_required' => true,
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'heure_depart',
            'field_type' => 'INPUT',
            'is_required' => true,
        ],
        [
            'document_type_id' => 9,
            'field_key' => 'heure_retour',
            'field_type' => 'INPUT',
            'is_required' => true,
        ],

        // attestation_travail (10)
        [
            'document_type_id' => 10,
            'field_key' => 'ref',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'reference_number',
            ]),
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'current_date',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'current_date',
                'format' => 'd/m/Y',
            ]),
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'title',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'title',
            ]),
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'fullname',
            'field_type' => 'SYSTEM',
            'is_required' => true,
            'config_json' => json_encode([
                'generator' => 'fullname',
            ]),
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'matricule',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'matricule',
            'is_required' => true,
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'grade',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'grade',
            'is_required' => true,
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'position',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'position',
            'is_required' => true,
        ],
        [
            'document_type_id' => 10,
            'field_key' => 'recruitment_date',
            'field_type' => 'AUTO',
            'source_table' => 'employees',
            'source_field' => 'recruitment_date',
            'is_required' => true,
            'config_json' => json_encode([
                'format' => 'd/m/Y',
            ]),
        ],
    ];

    foreach ($fields as $field) {
        DocumentField::create([
            'source_table' => null,
            'source_field' => null,
            'config_json' => null,
            ...$field,
        ]);
        }
    }
}
